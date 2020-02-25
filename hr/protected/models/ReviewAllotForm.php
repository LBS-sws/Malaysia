<?php

class ReviewAllotForm extends CFormModel
{
	public $id;
	public $employee_id;
	public $employee_name;
	public $city;
	public $name;
	public $entry_time;
	public $company_name;
	public $dept_name;
	public $status_type;
	public $year_type;
	public $review_id;
	public $code;
	public $phone;
	public $year;
	public $id_list;
	public $id_s_list;
	public $name_list;

	public $tem_s_ist;//审核权限的序列化
	public $tem_str;
	public $tem_list;
	public $tem_sum;
	public $four_with_count;

	public $review_type;
    public $count_num=100;
    public $change_num;


    public $no_of_attm = array(
        'review'=>0
    );
    public $docType = 'REVIEW';
    public $docMasterId = array(
        'review'=>0
    );
    public $files;
    public $removeFileId = array(
        'review'=>0
    );
	public function attributeLabels()
	{
		return array(
            'name'=>Yii::t('contract','Employee Name'),
            'code'=>Yii::t('contract','Employee Code'),
            'phone'=>Yii::t('contract','Employee Phone'),
            'dept_name'=>Yii::t('contract','Position'),
            'company_name'=>Yii::t('contract','Company Name'),
            'contract_id'=>Yii::t('contract','Contract Name'),
            'status_type'=>Yii::t('contract','Status'),
            'city'=>Yii::t('contract','City'),
            'entry_time'=>Yii::t('contract','Entry Time'),
            'year'=>Yii::t('contract','what year'),
            'year_type'=>Yii::t('contract','year type'),
            'review_type'=>Yii::t('contract','review type'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('review_id,employee_id, name,code,phone,dept_name,company_name,contract_id,status_type,city,entry_time,year,year_type,id_list,
			tem_str,tem_list,change_num,id','safe'),
            array('employee_id','required'),
            array('id_list','required'),
            array('tem_list','required'),
            array('employee_id','validateName'),
            array('change_num','validateChangeNum'),
            array('id_list','validateIdList'),
            array('tem_list','validateList'),
            array('files, removeFileId, docMasterId, no_of_attm','safe'),
		);
	}

	public function validateName($attribute, $params){
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("a.name,b.review_type")->from("hr_employee a")
            ->leftJoin("hr_dept b","a.position = b.id")
            ->where("a.id=:id and a.city in ($city_allow) AND a.staff_status = 0",array(":id"=>$this->employee_id))->queryRow();
        if($rows){
            $this->review_type = $rows["review_type"];
            $this->employee_name = $rows["name"];
            $rows = Yii::app()->db->createCommand()->select("id,status_type,review_type")->from("hr_review")
                ->where("employee_id=:id AND year = :year AND year_type = :year_type",array(":id"=>$this->employee_id,":year"=>$this->year,":year_type"=>$this->year_type))->queryRow();
            if($rows){
                if($rows["status_type"] == 4){
                    //$this->review_type = $rows["review_type"];
                    $this->review_id = $rows["id"];
                    $this->setScenario("edit");
                }else{
                    $message = "考核單已存在不可重複提交,錯誤碼:".$rows["status_type"];
                    $this->addError($attribute,$message);
                }
            }else{
                $this->setScenario("new");
            }
        }else{
            $message = Yii::t('contract','Employee Name'). Yii::t('contract',' not exist');
            $this->addError($attribute,$message);
        }
	}

	public function validateChangeNum($attribute, $params){
	    if(in_array($this->review_type,array(2,3))){ //評核類型是：技術員或銷售
            if(!is_numeric($this->change_num)||$this->change_num<0){
                $message = $this->getReviewStr($this->review_type).Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }else{
                if($this->review_type == 3){
                    if($this->change_num>10){
                        $message = $this->getReviewStr($this->review_type)."不能大于10";
                        $this->addError($attribute,$message);
                    }
                }
            }
        }else{
	        $this->change_num = 0;
        }
	}

	public function getReviewStr($type){
	    switch ($type){
            case 2:
                return Yii::t("contract","sick leave and personal leave");//总病假及事假天数
            case 3:
                return Yii::t("contract","Substantial sales performance");//实质销售成绩
        }
        return "異常";
    }

	public function returnChangeReviewType(){
	    $html='';
	    $className = get_class($this);
	    if(in_array($this->review_type,array(2,3))){
	        $change_num = '';
	        $arr = array("readonly"=>$this->getReadonly(),"min"=>0,"id"=>"changeTwo","data-change"=>"two");
	        if($this->review_type == 3){
	            $arr["max"] = 10;
	            $arr["data-change"] = "three";
            }
            if(!empty($this->change_num)){
                if($this->review_type == 3){
                    $change_num =$this->change_num*10;
                    $change_num = sprintf("%.2f",$change_num);
                }else{
                    $change_num = 15-($this->change_num*0.5);
                    $change_num = $change_num<0?0:$change_num;
                }
            }
	        $html.='<div class="form-group">';
            $html.=TbHtml::label($this->getReviewStr($this->review_type),'',array('class'=>"col-sm-2 control-label"));
            $html.='<div class="col-sm-2">';
            $html.=TbHtml::numberField($className."[change_num]",$this->change_num,$arr);
	        $html.='</div>';
            $html.=TbHtml::label(Yii::t("contract","review number"),'',array('class'=>"col-sm-2 control-label"));
            $html.='<div class="col-sm-2">';
            $html.=TbHtml::numberField("change_num",$change_num,array("readonly"=>true,'id'=>"change_value"));
	        $html.='</div>';
	        $html.='</div>';
        }

        return $html;
    }

    public function validateList($attribute, $params){
        if(!empty($this->tem_list)){
            $arr = array();
            $tem_s_list = array();
            $this->tem_sum = 0;
            $this->four_with_count = 0;
            $i = -1;
            foreach ($this->tem_list as $key => $list){
                if($list=='on'){
                    $rows = Yii::app()->db->createCommand()->select("a.id,a.pro_name,a.set_id,b.num_ratio,b.set_name,b.four_with")->from("hr_set_pro a")
                        ->leftJoin("hr_set b","b.id = a.set_id")
                        ->where('a.id=:id',array(':id'=>$key))->queryRow();
                    if(!$rows){
                        $message = Yii::t('contract','template name'). Yii::t('contract',' not exist');
                        $this->addError($attribute,$message);
                        break;
                    }else{
                        if(!key_exists($rows['set_id'],$tem_s_list)){
                            $i++;
                        }
                        $this->tem_sum+=intval($rows['num_ratio']);
                        if($rows['four_with']==1){
                            $this->four_with_count+=intval($rows['num_ratio']);
                        }
                        $arr[] = $key;
                        $tem_s_list[$rows['set_id']]['code']=$this->getSetCodeToKey($i);
                        $tem_s_list[$rows['set_id']]['name']=$rows['set_name'];
                        $tem_s_list[$rows['set_id']]['num_ratio']=$rows['num_ratio'];
                        $tem_s_list[$rows['set_id']]['four_with']=$rows['four_with'];
                        $tem_s_list[$rows['set_id']]['list'][$this->tem_sum]['id']=$this->tem_sum;
                        $tem_s_list[$rows['set_id']]['list'][$this->tem_sum]['name']=$rows['pro_name'];
                    }
                }
            }
            if (empty($arr)){
                $message = Yii::t('contract','reviewAllot project'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
                return false;
            }
            $this->tem_str = implode(",",$arr);
            $this->tem_s_ist = $tem_s_list;
        }
    }

	public function validateIdList($attribute, $params){
	    if(!empty($this->id_list)){ //考核經理驗證
            $this->count_num = $this->review_type == 3?30:100;
            $sum = 0;
            $this->name_list = array();
            $idList =array();
	        foreach ($this->id_list as &$list){
	            if(in_array($list["employee_id"],$idList)){
                    $message = Yii::t('contract','reviewAllot manager'). Yii::t('contract',' can not repeat');
                    $this->addError($attribute,$message);
                    return false;
                }else{
                    $idList[] = $list["employee_id"];
                }
                $rows = Yii::app()->db->createCommand()->select("name")->from("hr_employee")
                    ->where("id=:id",array(":id"=>$list["employee_id"]))->queryRow();
                if(!$rows){
                    $message = Yii::t('contract','reviewAllot manager'). Yii::t('contract',' not exist');
                    $this->addError($attribute,$message);
                    return false;
                }else{
                    $list["employee_name"] = $rows["name"];
                }
                if(empty($list['num'])||!is_numeric($list['num'])){
                    $message = Yii::t('contract','manager percent').Yii::t('contract',' can not be empty');
                    $this->addError($attribute,$message);
                    return false;
                }elseif ($list['num']<0||intval($list['num'])!=floatval($list['num'])){
                    $message = $rows["name"]."的考核所占比格式不正确";
                    $this->addError($attribute,$message);
                    return false;
                }
                $this->name_list[] = $rows["name"]."（".$list["num"]."%）";
                $sum+=intval($list["num"]);
            }
            if($sum!=$this->count_num){
                $message = '經理考核佔比必須為'.$this->count_num;
                $this->addError($attribute,$message);
                return false;
            }
            $this->id_s_list = implode(",",$idList);
            $this->name_list = implode(",",$this->name_list);
        }
	}

	public function retrieveData($index,$year,$year_type) {
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        //,b.status_type,b.year,b.year_type,b.id as review_id
        $dateTime = ReviewAllotList::getReviewDateTime($year,$year_type);
		$row = Yii::app()->db->createCommand()
            ->select("a.id,a.name,a.code,a.phone,a.city,a.entry_time,c.name as company_name,d.name as dept_name,d.review_type")
            ->from("hr_employee a")
            ->leftJoin("hr_company c","a.company_id = c.id")
            ->leftJoin("hr_dept d","a.position = d.id")
            ->where("a.id=:id and a.city in ($city_allow) AND a.staff_status = 0 AND replace(entry_time,'-', '/')<='$dateTime'",array(":id"=>$index))->queryRow();
		if ($row) {
            $this->review_type = $row['review_type'];
            $this->year = $year;
            $this->year_type = $year_type;
            $this->employee_id = $row['id'];
            $this->name = $row['name'];
            $this->city = $row["city"];
            $this->entry_time = $row['entry_time'];
            $this->company_name = $row['company_name'];
            $this->dept_name = $row['dept_name'];
            $this->code = $row['code'];
            $this->phone = $row['phone'];
            $review = Yii::app()->db->createCommand()
                ->select("*,docman$suffix.countdoc('REVIEW',id) as reviewdoc")
                ->from("hr_review")
                ->where("employee_id=:id and year = :year and year_type = :year_type",
                    array(
                        ":id"=>$row["id"],
                        ":year"=>$year,
                        ":year_type"=>$year_type,
                    )
                )->queryRow();
            if($review){
                $this->no_of_attm['review'] = $review['reviewdoc'];
                $this->change_num = $review['change_num'];
                if($review['status_type'] != 4){
                    $this->review_type = $review['review_type'];
                }
                $this->status_type = $review['status_type'];
                //$this->status_type = ReviewAllotList::getReviewStatuts($review['status_type'])["status"];
                $this->review_id = $review['id'];
                $this->id = $review['id'];
                $this->tem_str = $review['tem_str'];
                $this->tem_str = $review['tem_str'];
                $this->id_s_list = $review['id_s_list'];
                $this->id_list = json_decode($review['id_list'],true);
                $this->tem_s_ist = json_decode($review['tem_s_ist'],true);
            }
            $this->count_num = $this->review_type == 3?30:100;
            $this->getEmployeeTemplate();
            return true;
		}else{
		    return false;
        }
	}

	public function getSetCodeToKey($key){
	    $arr = array("甲","乙","丙","丁","戊","己","庚","辛","壬","癸","子","丑","寅","卯","辰","巳","午","未","申","酉","戌","亥");
	    if(key_exists($key,$arr)){
	        return $arr[$key];
        }else{
	        return "LBS";
        }

    }

	protected function getEmployeeTemplate(){
        if(empty($this->tem_str)){
            $row = Yii::app()->db->createCommand()->select("d.tem_str,a.id_s_list,a.id_list,a.name_list")
                ->from("hr_template_employee a")
                ->leftJoin("hr_template d","a.tem_id = d.id")
                ->where("a.employee_id= :id",array(":id"=>$this->employee_id))->queryRow();
            if($row){
                $this->tem_str = $row["tem_str"];
                $this->id_s_list = $row["id_s_list"];
                $this->id_list = json_decode($row["id_list"],true);
            }
        }

        if($this->change_num === null&&$this->review_type == 2){
            if($this->year_type == 1){
                $startTime = $this->year."/04";
                $endTime = $this->year."/09";
            }else{
                $startTime = $this->year."/10";
                $endTime = ($this->year+1)."/03";
            }
            $dateSql = "and date_format(a.start_time,'%Y/%m')>='$startTime' and date_format(a.start_time,'%Y/%m')<='$endTime'";
            $this->change_num = Yii::app()->db->createCommand()->select("sum(a.log_time)")
                ->from("hr_employee_leave a")
                ->leftJoin("hr_vacation b","a.vacation_id = b.id")
                ->where("a.employee_id= :id and a.status = 4 and b.sub_bool = 1 $dateSql",array(":id"=>$this->employee_id))->queryScalar();
            //var_dump($this->change_num);
        }
    }


	public function getReadonly(){
        if ($this->getScenario()=='view'||in_array($this->status_type,array(1,2,3))){
            return true;//只读
        }else{
            return false;
        }
    }


	public function reviewBack($index){
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
        $row = Yii::app()->db->createCommand()
            ->select("a.id,a.review_id,b.employee_id,b.year,b.year_type")
            ->from("hr_review_h a")
            ->leftJoin("hr_review b","a.review_id = b.id")
            ->leftJoin("hr_employee d","b.employee_id = d.id")
            ->where("a.id=:id and d.city in ($city_allow) and a.status_type=3",array(":id"=>$index))->queryRow();
	    if($row&&Yii::app()->user->validFunction('ZR12')){
            $this->review_id = $row["review_id"];
            $this->employee_id = $row["employee_id"];
            $this->year = $row["year"];
            $this->year_type = $row["year_type"];
            $bool = Yii::app()->db->createCommand()->select("id")->from("hr_review_h")
                ->where("status_type=3 and id!=:id and review_id=:review_id",array(":id"=>$index,":review_id"=>$row["review_id"]))->queryRow();
            $bool = $bool?2:1;
            Yii::app()->db->createCommand()->update('hr_review_h', array(
                'status_type'=>4,
                'luu'=>$uid
            ), 'id=:id', array(':id'=>$index));
            Yii::app()->db->createCommand()->update('hr_review', array(
                'status_type'=>$bool,//1:考核中  2:部分考核
                'luu'=>$uid
            ), 'id=:id', array(':id'=>$this->review_id));
            Dialog::message(Yii::t('dialog','Information'), Yii::t('contract','finish to send back'));
        }else{
            Dialog::message(Yii::t('dialog','Validation Message'), "退回異常，請於管理員聯繫");
        }
    }

    public function getReviewManagerList($city,$id_s_list=''){
	    $arr = array();
        $cityList = Email::getAllCityToMinCity($city);
        $city_allow = implode("','",$cityList);
        $sql = '';
        if(!empty($id_s_list)){
            $sql = " or a.id in ($id_s_list)";
        }
        $rows = Yii::app()->db->createCommand()
            ->select("a.id,a.name,a.code")
            ->from("hr_employee a")
            ->leftJoin("hr_dept d","a.position = d.id")
            ->where("((a.city in ('$city_allow') and d.review_leave=1) or d.review_leave = 2 $sql) AND a.staff_status = 0")->queryAll();
        foreach ($rows as $row){
            $arr[$row["id"]] = $row["code"]." - ".$row["name"];
        }

        return $arr;
    }

    public function getRowOnly($model,$num,$managerList,$bool,$list=array()){
        if(empty($list)){
            $list = array("employee_id"=>"","num"=>100);
        }
        $className = get_class($model);
        $html = "";
        $html .= "<tr>";
        $html.="<td>".TbHtml::dropDownList($className."[id_list][$num][employee_id]",$list["employee_id"],$managerList,array("class"=>"form-control","readonly"=>$bool))."</td>";
        $html.="<td>".TbHtml::numberField($className."[id_list][$num][num]",$list["num"],array("class"=>"form-control changeNum","readonly"=>$bool))."</td>";
        if(!$bool){
            if(empty($num)){
                $html.="<td>&nbsp;</td>";
            }else{
                $html.="<td>".TbHtml::button(Yii::t("misc","Delete"),array("class"=>"btn btn-danger delManager"))."</td>";
            }
        }else{
            if(!empty($model->review_id)&&!empty($list["employee_id"])){
                $rows = Yii::app()->db->createCommand()->select("id,status_type")->from("hr_review_h")
                    ->where('review_id=:review_id and handle_id=:id',
                        array(':review_id'=>$model->review_id,':id'=>$list["employee_id"]))->queryRow();
                if($rows){//none review
                    $status_type = $rows["status_type"] == 3?Yii::t("contract","success review"):Yii::t("contract","none review");
                    $html.="<td class='text-center'><span style='white-space: nowrap;'>$status_type</span></td>";
                    if(in_array($model->status_type,array(2,3))&&Yii::app()->user->validFunction('ZR12')){
                        if($rows["status_type"] == 3){
                            $html.="<td class='text-center'>".TbHtml::link(Yii::t("contract","send back"),Yii::app()->createUrl('reviewAllot/back',array('index'=>$rows['id'])),array("class"=>"btn btn-info"))."</td>";
                        }else{
                            $html.="<td class='text-center'><span style='white-space: nowrap;'>&nbsp;</span></td>";
                        }
                    }
                }
            }
        }
        $html.="</tr>";

        return $html;
    }

    public function returnManager($model){
        $bool = $model->getReadonly();
        $managerList = ReviewAllotForm::getReviewManagerList($model->city,$model->id_s_list);
	    $html ="<table class='table table-bordered table-striped' id='managerTable'><thead><tr><th width='50%'>".Yii::t("contract","reviewAllot manager")."</th><th width='40%'>".Yii::t("contract","manager percent")."</th>";
        $html.='<th>&nbsp;</th>';
        if(in_array($model->status_type,array(2,3)) && get_class($model)=="ReviewAllotForm" && Yii::app()->user->validFunction('ZR12')){
            $html.='<th>&nbsp;</th>';
        }
        $num = count($model->id_list);
	    $html.="</tr></thead><tbody data-num='$num'>";
        if (empty($model->id_list)){
            $html .= $this->getRowOnly($model,$num,$managerList,$bool);
        }else{
            $i = 0;
            foreach ($model->id_list as $list){
                $html .= $this->getRowOnly($model,$i,$managerList,$bool,$list);
                $i++;
            }
        }
        $html .= "</tbody>";
        if(!$bool){
            $html.="<tfoot><tr><td colspan='2'></td>";
            $html.="<td>".TbHtml::button(Yii::t("misc","Add"),array("class"=>"btn btn-primary","id"=>"addManager"))."</td>";
            $html.="</tr></tfoot>";
        }
        $html.="</table>";
	    return $html;
    }

    //刪除驗證
    public function validateUndo(){
        $row = Yii::app()->db->createCommand()->select("id")->from("hr_review")
            ->where("id=:id and status_type=1",array(":id"=>$this->review_id))->queryRow();
        if($row){
            return true;
        }
        return false;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		//$transaction=$connection->beginTransaction();
		try {
			$this->saveGoods($connection);
            $this->updateDocman($connection,'REVIEW');
			//$transaction->commit();
		}
		catch(Exception $e) {
			//$transaction->rollback();
			throw new CHttpException(404,'Cannot update. ('.$e->getMessage().')');
		}
	}

    protected function updateDocman(&$connection, $doctype) {
        if ($this->scenario=='new') {
            $docidx = strtolower($doctype);
            if ($this->docMasterId[$docidx] > 0) {
                $docman = new DocMan($doctype,$this->review_id,get_class($this));
                $docman->masterId = $this->docMasterId[$docidx];
                $docman->updateDocId($connection, $this->docMasterId[$docidx]);
            }
            $this->scenario = "edit";
        }
    }

	protected function saveGoods(&$connection) {
        $uid = Yii::app()->user->id;
        switch ($this->scenario) {
            case 'new':
                $connection->createCommand()->insert("hr_review", array(
                    'employee_id'=>$this->employee_id,
                    'year'=>$this->year,
                    'year_type'=>$this->year_type,
                    'id_list'=>json_encode($this->id_list),
                    'id_s_list'=>$this->id_s_list,
                    'name_list'=>$this->name_list,
                    'tem_s_ist'=>json_encode($this->tem_s_ist),
                    'status_type'=>$this->status_type,
                    'tem_str'=>$this->tem_str,
                    'review_type'=>$this->review_type,
                    'change_num'=>$this->change_num,
                    'lcu'=>$uid,
                ));
                $this->review_id = Yii::app()->db->getLastInsertID();
                break;
            case 'edit':
                $connection->createCommand()->update('hr_review', array(
                    'id_list'=>json_encode($this->id_list),
                    'id_s_list'=>$this->id_s_list,
                    'name_list'=>$this->name_list,
                    'tem_s_ist'=>json_encode($this->tem_s_ist),
                    'status_type'=>$this->status_type,
                    'tem_str'=>$this->tem_str,
                    'review_type'=>$this->review_type,
                    'change_num'=>$this->change_num,
                    'luu'=>$uid,
                ), 'id=:id', array(':id'=>$this->review_id));
                break;
            case 'undo':
                $connection->createCommand()->update('hr_review', array(
                    'status_type'=>4,
                    'luu'=>$uid,
                ), 'id=:id', array(':id'=>$this->review_id));
                $connection->createCommand()->delete('hr_review_h', 'review_id=:review_id', array(':review_id'=>$this->review_id));
                break;
        }

        $this->sendReview($connection);
		return true;
	}

	protected function sendReview($connection){
        if($this->status_type == 1){ //已發送，需要考核
            $email = new Email();
            $description="新的人才優化評核 - ".$this->employee_name."(".$this->year.ReviewAllotList::getYearTypeList($this->year_type).")";
            $subject=$description;
            $message="<p>员工编号：".$this->code."</p>";
            $message.="<p>员工姓名：".$this->name."</p>";
            $message.="<p>入职时间：".$this->entry_time."</p>";
            $message.="<p>员工职位：".$this->dept_name."</p>";
            $message.="<p>公司名字：".$this->company_name."</p>";
            $message.="<p>考核经理：".$this->name_list."</p>";
            $email->setDescription($description);
            $email->setMessage($message);
            $email->setSubject($subject);
            $email->addEmailToStaffId($this->employee_id);//添加備考人郵箱
            $connection->createCommand()->delete('hr_review_h', 'review_id=:review_id', array(':review_id'=>$this->review_id));
            foreach ($this->id_list as $list){ //給考核人分別添加考核表
                $email->addEmailToStaffId($list["employee_id"]);//添加主管郵箱
                $connection->createCommand()->insert("hr_review_h", array(
                    'review_id'=>$this->review_id,
                    'handle_id'=>$list["employee_id"],
                    'handle_name'=>$list["employee_name"],
                    'handle_per'=>$list["num"],
                    'tem_s_ist'=>json_encode($this->tem_s_ist),
                    'tem_sum'=>$this->tem_sum,
                    'four_with_count'=>$this->four_with_count,
                    'lcu'=>Yii::app()->user->id,
                ));
            }
            $email->sent();
        }
    }
}

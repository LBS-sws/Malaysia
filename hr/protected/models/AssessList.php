<?php

class AssessList extends CListPageModel
{
    public $employee_id;//員工id
    public $searchTimeStart;//開始日期
    public $searchTimeEnd;//結束日期
    public $checkBoxSent;//選中的id
    public $email_list;//郵箱列表
    public $test;//
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'work_type'=>Yii::t('contract','Leader'),
			'employee_name'=>Yii::t('contract','Employee Name'),
			'employee_code'=>Yii::t('contract','Employee Code'),
			'service_effect'=>Yii::t('fete','service effect'),
			'lcd'=>Yii::t('fete','Evaluation Time'),
			'city'=>Yii::t('contract','City'),
			'city_name'=>Yii::t('contract','City'),
			'email_bool'=>Yii::t('fete','email bool'),
			'email_list'=>Yii::t('fete','recipient'),
			'checkBoxSent'=>Yii::t('contract','Employee'),
			'lcu'=>Yii::t('fete','evaluator'),
            'staff_type'=>Yii::t('fete','staff type'),
		);
	}

    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchTimeStart, searchTimeEnd, checkBoxSent, email_list','safe',),
            array('email_list','required',"on"=>"sent"),
            array('checkBoxSent','required',"on"=>"sent"),
            array('email_list','validateEmail',"on"=>"sent"),
        );
    }

    public function validateEmail($attribute, $params){
        if(!empty($this->email_list)){
            $emailList = explode(";",$this->email_list);
            $emailList = array_filter($emailList);
            if(!empty($emailList)){
                foreach ($emailList as $email){
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $message = "邮箱格式不正确";
                        $this->addError($attribute,$message);
                        return false;
                    }
                }
            }else{
                $message = "邮箱格式不正确";
                $this->addError($attribute,$message);
            }
        }
    }

	public function retrieveDataByPage($pageNum=1)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $uid = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $employee_id = $this->employee_id;
		$sql1 = "select a.*,d.disp_name,b.name AS employee_name,b.code AS employee_code,b.city AS s_city,b.position 
                from hr_assess a LEFT JOIN hr_employee b ON a.employee_id = b.id
                LEFT JOIN security$suffix.sec_user d ON a.lcu = d.username
                where a.id!=0 
			";
		$sql2 = "select count(a.id)
                from hr_assess a LEFT JOIN hr_employee b ON a.employee_id = b.id
                LEFT JOIN security$suffix.sec_user d ON a.lcu = d.username
                where a.id!=0 
			";
		if(!Yii::app()->user->validFunction('ZR08')){
		    //沒有所有評估列表權限只顯示自己錄入的評估
            $sql1.=" and a.lcu = '$uid' ";
            $sql2.=" and a.lcu = '$uid' ";
        }else{
            $sql1.=" and b.city IN ($city_allow) ";
            $sql2.=" and b.city IN ($city_allow) ";
        }
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'employee_name':
					$clause .= General::getSqlConditionClause('b.name',$svalue);
					break;
				case 'lcu':
					$clause .= General::getSqlConditionClause('d.disp_name',$svalue);
					break;
				case 'staff_type':
				    $staffTypeSearch = $this->staffTypeSearchLike($svalue);
					$clause .= " and a.staff_type in $staffTypeSearch";
					break;
				case 'employee_code':
					$clause .= General::getSqlConditionClause('b.code',$svalue);
					break;
                case 'city_name':
                    $clause .= ' and b.city in '.WordForm::getCityCodeSqlLikeName($svalue);
                    break;
			}
		}
		if (!empty($this->searchTimeStart) && !empty($this->searchTimeStart)) {
			$svalue = str_replace("'","\'",$this->searchTimeStart);
            $clause .= " and a.lcd >='$svalue 00:00:00' ";
		}
		if (!empty($this->searchTimeEnd) && !empty($this->searchTimeEnd)) {
			$svalue = str_replace("'","\'",$this->searchTimeEnd);
            $clause .= " and a.lcd <='$svalue 23:59:59' ";
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.lcd desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
		    $staffType = PrizeList::getPrizeList();
			foreach ($records as $k=>$record) {
			    $colorList = $this->statusToColor($record['email_bool']);
				$this->attr[] = array(
					'id'=>$record['id'],
					'employee_name'=>$record['employee_name'],
					'employee_code'=>$record['employee_code'],
					'work_type'=>DeptForm::getDeptToId($record['position']),
					'service_effect'=>$record['service_effect'],
					'staff_type'=>$staffType[$record['staff_type']],
					'lcu'=>$record['disp_name'],
					'lcd'=>date("Y-m-d",strtotime($record['lcd'])),
					'status'=>$colorList["status"],
                    'city'=>CGeneral::getCityName($record["s_city"]),
					'style'=>$colorList["style"],
				);
			}
		}
		$session = Yii::app()->session;
		$session['assess_01'] = $this->getCriteria();
		return true;
	}

    //工種的模糊查詢
    private function staffTypeSearchLike($str){
        $staffType = PrizeList::getPrizeList();
        $arr = array();
        foreach ($staffType as $key=> $item){
            if (strpos($item,$str)!==false){
                array_push($arr,$key);
            }
        }
        if(empty($arr)){
            return "('')";
        }else{
            return "(".implode(",",$arr).")";
        }
    }

    //獲取用戶暱稱
    public function getDisNameToUsername($username){
        $suffix = Yii::app()->params['envSuffix'];
        $disName = Yii::app()->db->createCommand()->select("disp_name")->from("security$suffix.sec_user")
            ->where("username=:username",array(":username"=>$username))->queryRow();
        if($disName){
            return $disName["disp_name"];
        }else{
            return $username;
        }
    }

    //根據狀態獲取顏色
    public function statusToColor($status){
        switch ($status){
            // text-danger
            case 0:
                return array(
                    "status"=>Yii::t("fete","not sent"),
                    "style"=>""
                );
            case 1:
                return array(
                    "status"=>Yii::t("fete","been sent"),//已發送
                    "style"=>" text-primary"
                );
        }
        return array(
            "status"=>"",
            "style"=>""
        );
    }

    //獲取郵箱列表
    public function getEmailList(){
        $form = 'security'.Yii::app()->params['envSuffix'].'.sec_user';
        $sql = "select * from $form WHERE email != '' AND status='A' ORDER BY city DESC ";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array();
        if($rows){
            foreach ($rows as $row){
                $key = $row["username"]."!".$row["email"];
                $arr[$key]=$row["disp_name"];
            }
        }
        return $arr;
    }

    //發送郵件
    public function sentEmail(){
        $emailList = explode(";",$this->email_list);
        $emailList = array_filter($emailList);
        $suffix = Yii::app()->params['envSuffix'];
        $from_addr = Yii::app()->params['adminEmail'];
        $to_addr = json_encode($emailList);
        $checkId = implode(",",$this->checkBoxSent);
        $uid = Yii::app()->user->id;
        $connection = Yii::app()->db;
        $rows = $connection->createCommand()->select("a.*,b.name as employee_name,b.code AS employee_code,b.city AS s_city,b.position")
            ->from("hr_assess a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.id in ($checkId)")->queryAll();
        if($rows){
            $message = "";
            $email_title = "技术员评估 - ";
            $staffTypeList = PrizeList::getPrizeList();
            foreach ($rows as $row){
                $message .= "<p>员工编号：".$row["employee_code"]."</p>";
                $message .= "<p>员工名字：".$row["employee_name"]."</p>";
                $message .= "<p>员工城市：".CGeneral::getCityName($row["s_city"])."</p>";
                $message .= "<p>职位：".DeptForm::getDeptToId($row["position"])."</p>";
                if(array_key_exists($row["staff_type"],$staffTypeList)){
                    $message .= "<p>工种：".$staffTypeList[$row["staff_type"]]."</p>";
                }else{
                    $message .= "<p>工种：</p>";
                }
                $message .= "<p>整体效果：".$row["overall_effect"]."</p>";
                $message .= "<p>服务效果：".$row["service_effect"]."</p>";
                $message .= "<p>服务流程：".$row["service_process"]."</p>";
                $message .= "<p>细心度：".$row["carefully"]."</p>";
                $message .= "<p>判断力：".$row["judge"]."</p>";
                $message .= "<p>处理能力：".$row["deal"]."</p>";
                $message .= "<p>沟通能力：".$row["connects"]."</p>";
                $message .= "<p>服从度：".$row["obey"]."</p>";
                $message .= "<p>领导力：".$row["leadership"]."</p>";
                $message .= "<p>性格：".$row["characters"]."</p>";
                $message .= "<p>评估：".$row["assess"]."</p>";
                $message .= "<p>评估人：".$this->getDisNameToUsername($row["lcu"])."</p>";
                $message .="<p>&nbsp;</p>";
                $message .="<p>------------------------------------------</p>";
                $message .="<p>&nbsp;</p>";
                $email_title.=$row["employee_name"]."、";
            }
            $connection->createCommand()->insert("swoper$suffix.swo_email_queue", array(
                'request_dt'=>date('Y-m-d H:i:s'),
                'from_addr'=>$from_addr,
                'to_addr'=>$to_addr,
                'subject'=>$email_title,//郵件主題
                'description'=>$email_title,//郵件副題
                'message'=>$message,//郵件內容（html）
                'status'=>"P",
                'lcu'=>$uid,
            ));
            Yii::app()->db->createCommand()->update('hr_assess', array(
                'email_bool'=>1,
                'email_list'=>$to_addr,
            ), "id in ($checkId)");
        }
    }
}

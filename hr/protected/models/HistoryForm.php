<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class HistoryForm extends CFormModel
{
	/* User Fields */
    public $employee_id=0;
	public $id;
	public $name;
	public $city;
	public $code;
    public $sex;
	public $staff_id;
	public $company_id;
    public $address;
    public $address_code;
    public $contact_address;
    public $contact_address_code;
	public $phone;
    public $phone2;//緊急電話
	public $contract_id;
	public $user_card;
	public $department;
	public $position;
	public $wage;
	public $time=1;
	public $start_time;
	public $end_time;
	public $test_start_time;
	public $test_end_time;
	public $test_wage;
	public $word_status=1;
	public $test_type=1;
	public $word_html="";
    public $staff_status = 1;
    public $entry_time;//入職時間
    public $birth_time;//出生日期
    public $age;//年齡
    public $health;//身體狀況
    public $education;//學歷
    public $experience;//工作經驗
    public $english;//外語水平
    public $technology;//技術水平
    public $other;//其它說明
    public $year_day;//年假
    public $email;//員工郵箱
    public $remark;//備註
    public $price1;//每月工資
    public $price2;//加班工資
    public $price3;//每月補貼
    public $image_user;//員工照片
    public $image_code;//身份證照片
    public $image_work;//工作證明照片
    public $image_other;//其它照片
    public $ld_card;//勞動保障卡號
    public $sb_card;//社保卡號
    public $jj_card;//公積金卡號
    public $update_remark;//員工修改備註
    public $historyList;//員工修改備註
    public $ject_remark;//員工修改備註
    public $staff_type;//员工类别
    public $staff_leader;//队长/组长
    public $test_length;//
    public $attachment="";//附件
    public $nation;//民族
    public $household;//户籍类型
    public $empoyment_code;//就业登记证号
    public $social_code;//社会保障卡号
    public $fix_time=0;//合同類型
    public $opr_type;//合同變更類型
    public $leave_time;//離職時間
    public $leave_reason;//離職原因
    public $user_card_date;//身份证有效期
    public $emergency_user;//紧急联络人姓名
    public $emergency_phone;//紧急联络人手机号
    public $change_city;//調職城市
    public $code_old;//員工編號（舊）
    public $effect_time;//生效日期
    public $no_of_attm = array(
        'employee'=>0,
        'employ'=>0
    );
    public $docType = 'EMPLOYEE';
    public $docMasterId = array(
        'employee'=>0,
        'employ'=>0
    );
    public $files;
    public $removeFileId = array(
        'employee'=>0,
        'employ'=>0
    );
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
            'id'=>Yii::t('staff','Record ID'),
            'code'=>Yii::t('contract','Employee Code'),
            'sex'=>Yii::t('contract','Sex'),
            'age'=>Yii::t('contract','Age'),
            'birth_time'=>Yii::t('contract','Birth Date'),
            'name'=>Yii::t('contract','Employee Name'),
            'staff_id'=>Yii::t('contract','Employee Belong'),
            'company_id'=>Yii::t('contract','Employee Contract Belong'),
            'contract_id'=>Yii::t('contract','Employee Contract'),
            'address'=>Yii::t('contract','Old Address'),
            'contact_address'=>Yii::t('contract','Contact Address'),
            'phone'=>Yii::t('contract','Employee Phone'),
            'phone2'=>Yii::t('contract','Emergency call'),
            'user_card'=>Yii::t('contract','ID Card'),
            'department'=>Yii::t('contract','Department'),
            'position'=>Yii::t('contract','Position'),
            'wage'=>Yii::t('contract','Contract Pay'),
            'time'=>Yii::t('contract','Contract Time'),
            'start_time'=>Yii::t('contract','Contract Start Time'),
            'end_time'=>Yii::t('contract','Contract End Time'),
            'test_type'=>Yii::t('contract','Probation Type'),
            'test_time'=>Yii::t('contract','Probation Time'),
            'test_start_time'=>Yii::t('contract','Probation Start Time'),
            'test_end_time'=>Yii::t('contract','Probation End Time'),
            'test_wage'=>Yii::t('contract','Probation Wage'),
            'entry_time'=>Yii::t('contract','Entry Time'),
            'health'=>Yii::t('contract','Physical condition'),
            'education'=>Yii::t('contract','Degree level'),
            'experience'=>Yii::t('contract','Work experience'),
            'english'=>Yii::t('contract','Foreign language level'),
            'technology'=>Yii::t('contract','Technical level'),
            'other'=>Yii::t('contract','Other'),
            'year_day'=>Yii::t('contract','Annual leave'),
            'email'=>Yii::t('contract','Email'),
            'remark'=>Yii::t('contract','Remark'),
            'price1'=>Yii::t('contract','Wages Name'),
            'price3'=>Yii::t('contract','Wages Type'),
            'image_user'=>Yii::t('contract','Staff photo'),
            'image_code'=>Yii::t('contract','Id photo'),
            'image_work'=>Yii::t('contract','Work photo'),
            'image_other'=>Yii::t('contract','Other photo'),
            'ld_card'=>Yii::t('contract','Labor security card'),
            'sb_card'=>Yii::t('contract','Social security card'),
            'jj_card'=>Yii::t('contract','Accumulation fund card'),
            'update_remark'=>Yii::t('contract',"Operation")."".Yii::t('contract','Remark'),
            'ject_remark'=>Yii::t('contract',"Rejected Remark"),
            'staff_type'=>Yii::t('staff','Staff Type'),
            'staff_leader'=>Yii::t('staff','Team/Group Leader'),
            'test_length'=>Yii::t('contract','Probation Time Longer'),
            'attachment'=>Yii::t('contract','Attachment'),
            'nation'=>Yii::t('contract','nation'),
            'household'=>Yii::t('contract','Household type'),
            'empoyment_code'=>Yii::t('contract','Employment registration certificate'),
            'social_code'=>Yii::t('contract','Social security card number'),
            'fix_time'=>Yii::t('contract','contract deadline'),
            'opr_type'=>Yii::t('contract','Operation Type'),
            'leave_reason'=>Yii::t('contract','Leave Reason'),
            'leave_time'=>Yii::t('contract','Leave Time'),
            'user_card_date'=>Yii::t('contract','ID Card Date'),
            'emergency_user'=>Yii::t('contract','Emergency User'),
            'emergency_phone'=>Yii::t('contract','Emergency Phone'),
            'change_city'=>Yii::t('contract','Change City'),
            'change_city_old'=>Yii::t('contract','Staff City'),
            'code_old'=>Yii::t('contract','Code Old'),
            'effect_time'=>Yii::t('contract','Effect Time'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id,employee_id,update_remark, code, name, staff_id, company_id, contract_id, address, address_code, contact_address, contact_address_code, phone, phone2, user_card, department, position, wage,time,
             start_time, end_time, test_type, test_start_time, sex, test_end_time, test_wage, word_status, city, entry_time, age, birth_time, health,staff_status,user_card_date,emergency_user,emergency_phone,
             ld_card, sb_card, jj_card,test_length,staff_type,staff_leader,attachment,nation, household, empoyment_code, social_code, fix_time, opr_type, leave_reason, leave_time, code_old,
              education, experience, english, technology, other, year_day, email, remark, image_user, image_code, image_work, image_other, effect_time, change_city',
                'safe'),
			array('update_remark','required'),
			array('code','required'),
            array('effect_time','required',"on"=>"change"),
			array('opr_type','required',"on"=>"change"),
			array('leave_time','required',"on"=>"departure"),
			array('leave_reason','required',"on"=>"departure"),
            array('staff_id','required'),
			array('name','required'),
			array('code','validateCode'),
			array('sex','required'),
			array('name','validateName'),
			array('company_id','required'),
			array('contract_id','required'),
			array('address','required'),
			array('contact_address','required'),
			array('phone','required'),
			array('user_card','required'),
			array('department','required'),
			array('position','required'),
            array('wage','validateWage','on'=>"audit"),//由於工資有些用戶沒有權限
			array('time','required'),
            array('fix_time','required'),
			array('start_time','required'),
			array('end_time','validateEndTime'),
			array('test_type','required'),
			array('test_type','validateTestType'),
            array('year_day','required'),
            array('year_day', 'validateYearDay'),
            array('files, removeFileId, docMasterId, no_of_attm','safe'),
		);
	}

    public function validateYearDay($attribute, $params){
        if(!empty($this->year_day)){
            if(!is_numeric($this->year_day)){
                $message = "年假只能为数字";
                $this->addError($attribute,$message);
            }elseif(floatval($this->year_day)<0){
                $message = "年假不能小于0";
                $this->addError($attribute,$message);
            }else{
                $year_day = strval($this->year_day);
                $year_day = explode('.',$year_day);
                if(count($year_day)===2){
                    $year_day = end($year_day);
                    if($year_day%5 !== 0){
                        $message = "年假必须为0.5的倍数";
                        $this->addError($attribute,$message);
                    }
                }
            }
        }
    }

    public function validateEndTime($attribute, $params){
        if($this->fix_time == "fixation"){
            if(empty($this->end_time)){
                $message = Yii::t('contract','Contract End Time'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }
        }
    }
    public function validateWage($attribute, $params){
        if(empty($this->wage)){
            if(Yii::app()->user->validFunction('ZR02')){
                $message = Yii::t('contract','Contract Pay'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }else{
                $message = Yii::t('contract','You do not have salary change authority, please save the contact leader');
                $this->addError($attribute,$message);
            }
        }
    }

	public function validateTestType($attribute, $params){
	    if(!empty($this->test_type)){
	        if(empty($this->test_end_time)||empty($this->test_end_time)){
                $message = Yii::t('contract','Probation Time'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }
            if(empty($this->test_wage)){
                $message = Yii::t('contract','Probation Wage'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }elseif (!is_numeric($this->test_wage)){
                $message = Yii::t('contract','Probation Wage'). Yii::t('contract',' Must be Numbers');
                $this->addError($attribute,$message);
            }
        }
    }

	public function validateCode($attribute, $params){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id!=:id and code=:code ', array(':id'=>$this->employee_id,':code'=>$this->code))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Employee Code'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }
	public function validateName($attribute, $params){
/*        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id!=:id and name=:name ', array(':id'=>$this->employee_id,':name'=>$this->name))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Employee Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }*/
    }

    //獲取可用公司
    public function getCompanyToCity(){
	    $arr = array(""=>"");
        $city = $this->city;
        $rows = Yii::app()->db->createCommand()->select()->from("hr_company")
            ->where('city=:city ', array(':city'=>$city))->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }
    //自動變化表頭
    public function setFormTitle(){
        switch ($this->scenario){
            case "update":
                return Yii::t("contract","Staff Update");
            case "change":
                return Yii::t("contract","Staff Change");
            case "departure":
                return Yii::t("contract","Staff Departure");
            case "view":
                return Yii::t("contract","Staff View");
            default:
                return Yii::t("contract","Staff View");
        }
    }
    //獲取可用合同
    public function getContractToCity(){
	    $arr = array(""=>"");
        $city = $this->city;
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where('city=:city ', array(':city'=>$city))->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //驗證是否有變更記錄
    public function validateStaff($index,$type){
        $arr = array("update","change","departure");
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        if(in_array($type,$arr)){
            $count = Yii::app()->db->createCommand()->select("count(id)")->from("hr_employee_operate")
                ->where("employee_id=:id and city in ($city_allow)  and finish=0", array(':id'=>$index))->queryScalar();
            if($count>0){
                return false;
            }
        }
        return true;
    }

	public function retrieveData($index)
	{
        $suffix = Yii::app()->params['envSuffix'];
	    $type = $this->scenario;
	    $arr = array("update","change","departure");
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
	    if(in_array($type,$arr)){
            $count = Yii::app()->db->createCommand()->select("count(id)")->from("hr_employee_operate")
                ->where("employee_id=:id and city in ($city_allow) and finish=0", array(':id'=>$index))->queryScalar();
            if($count>0){
                return false;
            }
            $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('EMPLOY',id) as employdoc")->from("hr_employee")
                ->where("id=:id and city in ($city_allow) and staff_status=0", array(':id'=>$index))->queryAll();
        }else{
            $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('EMPLOYEE',id) as employeedoc")->from("hr_employee_operate")
                ->where('id=:id', array(':id'=>$index))->queryAll();
        }
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
			    if(!empty($row['employee_id'])){
                    $this->id = $row['id'];
                    $this->no_of_attm['employee'] = $row['employeedoc'];
			        $this->employee_id = $row['employee_id'];
                    $this->update_remark = $row['update_remark'];
                    $this->ject_remark = $row['ject_remark'];
                    $this->effect_time = $row['effect_time'];
                }else{
                    //$this->no_of_attm['employ'] = $row['employdoc'];
                    $this->employee_id = $row['id'];
                    $this->id = "";
                    $this->copyAttachment();
                }
                $this->historyList = AuditHistoryForm::getStaffHistoryList($this->employee_id);
                $this->code = $row['code'];
                $this->name = $row['name'];
                $this->sex = $row['sex'];
                $this->staff_id = $row['staff_id'];
                $this->company_id = $row['company_id'];
                $this->contract_id = $row['contract_id'];
                $this->address = $row['address'];
                $this->contact_address = $row['contact_address'];
                $this->phone = $row['phone'];
                $this->city = $row['city'];
                $this->user_card = $row['user_card'];
                $this->department = $row['department'];
                $this->position = $row['position'];
                $this->wage = $row['wage'];
                $this->start_time = $row['start_time'];
                $this->end_time = $row['end_time'];
                $this->test_type = $row['test_type'];
                $this->test_end_time = $row['test_end_time'];
                $this->test_start_time = $row['test_start_time'];
                $this->test_wage = $row['test_wage'];
                $this->word_status = $row['word_status'];
                $this->address_code = $row['address_code'];
                $this->contact_address_code = $row['contact_address_code'];
                $this->phone2 = $row['phone2'];
                $this->entry_time = $row['entry_time'];
                $this->birth_time = $row['birth_time'];
                $this->age = $row['age'];
                $this->health = $row['health'];
                $this->education = $row['education'];
                $this->staff_status = $row['staff_status'];
                $this->experience = $row['experience'];
                $this->english = $row['english'];
                $this->technology = $row['technology'];
                $this->other = $row['other'];
                $this->year_day = $row['year_day'];
                $this->email = $row['email'];
                $this->remark = $row['remark'];
/*                $this->price1 = $row['price1'];
                $this->price3 = explode(",",$row['price3']);*/
                $this->image_user = $row['image_user'];
                $this->image_code = $row['image_code'];
                $this->image_work = $row['image_work'];
                $this->image_other = $row['image_other'];
                $this->ld_card = $row['ld_card'];
                $this->sb_card = $row['sb_card'];
                $this->jj_card = $row['jj_card'];
                $this->test_length = $row['test_length'];
                $this->staff_type = $row['staff_type'];
                $this->staff_leader = $row['staff_leader'];
                $this->attachment = $row['attachment'];
                $this->nation = $row['nation'];
                $this->household = $row['household'];
                $this->empoyment_code = $row['empoyment_code'];
                $this->social_code = $row['social_code'];
                $this->fix_time = $row['fix_time'];
                $this->opr_type = key_exists('opr_type',$row)?$row['opr_type']:"";
                $this->leave_reason = $row['leave_reason'];
                $this->leave_time = $row['leave_time'];
                $this->user_card_date = $row['user_card_date'];
                $this->emergency_user = $row['emergency_user'];
                $this->emergency_phone = $row['emergency_phone'];
                $this->code_old = $row['code_old'];
                $this->change_city = empty($row['change_city'])?$row['city']:$row['change_city'];
                if($this->staff_status == 1 || $this->staff_status == 3){
                    $this->scenario = $row['operation'];
                }
                if(empty($this->scenario)){
                    $this->scenario = "view";
                }
				break;
			}
		}
		return true;
	}
	//刪除驗證
    public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select("id")->from("hr_employee_operate")
            ->where('id=:id and staff_status in (1,3)', array(':id'=>$this->id))->queryRow();
        if($rows){
            return true;
        }else{
            return false;
        }
    }

	//刪除草稿
    public function deleteHistory(){
        Yii::app()->db->createCommand()->delete('hr_employee_operate', 'id=:id', array(':id'=>$this->id));
    }
	
	public function saveData()
	{
        //工資單數組轉字符串(Start)
        if(is_array($this->price3)&&!empty($this->price1)){
            $this->price3 = implode(",",$this->price3);
        }else{
            $this->price3 = "";
        }
        //工資單數組轉字符串(END)
        $uid = Yii::app()->user->id;
	    $staffList = $this->attributes;
        $row = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id=:id', array(':id'=>$this->employee_id))->queryRow();
        unset($row["id"]);
        unset($row["luu"]);
        unset($row["lud"]);
        foreach ($row as $name =>$value){
            if(array_key_exists($name,$staffList)){
                $row[$name] = $staffList[$name];
            }
        }
        $row['lcu'] = $uid;
        $row['lcd'] = date("Y-m-d H:i:s");
        $row['staff_status'] = $this->staff_status;
        $row['ject_remark'] = "";
        $row['operation'] = $this->scenario;
        $row['effect_time'] = $this->effect_time;
        $row['opr_type'] = $this->opr_type;
        $row['employee_id'] = $this->employee_id;
        $row['update_remark'] = $this->update_remark;
        $row['change_city'] = $this->change_city;
        $row['attachment'] = 0;//後期修改（員工合同過期后是否已發送郵件 0：未發送  1：已發送）
        if($this->scenario == "view" && $this->staff_status == 3){
            unset($row['operation']);
            Yii::app()->db->createCommand()->update('hr_employee_operate', $row, 'id=:id', array(':id'=>$this->id));
            $row['operation'] = "Again Audit";//再次審核
            $id = "";
        }else if (empty($this->id)){
            $connection = Yii::app()->db;
            $connection->createCommand()->insert('hr_employee_operate', $row);
            $id = $connection->getLastInsertID();
            $this->id = $id;
            $this->updateDocman($connection,'EMPLOYEE');
            //複製員工的附件
            $this->copyAttachment();
        }else{
            Yii::app()->db->createCommand()->update('hr_employee_operate', $row, 'id=:id', array(':id'=>$this->id));
            $id = $this->id;
        }
        if($this->staff_status == 1){ //草稿不生成記錄
            return true;
        }
        $his_arr =  array(
            "employee_id"=>$this->employee_id,
            "history_id"=>$id,
            "remark"=>"",
            "lcu"=>$row['lcu'],
            "lcd"=>$row['lcd'],
        );
        if($row['operation'] =="change"){
            $his_arr["status"] = $row['opr_type'];
            $num = Yii::app()->db->createCommand()->select("count('id')")->from("hr_employee_history")
                ->where('employee_id=:employee_id and status="contract"',array(":employee_id"=>$this->employee_id))->queryScalar();
            if($num > 0 && $row['opr_type'] == "contract"){
                $num++;
                $his_arr["num"] = " - ".$num;
            }
        }else{
            $his_arr["status"] = $row['operation'];
        }
        //記錄
        Yii::app()->db->createCommand()->insert('hr_employee_history',$his_arr);

        //發送郵件
        $this->sendEmail($row,$his_arr);
	}


	//發送郵件
    private function sendEmail($row,$his_arr){
        if($row){
            $description=Yii::t("contract",$his_arr["status"])." - ".$row["name"];
            $subject=Yii::t("contract",$his_arr["status"])." - ".$row["name"];
            $message="<p>员工编号：".$row["code"]."</p>";
            $message.="<p>员工姓名：".$row["name"]."</p>";
            $message.="<p>要求审核日期：".date('Y-m-d H:i:s')."</p>";
            $message.="<p>操作备注：".$row["update_remark"]."</p>";
            $email = new Email($subject,$message,$description);
            $email->addEmailToPrefix("ZG02");
            $email->sent();
        }
    }

    protected function updateDocman(&$connection, $doctype) {
        $docidx = strtolower($doctype);
        if ($this->docMasterId[$docidx] > 0) {
            $docman = new DocMan($doctype,$this->id,get_class($this));
            $docman->masterId = $this->docMasterId[$docidx];
            $docman->updateDocId($connection, $this->docMasterId[$docidx]);
        }
    }

//複製員工的附件
    public function copyAttachment(){
        $connection = Yii::app()->db;
        $uid = Yii::app()->user->id;
        $suffix = Yii::app()->params['envSuffix'];
        $sql="SELECT a.id,b.display_name,b.phy_file_name,b.phy_path_name,b.file_type,b.remove,b.archive FROM docman$suffix.dm_master a,docman$suffix.dm_file b WHERE a.id = b.mast_id AND a.doc_type_code='EMPLOY' AND a.doc_id=".$this->employee_id;
        $attachment_old = $connection->createCommand($sql)->queryAll();
        if($attachment_old){//如果有附件
            $connection->createCommand()->insert("docman$suffix.dm_master", array(
                'doc_type_code'=>'EMPLOYEE',
                'doc_id'=>0,
                'lcu'=>$uid,
            ));
            $innerId = $connection->getLastInsertID();
            $this->docMasterId['employee']=$innerId;
            $this->no_of_attm['employee']=count($attachment_old);
            foreach ($attachment_old as $attachment){
                $arr = $attachment;
                unset($arr["id"]);
                $arr["mast_id"]=$innerId;
                $connection->createCommand()->insert("docman$suffix.dm_file", $arr);
            }
        }
    }

    //該方法後期已刪除
    public function setAttachment(){
        $str = $this->attachment;
        if(empty($str)){
            $arr = array();
        }else{
            $arr = explode(",",$str);
            for($i = 0;$i<count($arr);$i++){
                $rows = Yii::app()->db->createCommand()->select()->from("hr_attachment")
                    ->where('id=:id', array(':id'=>$arr[$i]))->queryRow();
                if($rows){
                    $arr[$i] = $rows;
                }else{
                    unset($arr[$i]);
                }
            }
        }
        $this->attachment = $arr;
        return $arr;
    }

    //根據歷史記錄的id獲取員工歷史信息
    public function getStaffToHistoryId($index){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee_operate")
            ->where('id=:id', array(':id'=>$index))->queryRow();
        if($rows){
            return $rows;
        }else{
            return array();
        }
    }
}

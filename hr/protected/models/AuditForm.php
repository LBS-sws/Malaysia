<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class AuditForm extends CFormModel
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
	public $ject_remark;//拒絕原因
    public $staff_type;//员工类别
    public $staff_leader;//队长/组长
    public $test_length;//
    public $attachment="";//附件
    public $nation;//民族
    public $household;//户籍类型
    public $empoyment_code;//就业登记证号
    public $social_code;//社会保障卡号
    public $fix_time=0;//合同類型
    public $user_card_date;//身份证有效期
    public $emergency_user;//紧急联络人姓名
    public $emergency_phone;//紧急联络人手机号
    public $code_old;//員工編號（舊）
    public $no_of_attm = array(
        'employ'=>0
    );
    public $docType = 'EMPLOY';
    public $docMasterId = array(
        'employ'=>0
    );
    public $files;
    public $removeFileId = array(
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
            'ject_remark'=>Yii::t('contract','Rejected Remark'),
            'staff_type'=>Yii::t('staff','Staff Type'),
            'staff_leader'=>Yii::t('staff','Team/Group Leader'),
            'test_length'=>Yii::t('contract','Probation Time Longer'),
            'attachment'=>Yii::t('contract','Attachment'),
            'nation'=>Yii::t('contract','nation'),
            'household'=>Yii::t('contract','Household type'),
            'empoyment_code'=>Yii::t('contract','Employment registration certificate'),
            'social_code'=>Yii::t('contract','Social security card number'),
            'fix_time'=>Yii::t('contract','contract deadline'),
            'user_card_date'=>Yii::t('contract','ID Card Date'),
            'emergency_user'=>Yii::t('contract','Emergency User'),
            'emergency_phone'=>Yii::t('contract','Emergency Phone'),
            'code_old'=>Yii::t('contract','Code Old'),
		);
	}

	/**
     *
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, code, name, staff_id, company_id, contract_id, address, address_code, contact_address, contact_address_code, phone, phone2, user_card, department, position, wage,time,
             start_time, end_time, test_type, test_start_time, sex, test_end_time, test_wage, word_status, city, entry_time, age, birth_time, health,ject_remark,staff_status,
              education, experience, english, technology, other, year_day, email, remark, image_user, image_code, image_work, image_other, code_old,
               test_length,staff_type,staff_leader,attachment,nation, household, empoyment_code, social_code, fix_time',
                'safe'),
			array('ject_remark','required',"on"=>"reject"),
		);
	}

    //獲取可用公司
    public function getCompanyToCity(){
	    $arr = array(""=>"");
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_company")
            ->where("city in ($city_allow)")->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }
    //獲取可用合同
    public function getContractToCity(){
	    $arr = array(""=>"");
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where("city in ($city_allow)")->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

	public function retrieveData($index)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('EMPLOY',id) as employdoc")->from("hr_employee")
            ->where("id=:id and city in ($city_allow) ", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
                $this->no_of_attm['employ'] = $row['employdoc'];
				$this->id = $row['id'];
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
                $this->ject_remark = $row['ject_remark'];
                $this->test_length = $row['test_length'];
                $this->staff_type = $row['staff_type'];
                $this->staff_leader = $row['staff_leader'];
                $this->attachment = $row['attachment'];
                $this->nation = $row['nation'];
                $this->household = $row['household'];
                $this->empoyment_code = $row['empoyment_code'];
                $this->social_code = $row['social_code'];
                $this->fix_time = $row['fix_time'];
                $this->user_card_date = $row['user_card_date'];
                $this->emergency_user = $row['emergency_user'];
                $this->emergency_phone = $row['emergency_phone'];
                $this->code_old = $row['code_old'];
				break;
			}
		}
		return true;
	}
	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveStaff($connection);
			$transaction->commit();
		}catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.'.$e->getMessage());
		}
	}


	protected function saveStaff(&$connection)
	{
		$sql = '';
        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'reject':
				$sql = "update hr_employee set
							staff_status = 3,
							ject_remark = :ject_remark,
							lud = :lud,
							luu = :luu 
						where id = :id
						";
				break;
			case 'audit':
				$sql = "update hr_employee set
							staff_status = 4,
							lud = :lud,
							luu = :luu 
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
        if (strpos($sql,':id')!==false)
            $command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':ject_remark')!==false)
			$command->bindParam(':ject_remark',$this->ject_remark,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lud')!==false)
			$command->bindParam(':lud',date('Y-m-d H:i:s'),PDO::PARAM_STR);

        //die();
		$command->execute();

        //記錄
        Yii::app()->db->createCommand()->insert('hr_employee_history', array(
            "employee_id"=>$this->id,
            "status"=>$this->scenario,
            "lcu"=>$uid,
            "lcd"=>date('Y-m-d H:i:s'),
        ));

        $this->sendEmail();
        return true;
	}

    private function sendEmail(){
        $row = Yii::app()->db->createCommand()->select("*")->from("hr_employee")
            ->where("id=:id",array(":id"=>$this->id))->queryRow();
        if($row){
            if ($this->getScenario() == "audit"){
                $description="员工审核 - ".$row["name"]."（通过）";
                $subject="员工审核 - ".$row["name"]."（通过）";
            }else{
                $description="员工审核 - ".$row["name"]."（拒绝）";
                $subject="员工审核 - ".$row["name"]."（拒绝）";
            }
            $message="<p>员工编号：".$row["code"]."</p>";
            $message.="<p>员工姓名：".$row["name"]."</p>";
            $message.="<p>员工入职日期：".$row["entry_time"]."</p>";
            $message.="<p>审核日期：".date('Y-m-d H:i:s')."</p>";
            if ($this->getScenario() == "reject"){
                $message.="<p>拒绝原因：".$this->ject_remark."</p>";
            }
            $email = new Email($subject,$message,$description);
            $email->addEmailToLcu($row["lcu"]);
            $email->sent();
        }
    }

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
}

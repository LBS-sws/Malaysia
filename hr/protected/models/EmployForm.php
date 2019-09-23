<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class EmployForm extends CFormModel
{
	/* User Fields */
	public $employee_id=0;
	public $id;
	public $name;
	public $city;
	public $code;
    public $sex;
	public $company_id;
	public $staff_id;
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
	public $price3=array();//每月補貼
	public $image_user;//員工照片
	public $image_code;//身份證照片
	public $image_work;//工作證明照片
	public $image_other;//其它照片
	public $ject_remark;//拒絕原因
	public $ld_card;//勞動保障卡號
	public $sb_card;//社保卡號
	public $jj_card;//公積金卡號
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
            'ld_card'=>Yii::t('contract','Labor security card'),
            'sb_card'=>Yii::t('contract','Social security card'),
            'jj_card'=>Yii::t('contract','Accumulation fund card'),
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
             start_time, end_time, test_type, test_start_time, sex, test_end_time, test_wage, word_status, city, entry_time, age, birth_time, health, ject_remark, staff_status,
              education, experience, english, technology, other, year_day, email, remark, image_user, image_code, image_work, image_other, fix_time, code_old,
               test_length,staff_type,staff_leader,attachment,nation, household, empoyment_code, social_code, user_card_date, emergency_user, emergency_phone',
                'safe'),
			array('entry_time','required'),
			array('name','required'),
			array('household','required'),
			array('staff_id','required'),
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
	public function validateName($attribute, $params){
/*        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id!=:id and name=:name ', array(':id'=>$this->id,':name'=>$this->name))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Employee Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }*/
    }

    //獲取可用公司
    public function getCompanyToCity($company_id){
        $sql = "";
	    if(!empty($company_id)&&is_numeric($company_id)){
	        $sql = " or id='$company_id'";
        }
	    $arr = array(""=>"");
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_company")
            ->where("city=:city$sql", array(':city'=>$city))->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //獲取可用合同
    public function getContractToCity($con_id){
        $sql = "";
        if(!empty($con_id)&&is_numeric($con_id)){
            $sql = " or id='$con_id'";
        }
	    $arr = array(""=>"");
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where("city=:city$sql", array(':city'=>$city))->queryAll();
        if(count($rows)>0){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //根據id獲取公司員工合同信息
    public function getEmployeeToId($id){
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id=:id and city=:city ', array(':id'=>$id,':city'=>$city))->queryAll();
        if($rows){
            $arr["company"]=CompanyForm::getCompanyToId($rows[0]["company_id"]);
            $wordIdList = ContractForm::getWordListToConIdDesc($rows[0]["contract_id"]);
            $arr["word"]=array();
            $arr["staff"]=$rows[0];
            foreach ($wordIdList as $wordId){
                $url = WordForm::getDocxUrlToId($wordId["name"]);
                if($url){
                    array_push($arr["word"],$url["docx_url"]);
                }
            }
            return $arr;
        }
        return false;
    }
    //根據id獲取員信息
    public function getEmployeeOneToId($id){
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id=:id and city=:city ', array(':id'=>$id,':city'=>$city))->queryAll();
        if($rows){
            return $rows[0];
        }
        return "";
    }

	public function retrieveData($index)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('EMPLOY',id) as employdoc")->from("hr_employee")
            ->where('id=:id and city=:city ', array(':id'=>$index,':city'=>$city))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
                $this->no_of_attm['employ'] = $row['employdoc'];
				$this->id = $row['id'];
				$this->code = $row['code'];
				$this->name = $row['name'];
				$this->sex = $row['sex'];
				$this->company_id = $row['company_id'];
				$this->staff_id = $row['staff_id'];
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

	public function parseWagesToArr($str){
        $arr = explode(",",$str);
        for($i=0;$i<count($arr);$i++){
            if(empty($arr[$i])){
                $arr[$i] = 0;
            }
        }
        return $arr;
    }

    //員工刪除時必須是草稿
    public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('id=:id and staff_status in (1,3,4)', array(':id'=>$this->id))->queryRow();
        if ($rows){
            return true;
        }
        return false;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveStaff($connection);
            $this->updateDocman($connection,'EMPLOY');
			$transaction->commit();
		}catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.'.$e->getMessage());
		}
	}

    protected function updateDocman(&$connection, $doctype) {
        if ($this->scenario=='new') {
            $docidx = strtolower($doctype);
            if ($this->docMasterId[$docidx] > 0) {
                $docman = new DocMan($doctype,$this->id,get_class($this));
                $docman->masterId = $this->docMasterId[$docidx];
                $docman->updateDocId($connection, $this->docMasterId[$docidx]);
            }
            $this->scenario = "edit";
        }
    }

	protected function saveStaff(&$connection)
	{
		$sql = '';
		$audit= false;
        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;
        if($this->scenario == "audit"){
            if(empty($this->id)){
                $this->scenario = "new";
            }else{
                $this->scenario = "edit";
            }
            $audit = true;
        }
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from hr_employee where id = :id and city = :city";
				break;
			case 'new':
				$sql = "insert into hr_employee(
							name, sex, attachment, staff_id, company_id, contract_id, city, address, contact_address, phone, user_card, department, position, wage, start_time, end_time, test_type, test_end_time, test_start_time,
							 test_wage,phone2,address_code,contact_address_code,entry_time,birth_time,age,health,education,experience,english,technology,other,year_day,fix_time,user_card_date,emergency_user,emergency_phone,code_old,
							 email,remark,image_user,image_code,image_work,image_other,staff_status,staff_leader,test_length,staff_type,lcu, nation, household, empoyment_code, social_code
						) values (
							:name, :sex, :attachment, :staff_id, :company_id, :contract_id, :city, :address, :contact_address, :phone, :user_card, :department, :position, :wage, :start_time, :end_time, :test_type, :test_end_time, :test_start_time,
							 :test_wage,:phone2,:address_code,:contact_address_code,:entry_time,:birth_time,:age,:health,:education,:experience,:english,:technology,:other,:year_day,:fix_time,:date_user_card,:emergency_user,:emergency_phone,:code_old,
							 :email,:remark,:image_user,:image_code,:image_work,:image_other,1,:staff_leader,:test_length,:staff_type,:lcu, :nation, :household, :empoyment_code, :social_code
						)";
				break;
			case 'edit':
				$sql = "update hr_employee set
							name = :name, 
							sex = :sex, 
							attachment = :attachment, 
							staff_type = :staff_type, 
							test_length = :test_length, 
							staff_leader = :staff_leader, 
							staff_id = :staff_id,
							company_id = :company_id,
							contract_id = :contract_id,
							address = :address,
							contact_address = :contact_address,
							phone = :phone,
							user_card = :user_card,
							department = :department,
							position = :position,
							wage = :wage,
							start_time = :start_time,
							end_time = :end_time,
							test_type = :test_type,
							test_end_time = :test_end_time,
							test_start_time = :test_start_time,
							test_wage = :test_wage,
							entry_time = :entry_time,
							birth_time = :birth_time,
							phone2 = :phone2,
							address_code = :address_code,
							contact_address_code = :contact_address_code,
							age = :age,
							health = :health,
							education = :education,
							experience = :experience,
							english = :english,
							technology = :technology,
							other = :other,
							year_day = :year_day,
							email = :email,
							remark = :remark,
							image_user = :image_user,
							image_code = :image_code,
							image_work = :image_work,
							image_other = :image_other,
							nation = :nation,
							household = :household,
							empoyment_code = :empoyment_code,
							social_code = :social_code,
							fix_time = :fix_time,
							user_card_date = :date_user_card,
							emergency_user = :emergency_user,
							emergency_phone = :emergency_phone,
							code_old = :code_old,
							luu = :luu 
						where id = :id
						";
				break;
		}
		if(intval($this->test_type) != 1){
		    $this->test_wage = null;
		    $this->test_start_time = null;
		    $this->test_end_time = null;
        }

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
        if (strpos($sql,':code_old')!==false)
            $command->bindParam(':code_old',$this->code_old,PDO::PARAM_STR);
		if (strpos($sql,':sex')!==false)
			$command->bindParam(':sex',$this->sex,PDO::PARAM_STR);
		if (strpos($sql,':name')!==false)
			$command->bindParam(':name',$this->name,PDO::PARAM_STR);
		if (strpos($sql,':staff_id')!==false)
			$command->bindParam(':staff_id',$this->staff_id,PDO::PARAM_INT);
		if (strpos($sql,':company_id')!==false)
			$command->bindParam(':company_id',$this->company_id,PDO::PARAM_INT);
		if (strpos($sql,':contract_id')!==false)
			$command->bindParam(':contract_id',$this->contract_id,PDO::PARAM_INT);
		if (strpos($sql,':address')!==false)
			$command->bindParam(':address',$this->address,PDO::PARAM_STR);
		if (strpos($sql,':contact_address')!==false)
			$command->bindParam(':contact_address',$this->contact_address,PDO::PARAM_STR);
		if (strpos($sql,':phone')!==false)
			$command->bindParam(':phone',$this->phone,PDO::PARAM_STR);
		if (strpos($sql,':user_card,')!==false)
			$command->bindParam(':user_card',$this->user_card,PDO::PARAM_STR);
		if (strpos($sql,':department')!==false)
			$command->bindParam(':department',$this->department,PDO::PARAM_STR);
		if (strpos($sql,':position')!==false)
			$command->bindParam(':position',$this->position,PDO::PARAM_STR);
		if (strpos($sql,':wage')!==false)
			$command->bindParam(':wage',$this->wage,PDO::PARAM_INT);
		if (strpos($sql,':start_time')!==false)
			$command->bindParam(':start_time',$this->start_time,PDO::PARAM_STR);
		if (strpos($sql,':end_time')!==false)
			$command->bindParam(':end_time',$this->end_time,PDO::PARAM_STR);
		if (strpos($sql,':test_type')!==false)
			$command->bindParam(':test_type',$this->test_type,PDO::PARAM_INT);
		if (strpos($sql,':test_end_time')!==false)
			$command->bindParam(':test_end_time',$this->test_end_time,PDO::PARAM_STR);
		if (strpos($sql,':test_start_time')!==false)
			$command->bindParam(':test_start_time',$this->test_start_time,PDO::PARAM_STR);
		if (strpos($sql,':test_wage')!==false)
			$command->bindParam(':test_wage',$this->test_wage,PDO::PARAM_INT);

		if (strpos($sql,':phone2')!==false)
			$command->bindParam(':phone2',$this->phone2,PDO::PARAM_STR);
		if (strpos($sql,':address_code')!==false)
			$command->bindParam(':address_code',$this->address_code,PDO::PARAM_STR);
		if (strpos($sql,':contact_address_code')!==false)
			$command->bindParam(':contact_address_code',$this->contact_address_code,PDO::PARAM_STR);
		if (strpos($sql,':entry_time')!==false)
			$command->bindParam(':entry_time',$this->entry_time,PDO::PARAM_STR);
		if (strpos($sql,':birth_time')!==false)
			$command->bindParam(':birth_time',$this->birth_time,PDO::PARAM_STR);
		if (strpos($sql,':age')!==false)
			$command->bindParam(':age',$this->age,PDO::PARAM_STR);
		if (strpos($sql,':health')!==false)
			$command->bindParam(':health',$this->health,PDO::PARAM_STR);
		if (strpos($sql,':education')!==false)
			$command->bindParam(':education',$this->education,PDO::PARAM_STR);
		if (strpos($sql,':experience')!==false)
			$command->bindParam(':experience',$this->experience,PDO::PARAM_STR);
		if (strpos($sql,':english')!==false)
			$command->bindParam(':english',$this->english,PDO::PARAM_STR);
		if (strpos($sql,':technology')!==false)
			$command->bindParam(':technology',$this->technology,PDO::PARAM_STR);
		if (strpos($sql,':other')!==false)
			$command->bindParam(':other',$this->other,PDO::PARAM_STR);
		if (strpos($sql,':year_day')!==false)
			$command->bindParam(':year_day',$this->year_day,PDO::PARAM_STR);
		if (strpos($sql,':email')!==false)
			$command->bindParam(':email',$this->email,PDO::PARAM_STR);
		if (strpos($sql,':remark')!==false)
			$command->bindParam(':remark',$this->remark,PDO::PARAM_STR);
		if (strpos($sql,':image_user')!==false)
			$command->bindParam(':image_user',$this->image_user,PDO::PARAM_STR);
		if (strpos($sql,':image_code')!==false)
			$command->bindParam(':image_code',$this->image_code,PDO::PARAM_STR);
		if (strpos($sql,':image_other')!==false)
			$command->bindParam(':image_other',$this->image_other,PDO::PARAM_STR);
		if (strpos($sql,':image_work')!==false)
			$command->bindParam(':image_work',$this->image_work,PDO::PARAM_STR);
		if (strpos($sql,':staff_type')!==false)
			$command->bindParam(':staff_type',$this->staff_type,PDO::PARAM_STR);
		if (strpos($sql,':test_length')!==false)
			$command->bindParam(':test_length',$this->test_length,PDO::PARAM_STR);
		if (strpos($sql,':staff_leader')!==false)
			$command->bindParam(':staff_leader',$this->staff_leader,PDO::PARAM_STR);
		if (strpos($sql,':attachment')!==false)
			$command->bindParam(':attachment',$this->attachment,PDO::PARAM_STR);
        if (strpos($sql,':nation')!==false)
            $command->bindParam(':nation',$this->nation,PDO::PARAM_STR);
        if (strpos($sql,':household')!==false)
            $command->bindParam(':household',$this->household,PDO::PARAM_STR);
        if (strpos($sql,':empoyment_code')!==false)
            $command->bindParam(':empoyment_code',$this->empoyment_code,PDO::PARAM_STR);
        if (strpos($sql,':social_code')!==false)
            $command->bindParam(':social_code',$this->social_code,PDO::PARAM_STR);
        if (strpos($sql,':fix_time')!==false)
            $command->bindParam(':fix_time',$this->fix_time,PDO::PARAM_STR);
        if (strpos($sql,':date_user_card')!==false)
            $command->bindParam(':date_user_card',$this->user_card_date,PDO::PARAM_STR);
        if (strpos($sql,':emergency_user')!==false)
            $command->bindParam(':emergency_user',$this->emergency_user,PDO::PARAM_STR);
        if (strpos($sql,':emergency_phone')!==false)
            $command->bindParam(':emergency_phone',$this->emergency_phone,PDO::PARAM_STR);

        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);

        //die();
		$command->execute();

        if ($this->scenario=='delete'){
            $this->deleteEmployee();
        }

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->lenStr();
            Yii::app()->db->createCommand()->update('hr_employee', array(
                'code'=>$this->code
            ), 'id=:id', array(':id'=>$this->id));
        }
        //審核
        if($audit){
            Yii::app()->db->createCommand()->update('hr_employee', array(
                'staff_status'=>2
            ), 'id=:id', array(':id'=>$this->id));

            //記錄
            Yii::app()->db->createCommand()->insert('hr_employee_history', array(
                "employee_id"=>$this->id,
                "status"=>"inset",
                "lcu"=>$uid,
                "lcd"=>date('Y-m-d H:i:s'),
            ));

            //發送郵件
            $this->sendEmail();
        }
        return true;
	}

	private function sendEmail(){
        $description="员工录入 - ".$this->name;
        $subject="员工录入 - ".$this->name;
        $message="<p>员工编号：".$this->code."</p>";
        $message.="<p>员工姓名：".$this->name."</p>";
        $message.="<p>员工所在城市：".Yii::app()->user->city_name()."</p>";
        $message.="<p>员工入职日期：".$this->entry_time."</p>";
        $email = new Email($subject,$message,$description);
        $email->addEmailToPrefix("ZG01");
        $email->sent();
    }

	private function lenStr(){
        $code = strval($this->id);
//Percy: Yii::app()->params['employeeCode']用來處理不同地區版本不同字首
        $this->code = Yii::app()->params['employeeCode'];
        for($i = 0;$i < 5-strlen($code);$i++){
            $this->code.="0";
        }
        $this->code .= $code;
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

    //刪除員工記錄表
    protected function deleteEmployee(){
        Yii::app()->db->createCommand()->delete('hr_employee_history', 'employee_id=:id',array(":id"=>$this->id));
    }

    //工資權限
    public function validateWageInput(){
	    if(Yii::app()->user->validFunction('ZR02')||Yii::app()->user->validRWFunction('ZG01')||Yii::app()->user->validRWFunction('ZG02')){
	        return true;
        }else{
	        return false;
        }
    }
}

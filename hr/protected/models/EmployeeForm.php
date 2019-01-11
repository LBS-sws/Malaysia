<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class EmployeeForm extends CFormModel
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
    public $historyList;//員工歷史
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
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, code, name, staff_id, company_id, contract_id, address, address_code, contact_address, contact_address_code, phone, phone2, user_card, department, position, wage,time,
             start_time, end_time, test_type, test_start_time, sex, test_end_time, test_wage, word_status, city, entry_time, age, birth_time, health,staff_status,
             ld_card, sb_card, jj_card, attachment,nation, household, empoyment_code, social_code, fix_time,
              education, experience, english, technology, other, year_day, email, remark, price1, price2, price3, image_user, image_code, image_work, image_other',
                'safe'),
			array('code','required'),
			array('name','required'),
            array('staff_id','required'),
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
			array('wage','required'),
			array('time','required'),
            array('fix_time','required'),
			array('start_time','required'),
			array('end_time','validateEndTime'),
			array('test_type','required'),
			array('test_type','validateTestType'),
            array('year_day','required'),
            array('year_day', 'validateYearDay'),
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
            ->where('id!=:id and code=:code ', array(':id'=>$this->id,':code'=>$this->code))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Employee Code'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
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
    //給勞務合同的文本賦予默認值
    public function resetStaff($staffList){
        foreach ($staffList as &$staff){
            foreach ($staff as &$value){
                if ($value === ""){
                    $value = "/";
                }
            }
        }
        return $staffList;
    }

    //生成合同文件
    public function updateEmployeeWord($employee_id,$arr=array()){

        $staff = EmployeeForm::getEmployeeToId($employee_id);
        if (!$staff){
            return false;
        }else{
            try{
                if(!empty($arr)){
                    $staff["word"] = $arr;
                }
                $staff = EmployeeForm::resetStaff($staff);
                $bool = true;
                if ($staff["staff"]["test_type"] != 1){
                    $bool = false;//無試用期
                }
                $contractBool = $staff["staff"]["fix_time"] == "fixation";
                $word = new Template($staff["word"],$bool,$contractBool,$staff["staff"]["city"]);

                $word->setValue("city",$staff["company"]["city"]);
                $word->setValue("companyname",$staff["company"]["name"]);
                $word->setValue("companyaddresspost2",$staff["company"]["postal2"]);//公司地址2 邮编
                $word->setValue("companyaddresspost",$staff["company"]["postal"]);//公司地址 邮编
                $word->setValue("companyaddress2",$staff["company"]["address2"]);//公司地址 2
                $word->setValue("companyaddress",$staff["company"]["address"]);
                $word->setValue("companyhead",$staff["company"]["head"]);
                $word->setValue("companyagent",$staff["company"]["agent"]);
                $word->setValue("companyphone",$staff["company"]["phone"]);
                $word->setValue("companyprotectno",$staff["company"]["security_code"]);//劳动保障代码
                $word->setValue("companyorgno",$staff["company"]["organization_code"]);//组织机构代码
                $word->setValue("companyregno",$staff["company"]["license_code"]);//证照编号
                
                $word->setValue("staffprovpostcode",$staff["staff"]["address_code"]);//原住地址 邮编
                $word->setValue("staffaddrpostcode",$staff["staff"]["contact_address_code"]);//通讯地址 邮编
                $word->setValue("staffdob",$staff["staff"]["birth_time"]);//出生日期
                $word->setValue("staffage",$staff["staff"]["age"]);//員工年齡
                $word->setValue("staffeducation",Yii::t("staff",$staff["staff"]["education"]));//学历
                $word->setValue("staffjoindate",date("Y-m-d",strtotime($staff["staff"]["entry_time"])));//入职时间
                $word->setValue("stafflanglevel",$staff["staff"]["english"]);//外语水平
                $word->setValue("stafftechlevel",$staff["staff"]["technology"]);//技术水平
                $word->setValue("staffotherinfo",$staff["staff"]["other"]);//补充资料-其它
                $word->setValue("staffprotectno",$staff["staff"]["social_code"]);//社会保障卡号
                $word->setValue("staffregno",$staff["staff"]["empoyment_code"]);//就业登记证号
                $word->setValue("staffprovtype",Yii::t("contract",$staff["staff"]["household"]));//戶籍類型
                $word->setValue("staffhealth",Yii::t("staff",$staff["staff"]["health"]));//身体状况
                $word->setValue("staffworkexp",$staff["staff"]["experience"]);//工作经验

                $word->setValue("staffname",$staff["staff"]["name"]);
                $word->setValue("staffcode",$staff["staff"]["code"]);
                $word->setValue("staffgender",Yii::t("contract",$staff["staff"]["sex"]));
                $word->setValue("staffidno",$staff["staff"]["user_card"]);
                $word->setValue("staffprov",$staff["staff"]["address"]);
                $word->setValue("staffaddress",$staff["staff"]["contact_address"]);
                $word->setValue("stafftelno",$staff["staff"]["phone"]);
                $word->setValue("staffdept",DeptForm::getDeptToId($staff["staff"]["department"]));
                $word->setValue("staffpost",DeptForm::getDeptToId($staff["staff"]["position"]));
                $word->setValue("staffsalary",$staff["staff"]["wage"]);//stafftestwage

                if($staff["staff"]["fix_time"] == "fixation"){
                    $word->setValue("staffyears1",date("Y",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffmonth1",date("m",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffday1",date("d",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffyears2",date("Y",strtotime($staff["staff"]["end_time"])));
                    $word->setValue("staffmonth2",date("m",strtotime($staff["staff"]["end_time"])));
                    $word->setValue("staffday2",date("d",strtotime($staff["staff"]["end_time"])));
                    $word->setValue("staffyears3","/");
                    $word->setValue("staffmonth3","/");
                    $word->setValue("staffday3","/");

                    $date1 = strtotime($staff["staff"]["end_time"]);
                    $date2 = strtotime($staff["staff"]["start_time"]);
                    $time_difference = $date1 - $date2;
                    $seconds_per_year = 60*60*24*365;
                    $yrs = round($time_difference / $seconds_per_year);
                    $duration = strval($yrs);
                    $word->setValue("staffduration",$duration);
                }else{
                    $word->setValue("staffyears1","/");
                    $word->setValue("staffmonth1","/");
                    $word->setValue("staffday1","/");
                    $word->setValue("staffyears2","/");
                    $word->setValue("staffmonth2","/");
                    $word->setValue("staffday2","/");
                    $word->setValue("staffyears3",date("Y",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffmonth3",date("m",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffday3",date("d",strtotime($staff["staff"]["start_time"])));
                    $word->setValue("staffduration","/");
                }
                if($staff["staff"]["test_type"]==1){
                    $word->setValue("stafftestyears1",date("Y",strtotime($staff["staff"]["test_start_time"])));
                    $word->setValue("stafftestmonth1",date("m",strtotime($staff["staff"]["test_start_time"])));
                    $word->setValue("stafftestday1",date("d",strtotime($staff["staff"]["test_start_time"])));
                    $word->setValue("stafftestyears2",date("Y",strtotime($staff["staff"]["test_end_time"])));
                    $word->setValue("stafftestmonth2",date("m",strtotime($staff["staff"]["test_end_time"])));
                    $word->setValue("stafftestday2",date("d",strtotime($staff["staff"]["test_end_time"])));
                    $test_start_time = strtotime($staff["staff"]["test_start_time"]);
                    $test_end_time = strtotime($staff["staff"]["test_end_time"]);
                    $yearNum = intval(date("Y",$test_end_time))-intval(date("Y",$test_start_time));
                    $monthNum = intval(date("m",$test_end_time))-intval(date("m",$test_start_time));
                    $testNum = $yearNum*12 + $monthNum;
                    if(intval(date("d",$test_end_time))>intval(date("d",$test_start_time))){
                        $testNum++;
                    }
                }else{
                    $word->setValue("stafftestyears1","/");
                    $word->setValue("stafftestmonth1","/");
                    $word->setValue("stafftestday1","/");
                    $word->setValue("stafftestyears2","/");
                    $word->setValue("stafftestmonth2","/");
                    $word->setValue("stafftestday2","/");
                    $testNum = "/";//　
                }
                $word->setValue("stafftestwage",$staff["staff"]["test_wage"]);
                $word->setValue("stafftest",$testNum);


                $word->save($staff["staff"]["code"]);
                //合同的地址格式：upload/staff/所在地區/員工編號.docx
                $wordUrl = "upload/staff/".$staff["staff"]["city"]."/".$staff["staff"]["code"].".docx";
                Yii::app()->db->createCommand()->update('hr_employee', array(
                    'word_status'=>1,
                    'word_url'=>$wordUrl
                ), 'id=:id', array(':id'=>$employee_id));

                return array(
                    "word_url"=>$wordUrl,
                    "name"=>$staff["staff"]["name"]
                );
            }catch (Exception $e){
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Error:Word Error , Not Font Word'));
                return false;
            }
        }
    }

    //根據id獲取公司員工合同信息
    public function getEmployeeToId($id){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where("id=:id and city in ($city_allow)", array(':id'=>$id))->queryAll();
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
            ->where('id=:id', array(':id'=>$id))->queryAll();
        if($rows){
            return $rows[0];
        }
        return "";
    }

	public function retrieveData($index)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("*,docman$suffix.countdoc('EMPLOY',id) as employdoc")->from("hr_employee")
            ->where("id=:id and city in ($city_allow)", array(':id'=>$index))->queryAll();
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
                $this->ld_card = $row['ld_card'];
                $this->sb_card = $row['sb_card'];
                $this->jj_card = $row['jj_card'];
                $this->historyList = AuditHistoryForm::getStaffHistoryList($this->id);
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
			case 'delete':
                $sql = "delete from hr_employee where id = :id and city = :city";
				break;
			case 'new':
				$sql = "insert into hr_employee(
							name, code, sex, staff_id, company_id, contract_id, city, address, contact_address, phone, user_card, department, position, wage, start_time, end_time, test_type, test_end_time, test_start_time, test_wage,
							 lcu, lcd, nation, household, empoyment_code, social_code, fix_time
						) values (
							:name, :code, :sex, :staff_id, :company_id, :contract_id, :city, :address, :contact_address, :phone, :user_card, :department, :position, :wage, :start_time, :end_time, :test_type, :test_end_time, :test_start_time, :test_wage,
							 :lcu, :lcd, :nation, :household, :empoyment_code, :social_code, :fix_time
						)";
				break;
			case 'edit':
				$sql = "update hr_employee set
							name = :name, 
							code = :code, 
							sex = :sex, 
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
							nation = :nation,
							household = :household,
							empoyment_code = :empoyment_code,
							social_code = :social_code,
							fix_time = :fix_time,
							lud = :lud,
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
		if (strpos($sql,':code')!==false)
			$command->bindParam(':code',$this->code,PDO::PARAM_STR);
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
		if (strpos($sql,':user_card')!==false)
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

        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcd')!==false)
			$command->bindParam(':lcd',date('Y-m-d H:i:s'),PDO::PARAM_STR);
		if (strpos($sql,':lud')!==false)
			$command->bindParam(':lud',date('Y-m-d H:i:s'),PDO::PARAM_STR);

        //die();
		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->scenario = "edit";
        }
        return true;
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

    //檢查是否有補充協議
    public function staffHasAgreement(){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee_history")
            ->where('employee_id=:employee_id and status in ("transfer","promotion","salary")', array(':employee_id'=>$this->id))->order('lcd desc')->queryAll();
        $arr = array();
        if($rows){
            foreach ($rows as $row){
                $staff_old =Yii::app()->db->createCommand()->select("city,change_city,finish")->from("hr_employee_operate")
                    ->where('id=:id', array(':id'=>$row['history_id']))->queryRow();
                if($staff_old["finish"] != 1){
                    continue;
                }
                if($row["status"] == "transfer"){
                    if($staff_old["city"] == $staff_old["change_city"]){
                        array_push($arr,$row);
                    }
                }else{
                    array_push($arr,$row);
                }
            }
        }
        return $arr;
    }

    //檢查是否有補充協議
    public function downAgreement($history_id){
        $word_url = AgreementForm::getAgreementUrl();
        if(empty($word_url)){
            throw new CHttpException(404,'協議文檔沒有配置，請與管理員聯繫');
        }else{
            $arr = $this->staffHasAgreement();
            if (empty($arr)){
                throw new CHttpException(404,'Not Find Agreement');
            }else{
                foreach ($arr as $key => $list){
                    if ($list["id"] == $history_id){
                        $staff["old"] = HistoryForm::getStaffToHistoryId($list["history_id"]);
                        if($key === 0){
                            $staff["now"] = $this->attributes;
                        }else{
                            $staff["now"] = HistoryForm::getStaffToHistoryId($arr[$key-1]["history_id"]);
                        }
                        $companyName = CompanyForm::getCompanyToId($staff["now"]["company_id"]);
                        $word = new Agreement($word_url,$this->city);

                        $word->setValue("oldDepartment",DeptForm::getDeptToId($staff["old"]["department"]));//崗位
                        $word->setValue("oldPosition",DeptForm::getDeptToId($staff["old"]["position"]));//職位
                        $word->setValue("oldWage",$staff["old"]["wage"]);//工資

                        $word->setValue("nowDepartment",DeptForm::getDeptToId($staff["now"]["department"]));//崗位
                        $word->setValue("nowPosition",DeptForm::getDeptToId($staff["now"]["position"]));//職位
                        $word->setValue("nowWage",$staff["now"]["wage"]);//工資

                        $word->setValue("companyname",$companyName["name"]);//公司名字
                        $word->setValue("staffname",$staff["now"]["name"]);//員工名字
                        $word->setValue("agreementyears",date("Y",strtotime($staff["old"]["effect_time"])));
                        $word->setValue("agreementmonth",date("m",strtotime($staff["old"]["effect_time"])));
                        $word->setValue("agreementday",date("d",strtotime($staff["old"]["effect_time"])));
                        $fileName = date("YmdHis",strtotime($list["lcd"]));
                        $word->save($fileName);
                        //協議的地址格式：upload/agreement/所在地區/協議時間.docx
                        $wordUrl = "upload/agreement/".$this->city."/".$fileName.".docx";
                        return $wordUrl;
                    }
                }
                throw new CHttpException(404,'Not Find Agreement');
            }
        }
    }
}

<?php

class LeaveForm extends CFormModel
{
	public $id;
	public $leave_code;
	public $employee_id;
	public $vacation_id;
	public $leave_cause;//加班原因
    public $leave_cost;//加班費用
	public $start_time;
	public $start_time_lg;
	public $end_time;
	public $end_time_lg='PM';
	public $log_time;
	public $z_index;//1:部門審核、2：主管、3：總監、4：你
	public $status;
	public $audit_remark;
    public $user_lcu;
    public $user_lcd;
	public $area_lcu;
	public $area_lcd;
	public $head_lcu;
	public $head_lcd;
    public $you_lcu;
    public $you_lcd;
	public $reject_cause;
	public $vacation_list;//倍率
	public $city;
	public $lcd;
	public $audit = false;//是否需要審核
    public $wage;//合約工資
    public $staff_type;//員工的辦公類型

    public $state;//員工的辦公類型



    public $no_of_attm = array(
        'leave'=>0
    );
    public $docType = 'LEAVE';
    public $docMasterId = array(
        'leave'=>0
    );
    public $files;
    public $removeFileId = array(
        'leave'=>0
    );

	public function attributeLabels()
	{
		return array(
            'leave_code'=>Yii::t('fete','Leave Code'),
            'vacation_id'=>Yii::t('fete','Leave Type'),
            'leave_cause'=>Yii::t('fete','Leave Cause'),
            'leave_cost'=>Yii::t('fete','Leave Cost'),
            'employee_id'=>Yii::t('contract','Employee Name'),
            'start_time'=>Yii::t('contract','Start Time'),
            'end_time'=>Yii::t('contract','End Time'),
            'log_time'=>Yii::t('fete','Log Date'),
            'status'=>Yii::t('contract','Status'),
            'user_lcu'=>Yii::t('fete','user lcu'),
            'user_lcd'=>Yii::t('fete','user lcd'),
            'area_lcu'=>Yii::t('fete','area lcu'),
            'area_lcd'=>Yii::t('fete','area lcd'),
            'head_lcu'=>Yii::t('fete','head lcu'),
            'head_lcd'=>Yii::t('fete','head lcd'),
            'you_lcu'=>Yii::t('fete','you lcu'),
            'you_lcd'=>Yii::t('fete','you lcd'),
            'audit_remark'=>Yii::t('fete','Audit Remark'),
            'reject_cause'=>Yii::t('contract','Rejected Remark'),
            'wage'=>Yii::t('contract','Contract Pay'),
            'lcd'=>Yii::t('fete','apply for time'),
            'state'=>Yii::t('contract','Status'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id,leave_code,employee_id,vacation_id,city,status,leave_cause,start_time,end_time,start_time_lg,end_time_lg,log_time,lcd','safe'),
            array('employee_id','validateUser','on'=>array("new","edit","audit")),
            array('vacation_id','required','on'=>array("new","edit","audit")),
            array('leave_cause','required','on'=>array("new","edit","audit")),
            array('log_time','required','on'=>array("new","edit","audit")),
            array('start_time','required','on'=>array("new","edit","audit")),
            array('end_time','required','on'=>array("new","edit","audit")),
            array('end_time','validateEndTime','on'=>array("new","edit","audit")),
            array('vacation_id','validateLeaveType','on'=>array("new","edit","audit")),
            array('log_time','validateLogTime','on'=>array("new","edit","audit")),
            array('log_time','numerical','allowEmpty'=>true,'integerOnly'=>false,'on'=>array("new","edit","audit")),
            array('files, removeFileId, docMasterId','safe'),
		);
	}

	public function validateUser($attribute, $params){
        if(Yii::app()->user->validFunction('ZR06')){
            if(empty($this->employee_id)){
                $message = Yii::t('contract','Employee Name').Yii::t('contract',' not exist');
                $this->addError($attribute,$message);
            }else{
                $employeeList = EmployeeForm::getEmployeeOneToId($this->employee_id);
                if($employeeList){
                    $this->city = $employeeList["city"];
                }else{
                    $message = "用戶不存在";
                    $this->addError($attribute,$message);
                }
            }
        }
    }
    //獲取年假的最大日期
    public function getMaxYearLeaveDate($employee_id,$time){
        $entry_time = date("Y/m/d",strtotime(date("Y/m/d")."+2 year"));
        $sql = "SELECT entry_time FROM hr_employee WHERE staff_status = 0 AND id=$employee_id";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if($row){
            $year = empty($time)?date("Y"):date("Y",strtotime($time));
            $thisMonth = empty($time)?date("/m/d"):date("/m/d",strtotime($time));
            $month = date("/m/d",strtotime($row["entry_time"]." - 1 day"));
            if($thisMonth>$month){
                $year++;
            }
            $entry_time = $year.$month;
        }
        return $entry_time;
    }

	//驗證請假類型
    public function validateLeaveType($attribute, $params){
	    $id = $this->vacation_id;
        $rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_vacation")->where("id='$id'")->queryRow();
        if($rows){
            $this->vacation_list = $rows;
            if($rows["vaca_type"] == "E"){ //年假
                $yearDay =YearDayForm::getSumDayToYear($this->employee_id,$this->start_time);
                $leaveNum =LeaveForm::getLeaveNumToYear($this->employee_id,$this->start_time);
                $maxDate = LeaveForm::getMaxYearLeaveDate($this->employee_id,$this->start_time);
                $leaveNum =$yearDay - floatval($leaveNum);
                if(floatval($this->log_time) > $leaveNum){
                    $message = Yii::t('fete','Log Date')."不能大于".$leaveNum."天";
                    $this->addError($attribute,$message);
                }
                if(date("Y-m-d",strtotime($this->end_time))>date("Y-m-d",strtotime($maxDate))){
                    $message = Yii::t('contract','End Time')."不能大于".$maxDate;
                    $this->addError($attribute,$message);
                }
            }
            if($rows["log_bool"]  == 1){
                if(floatval($this->log_time) > floatval($rows["max_log"])){
                    $message = Yii::t('fete','Log Date')."不能大于".$rows["max_log"]."天";
                    $this->addError($attribute,$message);
                }
            }
        }else{
            $message = Yii::t('fete','Leave Type').Yii::t('contract',' not exist');
            $this->addError($attribute,$message);
        }
    }
	//驗證時間週期
    public function validateLogTime($attribute, $params){
        if(!empty($this->log_time)){
            if(!is_numeric($this->log_time)){
                $message = Yii::t('fete','Log Date')."必須为数字";
                $this->addError($attribute,$message);
            }else{
                if (strpos($this->log_time,'.')!==false){
                    //含有小數
                    $float = end(explode(".",$this->log_time));
                    $float = intval($float);
                    if($float !== 5 && $float !== 0){
                        $message = Yii::t('fete','Log Date')."的小数必须为0.5";
                        $this->addError($attribute,$message);
                    }
                }
            }
        }
    }
    //請假時間段的驗證
    public function validateEndTime($attribute, $params){
        if(!empty($this->start_time)&&!empty($this->end_time)){
            if($this->start_time_lg == "AM"){
                $startTime = date("Y-m-d",strtotime($this->start_time))." 10:00:00";
            }else{
                $startTime = date("Y-m-d",strtotime($this->start_time))." 14:00:00";
            }
            if($this->end_time_lg == "AM"){
                $endTime = date("Y-m-d",strtotime($this->end_time))." 10:00:00";
            }else{
                $endTime = date("Y-m-d",strtotime($this->end_time))." 14:00:00";
            }
            $sql = "select leave_code from hr_employee_leave WHERE ((start_time>'$startTime' AND end_time <'$endTime') OR (start_time<='$startTime' AND end_time >='$startTime') OR (start_time<='$endTime' AND end_time >='$endTime')) ";
            //var_dump($sql);die();
            if(Yii::app()->user->validFunction('ZR06')){
                $sql.=" and employee_id='".$this->employee_id."'";
            }else{
                $sql.=" and employee_id='".$this->getEmployeeIdToUser()."'";;
            }
            if(!empty($this->id)&&is_numeric($this->id)){
                $sql.=" and id!=".$this->id;
            }
            $connection = Yii::app()->db;
            $rows = $connection->createCommand($sql)->queryRow();
            if($rows){
                $message = Yii::t('fete','A leave order has been issued during this period')."：".$rows["leave_code"];
                $this->addError($attribute,$message);
            }
        }
    }

    //根據加班id獲取加班信息
    public function getLeaveListToLeaveId($leave_id){
        $connection = Yii::app()->db;
        $sql = "select a.*,b.name AS employee_name,b.code AS employee_code ,b.entry_time,b.department,b.position,d.name AS vacation_name,d.vaca_type,e.name as company_name
                from hr_employee_leave a 
                LEFT JOIN hr_employee b ON a.employee_id = b.id
                LEFT JOIN hr_company e ON b.company_id=e.id
                LEFT JOIN hr_vacation d ON a.vacation_id=d.id
                where a.id =$leave_id
			";
        $records = $connection->createCommand($sql)->queryRow();
        if($records){
            $records["dept_name"]=DeptForm::getDeptToId($records["department"]);
            $records["posi_name"]=DeptForm::getDeptToId($records["position"]);
            if($records["vaca_type"] == "E"){ //年假
                $records["sumDay"] =YearDayForm::getSumDayToYear($records["employee_id"],$records["start_time"]);
                $records["leaveNum"] =LeaveForm::getLeaveNumToYear($records["employee_id"],$records["start_time"],true,$records['lcd']);
            }else{
                $records["sumDay"]=0;
                $records["leaveNum"]=0;
            }
            return $records;
        }else{
            return false;
        }
    }

    //獲取員工的簽名信息
    public function getSignatureToStaffId($staff_id,$bool = true){
        if($bool){
            $row = Yii::app()->db->createCommand()->select("*")
                ->from("hr_binding")->where("employee_id=:employee_id",array(":employee_id"=>$staff_id))->queryRow();
        }else{
            $row = array("user_id"=>$staff_id);
        }
        if($row){
            $suffix = Yii::app()->params['envSuffix'];
            $user_id = $row["user_id"];
            $field_blob = Yii::app()->db->createCommand()->select("field_blob")
                ->from("security$suffix.sec_user_info")->where("username=:username and field_id='signature'",array(":username"=>$user_id))->queryRow();
            if($field_blob){
                $field_blob = $field_blob["field_blob"];
                $field_value = Yii::app()->db->createCommand()->select("field_value")
                    ->from("security$suffix.sec_user_info")->where("username=:username and field_id='signature_file_type'",array(":username"=>$user_id))->queryRow();
                if($field_value){
                    $field_value = $field_value["field_value"];
                    if(!empty($field_value)&&!empty($field_blob)){
                        return array(
                            "field_blob"=>$field_blob,
                            //"field_blob"=>base64_decode($field_blob),
                            "field_value"=>$field_value,
                        );
                    }
                }
            }
        }
        return false;
    }

    //某年累積的請假天數（僅年假)
    public function getLeaveNumToYear($employee_id,$time="",$endBool=false,$lcd=''){
        if(empty($employee_id)){
            return 0;
        }
        $rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_employee")->where("id=:id",array(":id"=>$employee_id))->queryRow();
        if(!$rows){
            return 0;
        }
        if(empty($time)){
            $time = date("Y-m-d H:i:s");
        }
        $year = date("Y",strtotime($time));
        $month = date("m",strtotime($rows["entry_time"]));
        $day = date("d",strtotime($rows["entry_time"]));
        if(date("m-d",strtotime($time))>=date("m-d",strtotime($rows["entry_time"]))){
            $start_time = "$year-$month-$day 00:00:00";
            $end_time = (intval($year)+1)."-$month-$day 00:00:00";
        }else{
            $start_time = (intval($year)-1)."-$month-$day 00:00:00";
            $end_time = "$year-$month-$day 00:00:00";
        }
        $statusSql = "a.status NOT IN (0,3)";
        if($endBool){
            //$end_time = date("Y-m-d 23:59:59",strtotime($time));
            $statusSql = "a.status =  4 and a.lcd<='$lcd'";
        }
        $sql = "select sum(a.log_time) AS sumDay from hr_employee_leave a 
            LEFT JOIN hr_vacation b ON a.vacation_id = b.id
            WHERE a.start_time>'$start_time'AND a.start_time<='$end_time' AND $statusSql AND b.vaca_type='E' AND a.employee_id=$employee_id";
        //var_dump($sql);die();
        $Sum = Yii::app()->db->createCommand($sql)->queryRow();
        if($Sum){
            return $Sum["sumDay"];
        }else{
            return 0;
        }
    }

	public function retrieveData($index) {
        $lcuId = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()->select("a.*,b.wage,b.city as s_city,b.staff_type,b.name as employee_name,docman$suffix.countdoc('LEAVE',a.id) as leavedoc")
            ->from("hr_employee_leave a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.id=:id and b.city in ($city_allow)",array(":id"=>$index))->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
                $this->id = $row['id'];
                $this->leave_code = $row['leave_code'];
                $this->employee_id = $row['employee_id'];
                $this->wage = $row['wage'];
                $this->staff_type = $row['staff_type'];
                $this->vacation_id = $row['vacation_id'];
                $this->leave_cause = $row['leave_cause'];
                $this->start_time = date("Y/m/d",strtotime($row['start_time']));
                $this->end_time = date("Y/m/d",strtotime($row['end_time']));
                $this->log_time = $row['log_time'];
                $this->z_index = $row['z_index'];
                $this->start_time_lg = $row['start_time_lg'];
                $this->end_time_lg = $row['end_time_lg'];
                $this->state = LeaveForm::translationState($row['z_index']);
                $this->status = $row['status'];
                $this->user_lcd = $row['user_lcd'];
                $this->area_lcu = $row['area_lcu'];
                $this->area_lcd = $row['area_lcd'];
                $this->lcd = $row['lcd'];
                $this->head_lcu = $row['head_lcu'];
                $this->head_lcd = $row['head_lcd'];
                $this->you_lcu = $row['you_lcu'];
                $this->you_lcd = $row['you_lcd'];
                $this->city = $row['s_city'];
                $this->audit_remark = $row['audit_remark'];
                $this->reject_cause = $row['reject_cause'];
                $this->leave_cost = $row['leave_cost'];
                $this->no_of_attm['leave'] = $row['leavedoc'];
                break;
			}
		}
		return true;
	}

//1:部門審核、2：主管、3：總監、4：你
	public function translationState($str){
        switch ($str){
            case 1:
                return "部門審核（數據輸入 → 審核）";
            case 2:
                return "主管審核（員工 → 審核）";
            case 3:
                return "總監審核（審核 → 審核）";
            case 4:
                return "最高審核（系統設置 → 審核）";
            default:
                return $str;
        }
    }

    //刪除驗證
    public function deleteValidate(){
        return true;
    }
    //獲取員工工作日
    public function getUserWorkDay(){
        $dayNum = $this->staff_type == "Office"?22:26;
        return $dayNum;
    }
    //獲取假期的倍率
    public function getMuplite(){
        $id = $this->vacation_id;
        $rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_vacation")->where("id='$id'")->queryRow();
        if($rows){
            $sub_multiple = floatval($rows["sub_multiple"])/100;
        }else{
            $sub_multiple = 1.5;
        }
        return $sub_multiple;
    }
    //獲取當前城市的所有請假類型
    public function getLeaveTypeList($city){
        if(empty($city)){
            $city = Yii::app()->user->city();
        }
        $arr = array();
        $rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_vacation")->where("city='$city' OR only='default'")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveGoods($connection);
            $this->updateDocman($connection,'LEAVE');
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update. ('.$e->getMessage().')');
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

	protected function saveGoods(&$connection) {
		$sql = '';
        switch ($this->scenario) {
            case 'delete':
                $sql = "delete from hr_employee_leave where id = :id";
                break;
            case 'cancel':
                $sql = "delete from hr_employee_leave where id = :id";
                break;
            case 'new':
                $sql = "insert into hr_employee_leave(
							employee_id,vacation_id,leave_cause, start_time_lg, end_time_lg, start_time, end_time, log_time, leave_cost, city, z_index, status, lcu
						) values (
							:employee_id,:vacation_id,:leave_cause, :start_time_lg, :end_time_lg, :start_time, :end_time, :log_time, :leave_cost, :city, :z_index, :status, :lcu
						)";
                break;
            case 'edit':
                $sql = "update hr_employee_leave set
							vacation_id = :vacation_id, 
							employee_id = :employee_id, 
							leave_cause = :leave_cause, 
							leave_cost = :leave_cost, 
							start_time_lg = :start_time_lg, 
							end_time_lg = :end_time_lg, 
							start_time = :start_time, 
							end_time = :end_time, 
							log_time = :log_time, 
							city = :city, 
							z_index = :z_index, 
							lcd = :lcd, 
							status = :status, 
							reject_cause = '', 
							luu = :luu
						where id = :id
						";
                break;
        }
		if (empty($sql)) return false;

        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;//ZR06
        if(!Yii::app()->user->validFunction('ZR06')){
            $employeeList = $this->getEmployeeOneToUser();
            $this->employee_id = $employeeList["id"];
            $this->city = $employeeList["city"];
        }

        $this->resetLeaveCost();//計算員工的工資

        $command=$connection->createCommand($sql);
        if (strpos($sql,':id')!==false)
            $command->bindParam(':id',$this->id,PDO::PARAM_INT);
        if (strpos($sql,':employee_id')!==false)
            $command->bindParam(':employee_id',$this->employee_id,PDO::PARAM_STR);
        if (strpos($sql,':vacation_id')!==false)
            $command->bindParam(':vacation_id',$this->vacation_id,PDO::PARAM_STR);
        if (strpos($sql,':leave_cause')!==false)
            $command->bindParam(':leave_cause',$this->leave_cause,PDO::PARAM_STR);
        if (strpos($sql,':leave_cost')!==false)
            $command->bindParam(':leave_cost',$this->leave_cost,PDO::PARAM_STR);
        if (strpos($sql,':start_time_lg')!==false)
            $command->bindParam(':start_time_lg',$this->start_time_lg,PDO::PARAM_STR);
        if (strpos($sql,':end_time_lg')!==false)
            $command->bindParam(':end_time_lg',$this->end_time_lg,PDO::PARAM_STR);
        if (strpos($sql,':start_time')!==false)
            $command->bindParam(':start_time',$this->start_time,PDO::PARAM_STR);
        if (strpos($sql,':end_time')!==false)
            $command->bindParam(':end_time',$this->end_time,PDO::PARAM_STR);
        if (strpos($sql,':log_time')!==false)
            $command->bindParam(':log_time',$this->log_time,PDO::PARAM_STR);
        if (strpos($sql,':status')!==false)
            $command->bindParam(':status',$this->status,PDO::PARAM_STR);
        if (strpos($sql,':z_index')!==false){
            $z_index = AuditConfigForm::getCityAuditToCode($this->employee_id,1);
            $this->z_index = $z_index;
            $command->bindParam(':z_index',$this->z_index,PDO::PARAM_STR);
        }

        if (strpos($sql,':lcd')!==false)
            $command->bindParam(':lcd',date('Y-m-d H:i:s'),PDO::PARAM_STR);
        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
        if (strpos($sql,':luu')!==false)
            $command->bindParam(':luu',$uid,PDO::PARAM_STR);
        if (strpos($sql,':lcu')!==false)
            $command->bindParam(':lcu',$uid,PDO::PARAM_STR);
        $command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            Yii::app()->db->createCommand()->update('hr_employee_leave', array(
                'leave_code'=>"Q".$this->lenStr($this->id)
            ), 'id=:id', array(':id'=>$this->id));
        }

        //發送郵件
        $this->sendEmail();
		return true;
	}

	protected function sendEmail(){
        if($this->audit){
            $assList=array(
                1=>"ZA09",
                2=>"ZE06",
                3=>"ZG05",
                4=>"ZC11",
            );
            $email = new Email();
            $row = Yii::app()->db->createCommand()->select("*")->from("hr_employee")
                ->where('id=:id', array(':id'=>$this->employee_id))->queryRow();
            $description="新的请假单 - ".$row["name"];
            $subject="新的请假单 - ".$row["name"];
            $message="<p>请假编号：".$this->leave_code."</p>";
            $message.="<p>员工编号：".$row["code"]."</p>";
            $message.="<p>员工姓名：".$row["name"]."</p>";
            $message.="<p>员工城市：".General::getCityName($row["city"])."</p>";
            $message.="<p>请假时间：".$this->start_time." ~ ".$this->end_time."  (".$this->log_time."天)</p>";
            $message.="<p>请假原因：".$this->leave_cause."</p>";
            $email->setDescription($description);
            $email->setMessage($message);
            $email->setSubject($subject);
            $assType = $assList[$this->z_index];
            switch ($this->z_index){
                case 1:
                    $email->addEmailToPrefixAndPoi($assType,$row["department"]);
                    break;
                case 2:
                    $email->addEmailToPrefixAndOnlyCity($assType,$row["city"]);
                    break;
                default:
                    $email->addEmailToPrefixAndCity($assType,$row["city"]);
            }
            $email->sent();
        }
    }


	//獲取綁定員工的列表(解決地區變化問題$staff_id)
    public function getBindEmployeeList($staff_id=0){
        $city_allow = Yii::app()->user->city_allow();
        $arr = array(""=>"");
        $rows = Yii::app()->db->createCommand()->select("a.employee_id as id,b.name as name")->from("hr_binding a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where("b.city in ($city_allow) or b.id=:id",array(":id"=>$staff_id))->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }


    //獲取上午下午列表
    public function getAMPMList(){
        return array(
            "AM"=>Yii::t("fete","AM"),
            "PM"=>Yii::t("fete","PM")
        );
    }

	//計算員工的請假費用
    public function resetLeaveCost(){
        if($this->audit){
            $this->status = 1;
        }else{
            $this->status = 0;
        }
        $startTime = strtotime($this->start_time);
        $weekStart = getdate($startTime);
        $startPm = $this->start_time_lg;
        $endTime = strtotime($this->end_time);
        $weekEnd = getdate($endTime);
        $endPm = $this->end_time_lg;
        $day = ($endTime-$startTime)/(60*60*24);
        if($startPm != $endPm){
            if($startPm =="AM"){
                $day++;
            }
        }else{
            $day+=0.5;
        }
        if(in_array($weekStart["wday"],array(0,6))||in_array($weekEnd["wday"],array(0,6))||$day>=6||$weekStart["wday"]>$weekEnd["wday"]){
            //允許修改時間
            $day = $this->log_time;
        }
        $this->log_time = $day;
        if($startPm == "AM"){
            $this->start_time.=" 9:00:00";
        }else{
            $this->start_time.=" 13:00:00";
        }
        if($endPm == "AM"){
            $this->end_time.=" 12:00:00";
        }else{
            $this->end_time.=" 18:00:00";
        }
        $employeeList = EmployeeForm::getEmployeeOneToId($this->employee_id);
        $wage = floatval($employeeList["wage"]);
        $vacationList = $this->vacation_list;
        if($vacationList["sub_bool"] == 1){ //
            $dayNum = $employeeList["staff_type"] == "Office"?22:26;
            $sub_multiple = floatval($vacationList["sub_multiple"])/100;
            $this->leave_cost = ($wage/$dayNum)*floatval($this->log_time)*$sub_multiple;
        }else{
            $this->leave_cost = 0;
        }
    }

    private function lenStr($id){
        $code = strval($id);
//Percy: Yii::app()->params['employeeCode']用來處理不同地區版本不同字首
        $str = Yii::app()->params['employeeCode'];
        for($i = 0;$i < 5-strlen($code);$i++){
            $str.="0";
        }
        $str .= $code;
        $this->leave_code = $str;
        return $str;
    }

	//獲取當前用戶的員工id
	public function getEmployeeIdToUser(){
        $uid = Yii::app()->user->id;
        $rows = Yii::app()->db->createCommand()->select("employee_id")->from("hr_binding")
            ->where('user_id=:user_id',
                array(':user_id'=>$uid))->queryRow();
        if ($rows){
            return $rows["employee_id"];
        }
        return "";
    }
	//獲取當前用戶的員工id
	public function getEmployeeOneToUser(){
        $uid = Yii::app()->user->id;
        $rows = Yii::app()->db->createCommand()->select("b.id,b.city")->from("hr_binding a")
            ->leftJoin("hr_employee b","a.employee_id=b.id")
            ->where('user_id=:user_id',array(':user_id'=>$uid))->queryRow();
        if ($rows){
            return $rows;
        }
        return "";
    }

	//判斷輸入框能否修改
	public function getInputBool(){
        if($this->scenario == "view"){
            return true;
        }
        if($this->status == 1 || $this->status == 2 || $this->status == 4){
            return true;
        }
        return false;
    }
}

<?php

class LeaveList extends CListPageModel
{
    public $employee_id;//員工id
    public $searchTimeStart;//開始日期
    public $searchTimeEnd;//結束日期
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'leave_code'=>Yii::t('fete','Leave Code'),
			'vacation_id'=>Yii::t('fete','Leave Type'),
			'employee_name'=>Yii::t('contract','Employee Name'),
            'employee_code'=>Yii::t('contract','Employee Code'),
			'start_time'=>Yii::t('contract','Start Time'),
			'end_time'=>Yii::t('contract','End Time'),
			'log_time'=>Yii::t('fete','Log Date'),
			'status'=>Yii::t('contract','Status'),
            'city'=>Yii::t('contract','City'),
            'city_name'=>Yii::t('contract','City'),
            'lcd'=>Yii::t('fete','apply for time'),
		);
	}

    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, searchTimeStart, searchTimeEnd','safe',),
        );
    }
	//驗證賬號是否綁定員工
    public function validateEmployee(){
        $uid = Yii::app()->user->id;
        $rows = Yii::app()->db->createCommand()->select("employee_id,employee_name")->from("hr_binding")
            ->where('user_id=:user_id',
                array(':user_id'=>$uid))->queryRow();
        if ($rows){
            $this->employee_id = $rows["employee_id"];
            return true;
        }
        return false;
    }

	public function retrieveDataByPage($pageNum=1)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $lcuId = Yii::app()->user->id;
        $city_allow = Yii::app()->user->city_allow();
        $employee_id = $this->employee_id;
        $manager = AuditConfigForm::getManager($employee_id);
        //,docman$suffix.countdoc('LEAVE',a.id) as leavedoc
		$sql1 = "select  a.*,b.name AS employee_name,b.code AS employee_code,b.city AS s_city ,docman$suffix.countdoc('LEAVE',a.id) as leavedoc
              from hr_employee_leave a 
              LEFT JOIN hr_employee b ON a.employee_id = b.id 
              LEFT JOIN hr_dept d ON b.position = d.id 
              where a.id!=0 ";
		$sql2 = "select count(a.id)
				from hr_employee_leave a 
				LEFT JOIN hr_employee b ON a.employee_id = b.id 
                LEFT JOIN hr_dept d ON b.position = d.id 
				where a.id!=0 ";
		if(!Yii::app()->user->validFunction('ZR04')){
            $sql1.=" and d.manager <= ".$manager["manager"];
            $sql2.=" and d.manager <= ".$manager["manager"];
        }
        if(Yii::app()->user->validFunction('ZR04')||in_array($manager["manager"],array(2,3,4))){
            $sql1.=" and ((b.city in($city_allow) and a.status !=0) or a.employee_id='$employee_id' or a.lcu='$lcuId') ";
            $sql2.=" and ((b.city in($city_allow) and a.status !=0) or a.employee_id='$employee_id' or a.lcu='$lcuId') ";
        }elseif($manager["manager"] == 1){
            $sql1.=" and ((b.department='".$manager["department"]."' ";
            $sql2.=" and ((b.department='".$manager["department"]."' ";
            if(!empty($manager["group_type"])){
                $sql1.=" and b.group_type='".$manager["group_type"]."' ";
                $sql2.=" and b.group_type='".$manager["group_type"]."' ";
            }
            $sql1.=" and a.status !=0) or a.employee_id='$employee_id') ";
            $sql2.=" and a.status !=0) or a.employee_id='$employee_id') ";
        }else{
            $sql1.=" and (a.employee_id='$employee_id' or a.lcu='$lcuId') ";
            $sql2.=" and (a.employee_id='$employee_id' or a.lcu='$lcuId') ";
        }
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'leave_code':
					$clause .= General::getSqlConditionClause('a.leave_code',$svalue);
					break;
				case 'vacation_id':
					$clause .= General::getSqlConditionClause('vacation_id',$svalue);
					break;
                case 'employee_name':
                    $clause .= General::getSqlConditionClause('b.name',$svalue);
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
            $clause .= " and a.start_time >='$svalue 00:00:00' ";
        }
        if (!empty($this->searchTimeEnd) && !empty($this->searchTimeEnd)) {
            $svalue = str_replace("'","\'",$this->searchTimeEnd);
            $clause .= " and a.start_time <='$svalue 23:59:59' ";
        }
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by a.id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
			    $colorList = $this->statusToColor($record['status']);
				$this->attr[] = array(
					'id'=>$record['id'],
					'leavedoc'=>$record['leavedoc'],
					'leave_code'=>$record['leave_code'],
					'employee_name'=>$record['employee_name'],
					'employee_code'=>$record['employee_code'],
					'lcd'=>CGeneral::toDateTime($record['lcd']),
					'start_time'=>date("Y/m/d",strtotime($record['start_time'])),
					'end_time'=>date("Y/m/d",strtotime($record['end_time'])),
					'log_time'=>$record['log_time']."天",
					'vacation_id'=>VacationForm::getVacationNameToId($record['vacation_id']),
					'status'=>$colorList["status"],
                    'city'=>CGeneral::getCityName($record["s_city"]),
					'style'=>$colorList["style"],
				);
			}
		}
		$session = Yii::app()->session;
		$session['leave_01'] = $this->getCriteria();
		return true;
	}

    //根據狀態獲取顏色
    public function statusToColor($status){
        switch ($status){
            // text-danger
            case 0:
                return array(
                    "status"=>Yii::t("contract","Draft"),
                    "style"=>""
                );
            case 1:
                return array(
                    "status"=>Yii::t("contract","Sent, pending approval"),//已發送，等待審核
                    "style"=>" text-primary"
                );
            case 2:
                return array(
                    "status"=>Yii::t("contract","audit"),//審核通過
                    "style"=>" text-yellow"
                );
            case 3:
                return array(
                    "status"=>Yii::t("contract","Rejected"),//拒絕
                    "style"=>" text-danger"
                );
            case 4:
                return array(
                    "status"=>Yii::t("fete","approve"),//批准
                    "style"=>" text-green"
                );
        }
        return array(
            "status"=>"",
            "style"=>""
        );
    }
}

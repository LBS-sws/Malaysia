<?php
class RptOverTimeList extends CReport {
	protected function fields() {
		return array(
			'work_code'=>array('label'=>Yii::t('fete','Work Code'),'width'=>15,'align'=>'L'),
			'employee_code'=>array('label'=>Yii::t('contract','Employee Code'),'width'=>22,'align'=>'L'),
			'employee_name'=>array('label'=>Yii::t('contract','Employee Name'),'width'=>30,'align'=>'L'),
			'work_type'=>array('label'=>Yii::t('fete','Work Type'),'width'=>25,'align'=>'C'),
			'start_time'=>array('label'=>Yii::t('contract','Start Time'),'width'=>20,'align'=>'L'),
			'end_time'=>array('label'=>Yii::t('contract','End Time'),'width'=>20,'align'=>'L'),
			'log_time'=>array('label'=>Yii::t('fete','Log Date'),'width'=>15,'align'=>'L'),
			'lcd'=>array('label'=>Yii::t('fete','apply for time'),'width'=>15,'align'=>'L'),
			'work_cause'=>array('label'=>Yii::t('fete','Work Cause'),'width'=>30,'align'=>'L'),
			'work_address'=>array('label'=>Yii::t('fete','Work Address'),'width'=>30,'align'=>'L'),
		);
	}
	
	public function genReport() {
		$this->retrieveData();
		$this->title = $this->getReportName();
		$this->subtitle = Yii::t('report','Date').':'.$this->criteria['START_DT'].' - '.$this->criteria['END_DT'].' / '
			.Yii::t('report','Staffs').':'.$this->criteria['STAFFSDESC']
			;
		return $this->exportExcel();
	}

	public function retrieveData() {
        $start_dt = $this->criteria['START_DT'];
        $end_dt = $this->criteria['END_DT'];
        $start_dt = date("Y-m-d 00:00:00",strtotime($start_dt));
        $end_dt = date("Y-m-d 23:59:59",strtotime($end_dt));
		$city = $this->criteria['CITY'];
		$staff_id = $this->criteria['STAFFS'];
		
		$citymodel = new City();
		$citylist = $citymodel->getDescendantList($city);
		$citylist = empty($citylist) ? "'$city'" : "$citylist,'$city'";
		
		$suffix = Yii::app()->params['envSuffix'];
		
		$cond_staff = '';
		if (!empty($staff_id)) {
			$ids = explode('~',$staff_id);
			if(count($ids)>1){
                $cond_staff = implode(",",$ids);
            }else{
                $cond_staff = $staff_id;
            }
			if ($cond_staff!=''){
                $cond_staff = " and a.employee_id in ($cond_staff)";
            } 
		}
        $sql = "select a.*,b.name AS employee_name,b.code AS employee_code,b.city AS s_city 
                from hr_employee_work a 
                LEFT JOIN hr_employee b ON a.employee_id = b.id
                where b.city in($citylist) and a.status=4 and a.start_time >= '$start_dt' and a.start_time <= '$end_dt' 
                $cond_staff
				order by b.lcd desc, a.id
			";
		$rows = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($rows) > 0) {
            $costNumList = WorkList::getWorkTypeList();
			foreach ($rows as $row) {
                WorkList::resetWorkDate($row);
                $temp = array();
                $temp['start_time'] = date("Y/m/d H:i:s",strtotime($row['start_time']));
                $temp['end_time'] = date("Y/m/d H:i:s",strtotime($row['end_time']));
                $dayStr ="小時";
				$temp['work_address'] = $row['work_address'];
				$temp['work_cause'] = $row['work_cause'];
				$temp['work_code'] = $row['work_code'];
				$temp['employee_code'] = $row['employee_code'];
				$temp['employee_name'] = $row['employee_name'];
				$temp['work_type'] = $costNumList[$row['work_type']];
				$temp['log_time'] = $row['log_time'].$dayStr;
                $temp['lcd'] = $row['lcd'];
				$this->data[] = $temp;
			}
		}
		return true;
	}
	
	public function getReportName() {
		$city_name = isset($this->criteria) ? ' - '.General::getCityName($this->criteria['CITY']) : '';
		return (isset($this->criteria) ? Yii::t('report',$this->criteria['RPT_NAME']) : Yii::t('report','Nil')).$city_name;
	}
}
?>
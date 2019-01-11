<?php
/* Reimbursement Form */

class ReportY03Form extends CReportForm
{
    public $staffs;
    public $staffs_desc;
	
	protected function labelsEx() {
		return array(
            'staffs'=>Yii::t('report','Staffs'),
        );
	}
	
	protected function rulesEx() {
        return array(
            array('staffs, staffs_desc','safe'),
        );
	}
	
	protected function queueItemEx() {
		return array(
            'STAFFS'=>$this->staffs,
            'STAFFSDESC'=>$this->staffs_desc,
        );
	}
	
	public function init() {
		$this->id = 'RptLeaveList';
		$this->name = Yii::t('app','Leave record List');
		$this->format = 'EXCEL';
		$this->city = Yii::app()->user->city();
        $this->fields = 'start_dt,end_dt,staffs,staffs_desc';
        $this->start_dt = date("Y/m/d");
        $this->end_dt = date("Y/m/d");
        $this->staffs = '';
        $this->staffs_desc = Yii::t('misc','All');
	}
}

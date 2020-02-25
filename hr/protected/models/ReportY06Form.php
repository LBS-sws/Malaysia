<?php
/* Reimbursement Form */

class ReportY06Form extends CReportForm
{
    public $staffs;
    public $staffs_desc;
    public $year_type;

	protected function labelsEx() {
		return array(
            'staffs'=>Yii::t('report','Staffs'),
            'year_type'=>Yii::t('contract','year type'),
        );
	}
	
	protected function rulesEx() {
        return array(
            array('staffs, staffs_desc, year_type','safe'),
        );
	}
	
	protected function queueItemEx() {
		return array(
            'STAFFS'=>$this->staffs,
            'STAFFSDESC'=>$this->staffs_desc,
            'YEARTYPE'=>$this->year_type,
        );
	}
	
	public function init() {
		$this->id = 'RptReviewList';
		$this->name = Yii::t('app','Optimize assessment report');
		$this->format = 'EXCEL';
		$this->city = Yii::app()->user->city();
        $this->fields = 'city,year,year_type,staffs,staffs_desc';
        $this->year = date("Y");
        $this->year_type = 1;
        $this->staffs = '';
        $this->staffs_desc = Yii::t('misc','All');
	}

    public static function getCityList() {
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $list = array();
        $suffix = Yii::app()->params['envSuffix'];
        $clause = !empty($city_allow) ? " a.code in ($city_allow)" : " a.code ='$city'";
        $sql = "select a.code, a.name from security$suffix.sec_city a 
					where $clause order by a.code
			";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $row) {
                $list[$row['code']] = $row['name'];
            }
        }
        return $list;
    }
}

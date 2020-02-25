<?php

class SupportEmployeeList extends CListPageModel
{
    public $year;
    public $employee_id;

    public $cityList=array();

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
            'support_code'=>Yii::t('contract','support code'),
            'apply_city'=>Yii::t('contract','apply city'),
            'apply_date'=>Yii::t('contract','Start Time'),
            'apply_end_date'=>Yii::t('contract','End Time'),
            'year'=>Yii::t('fete','Year'),
            'employee_id'=>Yii::t('contract','support employee'),
            'review_sum'=>Yii::t('contract','review sum'),
            'status_type'=>Yii::t('contract','Status'),
		);
	}

    public function rules()
    {
        return array(
            array('year, employee_id','safe',),
        );
    }

	public function retrieveDataByPage($pageNum=1)
	{
        $suffix = Yii::app()->params['envSuffix'];
        $year = $this->year;
        $employee_id=$this->employee_id;
		$sql1 = "select a.*,b.name as city_name from hr_apply_support a 
                LEFT JOIN security$suffix.sec_city b ON a.apply_city =b.code 
                where a.status_type NOT IN (1,2,3,4) and  (date_format(a.apply_date,'%Y')='$year' OR date_format(a.apply_end_date,'%Y')='$year') and a.employee_id='$employee_id' 
			";
		$records = Yii::app()->db->createCommand($sql1)->queryAll();

		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
			    if(!in_array($record['apply_city'],$this->cityList)){
                    $this->cityList[] = $record['apply_city'];
                }
                $day = 30;
                $month = date("m",strtotime($record['apply_date']));
			    if($month == 2){
			        $day = date("Y",strtotime($record['apply_date']))%4==0?29:28;
                }else if (in_array($month,array(1,3,5,7,8,10,12))){
			        $day = 31;
                }
                if(date("Y",strtotime($record['apply_date']))!=$this->year){
                    $width = (strtotime($record['apply_end_date'])-strtotime($this->year."/01/01"))/(60*60*24*$day);
                }else{
                    $width = (strtotime($record['apply_end_date'])-strtotime($record['apply_date']))/(60*60*24*$day);
                }
                $width = sprintf("%.2f",$width);
				$this->attr[] = array(
					'start_date'=>date("Y年m月d日",strtotime($record['apply_date'])),
					'end_date'=>date("Y年m月d日",strtotime($record['apply_end_date'])),
					'width'=>$width,
					'url'=>Yii::app()->createUrl('supportSearch/view',array('index'=>$record['id'])),
					'day'=>$day,
					'city'=>$record['apply_city'],
					'city_name'=>$record['city_name'],
					'support_code'=>$record['support_code'],
				);
			}
		}
		$session = Yii::app()->session;
		$session['supportEmployee_01'] = $this->getCriteria();
		return true;
	}

    public function getYearList(){
	    $arr = array(""=>"选择年份");
        $rows = Yii::app()->db->createCommand()->select("date_format(a.apply_date,'%Y') as year,date_format(a.apply_end_date,'%Y') as end_year")->from("hr_apply_support a")
            ->group("year,end_year")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["year"]] = $row["year"];
                $arr[$row["end_year"]] = $row["end_year"];
            }
        }
        return $arr;
    }

    public function getEmployeeList(){
        $suffix = Yii::app()->params['envSuffix'];
	    $arr = array(""=>"选择员工");
        $rows = Yii::app()->db->createCommand()->select("c.id,c.name")->from("hr_apply_support a")
            ->leftJoin("hr_employee c","c.id = a.employee_id")->group("c.id,c.name")->queryAll();
        if($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    public function getStatus($arr){
	    $list =SupportApplyList::getStatusList();
	    if(key_exists($arr["status_type"],$list)){
            return $list[$arr["status_type"]];
        }else{
            return array(
                "status"=>Yii::t("contract","not sent"),
                "style"=>"text-danger"
            );//未發送
        }
    }

    public function getCriteria() {
        return array(
            'year'=>$this->year,
            'employee_id'=>$this->employee_id,
        );
    }
}

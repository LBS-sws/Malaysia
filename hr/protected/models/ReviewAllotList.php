<?php

class ReviewAllotList extends CListPageModel
{

    public $year;
    public $year_type;


	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'id'=>Yii::t('contract','ID'),
			'name'=>Yii::t('contract','Employee Name'),
			'code'=>Yii::t('contract','Employee Code'),
			'phone'=>Yii::t('contract','Employee Phone'),
			'position'=>Yii::t('contract','Position'),
			'company_id'=>Yii::t('contract','Company Name'),
			'contract_id'=>Yii::t('contract','Contract Name'),
			'status'=>Yii::t('contract','Status'),
			'city'=>Yii::t('contract','City'),
            'city_name'=>Yii::t('contract','City'),
            'entry_time'=>Yii::t('contract','Entry Time'),
            'year'=>Yii::t('contract','what year'),
            'year_type'=>Yii::t('contract','year type'),
            'department'=>Yii::t("contract","Department"),
            'review_type'=>Yii::t('contract','review type'),
		);
	}
    public function __construct($scenario='')
    {
        if(empty($this->year_type)){
            $this->year_type = intval(date("m"))<7?1:2;
        }
        if(empty($this->year)){
            $this->year = date("Y");
        }
        parent::__construct();
    }

    public function rules()
    {
        return array(
            array('attr, pageNum, noOfItem, totalRow, searchField, searchValue, orderField, orderType, filter, year, year_type','safe',),
        );
    }

    public function getReviewDateTime($year,$year_type){
        $dateTime = $year."/";
        if($year_type==1){
            $dateTime.="06/30";
        }else{
            $dateTime.="12/31";
        }
	    return $dateTime;
    }

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $dateTime = $this->getReviewDateTime($this->year,$this->year_type);
        //$dateTime = date("Y/m/d",strtotime("$dateTime - 3 month"));
        //$expr_sql = " and (b.year=$this->year or b.year is null) and (b.year_type=$this->year_type or b.year_type is null)";
		$sql1 = "select a.id,a.name,a.code,a.phone,a.city,a.entry_time,c.name as company_name,d.name as dept_name,d.review_type ,e.name as ment_name 
                from hr_employee a 
                LEFT JOIN hr_company c ON a.company_id = c.id
                LEFT JOIN hr_dept d ON a.position = d.id
                LEFT JOIN hr_dept e ON a.department = e.id
                where a.city IN ($city_allow) AND a.staff_status = 0 AND d.review_type IN (1,2,3,4) AND replace(entry_time,'-', '/')<='$dateTime' 
			";
		$sql2 = "select count(*) from hr_employee a 
                LEFT JOIN hr_company c ON a.company_id = c.id
                LEFT JOIN hr_dept d ON a.position = d.id
                LEFT JOIN hr_dept e ON a.department = e.id
                where a.city IN ($city_allow) AND a.staff_status = 0 AND d.review_type IN (1,2,3,4) AND replace(entry_time,'-', '/')<='$dateTime' 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'name':
					$clause .= General::getSqlConditionClause('a.name',$svalue);
					break;
				case 'code':
					$clause .= General::getSqlConditionClause('a.code',$svalue);
					break;
				case 'phone':
					$clause .= General::getSqlConditionClause('a.phone',$svalue);
					break;
                case 'position':
                    $clause .= General::getSqlConditionClause('d.name',$svalue);
                    break;
                case 'department':
                    $clause .= General::getSqlConditionClause('e.name',$svalue);
                    break;
                case 'city_name':
                    $clause .= ' and a.city in '.WordForm::getCityCodeSqlLikeName($svalue);
                    break;
                case 'status':
                    $clause .= $this->searchStatus($svalue);
                    break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
		    if($this->orderField == "status_type"){
                $order .=$this->orderStatusType();
            }else{
                $order .= " order by ".$this->orderField." ";
                if ($this->orderType=='D') $order .= "desc ";
            }
		}else{
            $order .= " order by a.id asc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		
		$list = array();
		$this->attr = array();
		if (count($records) > 0) {
            $time = date("Y-m-d");
            $reviewTypeList = DeptForm::getReviewType();
			foreach ($records as $k=>$record) {
                $arr = $this->resetStatus($record);
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
					'year'=>empty($record['year'])?$this->year:$record['year'],
					'year_type'=>$this->getYearTypeList($record['year_type']),
					'code'=>$record['code'],
					'position'=>$record['dept_name'],
                    'review_type'=>key_exists($record["review_type"],$reviewTypeList)?$reviewTypeList[$record["review_type"]]:$record["review_type"],
					'department'=>$record['ment_name'],
					'company_id'=>$record['company_name'],
					'phone'=>$record['phone'],
					'status'=>$arr["status"],
					'reviewdoc'=>$record["reviewdoc"],
					'style'=>$arr["style"],
                    'city'=>CGeneral::getCityName($record["city"]),
                    'entry_time'=>$record["entry_time"],
				);
			}
		}
		$session = Yii::app()->session;
		$session['reviewAllot_01'] = $this->getCriteria();
		return true;
	}

    public function getCriteria() {
        return array(
            'searchField'=>$this->searchField,
            'searchValue'=>$this->searchValue,
            'orderField'=>$this->orderField,
            'orderType'=>$this->orderType,
            'noOfItem'=>$this->noOfItem,
            'pageNum'=>$this->pageNum,
            'filter'=>$this->filter,
            'year'=>$this->year,
            'year_type'=>$this->year_type,
        );
    }

    protected function orderStatusType(){
        if ($this->orderType=='D'){
            $order_type = "desc";
        }else{
            $order_type = "asc";
        }
        $rows = Yii::app()->db->createCommand()
            ->select("employee_id")
            ->from("hr_review")
            ->where("year = :year and year_type = :year_type",
                array(
                    ":year"=>$this->year,
                    ":year_type"=>$this->year_type,
                )
            )->order("status_type $order_type")->queryAll();
        if($rows){
            $rows = implode(",",array_column($rows,"employee_id"));
            return " order by find_in_set(a.id,'$rows'),a.name $order_type";
        }else{
            return " order by a.name $order_type ";
        }
    }

	protected function resetStatus(&$record){
	    //,b.status_type,b.year,b.year_type,b.id as review_id
        $suffix = Yii::app()->params['envSuffix'];
        $rows = Yii::app()->db->createCommand()
            ->select("status_type,year,review_type,year_type,id as review_id,docman$suffix.countdoc('REVIEW',id) as reviewdoc")
            ->from("hr_review")
            ->where("employee_id=:id and year = :year and year_type = :year_type",
                array(
                    ":id"=>$record["id"],
                    ":year"=>$this->year,
                    ":year_type"=>$this->year_type,
                )
            )->queryRow();
        if($rows){
            if($rows['status_type'] != 4){
                $record["review_type"] = $rows["review_type"];
            }
            $record["status_type"] = $rows["status_type"];
            $record["year"] = $rows["year"];
            $record["year_type"] = $rows["year_type"];
            $record["review_id"] = $rows["review_id"];
            $record["reviewdoc"] = $rows["reviewdoc"];
        }else{
            $record["reviewdoc"] = 0;
            $record["status_type"] = 0;
            $record["year"] = $this->year;
            $record["year_type"] = $this->year_type;
            $record["review_id"] = 0;
        }

        return $this->getReviewStatuts($record["status_type"]);
    }

    public function getReviewStatuts($str){
        switch ($str){
            case 1:
                return array(
                    "status"=>Yii::t("contract","in review"),
                    "style"=>"text-primary"
                );//評核中
                break;
            case 2:
                return array(
                    "status"=>Yii::t("contract","more review"),
                    "style"=>"text-yellow"
                );//部分評核完成
                break;
            case 3:
                return array(
                    "status"=>Yii::t("contract","success review"),
                    "style"=>"text-success"
                );//評核完成
                break;
            case 4:
                return array(
                    "status"=>Yii::t("contract","Draft"),
                    "style"=>""
                );//評核完成
                break;
            default:
                return array(
                    "status"=>Yii::t("contract","none review"),
                    "style"=>"text-danger"
                );//未評核
        }
    }

    private function searchStatus($str){
        if($str === ""){
            return "";
        }
        $arr = array(
            //0=>Yii::t("contract","none review"),
            1=>Yii::t("contract","in review"),
            2=>Yii::t("contract","more review"),
            3=>Yii::t("contract","success review"),
            4=>Yii::t("contract","Draft"),
        );
        $idList = array();
        if($str === Yii::t("contract","none review")||$str === "未"){
            $rows = Yii::app()->db->createCommand()->select("employee_id")->from("hr_review")
                ->where("year = :year and year_type = :year_type",
                    array(":year"=>$this->year,":year_type"=>$this->year_type)
                )->queryAll();
            if($rows){
                foreach ($rows as $row){
                    if(!in_array($row["employee_id"],$idList)){
                        $idList[] = $row["employee_id"];
                    }
                }
            }
            if(!empty($idList)){
                return " and a.id not in (".implode(",",$idList).")";
            }
        }else{
            foreach ($arr as $key =>$item){
                if (strpos($item,$str)!==false){
                    $rows = Yii::app()->db->createCommand()->select("employee_id")->from("hr_review")
                        ->where("status_type=:status_type and year = :year and year_type = :year_type",
                            array(":status_type"=>$key,":year"=>$this->year,":year_type"=>$this->year_type)
                        )->queryAll();
                    if($rows){
                        foreach ($rows as $row){
                            if(!in_array($row["employee_id"],$idList)){
                                $idList[] = $row["employee_id"];
                            }
                        }
                    }
                }
            }
            if(!empty($idList)){
                return " and a.id in (".implode(",",$idList).")";
            }
        }
        return " and a.id=0";
    }

    public function getYearTypeList($num=-1){
	    if($num === -1){
	        return array(
	            1=>Yii::t("contract","first half year"),
	            2=>Yii::t("contract","last half year")
            );
        }else{
	        return $num ==2?Yii::t("contract","last half year"):Yii::t("contract","first half year");
        }
    }

    public function getYearList(){
        $year = date("Y");
        $arr = array();
        for ($i = $year-5;$i<$year+5;$i++){
            if($i<=2018){
                continue;
            }
            $arr[$i] = $i.Yii::t("contract"," year");
        }
        return $arr;
    }
}

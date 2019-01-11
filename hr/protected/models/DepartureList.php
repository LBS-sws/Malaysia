<?php

class DepartureList extends CListPageModel
{
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
			'leave_time'=>Yii::t('contract','Leave Time'),
			'status'=>Yii::t('contract','Status'),
			'city'=>Yii::t('contract','City'),
			'city_name'=>Yii::t('contract','City'),
            'entry_time'=>Yii::t('contract','Entry Time'),
		);
	}

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
		$sql1 = "select * from hr_employee
                where city in ($city_allow) AND staff_status = -1
			";
		$sql2 = "select count(id)
				from hr_employee 
				where city in ($city_allow) AND staff_status = -1
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'name':
					$clause .= General::getSqlConditionClause('name',$svalue);
					break;
				case 'code':
					$clause .= General::getSqlConditionClause('code',$svalue);
					break;
				case 'phone':
					$clause .= General::getSqlConditionClause('phone',$svalue);
					break;
                case 'position':
                    $clause .= ' and position in '.DeptForm::getDeptSqlLikeName($svalue);
                    break;
                case 'city_name':
                    $clause .= ' and city in '.WordForm::getCityCodeSqlLikeName($svalue);
                    break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}else{
            $order .= " order by id desc ";
        }

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		
		$list = array();
		$this->attr = array();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
			    $arr = $this->returnStaffStatus($record["test_type"],$record["test_start_time"],$record["test_end_time"],$record["staff_status"]);
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
					'code'=>$record['code'],
                    'position'=>DeptForm::getDeptToid($record['position']),
					'company_id'=>CompanyForm::getCompanyToId($record['company_id'])["name"],
					//'contract_id'=>ContractForm::getContractNameToId($record['contract_id']),
					'phone'=>$record['phone'],
					'leave_time'=>$record['leave_time'],
					'status'=>$arr["status"],
                    'city'=>CGeneral::getCityName($record["city"]),
					'style'=>$arr["style"],
                    'entry_time'=>$record["entry_time"],
				);
			}
		}
		$session = Yii::app()->session;
		$session['departure_01'] = $this->getCriteria();
		return true;
	}

	public function returnStaffStatus($testType,$start_time,$end_time,$staff_status=0){
	    $date = date("Y-m-d");
	    if($staff_status == -1){
            return array(
                "status"=>Yii::t("contract","departure"),
                "style"=>"text-danger"
            );//離職
        }
	    if($testType == 0){
	        return array(
	            "status"=>Yii::t("contract","formal staff"),
                "style"=>"text-primary"
            );//正式員工
        }else{
	        if(strtotime($date) >= strtotime($end_time)){
                return array(
                    "status"=>Yii::t("contract","formal staff"),
                    "style"=>"text-primary"
                );//正式員工
            }elseif(strtotime($date) >= strtotime($start_time)){
                return array(
                    "status"=>Yii::t("contract","probation period"),
                    "style"=>"text-yellow"
                );//試用期
            }else{
                return array(
                    "status"=>Yii::t("contract","No entry"),
                    "style"=>"text-success"
                );//未入职
            }
        }
    }
}

<?php

class EmployList extends CListPageModel
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
			'staff_status'=>Yii::t('contract','Status'),
            'entry_time'=>Yii::t('contract','Entry Time'),
            'city'=>Yii::t('contract','City'),
            'city_name'=>Yii::t('contract','City'),
		);
	}

    //獲取性別列表
    public function getSexList(){
        return array(""=>"","man"=>Yii::t("contract","man"),"woman"=>Yii::t("contract","woman"));
    }
    //獲取年齡列表
    public function getAgeList(){
        $list = array(""=>"");
        for ($num = 18;$num<70;$num++){
            $list[$num] = $num;
        }
        return $list;
    }
    //獲取健康列表
    public function getHealthList(){
        return array(""=>"","poor"=>Yii::t("staff","poor"),"general"=>Yii::t("staff","general"),"good"=>Yii::t("staff","good"));
    }
    //獲取戶籍列表
    public function getNationList(){
        return array(
            ""=>"",
            "Non-agricultural"=>Yii::t("contract","Non-agricultural"),
            "Agricultural"=>Yii::t("contract","Agricultural")
        );
    }
    //獲取合同期限列表
    public function getFixTimeList(){
        return array(
            "fixation"=>Yii::t("contract","fixation"),
            "nofixed"=>Yii::t("contract","nofixed")
        );
    }
    //獲取合同期限列表
    public function getOperationTypeList($staff_id = 0,$type=""){
        if(empty($staff_id)){
            $num = "";
        }else{
            $num = EmployList::getContractNumber($staff_id);
            if($type == "change"){
                $num++;
            }
            $num = " - ".$num;
        }
        return array(
            ""=>"",
            "salary"=>Yii::t("contract","salary"),
            "promotion"=>Yii::t("contract","promotion"),
            "transfer"=>Yii::t("contract","transfer"),
            "contract"=>Yii::t("contract","contract").$num
        );
    }
    //獲取健康列表
    public function getMonthList(){
        $list = array(""=>"");
        for ($num = 1;$num<=12;$num++){
            $list[$num]=$num.Yii::t("staff"," months");
        }
        return $list;
    }
    //獲取學歷列表
    public function getEducationList(){
        return array(
            ""=>"",
            "Primary school"=>Yii::t("staff","Primary school"),
            "Junior school"=>Yii::t("staff","Junior school"),
            "High school"=>Yii::t("staff","High school"),
            "Technical school"=>Yii::t("staff","Technical school"),
            "College school"=>Yii::t("staff","College school"),
            "Undergraduate"=>Yii::t("staff","Undergraduate"),
            "Graduate"=>Yii::t("staff","Graduate"),
            "Doctorate"=>Yii::t("staff","Doctorate")
        );
    }
    //獲取員工職能列表
    public function getStaffLeaderList(){
        return array("Nil"=>Yii::t("staff","Nil"),"Group Leader"=>Yii::t("staff","Group Leader"),"Team Leader"=>Yii::t("staff","Team Leader"));
    }
    //獲取員工類別列表
    public function getStaffTypeList(){
        return array(""=>"","Office"=>Yii::t("staff","Office"),"Sales"=>Yii::t("staff","Sales"),"Technician"=>Yii::t("staff","Technician"),"Others"=>Yii::t("staff","Others"));
    }
    //技術員
    public function getTechnicianList(){
        return array(Yii::t("misc","No"),Yii::t("misc","Yes"));
    }
    //經理級別
    public function getManagerList(){
        return array(
            Yii::t("fete","none"),
            Yii::t("fete","handle"),
            Yii::t("fete","charge"),
            Yii::t("fete","director"),
            Yii::t("fete","you")
        );
    }

    //獲取員工續約的次數
    public function getContractNumber($staff_id){
        $num = Yii::app()->db->createCommand()->select("count('id')")->from("hr_employee_history")
            ->where('employee_id=:employee_id and status="contract"',array(":employee_id"=>$staff_id))->queryScalar();
        if($num){
            return $num;
        }else{
            return 0;
        }
    }

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
		$sql1 = "select *,docman$suffix.countdoc('EMPLOY',id) as employdoc from hr_employee
                where city='$city' AND staff_status != 0 AND staff_status != -1
			";
		$sql2 = "select count(id)
				from hr_employee 
				where city='$city' AND staff_status != 0 AND staff_status != -1
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
			    $arr = $this->translateEmploy($record['staff_status']);
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
					'employdoc'=>$record['employdoc'],
					'code'=>$record['code'],
					'position'=>DeptForm::getDeptToid($record['position']),
					'company_id'=>CompanyForm::getCompanyToId($record['company_id'])["name"],
					//'contract_id'=>ContractForm::getContractNameToId($record['contract_id']),
					'phone'=>$record['phone'],
					'staff_status'=>$arr["status"],
					'style'=>$arr["style"],
                    'entry_time'=>$record["entry_time"],
				);
			}
		}
		$session = Yii::app()->session;
		$session['employ_01'] = $this->getCriteria();
		return true;
	}


	public function translateEmploy($status,$remark=0){
	    switch ($status){
	        // text-danger
            case 1:
                return array(
                    "status"=>Yii::t("contract","Draft"),
                    "style"=>""
                );
            case 2:
                return array(
                    "status"=>Yii::t("contract","Sent, pending approval"),//已發送，等待審核
                    "style"=>" text-primary"
                );
            case 3:
                return array(
                    "status"=>Yii::t("contract","Rejected"),//拒絕
                    "style"=>" text-danger"
                );
            case 4:
                return array(
                    "status"=>Yii::t("contract","Wait for social security"),//等待社保
                    "style"=>" text-yellow"
                );
        }
        return array(
            "status"=>"",
            "style"=>""
        );
    }
}

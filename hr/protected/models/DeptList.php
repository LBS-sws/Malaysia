<?php

class DeptList extends CListPageModel
{
    public $type = 0;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(	
			'id'=>Yii::t('contract','ID'),
			'z_index'=>Yii::t('contract','Level'),
			'city'=>Yii::t('misc','City'),
			'name'=>Yii::t('contract',' Name'),
            'name_0'=>Yii::t('contract','Dept Name'),
			'name_1'=>Yii::t('contract','Leader Name'),
			'dept_id'=>Yii::t('contract','in department'),
			'dept_class'=>Yii::t('contract','Job category'),
		);
	}
	public function getTypeName(){
	    if ($this->type == 1){
            return Yii::t("contract","Leader");
        }else{
            return Yii::t("contract","Dept");
        }
    }
	public function getTypeAcc(){
	    if ($this->type == 1){
            return "ZC02";
        }else{
            return "ZC01";
        }
    }

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
		$type = $this->type;
		$sql1 = "select * from hr_dept 
                where type=$type 
			";
		$sql2 = "select count(id)
				from hr_dept 
				where type=$type 
			";
		$clause = "";
        $rw = Yii::app()->user->validRWFunction($this->getTypeAcc());
        if(!$rw){
            $sql1.=" and city='$city' ";
            $sql2.=" and city='$city' ";
        }

		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'name':
					$clause .= General::getSqlConditionClause('name',$svalue);
					break;
				case 'city':
					$clause .= ' and city in '.WordForm::getCityCodeSqlLikeName($svalue);
					break;
			}
		}
		
		$order = "";
		if (!empty($this->orderField)) {
			$order .= " order by ".$this->orderField." ";
			if ($this->orderType=='D') $order .= "desc ";
		}

		$sql = $sql2.$clause;
		$this->totalRow = Yii::app()->db->createCommand($sql)->queryScalar();
		
		$sql = $sql1.$clause.$order;
		$sql = $this->sqlWithPageCriteria($sql, $this->pageNum);
		$records = Yii::app()->db->createCommand($sql)->queryAll();
		
		$list = array();
		$this->attr = array();
		$userList = CompanyForm::getUserList();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
					'city'=>WordForm::getCityNameToCode($record['city']),
					'z_index'=>$record['z_index'],
					'dept_id'=>$record['dept_id'],
					'dept_class'=>Yii::t("staff",$record['dept_class']),
                    'acc'=>$this->getTypeAcc()
				);
			}
		}
		$session = Yii::app()->session;
		$session['dept_01'] = $this->getCriteria();
		return true;
	}

}

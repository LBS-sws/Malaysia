<?php

class CompanyList extends CListPageModel
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
			'city'=>Yii::t('contract','City'),
			'name'=>Yii::t('contract','Company Name'),
			'head'=>Yii::t('contract','Head'),
			'agent'=>Yii::t('contract','Agent'),
			'tacitly'=>Yii::t('contract','Status'),
            'city'=>Yii::t('contract','City'),
		);
	}

	public function retrieveDataByPage($pageNum=1)
	{
		$suffix = Yii::app()->params['envSuffix'];
		$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
		$sql1 = "select * from hr_company 
                where city in ($city_allow) 
			";
		$sql2 = "select count(id)
				from hr_company 
				where city in ($city_allow) 
			";
		$clause = "";
		if (!empty($this->searchField) && !empty($this->searchValue)) {
			$svalue = str_replace("'","\'",$this->searchValue);
			switch ($this->searchField) {
				case 'name':
					$clause .= General::getSqlConditionClause('name',$svalue);
					break;
				case 'head':
					$clause .= General::getSqlConditionClause('head',$svalue);
					break;
				case 'city':
					$clause .= General::getSqlConditionClause('city',$svalue);
					break;
				case 'agent':
					$clause .= General::getSqlConditionClause('agent',$svalue);
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
		//$userList = CompanyForm::getUserList();
		if (count($records) > 0) {
			foreach ($records as $k=>$record) {
				$this->attr[] = array(
					'id'=>$record['id'],
					'name'=>$record['name'],
					'head'=>$record['head'],
					'agent'=>$record['agent'],
                    'city'=>CGeneral::getCityName($record["city"]),
					'tacitly'=>$record['tacitly']
				);
			}
		}
		$session = Yii::app()->session;
		$session['company_01'] = $this->getCriteria();
		return true;
	}

	//獲取管轄城市列表
    public function getSingleCityToList() {
        $str = Yii::app()->session['city_allow'];
        $city = Yii::app()->session['city'];
        $cityName = Yii::app()->session['city_name'];
        $items = explode(",",str_replace("'","",$str));
        $arr = array();
        if (($items===false) || empty($items)){
            $arr[$city] = $cityName;
        }else{
            if(count($items)<=1){
                $arr[$city] = $cityName;
            }else{
                foreach ($items as $item){
                    $arr[$item] = CGeneral::getCityName($item);
                }
            }
        }
        return $arr;
    }

}

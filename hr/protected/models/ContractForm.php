<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class ContractForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $name;
	public $city;
	public $word_arr=array();
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('staff','Record ID'),
			'name'=>Yii::t('contract','Contract Name'),
			'city'=>Yii::t('misc','City'),
			'word_arr'=>Yii::t('contract','Contract Word'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, name, word_arr, city','safe'),
			array('name','required'),
			array('city','required'),
			array('word_arr','required'),
			array('word_arr','validateWordArr'),
			array('name','validateName'),
		);
	}

	public function validateWordArr($attribute, $params){
	    if(!empty($this->word_arr)){
	        foreach ($this->word_arr as $word){
	            if(empty($word["name"])){
                    $message = Yii::t('contract','Word Name'). Yii::t('contract',' can not be empty');
                    $this->addError($attribute,$message);
                    return false;
                }
	            if(!is_numeric($word["index"])){
                    $message = Yii::t('contract','Level'). Yii::t('contract',' Must be Numbers');
                    $this->addError($attribute,$message);
                    return false;
                }
            }
        }
    }
	public function validateName($attribute, $params){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where('id!=:id and name=:name and city=:city ', array(':id'=>$this->id,':name'=>$this->name,':city'=>$this->city))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Contract Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

    //合同刪除時必須沒有員工
    public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('contract_id=:contract_id', array(':contract_id'=>$this->id))->queryAll();
        if ($rows){
            return false;
        }
        return true;
    }

    //獲取文檔的所有可選列表
    public function getWordList(){
	    $arr = array();
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_docx")->queryAll();
        if($rows){
            foreach ($rows as $word){
                $arr[$word["id"]] = $word["name"];
            }
        }
        return $arr;
    }
    //根據合同id獲取合同名字
    public function getContractNameToId($contract_id){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where('id=:id', array(':id'=>$contract_id))->queryAll();
        if($rows){
            return $rows[0]["name"];
        }
        return "";
    }

    //獲取合同下的所有文檔
    public function getWordListToConId($contract_id){
	    $arr = array();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract_docx")
            ->where('contract_id=:contract_id', array(':contract_id'=>$contract_id))->queryAll();
        if($rows){
            foreach ($rows as $word){
                $arr[$word["id"]] = array(
                    "id"=>$word["id"],
                    "name"=>$word["docx"],
                    "index"=>$word["index"]
                );
            }
        }
        return $arr;
    }
    //獲取合同下的所有文檔(并降序排序）
    public function getWordListToConIdDesc($contract_id){
	    $arr = array();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract_docx")
            ->where('contract_id=:contract_id', array(':contract_id'=>$contract_id))->order('index desc')->queryAll();
        if($rows){
            foreach ($rows as $word){
                $arr[$word["id"]] = array(
                    "id"=>$word["id"],
                    "name"=>$word["docx"],
                    "index"=>$word["index"]
                );
            }
        }
        return $arr;
    }
    public function delContractWordToId($id){
        $rows = Yii::app()->db->createCommand()->delete('hr_contract_docx', 'id=:id', array(':id'=>$id));
        return $rows;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_contract")
            ->where('id=:id', array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->name = $row['name'];
				$this->city = $row['city'];
				$this->word_arr =$this->getWordListToConId($row['id']);
				break;
			}
		}
		return true;
	}
	
	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveStaff($connection);
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.');
		}
	}

	protected function saveStaff(&$connection)
	{
		$sql = '';
        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from hr_contract where id = :id";
				break;
			case 'new':
				$sql = "insert into hr_contract(
							name, city, lcu
						) values (
							:name, :city, :lcu
						)";
				break;
			case 'edit':
				$sql = "update hr_contract set
							name = :name, 
							city = :city, 
							luu = :luu 
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':name')!==false)
			$command->bindParam(':name',$this->name,PDO::PARAM_STR);

        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);

		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            $this->scenario = "edit";
        }

        if(!empty($this->word_arr)){
            foreach ($this->word_arr as $item){
                if(empty($item["id"])){
                    //添加
                    Yii::app()->db->createCommand()->insert('hr_contract_docx', array(
                        'contract_id'=>$this->id,
                        'docx'=>$item["name"],
                        'index'=>$item["index"]
                    ));
                }else{
                    //修改
                    Yii::app()->db->createCommand()->update('hr_contract_docx', array(
                        'contract_id'=>$this->id,
                        'docx'=>$item["name"],
                        'index'=>$item["index"]
                    ), 'id=:id', array(':id'=>$item["id"]));
                }
            }
        }

        if ($this->scenario=='delete'){
            Yii::app()->db->createCommand()->delete('hr_contract_docx', 'contract_id=:contract_id', array(':contract_id'=>$this->id));
        }
        return true;
	}
}

<?php

class VacationForm extends CFormModel
{
	public $id;
	public $name;
	public $log_bool;
	public $max_log=0;
	public $sub_bool;
	public $sub_multiple=0;
	public $city;
    public $only;
    public $vaca_type;//休假類型

	public function attributeLabels()
	{
		return array(
            'name'=>Yii::t('fete','Vacation Name'),
            'city'=>Yii::t('contract','City'),
            'log_bool'=>Yii::t('fete','or max number of days'),
            'max_log'=>Yii::t('fete','most number of days'),
            'sub_bool'=>Yii::t('fete','Whether to deduct salary'),
            'sub_multiple'=>Yii::t('fete','deduct multiple'),
            'only'=>Yii::t('fete','Scope of application'),
            'vaca_type'=>Yii::t('fete','Vacation Type'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id, name,log_bool,max_log,sub_bool,city,sub_multiple,only,vaca_type','safe'),
            array('name','required'),
            array('city','required'),
            array('vaca_type','required'),
            array('only','required'),
			array('name','validateName'),
			array('name','validateLog'),
			array('name','validateSub'),
		);
	}

	public function validateName($attribute, $params){
        $city = $this->city;
        $id = -1;
        if(!empty($this->id)){
            $id = $this->id;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("hr_vacation")
            ->where('name=:name and city=:city and id!=:id',
                array(':name'=>$this->name,':id'=>$id,':city'=>$city))->queryAll();
        if(count($rows)>0){
            $message = Yii::t('contract','Reward Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
	}

	public function validateLog($attribute, $params){
        if($this->log_bool == 1 && empty($this->max_log)){
            $message = Yii::t('fete','most number of days'). Yii::t('contract',' can not be empty');
            $this->addError($attribute,$message);
        }
	}
	public function validateSub($attribute, $params){
        if($this->sub_bool == 1 && empty($this->sub_multiple)){
            $message = Yii::t('fete','deduct multiple'). Yii::t('contract',' can not be empty');
            $this->addError($attribute,$message);
        }
	}

	public function retrieveData($index) {
        $city_allow = Yii::app()->user->city_allow();
		$rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_vacation")->where("id=:id and (city in ($city_allow) OR only='default') ",array(":id"=>$index))->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
                $this->id = $row['id'];
                $this->name = $row['name'];
                $this->log_bool = $row['log_bool'];
                $this->max_log = $row['max_log'];
                $this->sub_bool = $row['sub_bool'];
                $this->sub_multiple = $row['sub_multiple'];
                $this->city = $row['city'];
                $this->only = $row['only'];
                $this->vaca_type = $row['vaca_type'];
                break;
			}
		}
		return true;
	}

    //根據id獲取請假類型
    public function getVacationNameToId($id){
        $rows = Yii::app()->db->createCommand()->select("name")
            ->from("hr_vacation")->where("id=:id",array(":id"=>$id))->queryRow();
        if($rows){
            return $rows["name"];
        }else{
            return $id;
        }
    }

    //根據id獲取請假類型
    public function getVacaTypeLIst(){
        return array(
            "E"=>Yii::t("fete","annual leave"),
            "A"=>Yii::t("fete","Overtime, special accommodation"),
            "B"=>Yii::t("fete","Wedding leave, funeral leave, nursing leave, maternity leave, late childbirth, breast-feeding leave"),
            "C"=>Yii::t("fete","Prenatal leave, sick leave"),
            "D"=>Yii::t("fete","Private affair leave")
        );
    }
    //刪除驗證
    public function deleteValidate(){
        return true;
    }

	public function saveData()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try {
			$this->saveGoods($connection);
			$transaction->commit();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update. ('.$e->getMessage().')');
		}
	}

	protected function saveGoods(&$connection) {
		$sql = '';
        switch ($this->scenario) {
            case 'delete':
                $sql = "delete from hr_vacation where id = :id";
                break;
            case 'new':
                $sql = "insert into hr_vacation(
							name,log_bool,max_log, sub_bool, sub_multiple, vaca_type, city, only, lcu
						) values (
							:name,:log_bool,:max_log, :sub_bool, :sub_multiple, :vaca_type, :city, :only, :lcu
						)";
                break;
            case 'edit':
                $sql = "update hr_vacation set
							name = :name, 
							log_bool = :log_bool, 
							vaca_type = :vaca_type, 
							max_log = :max_log, 
							sub_bool = :sub_bool, 
							city = :city, 
							only = :only, 
							sub_multiple = :sub_multiple, 
							luu = :luu
						where id = :id
						";
                break;
        }
		if (empty($sql)) return false;

        $city = Yii::app()->user->city();
        $uid = Yii::app()->user->id;

        $command=$connection->createCommand($sql);
        if (strpos($sql,':id')!==false)
            $command->bindParam(':id',$this->id,PDO::PARAM_INT);
        //log_bool,max_log,sub_bool,sub_multiple
        if (strpos($sql,':name')!==false)
            $command->bindParam(':name',$this->name,PDO::PARAM_STR);
        if (strpos($sql,':vaca_type')!==false)
            $command->bindParam(':vaca_type',$this->vaca_type,PDO::PARAM_STR);
        if (strpos($sql,':log_bool')!==false)
            $command->bindParam(':log_bool',$this->log_bool,PDO::PARAM_STR);
        if (strpos($sql,':max_log')!==false)
            $command->bindParam(':max_log',$this->max_log,PDO::PARAM_STR);
        if (strpos($sql,':sub_bool')!==false)
            $command->bindParam(':sub_bool',$this->sub_bool,PDO::PARAM_STR);
        if (strpos($sql,':sub_multiple')!==false)
            $command->bindParam(':sub_multiple',$this->sub_multiple,PDO::PARAM_STR);

        if (strpos($sql,':only')!==false)
            $command->bindParam(':only',$this->only,PDO::PARAM_STR);
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
		return true;
	}
}

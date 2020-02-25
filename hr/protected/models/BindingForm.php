<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class BindingForm extends CFormModel
{
	/* User Fields */
	public $id = 0;
	public $employee_id;
	public $employee_name;
	public $user_id;
	public $city;
	public $user_name;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('staff','Record ID'),
            'employee_id'=>Yii::t('contract','Employee Name'),
            'user_id'=>Yii::t('contract','Account number'),
            'city'=>Yii::t('contract','City'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, user_id, employee_id','safe'),
			array('user_id','required'),
			array('employee_id','required'),
			array('user_id','validateUser'),
			array('employee_id','validateEmployee'),
		);
	}

	public function validateUser($attribute, $params){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $from = "security".$suffix.".sec_user";
        $rows = Yii::app()->db->createCommand()->select("disp_name")->from($from)
            ->where("username=:username and city in ($city_allow)", array(':username'=>$this->user_id))->queryRow();
        if ($rows){
            $this->user_name = $rows["disp_name"];
        }else{
            $message = Yii::t('contract','Account number'). Yii::t('contract',' Did not find');
            $this->addError($attribute,$message);
        }
    }

	public function validateEmployee($attribute, $params){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("name,city")->from("hr_employee")
            ->where("id=:id and city in ($city_allow) and staff_status=0 ", array(':id'=>$this->employee_id))->queryRow();
        if ($rows){
            $this->employee_name = $rows["name"];
            $this->city = $rows["city"];
        }else{
            $message = Yii::t('contract','Employee Name'). Yii::t('contract',' Did not find');
            $this->addError($attribute,$message);
        }
    }
    //獲取用戶表的所有用戶(相同城市)
	public function getUserList(){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $from = "security".$suffix.".sec_user";
        $rows = Yii::app()->db->createCommand()->select("username,disp_name")->from($from)->where("city in ($city_allow) and status='A'")->queryAll();
        $bindList = Yii::app()->db->createCommand()->select("user_id")->from("hr_binding")->where("id !=:id",array(":id"=>$this->id))->queryAll();
        $bindList = array_column($bindList,"user_id");
        $arr = array(""=>"");
        foreach ($rows as $row){
            if(!in_array($row["username"],$bindList)){
                $arr[$row["username"]] = $row["disp_name"];
            }
        }
        return $arr;
    }
    //獲取用戶表的所有員工(相同城市)
	public function getEmployeeList(){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $from = "hr_employee";
        $rows = Yii::app()->db->createCommand()->select("id,name")->from($from)->where("city in ($city_allow) and staff_status=0")->queryAll();
        $bindList = Yii::app()->db->createCommand()->select("employee_id")->from("hr_binding")->where("id !=:id",array(":id"=>$this->id))->queryAll();
        $arr = array(""=>"");
        $bindList = array_column($bindList,"employee_id");
        foreach ($rows as $row){
            if(!in_array($row["id"],$bindList)){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    public function getEmployeeIdToUsername($username=""){
	    if(empty($username)){
            $username = Yii::app()->user->id;
        }
        $rows = Yii::app()->db->createCommand()->select("employee_id")->from("hr_binding")
            ->where("user_id=:user_id", array(':user_id'=>$username))->queryRow();
        if($rows){
            return $rows["employee_id"];
        }
	    return 0;
    }

    public function getEmployeeListToUsername($username=""){
	    if(empty($username)){
            $username = Yii::app()->user->id;
        }
        $rows = Yii::app()->db->createCommand()->select("b.*,c.manager as c_manager")->from("hr_binding a")
            ->leftJoin("hr_employee b","b.id = a.employee_id")
            ->leftJoin("hr_dept c","c.id = b.position")
            ->where("a.user_id=:user_id", array(':user_id'=>$username))->queryRow();
        if($rows){
            return $rows;
        }
	    return array();
    }

    public function getEmployeeListToEmployeeId($employee_id){
        $rows = Yii::app()->db->createCommand()->select("a.*,c.manager as c_manager")->from("hr_employee a")
            ->leftJoin("hr_dept c","c.id = a.position")
            ->where("a.id=:id", array(':id'=>$employee_id))->queryRow();
        if($rows){
            return $rows;
        }
	    return array();
    }

    //公司刪除時必須沒有員工
	public function validateDelete(){
/*        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('company_id=:company_id', array(':company_id'=>$this->id))->queryAll();
        if ($rows){
            return false;
        }*/
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $rows = Yii::app()->db->createCommand()->select("a.*,b.city as employee_city")->from("hr_binding a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->where("a.id=:id and b.city in ($city_allow) ", array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->user_name = $row['user_name'];
				$this->user_id = $row['user_id'];
                $this->employee_id = $row['employee_id'];
                $this->employee_name = $row['employee_name'];
                $this->city = $row['employee_city'];
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
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from hr_binding where id = :id and city IN ($city_allow)";
				break;
			case 'new':
				$sql = "insert into hr_binding(
							employee_id, employee_name, user_id, user_name, city, lcu
						) values (
							:employee_id, :employee_name, :user_id, :user_name, :city, :lcu
						)";
				break;
			case 'edit':
				$sql = "update hr_binding set
							employee_id = :employee_id, 
							employee_name = :employee_name, 
							city = :city, 
							user_id = :user_id,
							user_name = :user_name,
							luu = :luu,
							city = :city
						where id = :id
						";
				break;
		}

		$command=$connection->createCommand($sql);
		if (strpos($sql,':id')!==false)
			$command->bindParam(':id',$this->id,PDO::PARAM_INT);
		if (strpos($sql,':employee_id')!==false)
			$command->bindParam(':employee_id',$this->employee_id,PDO::PARAM_STR);
		if (strpos($sql,':employee_name')!==false)
			$command->bindParam(':employee_name',$this->employee_name,PDO::PARAM_STR);
		if (strpos($sql,':user_id')!==false)
			$command->bindParam(':user_id',$this->user_id,PDO::PARAM_STR);
		if (strpos($sql,':user_name')!==false)
			$command->bindParam(':user_name',$this->user_name,PDO::PARAM_STR);

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

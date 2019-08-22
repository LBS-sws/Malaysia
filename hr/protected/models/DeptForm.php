<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class DeptForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $name;
	public $city;
	public $z_index;
	public $dept_id=1;
	public $type;
	public $dept_class;
	public $manager=0;
	public $technician=0;
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('staff','Record ID'),
			'name'=>Yii::t('contract',' Name'),
			'city'=>Yii::t('misc','City'),
			'z_index'=>Yii::t('contract','Level'),
            'dept_id'=>Yii::t('contract','in department'),
            'dept_class'=>Yii::t('contract','Job category'),
            'manager'=>Yii::t('fete','Manager level audit'),
            'technician'=>Yii::t('fete','technician'),
		);
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//array('id, position, leave_reason, remarks, email, staff_type, leader','safe'),
            array('id, name, z_index, dept_id, type, dept_class, manager, technician','safe'),
			array('name','required'),
			array('city','validateCity'),
            array('dept_id','validateDeptId'),
			array('name','validateName'),
		);
	}

	public function validateName($attribute, $params){
	    $this->setCityToDept();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where('id!=:id and name=:name and city=:city and type=:type and dept_id=:dept_id ',
                array(':id'=>$this->id,':name'=>$this->name,':city'=>$this->city,':type'=>$this->type,':dept_id'=>$this->dept_id))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract',' Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

	public function validateDeptId($attribute, $params){
	    if($this->type == 1){
	        if(empty($this->dept_id)||!is_numeric($this->dept_id)){
                $message = Yii::t('contract','in department'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }else{
                $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
                    ->where('id=:id and type=0 ', array(':id'=>$this->dept_id))->queryRow();
                if (!$rows){
                    $message = Yii::t('contract','in department'). Yii::t('contract',' can not be empty');
                    $this->addError($attribute,$message);
                }
            }
        }
    }

	public function validateCity($attribute, $params){
	    if($this->type != 1){
	        if(empty($this->city)){
                $message = Yii::t('misc','City'). Yii::t('contract',' can not be empty');
                $this->addError($attribute,$message);
            }
        }
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
    public function setCityToDept(){
        if ($this->type == 1&&!empty($this->dept_id)){
            $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
                ->where('id=:id', array(':id'=>$this->dept_id))->queryRow();
            if($rows){
                $this->city = $rows["city"];
            }else{
                throw new CHttpException(404,'Cannot update.');
            }
        }
    }
    public function getDeptSqlLikeName($dept_name){
        $sql = "select id from hr_dept
                where type=1 AND name LIKE '%$dept_name%'
			";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array();
        foreach ($rows as $row){
            array_push($arr,"'".$row["id"]."'");
        }
        if(empty($arr)){
            return "('')";
        }else{
            $arr = implode(",",$arr);
            return "($arr)";
        }
    }
    //獲取職位列表
	public function getDeptAllListNoCity($type=0){
        $city = Yii::app()->user->city();
	    $arr=array(""=>"");
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where('type=:type', array(':type'=>$type))->order("city,z_index desc")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"]." - ".WordForm::getCityNameToCode($row["city"]);
            }
        }
        return $arr;
    }
    //獲取職位列表
	public function getDeptAllList($type=0){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
	    $arr=array(""=>"");
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where("type=:type and city in ($city_allow)", array(':type'=>$type))->order("z_index desc")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //獲取職位列表
	public function getDeptListToCity($dept_id,$city=''){
	    $sql = "";
	    if(!empty($dept_id)&&is_numeric($dept_id)){
	        $sql = " or id='$dept_id'";
        }
        if(empty($city)){
            $city = Yii::app()->user->city();
        }
	    $arr=array(""=>"");
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where("type='0' and city ='$city'$sql")->order("z_index desc")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //獲取崗位列表
	public function getPosiList($dept_id){
	    $arr=array(""=>"");
	    if(empty($dept_id)){
	        return $arr;
        }
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where("type='1' and dept_id =:dept_id",array(":dept_id"=>$dept_id))->order("z_index desc")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = $row["name"];
            }
        }
        return $arr;
    }

    //獲取職位列表(僅職位)
    public function getDeptOneAllList(){
        $city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $arr=array(""=>array("name"=>"","type"=>"","dept_class"=>""));
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where("type=:type and city in ($city_allow)", array(':type'=>1))->order("z_index desc")->queryAll();
        if ($rows){
            foreach ($rows as $row){
                $arr[$row["id"]] = array("name"=>$row["name"],"type"=>$row["dept_id"],"dept_class"=>$row["dept_class"]);
            }
        }
        return $arr;
    }
    //獲取職位名字
	public function getDeptToId($dept_id){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where('id=:id', array(':id'=>$dept_id))->queryRow();
        if ($rows){
            return $rows["name"];
        }
        return $dept_id;
    }

    //職位刪除時必須沒有員工
	public function validateDelete(){
	    if($this->type == 1){
            $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
                ->where('position=:position', array(':position'=>$this->id))->queryAll();
        }else{
            $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
                ->where('department=:department', array(':department'=>$this->id))->queryAll();
        }
        if ($rows){
            return false;
        }
        return true;
    }

	public function retrieveData($index)
	{
        $city = Yii::app()->user->city();
        $rows = Yii::app()->db->createCommand()->select()->from("hr_dept")
            ->where('id=:id ', array(':id'=>$index))->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->name = $row['name'];
				$this->z_index = $row['z_index'];
				$this->city = $row['city'];
                $this->type = $row['type'];
                $this->dept_id = $row['dept_id'];
                $this->dept_class = $row['dept_class'];
                $this->manager = $row['manager'];
                $this->technician = $row['technician'];
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
		    //$this->setCityToDept();//自動完成職位的城市歸屬
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
                $sql = "delete from hr_dept where id = :id";
				break;
			case 'new':
				$sql = "insert into hr_dept(
							name, type, z_index, dept_id, city, dept_class, manager, technician, lcu
						) values (
							:name, :type, :z_index, :dept_id, :city, :dept_class, :manager, :technician, :lcu
						)";
				break;
			case 'edit':
				$sql = "update hr_dept set
							name = :name, 
							city = :city, 
							type = :type, 
							z_index = :z_index,
							dept_id = :dept_id,
							dept_class = :dept_class,
							manager = :manager,
							technician = :technician,
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
		if (strpos($sql,':dept_id')!==false)
			$command->bindParam(':dept_id',$this->dept_id,PDO::PARAM_STR);
		if (strpos($sql,':z_index')!==false)
			$command->bindParam(':z_index',$this->z_index,PDO::PARAM_INT);
		if (strpos($sql,':type')!==false)
			$command->bindParam(':type',$this->type,PDO::PARAM_INT);
		if (strpos($sql,':dept_class')!==false)
			$command->bindParam(':dept_class',$this->dept_class,PDO::PARAM_STR);
		if (strpos($sql,':manager')!==false)
			$command->bindParam(':manager',$this->manager,PDO::PARAM_STR);
		if (strpos($sql,':technician')!==false)
			$command->bindParam(':technician',$this->technician,PDO::PARAM_STR);

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

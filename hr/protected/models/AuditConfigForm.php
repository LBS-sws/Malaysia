<?php

class AuditConfigForm extends CFormModel
{
	public $id;
	public $city;
	public $audit_index;

	public function attributeLabels()
	{
        return array(
            'city'=>Yii::t('contract','City'),
            'audit_index'=>Yii::t('fete','Audit index'),
        );
	}

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('id, city,audit_index','safe'),
            array('city','required'),
            array('audit_index','required'),
            array('city','validateCity'),
		);
	}


    public function validateCity($attribute, $params){
        $id = -1;
        if(!empty($this->id)){
            $id = $this->id;
        }
        $rows = Yii::app()->db->createCommand()->select("id")->from("hr_audit_con")
            ->where('city=:city and id!=:id',
                array(':id'=>$id,':city'=>$this->city))->queryAll();
        if(count($rows)>0){
            $message = Yii::t('contract','City'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }

    public function getManager($staff_id){
        $staffList = Yii::app()->db->createCommand()->select("a.department,c.manager as c_manager")->from("hr_employee a")
            ->leftJoin("hr_dept c","c.id = a.position")
            ->where("a.id=:id", array(':id'=>$staff_id))->queryRow();
        if($staffList){
            return array(
                "manager"=>$staffList["c_manager"],
                "department"=>$staffList["department"],
            );
        }else{
            return array(
                "manager"=>0,
                "department"=>0,
            );
        }
    }

	public function getCityAuditToCode($employee_id,$auditType="") {
        $staffList = Yii::app()->db->createCommand()->select("a.*,c.manager as c_manager")->from("hr_employee a")
            ->leftJoin("hr_dept c","c.id = a.position")
            ->where("a.id=:id", array(':id'=>$employee_id))->queryRow();
        if($staffList){
            $manager = $staffList["c_manager"];
            if(!empty($manager)){
                $manager = intval($manager);
                if(in_array($manager,array(1,2,3,4))){
                    $manager++;
                    $manager = $manager>=4?4:$manager;
                }
            }else{
                $manager = 1;
            }

            //後續添加
            $assList = AuditConfigForm::getAccessAndCity($staffList["city"],$staffList["department"],$auditType);
            for($i = $manager;$i<=count($assList);$i++){
                if($assList[$i]){
                    $manager = $i;
                    break;
                }
            }

            return $manager;
        }

        return 1;
	}
    //判斷審核人是否空缺
    public function getAccessAndCity($city,$department,$auditType=""){
        $systemId = Yii::app()->params['systemId'];
        $suffix = Yii::app()->params['envSuffix'];
        $command = Yii::app()->db->createCommand();
        if(empty($auditType)){ //加班
            $workOne = $command->select("a.user_id")->from("hr_binding a")
                ->leftJoin("hr_employee d","d.id = a.employee_id")
                ->leftJoin("security$suffix.sec_user b","b.username = a.user_id")
                ->leftJoin("security$suffix.sec_user_access c","c.username = a.user_id")
                ->where("b.status='A' and c.system_id='$systemId' and c.a_read_write like '%ZA08%' and d.department='$department'")
                ->queryAll();
            if($workOne){
                $workOne = array_column($workOne, 'user_id');
            }
            $command->reset();
            $workTwo = $command->select("a.username")->from("security$suffix.sec_user a")
                ->leftJoin("security$suffix.sec_user_access b","b.username = a.username")
                ->where("a.status='A' and b.system_id='$systemId' and b.a_read_write like '%ZE05%' and a.city=:city",
                    array(':city'=>$city))->queryAll();
            if($workTwo){
                $workTwo = array_column($workTwo, 'username');
            }
            $boolOne=true;
            $boolTwo=true;
            if((!$workOne)||($workOne==$workTwo)){
                $boolOne = false;
            }
            if(!$workTwo){
                $boolTwo = false;
            }
        }else{ //請假
            $leaveOne = $command->select("a.user_id")->from("hr_binding a")
                ->leftJoin("hr_employee d","d.id = a.employee_id")
                ->leftJoin("security$suffix.sec_user b","b.username = a.user_id")
                ->leftJoin("security$suffix.sec_user_access c","c.username = a.user_id")
                ->where("b.status='A' and c.system_id='$systemId' and c.a_read_write like '%ZA09%' and d.department='$department'")->queryAll();
            if($leaveOne){
                $leaveOne = array_column($leaveOne, 'user_id');
            }
            $command->reset();
            $leaveTwo = $command->select("a.username")->from("security$suffix.sec_user a")
                ->leftJoin("security$suffix.sec_user_access b","b.username = a.username")
                ->where("a.status='A' and b.system_id='$systemId' and b.a_read_write like '%ZE06%' and a.city=:city",
                    array(':city'=>$city))->queryAll();
            if($leaveTwo){
                $leaveTwo = array_column($leaveTwo, 'username');
            }
            $boolOne=true;
            $boolTwo=true;
            if((!$leaveOne)||($leaveOne==$leaveTwo)){
                $boolOne = false;
            }
            if(!$leaveTwo){
                $boolTwo = false;
            }
        }

        return array(
            1=>$boolOne,
            2=>$boolTwo,
            3=>true,
            4=>true,
        );
    }

	public function retrieveData($index) {
        $city_allow = Yii::app()->user->city_allow();
		$rows = Yii::app()->db->createCommand()->select("*")
            ->from("hr_audit_con")->where("id=:id",array(":id"=>$index))->queryAll();
		if (count($rows) > 0) {
			foreach ($rows as $row) {
                $this->id = $row['id'];
                $this->city = $row['city'];
                $this->audit_index = $row['audit_index'];
                break;
			}
		}
		return true;
	}

    //刪除驗證
    public function deleteValidate(){
        return true;
    }

    //獲取城市列表
    public function getCityList(){
        $suffix = Yii::app()->params['envSuffix'];
        $idSql ="";
        if(!empty($this->id)){
            $idSql = " and a.id !=".$this->id;
        }
        //select * from  B where (select count(1) as num from A where A.ID = B.ID) = 0
        $sql = "select * from security$suffix.sec_city b where (select count(1) as num from hr$suffix.hr_audit_con a where a.city = b.code $idSql) = 0";
        $records = Yii::app()->db->createCommand($sql)->queryAll();
        $arr = array(""=>"");
        if($records){
            foreach ($records as $record){
                $arr[$record["code"]] = $record["name"];
            }
        }
        return $arr;
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
                $sql = "delete from hr_audit_con where id = :id";
                break;
            case 'new':
                $sql = "insert into hr_audit_con(
							city,audit_index, lcu
						) values (
							:city,:audit_index, :lcu
						)";
                break;
            case 'edit':
                $sql = "update hr_audit_con set
							city = :city, 
							audit_index = :audit_index, 
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
        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
        if (strpos($sql,':audit_index')!==false)
            $command->bindParam(':audit_index',$this->audit_index,PDO::PARAM_INT);

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

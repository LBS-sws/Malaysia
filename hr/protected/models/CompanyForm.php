<?php

/**
 * UserForm class.
 * UserForm is the data structure for keeping
 * user form data. It is used by the 'user' action of 'SiteController'.
 */
class CompanyForm extends CFormModel
{
	/* User Fields */
	public $id;
	public $name;
	public $city;
	public $legal;//法定代表人
	public $legal_email;//法定代表人郵箱
	public $legal_city;//法人章所在城市
	public $head;
	public $head_email;//負責人郵箱
	public $agent;
	public $agent_email;//代理人郵箱
	public $address;
	public $postal;//郵政編碼
	public $address2;//地址2
	public $postal2;//郵政編碼2
	public $mie;//滅蟲執照級別
	public $phone;
	public $tacitly=0;
	public $security_code;
	public $organization_code;
	public $organization_time;
	public $license_code;
	public $license_time;
	public $taxpayer_num;//納稅人識別號




    public $no_of_attm = array(
        'company'=>0
    );
    public $docType = 'COMPANY';
    public $docMasterId = array(
        'company'=>0
    );
    public $files;
    public $removeFileId = array(
        'company'=>0
    );
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('staff','Record ID'),
			'name'=>Yii::t('contract','Company Name'),
			'head'=>Yii::t('contract','Company Head'),
			'agent'=>Yii::t('contract','Company Agent'),
			'address'=>Yii::t('contract','Company Address'),
			'phone'=>Yii::t('contract','Phone of consignee'),
			'security_code'=>Yii::t('contract','Security Code'),
			'organization_code'=>Yii::t('contract','Organization Code'),
			'organization_time'=>Yii::t('contract','Organization Time'),
			'license_code'=>Yii::t('contract','License Code'),
			'license_time'=>Yii::t('contract','License Time'),
			'tacitly'=>Yii::t('contract','Tacitly Company'),

            'legal'=>Yii::t('contract','Legal representative'),
            'legal_email'=>Yii::t('contract','Legal representative email'),
            'legal_city'=>Yii::t('contract','Legal representative city'),
            'head_email'=>Yii::t('contract','Responsible email'),
            'agent_email'=>Yii::t('contract','Agent email'),
            'postal'=>Yii::t('contract','Postal code'),
            'postal2'=>Yii::t('contract','Name of consignee'),
            'address2'=>Yii::t('contract','Address of consignee'),
            'mie'=>Yii::t('contract','Level of pest control'),
            'taxpayer_num'=>Yii::t('contract','Taxpayer number'),
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
            array('id, name, head, agent, address, phone, city, tacitly, security_code, organization_code, organization_time, license_code, license_time,
            legal, legal_email, legal_city, head_email, agent_email, postal, postal2, address2, mie, taxpayer_num
            ','safe'),
			array('name','required'),
			array('city','required'),
			array('name','validateName'),
			array('head','required'),
			array('head_email','required'),
			array('address','required'),
            array('license_time, organization_time','date','allowEmpty'=>true,
                'format'=>array('yyyy/MM/dd','yyyy-MM-dd','yyyy/M/d'),
            ),
            array('files, removeFileId, docMasterId, no_of_attm','safe'),
        );
	}

	public function validateName($attribute, $params){
        $city = $this->city;
        $rows = Yii::app()->db->createCommand()->select()->from("hr_company")
            ->where('id!=:id and name=:name and city=:city ', array(':id'=>$this->id,':name'=>$this->name,':city'=>$city))->queryAll();
        if (count($rows) > 0){
            $message = Yii::t('contract','Company Name'). Yii::t('contract',' can not repeat');
            $this->addError($attribute,$message);
        }
    }
    //獲取用戶表的所有用戶(相同城市)
	public function getUserList(){
        //$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $from = "security".$suffix.".sec_user";
        $rows = Yii::app()->db->createCommand()->select("username,disp_name")->from($from)->where("city in ($city_allow)")->queryAll();
        $arr = array(""=>"");
        foreach ($rows as $row){
            $arr[$row["username"]] = $row["disp_name"];
        }
        return $arr;
    }

    //根據公司id獲取公司信息
	public function getCompanyToId($company_id){
	    $arr=array("name"=>"");
        $rows = Yii::app()->db->createCommand()->select()->from("hr_company")
            ->where('id=:id', array(':id'=>$company_id))->queryAll();
        if (count($rows) > 0){
            $arr=$rows[0];
        }
        return $arr;
    }

    //公司刪除時必須沒有員工
	public function validateDelete(){
        $rows = Yii::app()->db->createCommand()->select()->from("hr_employee")
            ->where('company_id=:company_id', array(':company_id'=>$this->id))->queryAll();
        if ($rows){
            return false;
        }
        return true;
    }

	public function retrieveData($index)
	{
        //$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $suffix = Yii::app()->params['envSuffix'];
        $sql = "select a.* ,
				docman$suffix.countdoc('COMPANY',id) as companydoc
				from hr_company a
				where a.id=$index AND a.city in ($city_allow)";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
		if (count($rows) > 0)
		{
			foreach ($rows as $row)
			{
				$this->id = $row['id'];
				$this->name = $row['name'];
				$this->head = $row['head'];
                $this->agent = $row['agent'];
                $this->address = $row['address'];
                $this->phone = $row['phone'];
                $this->city = $row['city'];
                $this->tacitly = $row['tacitly'];
                $this->security_code = $row['security_code'];
                $this->organization_code = $row['organization_code'];
                $this->organization_time = $row['organization_time'];
                $this->license_code = $row['license_code'];
                $this->license_time = $row['license_time'];
                //legal, legal_email, legal_city, head_email, agent_email, postal, postal2, address2, mie
                $this->legal = $row['legal'];
                $this->legal_email = $row['legal_email'];
                $this->legal_city = $row['legal_city'];
                $this->head_email = $row['head_email'];
                $this->agent_email = $row['agent_email'];
                $this->postal = $row['postal'];
                $this->postal2 = $row['postal2'];
                $this->address2 = $row['address2'];
                $this->mie = $row['mie'];
                $this->taxpayer_num = $row['taxpayer_num'];
                $this->no_of_attm['company'] = $row['companydoc'];
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
            $this->updateDocman($connection,'COMPANY');
			$transaction->commit();

			//默認公司自動化
            $this->setTacitly();
		}
		catch(Exception $e) {
			$transaction->rollback();
			throw new CHttpException(404,'Cannot update.');
		}
	}


    protected function updateDocman(&$connection, $doctype) {
        if ($this->scenario=='new') {
            $docidx = strtolower($doctype);
            if ($this->docMasterId[$docidx] > 0) {
                $docman = new DocMan($doctype,$this->id,get_class($this));
                $docman->masterId = $this->docMasterId[$docidx];
                $docman->updateDocId($connection, $this->docMasterId[$docidx]);
            }
            $this->scenario = "edit";
        }
    }

	protected function saveStaff(&$connection)
	{
		$sql = '';
        //$city = Yii::app()->user->city();
        $city_allow = Yii::app()->user->city_allow();
        $uid = Yii::app()->user->id;
		switch ($this->scenario) {
			case 'delete':
                $sql = "delete from hr_company where id = :id and city IN ($city_allow)";
				break;
			case 'new':
				$sql = "insert into hr_company(
							name, agent, head, city, address, phone, security_code, organization_code, organization_time, license_code, license_time, tacitly, lcu
							, legal, legal_email, legal_city, head_email, agent_email, postal, postal2, address2, mie, taxpayer_num
						) values (
							:name, :agent, :head, :city, :address, :phone, :security_code, :organization_code, :organization_time, :license_code, :license_time, :tacitly, :lcu
							, :legal, :legal_email, :legal_city, :head_email, :agent_email, :postal, :postal2, :address2, :mie, :taxpayer_num
						)";
				break;
			case 'edit':
				$sql = "update hr_company set
							name = :name, 
							city = :city, 
							agent = :agent, 
							head = :head,
							address = :address,
							phone = :phone,
							security_code = :security_code,
							organization_code = :organization_code,
							organization_time = :organization_time,
							license_code = :license_code,
							license_time = :license_time,
							tacitly = :tacitly,
							mie = :mie,
							address2 = :address2,
							postal2 = :postal2,
							postal = :postal,
							agent_email = :agent_email,
							head_email = :head_email,
							legal_city = :legal_city,
							legal_email = :legal_email,
							legal = :legal,
							taxpayer_num = :taxpayer_num,
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
		if (strpos($sql,':agent')!==false)
			$command->bindParam(':agent',$this->agent,PDO::PARAM_STR);
		if (strpos($sql,':head')!==false)
			$command->bindParam(':head',$this->head,PDO::PARAM_STR);
		if (strpos($sql,':address')!==false)
			$command->bindParam(':address',$this->address,PDO::PARAM_STR);
		if (strpos($sql,':phone')!==false)
			$command->bindParam(':phone',$this->phone,PDO::PARAM_STR);
		if (strpos($sql,':organization_code')!==false)
			$command->bindParam(':organization_code',$this->organization_code,PDO::PARAM_STR);
		if (strpos($sql,':organization_time')!==false)
			$command->bindParam(':organization_time',$this->organization_time,PDO::PARAM_STR);
		if (strpos($sql,':security_code')!==false)
			$command->bindParam(':security_code',$this->security_code,PDO::PARAM_STR);
		if (strpos($sql,':license_code')!==false)
			$command->bindParam(':license_code',$this->license_code,PDO::PARAM_STR);
		if (strpos($sql,':license_time')!==false)
			$command->bindParam(':license_time',$this->license_time,PDO::PARAM_STR);
		if (strpos($sql,':tacitly')!==false)
			$command->bindParam(':tacitly',$this->tacitly,PDO::PARAM_INT);
        if (strpos($sql,':legal')!==false)
            $command->bindParam(':legal',$this->legal,PDO::PARAM_STR);
        if (strpos($sql,':legal_email')!==false)
            $command->bindParam(':legal_email',$this->legal_email,PDO::PARAM_STR);
        if (strpos($sql,':legal_city')!==false)
            $command->bindParam(':legal_city',$this->legal_city,PDO::PARAM_STR);
        if (strpos($sql,':head_email')!==false)
            $command->bindParam(':head_email',$this->head_email,PDO::PARAM_STR);
        if (strpos($sql,':agent_email')!==false)
            $command->bindParam(':agent_email',$this->agent_email,PDO::PARAM_STR);
        if (strpos($sql,':postal')!==false)
            $command->bindParam(':postal',$this->postal,PDO::PARAM_STR);
        if (strpos($sql,':postal2')!==false)
            $command->bindParam(':postal2',$this->postal2,PDO::PARAM_STR);
        if (strpos($sql,':address2')!==false)
            $command->bindParam(':address2',$this->address2,PDO::PARAM_STR);
        if (strpos($sql,':mie')!==false)
            $command->bindParam(':mie',$this->mie,PDO::PARAM_STR);
        if (strpos($sql,':taxpayer_num')!==false)
            $command->bindParam(':taxpayer_num',$this->taxpayer_num,PDO::PARAM_STR);
        //legal, legal_email, legal_city, head_email, agent_email, postal, postal2, address2, mie
        if (strpos($sql,':city')!==false)
            $command->bindParam(':city',$this->city,PDO::PARAM_STR);
		if (strpos($sql,':luu')!==false)
			$command->bindParam(':luu',$uid,PDO::PARAM_STR);
		if (strpos($sql,':lcu')!==false)
			$command->bindParam(':lcu',$uid,PDO::PARAM_STR);

		$command->execute();

        if ($this->scenario=='new'){
            $this->id = Yii::app()->db->getLastInsertID();
            //$this->scenario = "edit";
        }
        return true;
	}

	protected function setTacitly(){
	    if($this->tacitly == 1){
            Yii::app()->db->createCommand()->update('hr_company', array(
                'tacitly'=>0
            ), 'id!=:id and city=:city', array(':id'=>$this->id,":city"=>$this->city));
        }
    }


}

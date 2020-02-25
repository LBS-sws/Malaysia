<?php

class SupportSearchForm extends CFormModel
{
	public $id;
	public $support_code;
	public $apply_date;
	public $apply_num;
	public $apply_type;
	public $service_type;
	public $apply_end_date;
	public $apply_length=1;
	public $apply_remark;
	public $length_type=1;
	public $apply_city;
	public $city_name;
	public $apply_lcu;
	public $update_type;
	public $update_remark;
	public $employee_id;
	public $employee_name;
	public $audit_remark;
	public $tem_list;
	public $tem_s_ist;
	public $tem_str;
	public $tem_sum;
	public $sumNum=0;
	public $review_sum;
	public $status_type=1;
	public $change_num;
	public $early_remark;
	public $early_date;
	public $reject_remark;
    public $privilege;
    public $privilege_user;
	public $city="HK";

	public function attributeLabels()
	{
        return array(
            'support_code'=>Yii::t('contract','support code'),
            'apply_city'=>Yii::t('contract','apply city'),
            'apply_date'=>Yii::t('contract','Start Time'),
            'length_type'=>Yii::t('contract','support length'),
            'apply_end_date'=>Yii::t('contract','End Time'),
            'employee_id'=>Yii::t('contract','support employee'),
            'apply_remark'=>Yii::t('contract','apply remark'),
            'review_sum'=>Yii::t('contract','review sum'),
            'status_type'=>Yii::t('contract','Status'),

            'update_remark'=>Yii::t('contract','update remark'),
            'audit_remark'=>Yii::t('contract','audit remark'),

            'early_remark9'=>Yii::t('contract','early remark'),
            'early_date9'=>Yii::t('contract','early date'),

            'early_remark10'=>Yii::t('contract','renewal remark'),
            'early_date10'=>Yii::t('contract','renewal date'),
            'reject_remark'=>Yii::t('contract','Rejected Remark'),
            'apply_type'=>Yii::t('queue','Type'),
            'privilege'=>Yii::t('contract','privilege'),
            'privilege_user'=>Yii::t('contract','privilege user'),
            'service_type'=>Yii::t('contract','service type'),
        );
	}

	public function retrieveData($index) {
        $suffix = Yii::app()->params['envSuffix'];
        $city = Yii::app()->user->city;
        $city_allow = Yii::app()->user->city_allow();
        if(Yii::app()->user->validFunction('ZR11')||Yii::app()->user->validFunction('AY02')){
            $sqlEx = " ";
            //$sqlEx = " and a.apply_city in ($city_allow) ";
        }else{
            $bindEmployee = BindingForm::getEmployeeIdToUsername();
            $sqlEx = " and a.employee_id=$bindEmployee ";
        }
        $row = Yii::app()->db->createCommand()->select("a.*,b.name as employee_name,c.name as city_name")->from("hr_apply_support a")
            ->leftJoin("hr_employee b","a.employee_id = b.id")
            ->leftJoin("security$suffix.sec_city c","c.code = a.apply_city")
            ->where(" a.status_type != 1 $sqlEx and a.id=:id",array(":id"=>$index))->queryRow();
		if ($row) {
            $this->id = $row['id'];
            $this->support_code = $row['support_code'];
            $this->apply_city = $row['apply_city'];
            $this->city_name = $row['city_name'];
            $this->apply_date = $row['apply_date'];
            $this->apply_end_date = $row['apply_end_date'];
            $this->apply_remark = $row['apply_remark'];
            $this->status_type = $row['status_type'];

            $this->apply_type = $row['apply_type'];
            $this->service_type = $row['service_type'];

            $this->employee_id = $row['employee_id'];
            $this->employee_name = $row['employee_name'];
            $this->update_type = $row['update_type'];
            $this->update_remark = $row['update_remark'];
            $this->audit_remark = $row['audit_remark'];
            $this->length_type = $row['length_type'];
            $this->apply_length = $row['apply_length'];
            $this->tem_str = $row['tem_str'];
            $this->early_date = $row['early_date'];
            $this->early_remark = $row['early_remark'];
            $this->tem_s_ist = json_decode($row['tem_s_ist'],true);

            $this->privilege = $row['privilege'];
            $this->privilege_user = $row['privilege_user'];
		}
		return true;
	}

    public function getHistoryHtml($id){
        $suffix = Yii::app()->params['envSuffix'];
        $html = '<table id="tblFlow" class="table table-bordered table-striped table-hover">';
        $html.= '<thead><tr>';
        $html.='<th>'.Yii::t("contract","Start Time").'</th>';
        $html.='<th>'.Yii::t("contract","End Time").'</th>';
        $html.='<th>'.Yii::t("contract","Operator Time").'</th>';
        $html.='<th>'.Yii::t("contract","Operator User").'</th>';
        $html.='<th>'.Yii::t("contract","Status").'</th>';
        $html.='</tr></thead><tbody>';

        $rows = Yii::app()->db->createCommand()->select("a.start_date,a.end_date,a.status_type,b.disp_name,a.lcd")->from("hr_apply_support_history a")
            ->leftJoin("security$suffix.sec_user b","a.lcu = b.username")
            ->where("a.support_id=:id",array(":id"=>$id))->order("lcd asc")->queryAll();
        if($rows){
            $arr = SupportApplyList::getStatusList();
            foreach ($rows as $row){
                $status = key_exists($row['status_type'],$arr)?$arr[$row['status_type']]:array('status'=>$row['status_type']);
                $html.="<tr>";
                $html.='<td>'.$row['start_date'].'</td>';
                $html.='<td>'.$row['end_date'].'</td>';
                $html.='<td>'.$row['lcd'].'</td>';
                $html.='<td>'.$row['disp_name'].'</td>';
                $html.='<td>'.$status['status'].'</td>';
                $html.="</tr>";
            }
        }
        $html.='</tbody></table>';

        return $html;
    }

    //是否只讀
    public function getReadonly(){
        return $this->scenario=='view'||!in_array($this->status_type,array(1,2,3,4));
    }

}

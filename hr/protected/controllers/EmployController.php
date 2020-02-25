<?php

/**
 * Created by PhpStorm.
 * User: 沈超
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class EmployController extends Controller
{
	public $function_id='ZE01';

    public function filters()
    {
        return array(
            'enforceSessionExpiration',
            'enforceNoConcurrentLogin',
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('new','edit','save','delete','audit','finish','uploadImg','fileupload','fileRemove'),
                'expression'=>array('EmployController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view','fileDownload'),
                'expression'=>array('EmployController','allowReadOnly'),
            ),
            array('allow',
                'actions'=>array('generate','addDate','printImage','changeDepart'),
                'expression'=>array('EmployController','allowWrite'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('ZE01');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('ZE01');
    }

    public static function allowWrite() {
        return !empty(Yii::app()->user->id);
    }

    public function actionIndex($pageNum=0){
        $model = new EmployList;
        if (isset($_POST['EmployList'])) {
            $model->attributes = $_POST['EmployList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['employ_01']) && !empty($session['employ_01'])) {
                $criteria = $session['employ_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }

    public function actionNew()
    {
        $model = new EmployForm('new');
        $model->entry_time = $model->test_start_time = date("Y/m/d");
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new EmployForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new EmployForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionSave()
    {
        if (isset($_POST['EmployForm'])) {
            $model = new EmployForm($_POST['EmployForm']['scenario']);
            $model->attributes = $_POST['EmployForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('employ/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    public function actionAudit()
    {
        if (isset($_POST['EmployForm'])) {
            $model = new EmployForm('audit');
            $model->attributes = $_POST['EmployForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('employ/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $model->setScenario($_POST['EmployForm']['scenario']);
                $this->render('form',array('model'=>$model,));
            }
        }
    }
    public function actionFinish()
    {
        if (isset($_POST['EmployForm'])) {
            $data = $_POST['EmployForm'];
            $uid = Yii::app()->user->id;
            Yii::app()->db->createCommand()->update('hr_employee', array(
                'jj_card'=>$data['jj_card'],
                'social_code'=>$data['social_code'],
                'staff_status'=>0,
                'staff_old_status'=>0,
            ), 'id=:id and staff_status=4', array(':id'=>$data['id']));
            //記錄
            Yii::app()->db->createCommand()->insert('hr_employee_history', array(
                "employee_id"=>$data['id'],
                "status"=>"finish",
                "lcu"=>$uid,
                "lcd"=>date('Y-m-d H:i:s'),
            ));
            Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
            $this->redirect(Yii::app()->createUrl('employ/index'));
        }
    }

    //生成合同
    public function actionGenerate($index=0){
        if (empty($index) || !is_numeric($index)){
            $this->redirect(Yii::app()->createUrl('employ/index'));
        }else{
            $bool = EmployeeForm::updateEmployeeWord($index);
            if (!$bool){
                $this->redirect(Yii::app()->createUrl('employ/index'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), Yii::t('contract','Contract formation success'));
                $this->redirect(Yii::app()->createUrl('employ/edit',array('index'=>$index)));
            }
        }
    }

    //刪除草稿
    public function actionDelete(){
        $model = new EmployForm('delete');
        if (isset($_POST['EmployForm'])) {
            $model->attributes = $_POST['EmployForm'];
            if($model->validateDelete()){
                $model->saveData();
                $this->redirect(Yii::app()->createUrl('employ/index'));
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), Yii::t('contract','The dept has staff being used, please delete the staff first'));
                $this->redirect(Yii::app()->createUrl('employ/edit',array('index'=>$model->id)));
            }
        }
    }

    //上傳圖片
    public function actionUploadImg(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $model = new UploadImgForm();
            $img = CUploadedFile::getInstance($model,'file');
            $city = Yii::app()->user->city();
            $path =Yii::app()->basePath."/../upload/images/";
            if (!file_exists($path)){
                mkdir($path);
                $myfile = fopen($path."index.php", "w");
                fclose($myfile);
            }
            $path.=$city."/";
            if (!file_exists($path)){
                mkdir($path);
                $myfile = fopen($path."index.php", "w");
                fclose($myfile);
            }
            $url = "upload/images/".$city."/".date("YmdHsi").".".$img->getExtensionName();
            $model->file = $img->getName();
            if ($model->file && $model->validate()) {
                $img->saveAs($url);
                //$url = "/".Yii::app()->params['systemId']."/".$url;
                $url = "../../".$url;
                echo CJSON::encode(array('status'=>1,'data'=>$url));
            }else{
                echo CJSON::encode(array('status'=>0,'error'=>$model->getErrors()));
            }
        }else{
            $this->redirect(Yii::app()->createUrl('employ/index'));
        }
    }

    //時間運算
    public function actionAddDate(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $date = $_POST['dateTime'];
            $month = $_POST['month'];
            $lastDate = date('Y-m-d', strtotime("$date +$month month"));
            $oldMonth = intval(date("m",strtotime($date)));
            $oldYear = intval(date("Y",strtotime($date)));

            $newMonth = intval(date("m",strtotime($lastDate)));
            $newYear = intval(date("Y",strtotime($lastDate)));
            if(($newYear-$oldYear)*12 + $newMonth - $oldMonth > $month){
                $lastDate = date("Y-m-01",strtotime($lastDate));
            }
            $lastDate = date('Y-m-d', strtotime("$lastDate - 1 day"));
            echo CJSON::encode($lastDate);
        }else{
            $this->redirect(Yii::app()->createUrl('employ/index'));
        }
    }

    //職位
    public function actionChangeDepart(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $department = Yii::app()->request->getPost('department',"");
            $change_city = Yii::app()->request->getPost('change_city',"");
            $position = Yii::app()->request->getPost('position',"");
            $type = Yii::app()->request->getPost('type',"");
/*            $department = $_POST['department'];
            $change_city = $_POST['change_city'];
            $position = $_POST['position'];
            $type = $_POST['type'];*/
            $json = array("data"=>"","status"=>1);
            $model = new DeptForm();
            if($type=="department"){
                $data = $model->getPosiList($department);
                unset($data[""]);
                $json["data"] = $data;
                $json["sales_type"] = $model->getSalesTypeToId($department);
            }elseif($type=="change_city"){
                $data = $model->getDeptListToCity("",$change_city);
                unset($data[""]);
                $json["data"] = $data;
                reset($data);
                $department = key($data);
                $json["sales_type"] = $model->getSalesTypeToId($department);
            }else{
                $model->retrieveData($position);
                $json["data"]['dept_class'] = $model->dept_class;
            }
            echo CJSON::encode($json);
        }else{
            $this->redirect(Yii::app()->createUrl('employ/index'));
        }
    }

    //上傳附件
    public function actionFileupload($doctype) {
        $model = new EmployForm();
        if (isset($_POST['EmployForm'])) {
            $model->attributes = $_POST['EmployForm'];

            $id = ($_POST['EmployForm']['scenario']=='new') ? 0 : $model->id;
            $docman = new DocMan($model->docType,$id,get_class($model));
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            if (isset($_FILES[$docman->inputName])) $docman->files = $_FILES[$docman->inputName];
            $docman->fileUpload();
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    //刪除附件
    public function actionFileRemove($doctype) {
        $model = new EmployForm();
        if (isset($_POST['EmployForm'])) {
            $model->attributes = $_POST['EmployForm'];

            $docman = new DocMan($model->docType,$model->id,'EmployForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            echo $docman->genTableFileList(false);
        } else {
            echo "NIL";
        }
    }

    //下載附件
    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select city from hr_employee where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row!==false) {
            $citylist = Yii::app()->user->city_allow();
            if (strpos($citylist, $row['city']) !== false) {
                $docman = new DocMan($doctype,$docId,'EmployForm');
                $docman->masterId = $mastId;
                $docman->fileDownload($fileId);
            } else {
                throw new CHttpException(404,'Access right not match.');
            }
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }


    public function actionPrintImage($id = 0,$staff = 0,$str="") {
        if (empty($staff)||empty($id)){
            $id = empty($id)?$staff:$id;
            $rows = Yii::app()->db->createCommand()->select("$str")
                ->from("hr_employee")->where("id=:id",array(":id"=>$id))->queryRow();
        }else{
            $rows = Yii::app()->db->createCommand()->select("$str")
                ->from("hr_employee_operate")->where("id=:id",array(":id"=>$id))->queryRow();
        }
        if($rows){
            if(empty($rows[$str])){
                echo "圖片不存在";
                return false;
            }else{
                $n = new imgdata;
                $path = "protected/controllers/".$rows[$str];
                if (file_exists($path)) {
                    $n -> getdir($path);
                    $n -> img2data();
                    $n -> data2img();
                } else {
                    echo "地址不存在";
                    return false;
                }
            }
        }else{
            echo "沒找到圖片";
            return false;
        }
    }
}
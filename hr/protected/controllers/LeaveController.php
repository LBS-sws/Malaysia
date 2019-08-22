<?php

/**
 * Created by PhpStorm.
 * User: 請假active
 * Date: 2017/6/7 0007
 * Time: 上午 11:30
 */
class LeaveController extends Controller
{
	public $function_id='ZA06';

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
                'actions'=>array('new','edit','delete','save','audit','fileupload','fileRemove','ajaxYearDay','test'),
                'expression'=>array('LeaveController','allowReadWrite'),
            ),
            array('allow',
                'actions'=>array('index','view','fileDownload','PdfDownload'),
                'expression'=>array('LeaveController','allowReadOnly'),
            ),
            array('allow',
                'actions'=>array('addDate'),
                'expression'=>array('LeaveController','allowWrite'),
            ),
            array('allow',
                'actions'=>array('cancel'),
                'expression'=>array('LeaveController','allowCancelled'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowReadWrite() {
        return Yii::app()->user->validRWFunction('ZA06');
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('ZA06');
    }

    public static function allowWrite() {
        return !empty(Yii::app()->user->id);
    }

    public static function allowCancelled() {
        return Yii::app()->user->validFunction('ZR05');
    }

    public function actionIndex($pageNum=0){
        $model = new LeaveList;
        if($model->validateEmployee()){
            if (isset($_POST['LeaveList'])) {
                $model->attributes = $_POST['LeaveList'];
            } else {
                $session = Yii::app()->session;
                if (isset($session['leave_01']) && !empty($session['leave_01'])) {
                    $criteria = $session['leave_01'];
                    $model->setCriteria($criteria);
                }
            }
            $model->determinePageNum($pageNum);
            $model->retrieveDataByPage($model->pageNum);
            $this->render('index',array('model'=>$model));
        }else{
            throw new CHttpException(404,Yii::t("contract",'The account has no binding staff, please contact the administrator'));
        }
    }


    public function actionNew()
    {
        $model = new LeaveForm('new');
        $employeeId = WorkList::getEmployeeId();
        if(empty($employeeId)){
            throw new CHttpException(404,Yii::t("contract",'The account has no binding staff, please contact the administrator'));
        }else{
            $model->employee_id = $employeeId;
        }
        $this->render('form',array('model'=>$model,));
    }

    public function actionEdit($index)
    {
        $model = new LeaveForm('edit');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }

    public function actionView($index)
    {
        $model = new LeaveForm('view');
        if (!$model->retrieveData($index)) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $this->render('form',array('model'=>$model,));
        }
    }


    public function actionSave()
    {
        if (isset($_POST['LeaveForm'])) {
            $model = new LeaveForm($_POST['LeaveForm']['scenario']);
            $model->attributes = $_POST['LeaveForm'];
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('leave/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    public function actionAudit()
    {
        if (isset($_POST['LeaveForm'])) {
            $model = new LeaveForm($_POST['LeaveForm']['scenario']);
            $model->attributes = $_POST['LeaveForm'];
            $model->audit = true;
            if ($model->validate()) {
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Save Done'));
                $this->redirect(Yii::app()->createUrl('leave/edit',array('index'=>$model->id)));
            } else {
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Validation Message'), $message);
                $model->audit = false;
                $this->render('form',array('model'=>$model,));
            }
        }
    }

    //刪除
    public function actionDelete(){
        $model = new LeaveForm('delete');
        if (isset($_POST['LeaveForm'])) {
            $model->attributes = $_POST['LeaveForm'];
            if($model->validate()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Record Deleted'));
                $this->redirect(Yii::app()->createUrl('leave/index'));
            }else{
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','This record is already in use'));
                $this->redirect(Yii::app()->createUrl('leave/edit',array('index'=>$model->id)));
            }
        }
    }

    //取消
    public function actionCancel(){
        $model = new LeaveForm('cancel');
        if (isset($_POST['LeaveForm'])) {
            $model->attributes = $_POST['LeaveForm'];
            if($model->validate()){
                $model->saveData();
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','Cancel Done'));
                $this->redirect(Yii::app()->createUrl('leave/index'));
            }else{
                $message = CHtml::errorSummary($model);
                Dialog::message(Yii::t('dialog','Information'), Yii::t('dialog','This record is already in use'));
                $this->redirect(Yii::app()->createUrl('leave/edit',array('index'=>$model->id)));
            }
        }
    }


    //時間運算
    public function actionAddDate(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $startDate = $_POST['startDate'];
            $day = $_POST['day'];
            if(empty($startDate)||empty($day)){
                echo CJSON::encode(array("status"=>0,"message"=>"時間不能為空"));
                return true;
            }
            if(!is_numeric($day)){
                echo CJSON::encode(array("status"=>0,"message"=>"时间周期只能為數字"));
                return true;
            }
            if($day < 2){
                echo CJSON::encode(array("status"=>0,"message"=>"时间周期必須大於1"));
                return true;
            }
            $day--;
            $lastDate = date('Y/m/d', strtotime("$startDate +$day day"));
            echo CJSON::encode(array("status"=>1,"lastDate"=>$lastDate));
        }else{
            $this->redirect(Yii::app()->createUrl(''));
        }
    }



    public function actionFileupload($doctype) {
        $model = new LeaveForm();
        if (isset($_POST['LeaveForm'])) {
            $model->attributes = $_POST['LeaveForm'];

            $id = ($_POST['LeaveForm']['scenario']=='new') ? 0 : $model->id;
            $docman = new DocMan($model->docType,$id,get_class($model));
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            if (isset($_FILES[$docman->inputName])) $docman->files = $_FILES[$docman->inputName];
            $docman->fileUpload();
            if($_POST['LeaveForm']['scenario']=='new'||$model->status == 0||$model->status == 3){
                echo $docman->genTableFileList(false);
            }else{
                echo $docman->genTableFileList(false,false);
            }
        } else {
            echo "NIL";
        }
    }

    public function actionFileRemove($doctype) {
        $model = new LeaveForm();
        if (isset($_POST['LeaveForm'])) {
            $model->attributes = $_POST['LeaveForm'];

            $docman = new DocMan($model->docType,$model->id,'LeaveForm');
            $docman->masterId = $model->docMasterId[strtolower($doctype)];
            $docman->fileRemove($model->removeFileId[strtolower($doctype)]);
            if($_POST['LeaveForm']['scenario']=='new'||$model->status == 0||$model->status == 3){
                echo $docman->genTableFileList(false);
            }else{
                echo $docman->genTableFileList(false,false);
            }
        } else {
            echo "NIL";
        }
    }

    public function actionFileDownload($mastId, $docId, $fileId, $doctype) {
        $sql = "select city from hr_employee_leave where id = $docId";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        if ($row!==false) {
            $citylist = Yii::app()->user->city_allow();
            if (strpos($citylist, $row['city']) !== false) {
                $docman = new DocMan($doctype,$docId,'LeaveForm');
                $docman->masterId = $mastId;
                $docman->fileDownload($fileId);
            } else {
                throw new CHttpException(404,'Access right not match.');
            }
        } else {
            throw new CHttpException(404,'Record not found.');
        }
    }

    //PDF下載
    public function actionPdfDownload($index = 0){
        $model = new LeaveForm('edit');
        $arr = $model->getLeaveListToLeaveId($index);
        if (!$arr) {
            throw new CHttpException(404,'The requested page does not exist.');
        } else {
            $pdf = new MyPDFTwo();
            $pdf->setPageToLeave($arr);
            $pdf->getOutput($arr["employee_name"]."".$arr["leave_code"]);
        }
    }

    //计算年假
    public function actionAjaxYearDay(){
        if(Yii::app()->request->isAjaxRequest) {//是否ajax请求
            $index = $_POST["index"];
            $time = $_POST["time"];
            $leave_type = $_POST["leave_type"];
            $model = new VacationForm();
            $html = "";
            $entry_time = date("Y/m/d",strtotime(date("Y/m/d")."+2 year"));
            if($model->retrieveData($leave_type)){
                if($model->vaca_type == 'E'){
                    $yearDay =YearDayForm::getSumDayToYear($index,$time);
                    $leaveNum =LeaveForm::getLeaveNumToYear($index,$time);
                    $entry_time =LeaveForm::getMaxYearLeaveDate($index,$time);
                    $leaveNum =$yearDay - floatval($leaveNum);
                    $html = "<p class='form-control-static text-success'>年假剩余天数：".$leaveNum."</p>";
                }
            }
            echo CJSON::encode(array("status"=>1,"html"=>$html,"entry_time"=>$entry_time));
        }else{
            $this->redirect(Yii::app()->createUrl(''));
        }
    }

    //计算年假
    public function actionTest(){
        //var_dump(11111);
        //echo CJSON::encode(array("status"=>1,"html"=>0,"entry_time"=>1));
        if(key_exists("callback",$_GET)){
            $callback = $_GET['callback'];
        }else{
            $callback = "";
        }
        echo $callback.'({"code":200,"message":"","value":[{"adId":0,"adPic":"http://biz.img.jie0.cn/pic/1/d35c7d4f-071c-442b-9679-73efbed4fac1.jpg","adType":3,"addIncome":0.00,"advertiser":"丰科兑换","background":"丰科e商务主营品牌百货、家纺、家电、数码、箱包和进口食品。","businessId":0,"click":175,"clickRate":0.5600,"cost":0.00,"coupons":0,"createTime":1558319419000,"effect":1.00,"hotIndex":0,"id":83,"index":0,"industryId":162,"outerPic":"http://biz.img.jie0.cn/pic/1/0187d784-6ac3-4480-8f91-44537407f7fd.jpg","pic":"http://biz.img.jie0.cn/pic/1/dca64fed-5d02-431f-92ab-18974639cfab.png","pictures":"http://biz.img.jie0.cn/pic/1/4dda9469-3d9b-4e1e-824b-7d2f3b331142.png,http://biz.img.jie0.cn/pic/1/e4c53bfb-0875-424d-85c0-f6cc41c5521d.png,http://biz.img.jie0.cn/pic/1/60948246-5641-40f1-9ed6-4b57933aa704.png,http://biz.img.jie0.cn/pic/1/b33b548e-c9c3-4e52-a9e0-7dce1732ee0c.png,http://biz.img.jie0.cn/pic/1/214d554a-beeb-4c79-8413-4d31ebbe5754.png","publishTime":1558319419000,"purpose":"扩大品牌知名度，通过新渠道挖掘更多潜力用户，促成转化。","pushDesc":"<p>辛苦了，母亲<br/>520献礼，陪伴未曾缺席<br/>罗莱空调被，仅169元<br/>按购买的第30单倍数免费送<br/></p>","pushIcon":"http://biz.img.jie0.cn/pic/1/197016f8-a1ec-4816-aabf-ff2c8910e9a1.jpg","pushSubtitle":"520献礼，陪伴未曾缺席","pushTitle":"丰科兑换广告案例","qrUrl":"http://biz.img.jie0.cn/pic/brain/news/qr/d8c698d7-a39a-4ff0-9a3a-50550d0161f9.jpg","readCount":22,"show":30865,"showTag":0,"status":0,"storeId":0,"strategy":"1.推广微信小程序，直接触达用户促进转化；\n2.针对优店宝的数据，面向23-48岁的女性投放。","testPhone":null,"type":1,"url":null},{"adId":0,"adPic":"http://biz.img.jie0.cn/pic/1/a13439cd-6959-494a-a09a-3cfb6da36466.jpg","adType":3,"addIncome":0.00,"advertiser":"迪欧空间杭州大都会店","background":"迪欧空间创立于1995年，是中国最具规模的软体家具生产企业之一，拥有成熟的设计理念和专业的制造团队，致力打造中国布艺沙发第一品牌。","businessId":0,"click":206,"clickRate":0.7800,"cost":0.00,"coupons":0,"createTime":1558318260000,"effect":2.00,"hotIndex":0,"id":82,"index":0,"industryId":281,"outerPic":"http://biz.img.jie0.cn/pic/1/5bd5d5cf-c061-40e4-9b2a-429052d24a05.jpg","pic":"http://biz.img.jie0.cn/pic/1/6bdc7871-27a3-49f7-b914-b0ab4d8badf2.png","pictures":"http://biz.img.jie0.cn/pic/1/2ad18a3e-7e8f-4bea-87f1-4ca3a55bd41c.png,http://biz.img.jie0.cn/pic/1/eb59952f-dbb3-4982-9f67-e9e7629d0994.png,http://biz.img.jie0.cn/pic/1/439354d9-6f76-4637-a3a8-159eb2f08f81.png","publishTime":1558318260000,"purpose":"迪欧空间希望通过精准的定向推广，获得有效的线索信息，推广优惠活动，实现线下引流。","pushDesc":"<p>新房到手别焦急装<br/>错一步毁一房<br/>先领整体家居方案<br/>再砸金蛋领取现金红包<br/></p>","pushIcon":"http://biz.img.jie0.cn/pic/1/636efbac-daf3-468a-bf43-9ce6a09b7b99.jpg","pushSubtitle":"砸金蛋领取现金红包","pushTitle":"迪欧空间广告案例","qrUrl":"http://biz.img.jie0.cn/pic/brain/news/qr/4c0d4013-9868-480f-bd2a-96bd5b358e2c.jpg","readCount":15,"show":26257,"showTag":0,"status":0,"storeId":0,"strategy":"1.针对优店宝数据，在杭州市进行广告投放；\n2.收集线索，2019.5.25到店即可参加活动，领取礼品；\n3.广告页面搭配小程序一同投放，给顾客不同的展现页面。","testPhone":null,"type":1,"url":null}]})';
        exit;
        //Yii::app()->end();
    }
}
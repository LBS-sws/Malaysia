<?php

/**
 */
class SupportEmployeeController extends Controller
{
	public $function_id='AY04';

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
                'actions'=>array('index'),
                'expression'=>array('SupportEmployeeController','allowReadOnly'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function allowReadOnly() {
        return Yii::app()->user->validFunction('AY04');
    }

    public function actionIndex($pageNum=0){
        $model = new SupportEmployeeList;
        if (isset($_POST['SupportEmployeeList'])) {
            $model->attributes = $_POST['SupportEmployeeList'];
        } else {
            $session = Yii::app()->session;
            if (isset($session['supportEmployee_01']) && !empty($session['supportEmployee_01'])) {
                $criteria = $session['supportEmployee_01'];
                $model->setCriteria($criteria);
            }
        }
        $model->determinePageNum($pageNum);
        $model->retrieveDataByPage($model->pageNum);
        $this->render('index',array('model'=>$model));
    }
}
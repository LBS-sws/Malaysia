<?php
$this->pageTitle=Yii::app()->name . ' - supportEmployee';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'supportEmployee-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>
<style>
    .supportDiv{position: absolute;top: 0px;left: 0px;padding: 10px 10px 0px 10px;box-shadow:0 5px 10px rgba(0,0,0,0.1);background: #fff;display: none;z-index: 2;width: 195px;}
    .supportDiv:after{content:" ";position: absolute;margin-left:-5px;bottom: -8px;left: 50%;border-top: 10px solid #fff;border-left: 5px solid transparent;border-right: 5px solid transparent;}</style>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Search support employee'); ?></strong>
    </h1>
    <!--
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Layout</a></li>
            <li class="active">Top Navigation</li>
        </ol>
    -->
</section>

<section class="content">
    <div class="box">
        <div class="box-body">
            <div class="col-lg-12">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'year',array('class'=>"")); ?>
                    <?php echo $form->dropDownList($model, 'year',$model->getYearList(),
                        array('class'=>'form-control'));
                    ?>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'employee_id',array('class'=>"")); ?>
                    <?php echo $form->dropDownList($model, 'employee_id',$model->getEmployeeList(),
                        array('class'=>'form-control'));
                    ?>
                </div>

                <div class="btn-group" role="group">
                    <?php echo TbHtml::button('<span class="fa fa-search"></span> '.Yii::t('misc','Search'), array(
                        'submit'=>Yii::app()->createUrl('supportEmployee/index')));
                    ?>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="col-lg-8 col-lg-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="svgSupport">

                </svg>
            </div>
        </div>
    </div>
</section>

<?php

$js = "
$(window).resize(function(){
    $('#svgSupport').svgSupport({'dataList':".json_encode($model->attr).",'yList':".json_encode($model->cityList)."});
}).trigger('resize');
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/svg.js", CClientScript::POS_END);//
?>

<?php $this->endWidget(); ?>


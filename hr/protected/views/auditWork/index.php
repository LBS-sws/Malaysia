<?php
$this->pageTitle=Yii::app()->name . ' - auditWork';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'auditWork-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','audit for work overtime'); ?></strong>
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
    <?php
    $search = array(
        'work_code',
        'employee_name',
        'city_name',
    );
    $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('fete','Overtime work List'),
        'model'=>$model,
        'viewhdr'=>'//auditWork/_listhdr',
        'viewdtl'=>'//auditWork/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
        'searchlinkparam'=>array('only'=>$model->only),
        'search'=>$search,
    ));
    ?>
</section>
<?php
echo $form->hiddenField($model,'pageNum');
echo $form->hiddenField($model,'totalRow');
echo $form->hiddenField($model,'orderField');
echo $form->hiddenField($model,'orderType');
?>
<?php $this->endWidget(); ?>

<?php
$js = Script::genTableRowClick();
Yii::app()->clientScript->registerScript('rowClick',$js,CClientScript::POS_READY);
?>


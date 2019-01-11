<?php
if (empty($model->id)&&$model->scenario == "edit"){
    $this->redirect(Yii::app()->createUrl('binding/index'));
}
$this->pageTitle=Yii::app()->name . ' - Binding Form';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'binding-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('contract','Binding Form'); ?></strong>
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
	<div class="box"><div class="box-body">
	<div class="btn-group" role="group">
		<?php echo TbHtml::button('<span class="fa fa-reply"></span> '.Yii::t('misc','Back'), array(
				'submit'=>Yii::app()->createUrl('binding/index')));
		?>

        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('binding/save')));
            ?>
            <?php if ($model->scenario=='edit'): ?>
                <?php
                echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('contract','Add Binding'), array(
                    'submit'=>Yii::app()->createUrl('binding/new'),
                ));
                ?>
                <?php echo TbHtml::button('<span class="fa fa-remove"></span> '.Yii::t('misc','Delete'), array(
                        'name'=>'btnDelete','id'=>'btnDelete','data-toggle'=>'modal','data-target'=>'#removedialog',)
                );
                ?>
            <?php endif; ?>
        <?php endif ?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>
			<div class="form-group text-error">
                <div class="col-sm-3 col-sm-offset-2">
                    <p class="form-control-static text-danger">注意：员工与账号为一对一关系</p>
                </div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'employee_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'employee_id',$model->getEmployeeList(),
                        array('disabled'=>($model->scenario=='view'))
                    ); ?>
                </div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'user_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'user_id',$model->getUserList(),
                        array('disabled'=>($model->scenario=='view'))
                    ); ?>
                </div>
			</div>

		</div>
	</div>
</section>

<?php
$this->renderPartial('//site/removedialog');
?>
<?php

$js = Script::genDeleteData(Yii::app()->createUrl('binding/delete'));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->


<?php
if (empty($model->id)&&$model->scenario != "new"){
    $this->redirect(Yii::app()->createUrl('dept/index',array("type"=>$model->type)));
}
$this->pageTitle=Yii::app()->name . ' - Dept Form';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'dept-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo $model->getTypeName().Yii::t('contract',' Form'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('dept/index',array("type"=>$model->type))));
		?>
        <?php if ($model->scenario == "edit"): ?>
            <?php echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('misc','Add'), array(
                'submit'=>Yii::app()->createUrl('dept/new',array("type"=>$model->type))));
            ?>
        <?php endif ?>

        <?php if ($model->scenario!='view'): ?>
            <?php echo TbHtml::button('<span class="fa fa-upload"></span> '.Yii::t('misc','Save'), array(
                'submit'=>Yii::app()->createUrl('dept/save',array("type"=>$model->type))));
            ?>
            <?php if ($model->scenario=='edit'): ?>
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
			<?php echo $form->hiddenField($model, 'type'); ?>

            <?php if ($model->type==1): ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label required"><?php echo Yii::t("contract","in department");?><span class="required">*</span></label>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'dept_id',$model->getDeptAllListNoCity(),
                            array('readonly'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>
			<div class="form-group">
                <label class="col-sm-2 control-label required"><?php echo $model->getTypeName().Yii::t("contract"," Name");?><span class="required">*</span></label>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'name',
						array('readonly'=>($model->scenario=='view'))
					); ?>
				</div>
			</div>


            <?php if ($model->type==1): ?>
                <?php echo $form->hiddenField($model, 'city'); ?>
            <?php else: ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'city',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'city',WordForm::getCityListAll(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'sales_type',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'sales_type',DeptForm::getSalesType(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>

			<div class="form-group">
				<?php echo $form->labelEx($model,'z_index',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->numberField($model, 'z_index',
                        array('mim'=>0,'readonly'=>($model->scenario=='view'))
                    ); ?>
                </div>
			</div>
            <?php if ($model->type==1): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'dept_class',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'dept_class',EmployList::getStaffTypeList(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->type==1): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'manager',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'manager',EmployList::getManagerList(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->type==1): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'technician',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'technician',EmployList::getTechnicianList(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($model->type==1): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'review_status',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'review_status',array(Yii::t("contract","not Participate"),Yii::t("contract","Participate")),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'review_type',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'review_type',$model->getReviewType(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'review_leave',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->dropDownList($model, 'review_leave',$model->getReviewLeave(),
                            array('disabled'=>($model->scenario=='view'))
                        ); ?>
                    </div>
                </div>
            <?php endif; ?>

		</div>
	</div>
</section>

<?php
$this->renderPartial('//site/removedialog');
?>
<?php
$js = Script::genDeleteData(Yii::app()->createUrl('dept/delete',array("type"=>$model->type)));
Yii::app()->clientScript->registerScript('deleteRecord',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->


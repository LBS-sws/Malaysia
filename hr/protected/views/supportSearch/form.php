<?php
if (empty($model->id)){
    $this->redirect(Yii::app()->createUrl('supportSearch/index'));
}
$this->pageTitle=Yii::app()->name . ' - supportSearch';
?>
<style>

    select[readonly="readonly"]{pointer-events: none;}
</style>
<?php $form=$this->beginWidget('TbActiveForm', array(
'id'=>'supportSearch-form',
'enableClientValidation'=>true,
'clientOptions'=>array('validateOnSubmit'=>true),
'layout'=>TbHtml::FORM_LAYOUT_HORIZONTAL,
    'htmlOptions'=>array('enctype' => 'multipart/form-data')
)); ?>

<section class="content-header">
	<h1>
		<strong><?php echo Yii::t('contract','support form'); ?></strong>
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
				'submit'=>Yii::app()->createUrl('supportSearch/index')));
		?>
	</div>
	<div class="btn-group pull-right" role="group">
		<?php echo TbHtml::button('<span class="fa fa-calendar"></span> '.Yii::t('app','History'), array(
            'data-toggle'=>'modal','data-target'=>'#historydialog'));
		?>
	</div>
	</div></div>

	<div class="box box-info">
		<div class="box-body">
			<?php echo $form->hiddenField($model, 'scenario'); ?>
			<?php echo $form->hiddenField($model, 'id'); ?>
			<?php echo $form->hiddenField($model, 'status_type'); ?>

            <?php if (in_array($model->status_type,array(9,10))): ?>
                <div class="form-group has-error">
                    <?php echo $form->labelEx($model,'early_date'.$model->status_type,array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo $form->textField($model, 'early_date',
                            array('class'=>'form-control','readonly'=>(true)));
                        ?>
                    </div>
                </div>
                <div class="form-group has-error">
                    <?php echo $form->labelEx($model,'early_remark'.$model->status_type,array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-4">
                        <?php echo $form->textArea($model, 'early_remark',
                            array('rows'=>3,'readonly'=>(true))
                        ); ?>
                    </div>
                </div>
            <?php endif ?>


            <div class="form-group">
                <?php echo $form->labelEx($model,'support_code',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'support_code',
                        array('class'=>'form-control','readonly'=>(true)));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'service_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-2">
                    <?php echo $form->dropDownList($model, 'service_type',SupportApplyList::getServiceList(),
                        array('readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <?php if ($model->apply_type==2): ?>
                <div class="form-group">
                    <?php echo $form->labelEx($model,'apply_type',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-3">
                        <?php echo TbHtml::textField('apply_type', Yii::t("contract","renewal"),
                            array('class'=>'form-control','readonly'=>(true)));
                        ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'apply_city',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'city_name',
                        array('class'=>'form-control','readonly'=>(true)));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'apply_date',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <?php echo $form->textField($model, 'apply_date',
                            array('class'=>'form-control','readonly'=>(true),'id'=>"apply_date"));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'apply_end_date',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <?php echo $form->textField($model, 'apply_end_date',
                            array('class'=>'form-control','readonly'=>(true),'id'=>"apply_end_date"));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'length_type',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-2">
                    <div class="input-group">
                        <?php echo $form->textField($model, 'apply_length',
                            array('rows'=>3,'readonly'=>(true),'id'=>"apply_length")
                        ); ?>
                        <span class="input-group-btn" style="width: 80px;">
                        <?php echo $form->dropDownList($model, 'length_type',array(1=>"个月",2=>"天"),
                            array('rows'=>3,'readonly'=>(true),'id'=>"length_type")
                        ); ?>
                    </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'privilege',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-2">
                    <?php echo $form->dropDownList($model, 'privilege',SupportApplyList::getPrivilegeList(),
                        array('readonly'=>($model->getReadonly()),'id'=>'privilege')
                    ); ?>
                </div>
            </div>
            <div class="form-group" id="privilege_user" style="<?php if($model->privilege != 1){ echo "display:none;";}?>">
                <?php echo $form->labelEx($model,'privilege_user',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->dropDownList($model, 'privilege_user',SupportApplyForm::getPrivilegeUserList($model->apply_city),
                        array('readonly'=>($model->getReadonly()))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'apply_remark',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-4">
                    <?php echo $form->textArea($model, 'apply_remark',
                        array('rows'=>3,'readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'audit_remark',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-4">
                    <?php echo $form->textArea($model, 'audit_remark',
                        array('rows'=>3,'readonly'=>(true))
                    ); ?>
                </div>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model,'employee_id',array('class'=>"col-sm-2 control-label")); ?>
                <div class="col-sm-3">
                    <?php echo $form->textField($model, 'employee_name',
                        array('rows'=>3,'readonly'=>(true))
                    ); ?>
                </div>
            </div>

            <?php if ($model->update_type==1): ?>
                <div class="form-group has-error">
                    <?php echo $form->labelEx($model,'update_remark',array('class'=>"col-sm-2 control-label")); ?>
                    <div class="col-sm-4">
                        <?php echo $form->textArea($model, 'update_remark',
                            array('rows'=>3,'readonly'=>(true))
                        ); ?>
                    </div>
                </div>
            <?php endif ?>

            <?php
            if(!in_array($model->status_type,array(1,2,12))){ //顯示評核分數
                echo "<legend>".Yii::t("contract","reviewAllot project")."</legend>";
                $supportModel = new SupportAuditForm();
                $tabs = $supportModel->getTabList($model);
                $this->widget('bootstrap.widgets.TbTabs', array(
                    'tabs'=>$tabs,
                ));
            }
            ?>
		</div>
	</div>
</section>

<?php
$this->renderPartial('//site/history',array('tableHtml'=>SupportSearchForm::getHistoryHtml($model->id)));


$js = "
";
Yii::app()->clientScript->registerScript('calcFunction',$js,CClientScript::POS_READY);

$js = Script::genReadonlyField();
Yii::app()->clientScript->registerScript('readonlyClass',$js,CClientScript::POS_READY);
?>

<?php $this->endWidget(); ?>

</div><!-- form -->


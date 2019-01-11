<?php
$this->pageTitle=Yii::app()->name . ' - MakeWages';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'makeWages-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Wages Make'); ?></strong>
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
            <div class="btn-group" role="group">
                <?php
                //var_dump(Yii::app()->session['rw_func']);
                if (Yii::app()->user->validRWFunction('ZA04'))
                    echo TbHtml::button('<span class="fa fa-file-o"></span> '.Yii::t('app','Wages Make'), array(
                        'submit'=>Yii::app()->createUrl('makeWages/new'),
                    ));
                ?>
            </div>
            <?php
            $list = MakeWagesForm::getEmployeeList();
            if(count($list)>1){
                echo "<div class='btn-group pull-right text-right' style='padding: 7px;'><span class='text-warning'>".Yii::t("contract","Staff to be produced")."：".(count($list)-1)."人</span></div>";
            }else{
                echo "<div class='btn-group pull-right text-right' style='padding: 7px;'><span class='text-success'>".Yii::t("contract","The payroll is completed this month")."</span></div>";
            }
            ?>
        </div>
    </div>
    <?php
    $search = array(
        'code',
        'name',
    );
    if (!Yii::app()->user->isSingleCity()) $search[] = 'city_name';
   $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('contract','Wages List'),
        'model'=>$model,
        'viewhdr'=>'//makeWages/_listhdr',
        'viewdtl'=>'//makeWages/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
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


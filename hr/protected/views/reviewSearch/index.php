<?php
$this->pageTitle=Yii::app()->name . ' - reviewSearch';
?>

<?php $form=$this->beginWidget('TbActiveForm', array(
    'id'=>'reviewSearch-list',
    'enableClientValidation'=>true,
    'clientOptions'=>array('validateOnSubmit'=>true,),
    'layout'=>TbHtml::FORM_LAYOUT_INLINE,
)); ?>

<section class="content-header">
    <h1>
        <strong><?php echo Yii::t('app','Review Search'); ?></strong>
    </h1>
</section>

<section class="content">
    <?php
    $search = array(
        'code',
        'name',
        'department',
        'position',
        'status',
    );
    $search_add_html="";
    $modelName = get_class($model);
    $search_add_html .= TbHtml::dropDownList($modelName.'[year]',$model->year,$model->getYearList(),
        array("class"=>"form-control"));
    $search_add_html.="<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
    $search_add_html .= TbHtml::dropDownList($modelName.'[year_type]',$model->year_type,$model->getYearTypeList(),
        array("class"=>"form-control"));
    if (!Yii::app()->user->isSingleCity()) $search[] = 'city_name';
    $this->widget('ext.layout.ListPageWidget', array(
        'title'=>Yii::t('contract','Employee List'),
        'model'=>$model,
        'viewhdr'=>'//reviewSearch/_listhdr',
        'viewdtl'=>'//reviewSearch/_listdtl',
        'gridsize'=>'24',
        'height'=>'600',
        'search'=>$search,
        'search_add_html'=>$search_add_html,
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


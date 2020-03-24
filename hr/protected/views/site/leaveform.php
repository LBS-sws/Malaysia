
<?php if (!empty($model->reject_cause)): ?>
    <div class="form-group has-error">
        <?php echo $form->labelEx($model,'reject_cause',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-6">
            <?php echo $form->textArea($model, 'reject_cause',
                array('readonly'=>(true),"rows"=>4)
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if ($model->scenario!='new'): ?>
    <div class="form-group">
        <?php echo TbHtml::label($model->getAttributeLabel("leave_code").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
        <div class="col-lg-4">
            <?php echo $form->textField($model, 'leave_code',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if (get_class($model) == "LeaveForm"&&Yii::app()->user->validFunction('ZR06')&&!$model->getInputBool()): ?>
    <div class="form-group">
        <?php echo TbHtml::label($model->getAttributeLabel("employee_id").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
        <div class="col-lg-4">
            <?php echo $form->dropDownList($model, 'employee_id',LeaveForm::getBindEmployeeList($model->employee_id),
                array('readonly'=>($model->getInputBool()),'id'=>'employee_id')
            ); ?>
        </div>
    </div>
<?php else:?>
    <div class="form-group">
        <?php echo TbHtml::label($model->getAttributeLabel("employee_id").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
        <div class="col-lg-4">
            <?php echo $form->hiddenField($model, 'employee_id',array('id'=>'employee_id')); ?>
            <?php echo TbHtml::textField("employee_name",YearDayList::getEmployeeNameToId($model->employee_id),array('readonly'=>true))?>
        </div>
    </div>
<?php endif; ?>
<div class="form-group">
    <?php echo TbHtml::label($model->getAttributeLabel("vacation_id").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
    <div class="col-lg-3">
        <?php echo $form->dropDownList($model, 'vacation_id',LeaveForm::getLeaveTypeList($model->city),
            array('readonly'=>($model->getInputBool()),"id"=>"leave_type")
        ); ?>
    </div>
</div>
<div class="form-group">
    <?php echo TbHtml::label(Yii::t("contract","Apply Time").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
    <div class="col-lg-7">
        <?php
        $leaveModel = new LeaveForm();
        $leaveModel->setAttributes($model->getAttributes());
        echo $leaveModel->parintLeaveTimeTable($model->getInputBool());
        ?>
    </div>
</div>
<?php if (!$model->getInputBool()): ?>
<script>
    $(function () {
        $('#addTimeTable').delegate("#addLeaveTime","click",function () {
            var tBody = $("#addTimeTable>tbody:first");
            var num = tBody.data("num");
            tBody.data("num",num+1);
            var html = $("#leaveTrModel").html();
            html = html.replace(/#key#/g, "LeaveForm[addTime]["+num+"]");
            html = $("<tr>"+html+"</tr>");
            html.find('.dateTime').datepicker({autoclose: true, format: 'yyyy/mm/dd',language: 'zh_cn'});
            tBody.append(html);
        });

        $('#addTimeTable').delegate(".s_time","change",function () {
            var value = $(this).parents("tr:first").find(".e_time").val();
            if(value == ""){
                $(this).parents("tr:first").find(".e_time").val($(this).val());
            }
        });

        $('#addTimeTable').delegate(".delWages","click",function () {
            $(this).parents("tr:first").remove();
        });

        $('.dateTime').datepicker({autoclose: true, format: 'yyyy/mm/dd',language: 'zh_cn'});


        $('#addTimeTable').delegate(".s_time,.e_time,.s_long,.e_long",'change',function(){
            var hours = 0;
            $('#log_time').attr('readonly',true);
            $('#addTimeTable>tbody>tr').each(function () {
                var start_day = $(this).find(".s_time:first").val();
                var end_day = $(this).find(".e_time:first").val();
                var start_hour = $(this).find(".s_long:first").val();
                var end_hour = $(this).find(".e_long:first").val();
                hours+=sumTimeLogToLeave(start_day,end_day,start_hour,end_hour);
                if($('#log_time').attr('readonly')==undefined){
                    return false;
                }
            });
            if($('#log_time').attr('readonly')=="readonly"){
                $('#log_time').val(hours);
            }
        });
    });
    function sumTimeLogToLeave(start_day,end_day,start_hour,end_hour) {
        var hours = 0;
        if(start_day!=''&&end_day!=''){
            var d1 = new Date(start_day);
            var week1 = d1.getDay();
            var d2 = new Date(end_day);
            var week2 = d2.getDay();
            d1 = d1.getTime();
            d2 = d2.getTime();
            if(d1<=d2){
                var time = d2-d1;
                hours=time/(24*3600*1000);
                if(start_hour==end_hour){
                    hours+=0.5;
                }else{
                    if(start_hour == 'AM'){
                        hours++;
                    }
                }
                if(hours>0){
                    if(week1 === 0 || week1 === 6 || week2 === 0 || week2 === 6 || hours >= 6 || week1 > week2){
                        $('#log_time').attr('readonly',false);
                    }
                }else{
                    hours = 0;
                }
            }
        }
        return hours;
    }
</script>
<?php endif; ?>
<div class="form-group">
    <?php echo TbHtml::label($model->getAttributeLabel("log_time").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
    <div class="col-lg-3">
        <div class="input-group">
            <?php echo $form->numberField($model, 'log_time',
                array('readonly'=>(true),"id"=>"log_time")
            ); ?>
            <span class="input-group-addon">天</span>
        </div>
    </div>
</div>
<?php if (!empty($model->lcd)): ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'lcd',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'lcd',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
<div class="form-group">
    <?php echo TbHtml::label($model->getAttributeLabel("leave_cause").'<span class="required">*</span>',"",array('class'=>"col-lg-2 control-label"));?>
    <div class="col-lg-6">
        <?php echo $form->textArea($model, 'leave_cause',
            array('readonly'=>($model->getInputBool()),"rows"=>4)
        ); ?>
    </div>
</div>

<?php if (!empty($model->user_lcu)): ?>
    <legend><?php echo Yii::t("fete","Audit Info")?></legend>
    <div class="form-group">
        <?php echo $form->labelEx($model,'user_lcu',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'user_lcu',
                array('readonly'=>(true))
            ); ?>
        </div>
        <?php echo $form->labelEx($model,'user_lcd',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'user_lcd',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($model->area_lcu)): ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'area_lcu',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'area_lcu',
                array('readonly'=>(true))
            ); ?>
        </div>
        <?php echo $form->labelEx($model,'area_lcd',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'area_lcd',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($model->head_lcu)): ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'head_lcu',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'head_lcu',
                array('readonly'=>(true))
            ); ?>
        </div>
        <?php echo $form->labelEx($model,'head_lcd',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'head_lcd',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!empty($model->you_lcu)): ?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'you_lcu',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'you_lcu',
                array('readonly'=>(true))
            ); ?>
        </div>
        <?php echo $form->labelEx($model,'you_lcd',array('class'=>"col-lg-2 control-label")); ?>
        <div class="col-lg-3">
            <?php echo $form->textField($model, 'you_lcd',
                array('readonly'=>(true))
            ); ?>
        </div>
    </div>
<?php endif; ?>
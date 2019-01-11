<tr>
	<th></th>
	<th>
		<?php
        echo TbHtml::link($this->model->getTypeName().$this->getLabelName('name').$this->drawOrderArrow('name'),'#',$this->createOrderLink('dept-list','name'))
			;
		?>
	</th>
    <?php
        if($this->model->type == 1){
            echo "<th>";
            echo TbHtml::link($this->getLabelName('dept_id').$this->drawOrderArrow('dept_id'),'#',$this->createOrderLink('dept-list','dept_id'));
            echo "</th>";
        }
    ?>
	<th>
		<?php echo TbHtml::link($this->getLabelName('city').$this->drawOrderArrow('city'),'#',$this->createOrderLink('dept-list','city'))
			;
		?>
	</th>
	<th>
		<?php echo TbHtml::link($this->getLabelName('z_index').$this->drawOrderArrow('z_index'),'#',$this->createOrderLink('dept-list','z_index'))
			;
		?>
	</th>
    <?php
    if($this->model->type == 1){
        echo "<th>";
        echo TbHtml::link($this->getLabelName('dept_class').$this->drawOrderArrow('dept_class'),'#',$this->createOrderLink('dept-list','dept_class'));
        echo "</th>";
    }
    ?>
</tr>

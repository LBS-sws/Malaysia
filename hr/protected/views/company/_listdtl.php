<tr class='clickable-row' data-href='<?php echo $this->getLink('ZA02', 'company/edit', 'company/view', array('index'=>$this->record['id']));?>'>


    <td><?php echo $this->drawEditButton('ZA02', 'company/edit', 'company/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['name']; ?></td>
    <td><?php echo $this->record['city']; ?></td>
    <td><?php echo $this->record['head']; ?></td>
	<td><?php echo $this->record['agent']; ?></td>
	<td>
        <?php
        if ($this->record['tacitly'] == 1){
            echo Yii::t("contract","Tacitly Company");
        }else{
            echo "&nbsp;";
        }
        ?>
    </td>
</tr>

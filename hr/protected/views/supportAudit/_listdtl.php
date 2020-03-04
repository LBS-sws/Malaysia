<tr class='clickable-row <?php echo $this->record['style'];?>' data-href='<?php echo $this->getLink('AY02', 'supportAudit/edit', 'supportAudit/view', array('index'=>$this->record['id']));?>'>


	<td><?php echo $this->drawEditButton('AY02', 'supportAudit/edit', 'supportAudit/view', array('index'=>$this->record['id'])); ?></td>



    <td><?php echo $this->record['support_code']; ?></td>
    <td><?php echo $this->record['apply_type']; ?></td>
    <td><?php echo $this->record['service_type']; ?></td>
    <td><?php echo $this->record['privilege']; ?></td>
    <td><?php echo $this->record['apply_city']; ?></td>
    <td><?php echo $this->record['apply_date']; ?></td>
    <td><?php echo $this->record['apply_end_date']; ?></td>
    <td><?php echo $this->record['name']; ?></td>
    <td><?php echo $this->record['dept_name']; ?></td>
    <td><?php echo $this->record['review_sum']; ?></td>
    <td><?php echo $this->record['status']; ?></td>
</tr>

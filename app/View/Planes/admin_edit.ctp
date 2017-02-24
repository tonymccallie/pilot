<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Edit Plane
	</h3>
</div>
<div class="">
	<?php echo $this->Form->create('Plane') ?>
	<div class="row-fluid">
		<div class="span6">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('tag',array('class'=>'span12'));
				echo $this->Form->input('Manager',array('label'=>'Managers','options'=>$users,'class'=>'span12','multiple'=>'checkbox'));
				echo $this->Form->input('Owner',array('label'=>'Owners','options'=>$users,'class'=>'span12','multiple'=>'checkbox'));
				
			?>
		</div>
	</div>
	<?php echo $this->Form->end(array('label'=>'Save Plane','class'=>'btn')); ?>
</div>
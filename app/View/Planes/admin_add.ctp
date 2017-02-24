<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Add Plane
	</h3>
</div>
<div class="">
	<?php echo $this->Form->create() ?>
	<div class="row-fluid">
		<div class="span6">
			<?php
				echo $this->Form->input('tag',array('class'=>'span12'));
				echo $this->Form->input('User.User',array('label'=>'Owners','options'=>$users,'class'=>'span12','multiple'=>'checkbox'));
			?>
		</div>
	</div>
	<?php echo $this->Form->end(array('label'=>'Add Plane','class'=>'btn')); ?>
</div>
<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Flightplan
	</h3>
</div>
<div class="">
	<?php echo $this->Form->create(); ?>
	<div class="row-fluid">
		<div class="span6">
			<?php
				echo $this->Form->input('pilot_id',array('value'=>Authsome::get('User.id'), 'options'=>$pilots, 'empty' => 'Choose Pilot', 'class'=>'span12'));
				echo $this->Form->input('plane_id',array('options'=>$planes, 'empty' => 'Choose Plane', 'class'=>'span12'));
			?>
		</div>
	</div>
	<?php echo $this->Form->end(array('label'=>'Next','class'=>'btn')); ?>
</div>
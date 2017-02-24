<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Add User
	</h3>
</div>
<div class="">
	<?php
		echo $this->Form->create();
	?>
	<div class="row-fluid">
		<div class="span6">
			<?php
				echo $this->Form->input('email',array('class'=>'span12'));
				echo $this->Form->input('role_id',array('class'=>'span12'));
			?>
		</div>
		<div class="span6">
			<?php
				echo $this->Form->input('first_name',array('class'=>'span12'));
				echo $this->Form->input('last_name',array('class'=>'span12'));
			?>
		</div>
	</div>	
	<?php
		echo $this->Form->end(array('label'=>'Add User','class'=>'btn'));
	?>
</div>
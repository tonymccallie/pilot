<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Add User
	</h3>
</div>
<div class="">
	<?php
		echo $this->Form->create();
			echo $this->Form->input('email',array());
			echo $this->Form->input('role_id',array());
		echo $this->Form->end(array('label'=>'Add User','class'=>'btn'));
	?>
</div>
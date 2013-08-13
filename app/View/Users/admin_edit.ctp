<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Edit User
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('<i class="icon-trash"></i> ', array('action' => 'delete', $this->data['User']['id']), array('escape'=>false,'class'=>'btn'),'Are you sure you want to delete this User?'); ?>
		</div>
	</h3>
</div>
<div class="">
	<?php
		echo $this->Form->create();
			echo $this->Form->input('id',array());
			echo $this->Form->input('email',array());
			echo $this->Form->input('role_id',array());
		echo $this->Form->end(array('label'=>'Save User','class'=>'btn'));
	?>
</div>
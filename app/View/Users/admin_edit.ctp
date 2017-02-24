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
	?>
	<div class="row-fluid">
		<div class="span6">
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('email',array('class'=>'span12'));
				echo $this->Form->input('role_id',array('class'=>'span12'));
				echo $this->Form->input('rate',array('label'=>'Day Rate','class'=>'span12'));
			?>
		</div>
		<div class="span6">
			<?php
				echo $this->Form->input('first_name',array('class'=>'span12'));
				echo $this->Form->input('last_name',array('class'=>'span12'));
				echo $this->Form->input('passwd',array('class'=>'span12'));
				echo $this->Form->input('passwd_verify',array('class'=>'span12','type'=>'password'));
			?>
		</div>
	</div>	
	<?php
		echo $this->Form->end(array('label'=>'Save User','class'=>'btn'));
	?>
</div>
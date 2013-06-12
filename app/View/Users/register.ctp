<div class="span6 offset3">
	<div class="well">
		<h2>Register</h2>
		<?php
			echo $this->Form->create('User'); 
				echo $this->Form->input('email', array('label' => false,'placeholder'=>'Email','class'=>'span12'));
				echo $this->Form->input('passwd', array('label' => false,'placeholder'=>'Password','class'=>'span12')); 
				echo $this->Form->input('passwd_verify',array('type'=>'password','label'=>false,'placeholder'=>'Password Verify','class'=>'span12'));
			echo $this->Form->end(array('label'=>'Verify Email Address','class'=>'btn btn-primary pull-right'));		
		?>
	</div>
</div>
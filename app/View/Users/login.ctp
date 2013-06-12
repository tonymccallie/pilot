<div class="span8 offset2">
	<div class="well">
		<h2>Login</h2>
		<?php
			$url = $this->Session->read('requested_url');
			echo $this->Form->create('User', array('action' => $this->action)); 
				echo $this->Form->input('url',array('value'=>$url,'type'=>'hidden'));
		?>
		<div class="row-fluid">
			<div class="span6">
				<?php echo $this->Form->input('email', array('label' => false,'placeholder'=>'Email','class'=>'span12')); ?>
			</div>
			<div class="span6">
				<?php echo $this->Form->input('passwd', array('label' => false,'placeholder'=>'Password','class'=>'span12')); ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="btn-group pull-right">
					<?php
						echo $this->Html->link('Forgot Password?', array('action' => 'recover'),array('class'=>'btn','escape'=>false));
						echo $this->Html->link('Register',array('action'=>'register'),array('class'=>'btn','escape'=>false));
						echo $this->Form->submit('Login',array('class'=>'btn btn-primary','div'=>false));
					?>
				</div>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
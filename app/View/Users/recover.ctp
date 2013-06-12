<div class="span6 offset3">
	<div class="well">
		<h2>Recover Password</h2>
		<?php
			echo $this->Form->create('User'); 
				echo $this->Form->input('email', array('label' => false,'placeholder'=>'Email','class'=>'span12'));
			echo $this->Form->end(array('label'=>'Request Password Reset','class'=>'btn btn-primary pull-right'));
		?>
	</div>
</div>
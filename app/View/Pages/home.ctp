<div class="span6 offset3">
	<div class="well">
		<h1>Air-sistant</h1>
		<p>Welcome to Air-sistant, the flight logging tool.</p>
		<div class="btn-group">
			<?php
				echo $this->Html->link('Register','/users/register',array('escape'=>false,'class'=>'btn btn-large'));
				echo $this->Html->link('Login','/users/login',array('class'=>'btn btn-large btn-primary'));
			?>
		</div>
	</div>
</div>
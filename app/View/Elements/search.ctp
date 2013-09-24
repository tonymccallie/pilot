<div class="input-append pull-right">
	<?php
		echo $this->Form->create();
			echo $this->Form->input('search',array('div'=>false, 'class'=>'','label'=>false));
			echo $this->Form->submit('Search',array('class'=>'btn','div'=>false));
		echo $this->Form->end();
	?>
</div>
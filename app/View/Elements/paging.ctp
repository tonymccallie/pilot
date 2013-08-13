<div class="pagination pull-right">
	<ul>
	<?php

		echo $this->Paginator->first('<<',array(
			'tag' => 'li',
		));
		echo $this->Paginator->prev('<',array(
			'tag' => 'li',
			'escape' => false,
		),'<a href="#"><</a>');
		echo $this->Paginator->numbers(array(
			'modulus' => 4,
			'separator' => '',
			'tag' => 'li',
			'currentClass'=>'active',
		));
		echo $this->Paginator->next('>',array(
			'tag' => 'li',
			'escape' => false
		),'<a href="#">></a>');
		echo $this->Paginator->last('>>',array(
			'tag' => 'li'
		));
	?>
	</ul>
</div>
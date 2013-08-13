<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Users
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('Add User', array('action' => 'add'),array('class'=>'btn','escape'=>false)); ?>
		</div>
	</h3>
</div>
<div class="">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>
					<?php echo $this->Paginator->sort('email','<i class="icon-sort"></i> Email',array('escape'=>false)); ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('role_id','<i class="icon-sort"></i> Role',array('escape'=>false)); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($users as $user): ?>
			<tr>
				<td><?php echo $this->Html->link($user['User']['email'],array('action'=>'edit',$user['User']['id'])) ?></td>
				<td><?php echo $user['Role']['name'] ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<?php echo $this->element('paging') ?>
</div>
<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Flightplans
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('Add Flightplan', array('action' => 'add'),array('class'=>'btn','escape'=>false)); ?>
		</div>
	</h3>
</div>
<div class="">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>
					<?php echo $this->Paginator->sort('plane_id','<i class="icon-sort"></i> Plane',array('escape'=>false)); ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('pilot_id','<i class="icon-sort"></i> Pilot',array('escape'=>false)); ?>
				</th>
				<th>
					<?php echo $this->Paginator->sort('start','<i class="icon-sort"></i> Date',array('escape'=>false)); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($flightplans as $flightplan): ?>
			<tr>
				<td><?php echo $this->Html->link($flightplan['Plane']['tag'],array('action'=>'edit',$flightplan['Flightplan']['id'])); ?></td>
				<td><?php echo $flightplan['Pilot']['first_name'].' '.$flightplan['Pilot']['last_name'] ?></td>
				<td><?php echo date('m/d/Y',strtotime($flightplan['Flightplan']['start'])) ?> <?php echo $this->Html->link('report',array('action'=>'report',$flightplan['Flightplan']['id'],'ajax'=>true)) ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<?php echo $this->element('paging') ?>
</div>
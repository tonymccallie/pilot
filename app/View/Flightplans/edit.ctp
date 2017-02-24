<script type="text/javascript">
/* <![CDATA[ */

$(document).ready(function() {
	$('#stopsearch').typeahead({
		source: function(query, process) {
			return $.get('<?php echo $this->webroot ?>airports/search',{ query: query }, function(data) {
				if(data.length > 0) {
					json = JSON.parse(data);
					return process(json.options);
				} else {
					return process([]);
				}
				
			})
		}
	});
});


var stopApp = angular.module('stopApp',[]);

stopApp.controller('stopController',function($scope) {
	console.log('stopController');
	$scope.stops = <?php echo $this->request->data['Flightplan']['stops'] ?>;
	
	$scope.airport = "";
	
	$scope.add = function(data) {
		$scope.stops.push({'name':$scope.airport});
		$scope.airport = "";
	}
	
	$scope.remove = function(index) {
		$scope.stops.splice(index,1);
	}
});

/* ]]> */
</script>
<div class="admin_header">
	<h3>
		<i class="icon-edit"></i> Edit Flightplan
	</h3>
</div>
<div class="" ng-app="stopApp">
	<div ng-controller="stopController">
	<?php echo $this->Form->create(); ?>
		<div class="row-fluid">
			<div class="span6">
				<?php
					echo $this->Form->input('id',array());
				?>
	<!--
				<div class="full">
					<div class="fifty">
						<?php //echo $this->Form->input('date',array('class'=>'span12','type'=>'text','class'=>'datepicker')); ?>
					</div>
				</div>
	-->
				<?php
					echo $this->Form->input('responsible_id',array('label'=>'Responsible Owner','class'=>'span12','options'=>$owners, 'empty'=>'Please Choose'));
					
					
				?>
				<div class="full">
					<div class="fifty">
						<?php echo $this->Form->input('hobbs_in',array('class'=>'span11')); ?>
					</div>
					<div class="fifty">
						<?php echo $this->Form->input('hobbs_out',array('class'=>'span11')); ?>
					</div>
				</div>
				<div class="full">
					<div class="fifty">
						<?php echo $this->Form->input('Flightplan.start.date',array('label'=>'Start Date','class'=>'span12','type'=>'text','class'=>'datepicker')); ?>
					</div>
					<div class="fifty">
						<?php echo $this->Form->input('Flightplan.start.time',array('label'=>'Start Time','class'=>'span12','type'=>'text','class'=>'timepicker')); ?>
					</div>
				</div>
				<div class="full">
					<div class="fifty">
						<?php echo $this->Form->input('Flightplan.stop.date',array('label'=>'Stop Date','class'=>'span12','type'=>'text','class'=>'datepicker')); ?>
					</div>
					<div class="fifty">
						<?php echo $this->Form->input('Flightplan.stop.time',array('label'=>'Stop Time','class'=>'span12','type'=>'text','class'=>'timepicker')); ?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<?php echo $this->Form->input('days',array('label'=>'Days at $'.number_format($this->request->data['Pilot']['rate'],2),'class'=>'span12')); ?>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<h4>Stops</h4>
						<table class="table table-striped table-bordered">
							<tr ng-repeat="stop in stops">
								<td>{{stop.name}}<a ng-click="remove($index)" class="pull-right padding"><i class="icon-trash"></i></a></td>
							</tr>
						</table>
						<div class="input-append">
							<input class="span10" id="stopsearch" type="text" ng-model="airport" placholder="Search" autocomplete="off" />
							<a id="stopadd" class="add-on btn" ng-click="add()"><i class="icon-plus"></i></a>
						</div>
					</div>
				</div>
				<?php
					echo $this->Form->input('stops',array('type'=>'hidden','value'=>'{{ stops | json }}'));
					echo $this->Form->input('maintenance',array('class'=>'span12'));
					echo $this->Form->input('notes',array('class'=>'span12'));
					echo $this->Form->input('submit',array('type'=>'checkbox'));
				?>
			</div>
		</div>
		<?php echo $this->Form->end(array('label'=>'Save','class'=>'btn')); ?>
	</div>
</div>
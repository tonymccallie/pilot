<?php
App::uses('AppModel', 'Model');
class Flightplan extends AppModel {
	public $belongsTo = array(
		'Pilot' => array(
			'className' => 'User',
			'foreignKey' => 'pilot_id'
		),
		'Responsible' => array(
			'className' => 'User',
			'foreignKey' => 'responsible_id'
		),
		'Plane'
	);
	
	public $hasMany = array(
		'Stop'
	);
}
?>
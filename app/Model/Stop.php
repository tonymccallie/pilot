<?php
App::uses('AppModel', 'Model');
class Stop extends AppModel {
	var $order = array('Stop.sort ASC');

	public $belongsTo = array(
		'Flightplan'
	);
}
?>
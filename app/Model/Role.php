<?php
App::uses('AppModel', 'Model');
class Role extends AppModel {
	var $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'role_id'
		)
	);	
}
?>
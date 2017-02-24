<?php
App::uses('AppModel', 'Model');
class Plane extends AppModel {
	public $displayField = 'tag';
	
	public $hasAndBelongsToMany = array(
		'Owner' => array(
			'className' => 'User',
			'joinTable' => 'plane_owners',
			'foreignKey' => 'plane_id',
			'associationForeignKey' => 'user_id',
			'unique' => true, //true = delete current relations on save
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Manager' => array(
			'className' => 'User',
			'joinTable' => 'plane_managers',
			'foreignKey' => 'plane_id',
			'associationForeignKey' => 'user_id',
			'unique' => true, //true = delete current relations on save
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		
	);
	
	public $hasMany = array(
		'Flightplan'
	);
}
?>
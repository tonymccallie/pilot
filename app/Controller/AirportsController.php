<?php
App::uses('AppController', 'Controller');
class AirportsController extends AppController {
	public function search() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$tmpairports = $this->Airport->find('concatlist',array(
			'fields'=>array(
				'Airport.id','Airport.ident','Airport.name'
			),
			'separator' => ' - ',
			'limit' => 10,
			'conditions' => array(
				'OR' => array(
					'Airport.name LIKE' => '%'.$this->request->query['query'].'%',
					'Airport.ident LIKE' => '%'.$this->request->query['query'].'%',
					'Airport.city LIKE' => '%'.$this->request->query['query'].'%'
				)
			)
		));
		$airports = array("options"=>array());
		foreach($tmpairports as $airport) {
			array_push($airports['options'], $airport);
		}
		echo json_encode($airports);
	}
}
?>
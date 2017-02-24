<?php
App::uses('AppController', 'Controller');
class PlanesController extends AppController {
	public function admin_index() {
		$planes = $this->paginate();
		$this->set(compact('planes'));
	}
	
	public function admin_add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Plane->save($this->request->data)) {
				$this->Session->setFlash('The plane has been saved','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The plane could not be saved. Please, try again.','error');
			}
		}
		$users = $this->Plane->User->find('list',array('fields'=>array(
			'id','name'
		),'conditions'=>array(
			'User.last_name NOT' => ''
		)));
		$this->set(compact('users'));
	}
	
	public function admin_edit($id = null) {
		//$options = array('conditions' => array('Plane.id' => $id),'contain'=>array('Manager','Owner'));
		//$plane = $this->Plane->find('first', $options);

		if (!$this->Plane->exists($id)) {
			throw new NotFoundException(__('Invalid plane'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Plane->saveAll($this->request->data)) {
				$this->Session->setFlash('The plane has been saved','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The plane could not be saved. Please, try again.','error');
			}
		} else {
			$options = array('conditions' => array('Plane.id' => $id));
			$this->request->data = $this->Plane->find('first', $options);
		}
		$users = $this->Plane->Owner->find('concatlist',array(
			'fields'=>array(
				'id','first_name','last_name'
			),
			'conditions'=>array(
				'Owner.last_name NOT' => ''
			),
			'order' => array(
				'Owner.last_name' => 'asc'
			)
		));
		$this->set(compact('users'));
	}
}
?>
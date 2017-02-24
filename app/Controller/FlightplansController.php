<?php
App::uses('AppController', 'Controller');
class FlightplansController extends AppController {
	
	public function index() {
		$flightplans = $this->Flightplan->find('all');
		//debug($flightplans);
		$this->set(compact('flightplans'));
	}
	
	public function ajax_report($id) {
		$this->layout = 'ajax';
		if (!$this->Flightplan->exists($id)) {
			throw new NotFoundException(__('Invalid Flightplan'));
		}
		$flightplan = $this->Flightplan->findById($id);
		$this->set(compact('flightplan'));
	}
	
	public function ajax_pdf($id) {
		$this->layout = "ajax";
		$url = Common::currentUrl().'ajax/flightplans/report/'.$id;
		App::import('Vendor','HTML2PDF',array('file'=>'html2pdf/html2pdf.class.php'));
		$html2pdf = new HTML2PDF('P','LETTER','en');
		$content = file_get_contents($url);
		$html2pdf->pdf->SetDisplayMode('real');
		$html2pdf->writeHTML($content);
		$flightplan = $this->Flightplan->findById($id);
		$filename = 'flightplan_'.$id.'.pdf';
		//$file = $html2pdf->Output(APP . 'webroot/reports/'.$filename,'F'); //'F' to write file
		$file =$html2pdf->Output($filename); //stream file
	}
	
	function __subFlightplan($id = null) {
		$flightplan = $this->Flightplan->find('first',array(
			'conditions' => array(
				'Flightplan.id' => $id
			),
			'contain' => array(
				'Plane' => array(
					'Owner','Manager'
				),'Pilot'
			)
		));
		
		$owners = Set::extract('/Plane/Owner/email',$flightplan);
		$managers = Set::extract('/Plane/Manager/email',$flightplan);
		$pilot = array($flightplan['Pilot']['email']);
		$emails = array_unique(am($owners, $managers, $pilot));
		
		$url = Common::currentUrl().'ajax/flightplans/report/'.$id;
		App::import('Vendor','HTML2PDF',array('file'=>'html2pdf/html2pdf.class.php'));
		$html2pdf = new HTML2PDF('P','LETTER','en');
		$content = file_get_contents($url);
		$html2pdf->pdf->SetDisplayMode('real');
		$html2pdf->writeHTML($content);
		$filename = 'flightplan_'.$id.'.pdf';
		$file = APP . 'webroot/reports/'.$filename;
		$html2pdf->Output($file,'F'); //'F' to write file
		
		Common::email(array(
			'to' => $emails,
			'subject' => 'New Flightplan Submitted by '.$flightplan['Pilot']['first_name'].' '.$flightplan['Pilot']['last_name'],
			'template' => 'flightplan',
			'attachments' => array(
				$file
			),
			'variables' => array(
				'flightplan' => $flightplan,
				'file' => $file,
				'url'=>$url
			)
		),'');

	}

	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Flightplan->save($this->request->data)) {
				$this->redirect(array('action' => 'edit',$this->Flightplan->getLastInsertID()));
			} else {
				$this->Session->setFlash('The Flightplan could not be saved. Please, try again.','error');
			}
		}
		$pilot = $this->Flightplan->Pilot->Role->lookup(array(
			'name'=>'Pilot'
		));
		$pilots = $this->Flightplan->Pilot->find('concatlist',array(
			'fields' => array(
				'Pilot.id', 'Pilot.first_name', 'Pilot.last_name'	
			),
			'conditions' => array(
				'Pilot.role_id' => $pilot
			),
			'order' => array('Pilot.email'=>'asc')
		));
		$planes = $this->Flightplan->Plane->find('list',array(

		));
		$this->set(compact('pilots','planes'));
	}
	
	public function edit($id = null) {
/*
		$options = array('conditions' => array('Plane.id' => $id),'contain'=>array('Manager','Owner'));
		$this->request->data = $this->Plane->find('first', $options);
		die(debug($this->request->data));
*/

		$bolSubmit = false;
		
		if (!$this->Flightplan->exists($id)) {
			throw new NotFoundException(__('Invalid Flightplan'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!empty($this->request->data['Flightplan']['start']['date'])) {
				$this->request->data['Flightplan']['start'] = date('Y-m-d H:i:s',strtotime($this->request->data['Flightplan']['start']['date'].' '.$this->request->data['Flightplan']['start']['time']));
			}
			if(!empty($this->request->data['Flightplan']['stop']['date'])) {
				$this->request->data['Flightplan']['stop'] = date('Y-m-d H:i:s',strtotime($this->request->data['Flightplan']['stop']['date'].' '.$this->request->data['Flightplan']['stop']['time']));
			}
			if($this->request->data['Flightplan']['submit']) {
				$this->request->data['Flightplan']['submit'] = date('Y-m-d H:i:s');
				$bolSubmit = true;
			}
			if ($this->Flightplan->save($this->request->data)) {
				if($bolSubmit) {
					$this->__subFlightplan($id);
				}
				$this->Session->setFlash('The Flightplan has been saved','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The Flightplan could not be saved. Please, try again.','error');
			}
		} else {
			$options = array('conditions' => array('Flightplan.id' => $id));
			$this->request->data = $this->Flightplan->find('first', $options);
			if(!empty($this->request->data['Flightplan']['start'])) {
				$this->request->data['Flightplan']['start'] = array(
					'date' => date('m/d/Y',strtotime($this->request->data['Flightplan']['start'])),
					'time' => date('h:i A',strtotime($this->request->data['Flightplan']['start']))
				);
			}
			if(!empty($this->request->data['Flightplan']['stop'])) {
				$this->request->data['Flightplan']['stop'] = array(
					'date' => date('m/d/Y',strtotime($this->request->data['Flightplan']['stop'])),
					'time' => date('h:i A',strtotime($this->request->data['Flightplan']['stop']))
				);
			}
			
			if(!empty($this->request->data['Flightplan']['submit'])) {
				$this->request->data['Flightplan']['submit'] = 1;
			}
			
			if(empty($this->request->data['Flightplan']['stops'])) {
				$this->request->data['Flightplan']['stops'] = "[]";
			}
		}
		$plane = $this->Flightplan->Plane->find('first',array(
			'conditions'=>array(
				'Plane.id' => $this->request->data['Flightplan']['plane_id']
			),'contain'=>array(
				'Owner'
			)
		));
		$owners = array();
		foreach($plane['Owner'] as $owner) {
			$owners[$owner['id']] = $owner['first_name'].' '.$owner['last_name'];
		}
		$this->set(compact('owners'));
	}

	public function admin_index() {
		$flightplans = $this->paginate();
		$this->set(compact('flightplans'));
	}
	
	public function admin_add() {
		
	}
	
	public function admin_edit($id = null) {
/*
		$options = array('conditions' => array('Plane.id' => $id),'contain'=>array('Manager','Owner'));
		$this->request->data = $this->Plane->find('first', $options);
		die(debug($this->request->data));
*/
		if (!$this->Flightplan->exists($id)) {
			throw new NotFoundException(__('Invalid Flightplan'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!empty($this->request->data['Flightplan']['start']['date'])) {
				$this->request->data['Flightplan']['start'] = date('Y-m-d H:i:s',strtotime($this->request->data['Flightplan']['start']['date'].' '.$this->request->data['Flightplan']['start']['time']));
			}
			if(!empty($this->request->data['Flightplan']['stop']['date'])) {
				$this->request->data['Flightplan']['stop'] = date('Y-m-d H:i:s',strtotime($this->request->data['Flightplan']['stop']['date'].' '.$this->request->data['Flightplan']['stop']['time']));
			}

			if ($this->Flightplan->save($this->request->data)) {
				$this->Session->setFlash('The Flightplan has been saved','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The Flightplan could not be saved. Please, try again.','error');
			}
		} else {
			$options = array('conditions' => array('Flightplan.id' => $id));
			$this->request->data = $this->Flightplan->find('first', $options);
			if(!empty($this->request->data['Flightplan']['start'])) {
				$this->request->data['Flightplan']['start'] = array(
					'date' => date('m/d/Y',strtotime($this->request->data['Flightplan']['start'])),
					'time' => date('h:i A',strtotime($this->request->data['Flightplan']['start']))
				);
			}
			if(!empty($this->request->data['Flightplan']['stop'])) {
				$this->request->data['Flightplan']['stop'] = array(
					'date' => date('m/d/Y',strtotime($this->request->data['Flightplan']['stop'])),
					'time' => date('h:i A',strtotime($this->request->data['Flightplan']['stop']))
				);
			}
			
			if(empty($this->request->data['Flightplan']['stops'])) {
				$this->request->data['Flightplan']['stops'] = "[]";
			}
		}
		$plane = $this->Flightplan->Plane->find('first',array(
			'conditions'=>array(
				'Plane.id' => $this->request->data['Flightplan']['plane_id']
			),'contain'=>array(
				'Owner'
			)
		));
		$owners = array();
		foreach($plane['Owner'] as $owner) {
			$owners[$owner['id']] = $owner['first_name'].' '.$owner['last_name'];
		}
		$pilot = $this->Flightplan->Pilot->Role->lookup(array(
			'name'=>'Pilot'
		));
		$pilots = $this->Flightplan->Pilot->find('concatlist',array(
			'fields' => array(
				'Pilot.id', 'Pilot.first_name', 'Pilot.last_name'	
			),
			'conditions' => array(
				'Pilot.role_id' => $pilot
			),
			'order' => array('Pilot.email'=>'asc')
		));
		$this->set(compact('owners','pilots'));
	}
}
?>
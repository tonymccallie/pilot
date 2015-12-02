<?php
App::uses('AppController', 'Controller');
class DecisionsController extends AppController {
	function ajax_update() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$this->view = "ajax";
		$message = array(
			'status' => 'ERROR',
			'data' => array()
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		
		if(!empty($params['id'])) {
			$data = $this->Decision->findById($params['id']);
			
		} else {
			$data = array(
				'Decision' => array(
					'user_id' => $params['user_id']
				)
			);
			
			$this->Decision->create();
		}
		
		if(!empty($params['reminder'])) {
			$data['Decision']['alert_date'] = date('Y-m-d 12:00:00',strtotime($params['reminder']));
		}
		
		if($this->Decision->save($data)) {
			if(!empty($params['id'])) {
				$id = $params['id'];
			} else {
				$id = $this->Decision->getInsertId();
			}
			$message = array(
				'status' => 'SUCCESS',
				'id' => $id
			);
		}
		
		echo json_encode($message);
	}
	
	function ajax_delete() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$this->view = "ajax";
		$message = array(
			'status' => 'ERROR',
			'data' => array()
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		
		if(!empty($params['id'])) {
			$message = array(
				'status' => 'SUCCESS'
			);
			if($this->Decision->delete($params['id'])) {
				
			}
		}
				
		echo json_encode($message);
	}
	
	function ajax_cron() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$decisions = $this->Decision->find('all',array(
			'conditions' => array(
				'Decision.alert_date' => date('Y-m-d')
			)
		));
		if($decisions) {
			$users = Set::extract('/User/push_id',$decisions);
			$delete = Set::extract('/Decision/id',$decisions);
			$data = '{"user_ids": ["'.implode('","', $users).'"],"production": false,"notification":{"title": "Marriage Strong Reminder", "alert":"You have a decision you wanted to be reminded about today."}}';
			//debug($data);
			App::uses('HttpSocket', 'Network/Http');
			$HttpSocket = new HttpSocket();
			//curl -u 70df943bed932fae7ff8f09a57b632769575bc24a217fb9e: -H "Content-Type: application/json" -H "X-Ionic-Application-Id: b5459458" https://push.ionic.io/api/v1/push -d '{"user_ids": ["949afe04-02e7-4d6f-84eb-f4a9d57a876e"],"production": false,"notification":{"title": "Test Multiple", "alert":"Heyo"}}'
			$results = $HttpSocket->post('https://push.ionic.io/api/v1/push',$data,array(
				'auth' => array(
					'method' => 'Basic',
					'user' => '70df943bed932fae7ff8f09a57b632769575bc24a217fb9e',
					'pass' => ''
				),
				'header' => array(
					'Content-Type' => 'application/json',
					'X-Ionic-Application-Id' => 'b5459458'
				)
			));
			$this->Decision->deleteAll(array('Decision.id' => $delete));
			var_dump($results->raw);
			echo "\n".count($decisions)." reminders sent.\n";
		} else {
			echo "\nNo reminders today.\n";
		}
	}
}
?>
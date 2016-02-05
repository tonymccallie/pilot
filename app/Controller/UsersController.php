<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {
	
	function ajax_login() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		if($params) {
			$user = $this->User->findByEmail($params['email']);
			if(!$user) {
				$message = array(
					'status' => 'MESSAGE',
					'data' => 'No user found with that email address.'
				);
			} else {
				if($user['User']['passwd'] == Authsome::hash($params['password'])) {
					$message = array(
						'status' => 'SUCCESS',
						'data' => $user
					);
				} else {
					$message = array(
						'status' => 'MESSAGE',
						'data' => 'Incorrect password'
					);
				}
			}
		}
		
		echo json_encode($message);
	}
	
	function ajax_facebook() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		if($params) {
			$user = $this->User->findByFacebook($params['id']);
			if(!$user) {
				$user = $this->User->findByEmail($params['email']);
				if(!$user) {
					$user = array(
						'User' => array(
							'facebook' => $params['id'],
							'email' => $params['email'],
							'first_name' => $params['first_name'],
							'last_name' => $params['last_name'],
							'role_id' => 2,
							'verified' => 'NOW()',
							'link_code' => Common::generateRandom(9,true)
						)
					);	
				} else {
					$user['User']['facebook'] = $params['id'];
					$user['User']['email'] = $params['email'];
				}
				$this->User->create();
				if($this->User->save($user)) {
					$user = $this->User->findByFacebook($params['id']);
					$message = array(
						'status' => 'SUCCESS',
						'data' => $user
					);
				} else {
					
				}
			} else {
				$message = array(
					'status' => 'SUCCESS',
					'data' => $user
				);
			}
			$message['data'] = $user;
		}
		
		echo json_encode($message);
	}
	
	function ajax_register() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		
		if($params) {
			$bolCreate = true;
			$user = $this->User->findByEmail($params['email']);
			if(!$user) {
				$user = array(
					'User' => array(
						'passwd' => Authsome::hash($params['password']),
						'email' => $params['email'],
						'first_name' => $params['first_name'],
						'last_name' => $params['last_name'],
						'role_id' => 2,
						'verified' => 'NOW()',
						'link_code' => Common::generateRandom(9,true)
					)
				);	
			} else {
				if((!empty($user['User']['passwd']))&&($user['User']['passwd'] != Authsome::hash($params['password']))) {
					$message = array(
						'status' => 'MESSAGE',
						'data' => 'A user with this email address already exists. Please log in to continue.'
					);
					$bolCreate = false;
				} else {
					$user['User']['passwd'] = Authsome::hash($params['password']);
				}
				if(empty($user['User']['link_code'])) {
					$user['User']['link_code'] = Common::generateRandom(9,true);
				}
			}
			if($bolCreate) {
				$this->User->create();
				if($this->User->save($user, false)) {
					$user = $this->User->findByEmail($params['email']);
					$message = array(
						'status' => 'SUCCESS',
						'data' => $user
					);
				} else {
					$message['data'] = 'There was an error saving the User';
				}
			}
		}
		
		echo json_encode($message);
	}
	
	function ajax_link() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);

		if($params) {
			$user = $this->User->findById($params['user_id']);
			$spouse = $this->User->findByLinkCode($params['code']);
			if(!$user) {
				$message = array(
					'status' => 'MESSAGE',
					'data' => 'No user found with that id.'
				);
			} else {
				if(!$spouse) {
					$message = array(
						'status' => 'MESSAGE',
						'data' => 'No spouse found with that code.'
					);
				} else {
					$data = array(
						array('User'=>$user['User']),
						array('User'=>$spouse['User'])
					);
					$data[0]['User']['spouse_id'] = $spouse['User']['id'];
					$data[1]['User']['spouse_id'] = $user['User']['id'];
					if($this->User->saveAll($data, array('validate'=>false))) {
						$newuser = $this->User->findById($user['User']['id']);
						$message = array(
							'status' => 'SUCCESS',
							'data' => $newuser
						);
					}
				}
			}
		} else {
			$message['data'] = 'No params';
		}

		
		echo json_encode($message);
	}
	
	function ajax_recover() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		echo json_encode($message);
	}
	
	function ajax_update() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		$params['User']['json'] = json_encode($params['data']);
		unset($params['User']['spouse_id']);
		unset($params['Spouse']);
		
		if($this->User->save($params,array('validate'=>false))) {
			$user = $this->User->findById($params['User']['id']);
			$message = array(
				'status' => 'SUCCESS',
				'data' => am($user,$params)
			);
		}
		
		//$message['data'] = $params;
		
		echo json_encode($message);
	}
	
	function ajax_upload() {
		Configure::write('debug', 2);
		$this->layout = "ajax";
		$this->view = "ajax";
		
		$message = array(
			'status' => 'ERROR',
			'data' => 'Init'
		);
		
		$params = json_decode(file_get_contents('php://input'),true);
		
		$this->log(array($this->request->params, $_POST));
		$tempFile = $this->request->params['form']['post']['tmp_name'];
		move_uploaded_file($tempFile,APP . 'webroot/uploads/image_'.$_POST['user_id'].'.jpg');
		
		$message = '/uploads/image_'.$_POST['user_id'].'.jpg';
		
		echo $message;
	}
	
	function ajax_cron() {
		Configure::write('debug', 0);
		$this->layout = "ajax";
		$this->view = "ajax";
		$decisions = "";
	}
	
	
	function oauthlogin() {
		$this->layout = "ajax";
		$this->view = "ajax";
	}
	
	function oauthlogout() {
		$this->layout = "ajax";
		$this->view = "ajax";
	}
	
	function login() {
		Authsome::logout();
		if(empty($this->request->data)) {
			return;
		}
		$user = Authsome::login($this->request->data['User']);

		if (!$user) {
			$this->Session->setFlash('Unable to login with that information. Did you verify the account?','alert');
			$this->redirect(array('action'=>'login'));
			return;
		}
		
		Authsome::persist('1 month');
		
		if(!empty($user['User']['refer_url'])) {
			$this->request->data['User']['url'] = $user['User']['refer_url'];
			$user['User']['refer_url'] = "";
			unset($user['User']['passwd']);
			$this->User->save($user);
			$this->Session->write('User',$this->User->update($this->Session->read('User')));
		}
		$this->Session->delete('dashboard_url');
		if((empty($this->request->data['User']['url']))||($this->request->data['User']['url']=='/users/logout')) {
			$this->request->data['User']['url'] = "/dashboard/";
		}
		

		return $this->redirect($this->request->data['User']['url']);

	}
	
	function logout() {
		Authsome::logout();
		return $this->redirect('/');
	}
	
	function recover($key = null) {
		if(!empty($key)) {
			if(!empty($this->request->data)) {
				if($this->User->save($this->request->data)) {
					$this->Session->setFlash('Password successfully changed.', 'success');
					$this->redirect(array('action'=>'login'));
				} else {
					$this->Session->setFlash('There was an error changing the password.', 'error');
				}
			}
			$keyArray = explode('-',$key);
			$this->request->data = $this->User->findById($keyArray[0]);
			$this->request->data['User']['passwd'] = '';
			$this->view = 'password';
		} else {
			if(!empty($this->request->data)) {
				
				$user = $this->User->findByEmail($this->request->data['User']['email']);
				if(!$user) {
					$this->Session->setFlash('We were unable to find an account with that email address.', 'alert');
					return true;
				}
				$url = Common::currentUrl().'users/recover/'.$user['User']['id'].'-'.substr($user['User']['passwd'],0,6);
				
				Common::email(array(
					'to' => $this->request->data['User']['email'],
					'subject' => 'Password Reset Instructions',
					'template' => 'recover',
					'variables' => array(
						'url' => $url
					)
				),'');

				$this->Session->setFlash('An email has been sent to '.$this->request->data['User']['email'].' with a link to reset your password.', 'success');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			}
		}
	}
	
	function password() {
		if(!empty($this->request->data)) {
			if($this->User->save($this->request->data)) {
				$this->Session->setFlash('Password successfully changed.', 'success');
				$this->redirect(array('action'=>'dashboard'));
			} else {
				$this->Session->setFlash('There was an error changing the password.', 'error');
			}
		} else {
			$this->request->data = $this->User->findById(Authsome::get('id'));
			$this->request->data['User']['passwd'] = '';
		}
	}
	
	function register($regkey = '') {
		if(!empty($regkey)) {
			$arRegkey = explode('-',$regkey);
			
			$user = $this->User->find('first',array(
				'conditions' => array(
					'User.id' => $arRegkey[0],
					'SUBSTR(User.passwd,1,6)' => $arRegkey[1],
					'User.verified' => null
				)
			));

			if(!$user) {
				$this->Session->setFlash('That user could not be located or has already been verified.','alert');
			} else {
				$this->User->updateAll(
					array(
						'verified' => "'".date('Y-m-d H:i')."'"
					),
					array(
						'User.id' => $user['User']['id']
					)
				);
				$this->Session->setFlash('Thank you for confirming your email. You may now login!', 'success');
				$this->redirect(array('controller'=>'users','action'=>'login'));
			}
		} else {
			if (!empty($this->request->data)) {
				$this->User->create();

				$this->User->validate['passwd'] = array(
					'ruleTitle' => array(
						'rule' => array('notEmpty'),
						'message' => 'A Password is required.'
					)
				);
				
				//Get User Role
				$this->request->data['User']['role_id'] = $this->User->Role->lookup(array(
					'name'=>'User',
					'permissions' => '*:*,!*:admin_*',
				));
								
				if ($this->User->save($this->request->data)) {
					$this->request->data['User']['passwd'] = Authsome::hash($this->request->data['User']['passwd']);
					$url = Common::currentUrl().'users/register/'.$this->User->getLastInsertId().'-'.substr($this->request->data['User']['passwd'],0,6);
					Common::email(array(
						'to' => $this->request->data['User']['email'],
						'subject' => 'New User Registration',
						'template' => 'register',
						'variables' => array(
							'url' => $url
						)
					),'');
					$this->Session->setFlash('Thank you for registering. An email has been sent to '.$this->request->data['User']['email'].'. Please click on the link in the email to verify your account.','success');
					$this->redirect(array('action'=>'login'));
				} else {
					$this->Session->setFlash('There was a problem creating the account, see below.','error');
				}
			}
		}
	}
	
	
	function dashboard() {
		
	}
	
	
	public function admin_index() {
		//$this->User->recursive = 0;
/*
		$this->paginate = array(
			'contain' => array()
		);
*/
		
		$this->set('users', $this->paginate());
	}

	public function admin_edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash('The user has been saved','success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The user could not be saved. Please, try again.','error');
			}
		} else {
			$options = array('conditions' => array('User.id' => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$this->set('roles',$this->User->Role->find('list'));
	}
	
	public function admin_push() {
		//curl -u 70df943bed932fae7ff8f09a57b632769575bc24a217fb9e: -H "Content-Type: application/json" -H "X-Ionic-Application-Id: b5459458" https://push.ionic.io/api/v1/push -d '{"user_ids": ["949afe04-02e7-4d6f-84eb-f4a9d57a876e"],"production": false,"notification":{"title": "Test Multiple", "alert":"Heyo"}}'
	}


	public function admin_delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash('User deleted','success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash('User was not deleted','error');
		$this->redirect(array('action' => 'index'));
	}
}
?>
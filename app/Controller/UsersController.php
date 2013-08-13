<?php
App::uses('AppController', 'Controller');
class UsersController extends AppController {
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
		$this->User->recursive = 0;
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
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$this->set('roles',$this->User->Role->find('list'));
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
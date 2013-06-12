<?php
App::uses('Controller', 'Controller');
App::uses('User','Model');
class AppController extends Controller {
	public $helpers = array('Time','Html','Form','Session');

	public $components = array(
		'Authsome' => array('model' => 'User'),
		'Session','Email','Cookie',
		'DebugKit.Toolbar'
	);
	
	public $user = array();

	
	public function beforeFilter() {
	/*
		IE6 REMOVAL
		if(((eregi("MSIE 6",$_SERVER['HTTP_USER_AGENT']))&&(!eregi("MSIE 8",$_SERVER['HTTP_USER_AGENT'])))||(eregi("MSIE 5",$_SERVER['HTTP_USER_AGENT']))){
			Configure::write('debug', 0);
			$this->layout = "ie6";
			return true;
		}
	*/
		$email = Authsome::get('User.email');
		if ((!User::allowed($this->name, $this->action))&&(empty($this->request->params['ajax']))) {
			$this->Session->write('requested_url','/'.$this->request->url);
			if($email == 'guest@greyback.net') {
				$this->Session->setFlash('Oops! The page you were trying to reach is restricted. Please log in to continue.','alert');
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			} else {
				$this->Session->setFlash('Oops! The page you were trying to reach is restricted. You need to be an administrator to access that.','alert');
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard','admin'=>false));
			}
		}
		
		//Force page after login
		if((empty($this->request->url))&&($email != 'guest@greyback.net')) {
			$this->redirect('/dashboard');
		}
		$this->user = $this->Session->read('User');
	}
}

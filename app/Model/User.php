<?php
App::uses('AppModel', 'Model');
class User extends AppModel {
	var $order = array('User.email');
	
	var $belongsTo = array(
		'Role' => array(
			'className' => 'Role',
			'foreignKey' => 'role_id'
		),
	);

	var $hasMany = array(
		'LoginToken' => array(
			'dependent' => true,
		),
	);
	
	public $hasAndBelongsToMany = array(
		'Owner' => array(
			'className' => 'Plane',
			'joinTable' => 'plane_owners',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'plane_id',
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
			'className' => 'Plane',
			'joinTable' => 'plane_managers',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'plane_id',
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


	var $validate = array(
		'email' => array(
			'ruleTitle' => array(
				'rule' => array('email'),
				'message' => 'Please use a valid email address.'
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'That email address is already in use.'
			)
		),
	);


/**
 * beforeSave function.
 * 
 * @access public
 * @return void
 */
	public function beforeValidate($options = array()) {
		if(!empty($this->data[$this->alias]['passwd'])) {
			$this->validate['passwd'] = array(
				'rule' => array('passwordVerify'),
				'message' => 'The passwords do not match.'
			);
		}
	}


/**
 * passwordVerify function.
 * 
 * @access public
 * @return void
 */
	function passwordVerify($check) {
		if($this->data[$this->alias]['passwd'] != $this->data[$this->alias]['passwd_verify']) {
			return false;
		} else {
			$this->data[$this->alias]['passwd'] = Authsome::hash($this->data[$this->alias]['passwd']);
			return true;
		}
	}

/**
 * Authsome Login
 *
 * @param string $type Type
 * @param array $credentials Credentials
 * @return mixed Boolean False on failure, array of model results on success
 * @access public
 */
	public function authsomeLogin($type, $credentials = array()) {
		switch ($type) {
			case 'guest':
				$guestRole = $this->Role->lookup(array(
					'name' => 'Guest',
					'permissions' => '!*:*,CakeError:*,Pages:*,Users:login,Users:register,Users:recover,Users:ajax_*',
				));

				$guestUser = $this->lookup(array(
					'email' => 'guest@greyback.net',
					'role_id' => $guestRole
				));

				$conditions = array('User.id' => $guestUser);
				break;
			case 'credentials':
				$password = Authsome::hash($credentials['passwd']);
				$conditions = array(
					'User.email' => $credentials['email'],
					'User.passwd' => $password,
					'User.verified NOT' => null
				);
				break;
			case 'cookie':
				$arToken = explode(':', $credentials['token']);
				if(count($arToken)<=1) {
					return false;
				}
				list($token, $userId) = $arToken;
				$duration = $credentials['duration'];

				$loginToken = $this->LoginToken->find('first', array(
					'conditions' => array(
						'user_id' => $userId,
						'token' => $token,
						'duration' => $duration,
						'used' => false,
						'expires <=' => date('Y-m-d H:i:s', strtotime($duration)),
					),
					'contain' => false
				));

				if (!$loginToken) {
					return false;
				}

				$loginToken['LoginToken']['used'] = true;
				$this->LoginToken->save($loginToken);

				$conditions = array(
					'User.id' => $loginToken['LoginToken']['user_id']
				);
				break;
			default:
				return false;
		}

		$contain = $this->containConfig;
		return $this->find('first', compact('conditions', 'contain'));
	}

/**
 * Authsome Persist
 *
 * Creates and stores a unique token for the given $user which can be used to
 * look him up again using authsomeRemember in the future.
 *
 * @param array $user User information
 * @return string Authsome Persist string
 * @access public
 */
	public function authsomePersist($user, $duration) {
		$token = md5(uniqid(mt_rand(), true));
		$userId = $user['User']['id'];

		$this->LoginToken->create(array(
			'user_id' => $userId,
			'token' => $token,
			'duration' => $duration,
			'expires' => date('Y-m-d H:i:s', strtotime($duration)),
		));
		$this->LoginToken->save();

		return "${token}:${userId}";
	}

/**
 * Can
 *
 * Checks permissions and access rights to perform a function
 *
 * @param string $type 
 * @param string $model 
 * @param string $record 
 * @param string $options 
 * @return boolean Allowed
 * @access public
 * @static
 */
	public static function can($type, $model, $record, $options = array()) {
		Assert::true(method_exists($model, 'permissions'), array(
			'message' => $model.'::permissions() is not implemented!',
		));

		$permissions = call_user_func(
			array($model, 'permissions'),
			$record,
			$options
		);

		Assert::true(array_key_exists($type, $permissions), array(
			'message' => sprintf(
				'Model %s has no %s_permissions field!',
				$model,
				$type
			),
		));

		$userId = Authsome::get('id');
		$roleId = Authsome::get('role_id');

		return Common::requestAllowed($roleId, $userId, $permissions[$type]);
	}

/**
 * Allowed
 *
 * @param string $controller 
 * @param string $action 
 * @param string $obj 
 * @return void
 * @access public
 */
	static function allowed($controller, $action, $obj = null) {
		$rolePerms = Authsome::get('Role.permissions');
		return Common::requestAllowed($controller, $action, $rolePerms, true);
	}


/**
 * update function.
 * 
 * @access public
 * @param mixed $user
 * @return void
 */
	public function update($user) {
		$user = $this->find('first',array(
			'conditions' => array(
				'User.id' => $user['User']['id']
			),
			'contain' => $this->containConfig
		));
		return $user;
	}	

}
?>
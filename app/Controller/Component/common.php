<?php
/**
 * Common Class
 *
 * Place for commonly used functions
 *
 */
class Common {
/**
 * truncate function.
 * 
 * @param mixed $string
 * @param int $limit. (default: 100)
 * @param string $break. (default: " ")
 * @param string $pad. (default: "...")
 * @param string $tags. (default: "")
 * @return void
 * @access public
 * @static
 */
	static function truncate($string, $limit = 100, $break=" ", $pad="...", $tags = "") {
		// return with no change if string is shorter than $limit 
		$string = strip_tags($string,$tags);
		if(strlen($string) <= $limit) return $string; 

		// is $break present between $limit and the end of the string?  
		if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
			if($breakpoint < strlen($string) - 1) { 
				$string = substr($string, 0, $breakpoint) . $pad; 
			} 
		} 
		return $string; 
	}

/**
 * slug function.
 * 
 * @param mixed $string
 * @return void
 * @access public
 * @static
 */
	static function slug($string) {
		$string = strtolower(trim($string));
		$string = preg_replace('/[^a-z0-9-]/i', '-', $string); 
		$string = preg_replace('/-[-]*/i', '-', $string);

		$currentMaximumURLLength = 100;

		if (strlen($string) > $currentMaximumURLLength) {
			$string = substr($string, 0, $currentMaximumURLLength);
		} 
		return $string;
	}

/**
 * dateFix function.
 * 
 * @param array $date. (default: array())
 * @return void
 * @access public
 * @static
 */
	static function dateFix(&$date = array()) {
		$out = "";

		if(!empty($date['date'])) {
			if(!empty($date['time'])) {
				$out = date('Y-m-d H:i:00',strtotime($date['date']." ".$date['time']));
			} else {
				$out = date('Y-m-d',strtotime($date['date']));
			}
		} else {
			if(!empty($date['time'])) {
				$out = date('H:i:00',strtotime($date['date']));
			}
		}

		if((empty($date['date']))&&(empty($date['time']))) {
			$out = null;
		}
		$date = $out;
	}

/**
 * dateBuild function.
 * 
 * @static
 * @param string $date. (default: "")
 * @return void
 * @access public
 * @static
 */
	static function dateBuild(&$date = "") {
		if(!empty($date)) {
			$dateObj = strtotime($date);
			$date = array(
				'date' => date('m/d/Y',$dateObj),
				'time' => date('h:ia',$dateObj),
			);
		} else {
			$date = array(
				'date' => "",
				'time' => ""
			);
		}

		return $date;
	}

/**
 * permFix function.
 * 
 * @static
 * @param array &$perms. (default: array())
 * @return void
 * @access public
 * @static
 */
	static function permFix(&$perms = array()) {
		$strPerms = "";
		if(!empty($perms)) {
			foreach($perms as $perm => $role) {
				$strPerms.=$role.":*,";
			}
			$perms = substr($strPerms,0,strlen($strPerms)-1);
		} else {
			$perms = "";
		}
	}


/**
 * permBuild function.
 * 
 * @static
 * @param string &$perms. (default: "")
 * @return void
 * @access public
 * @static
 */
	static function permBuild(&$perms = "") {
		$arData = explode(",",$perms);
		$arPerms = array();
		foreach($arData as $perm) {
			$arPerm = explode(":",$perm);
			$arPerms[] = $arPerm[0];
		}
		$perms = $arPerms;
	}


/**
 * getPositions function.
 * 
 * @param mixed $template
 * @return array
 * @access public
 */
	function getPositions($template) {
		$placeholders = $positions = array();
		preg_match_all('(###[A-Za-z0-9_\-]*###)',$template,$placeholders,PREG_SET_ORDER);

		foreach($placeholders as $key=>$val) {
			$placeholders[$key] = substr($val[0],3,strlen($val[0])-6);
			$positions[$placeholders[$key]] = "";
		}
		return $positions;
	}


/**
 * Request Allowed
 *
 * @param string $object 
 * @param string $property 
 * @param string $rules 
 * @param string $default 
 * @return void
 * @access public
 */
	function requestAllowed($object, $property, $rules, $default = false) {
		$allowed = $default;

		preg_match_all('/\s?([^:,]+):([^,:]+)/is', $rules, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			list($rawMatch, $allowedObject, $allowedProperty) = $match;
			$rawMatch = trim($rawMatch);
			$allowedObject = trim($allowedObject);
			$allowedProperty = trim($allowedProperty);
			$allowedObject = str_replace('*', '.*', $allowedObject);
			$allowedProperty = str_replace('*', '.*', $allowedProperty);

			$negativeCondition = false;
			if (substr($allowedObject, 0, 1) == '!') {
				$allowedObject = substr($allowedObject, 1);
				$negativeCondition = true;
			}

			if (preg_match('/^'.$allowedObject.'$/i', $object) && preg_match('/^'.$allowedProperty.'$/i', $property)) {
				$allowed = !$negativeCondition;
			}
		}
		return $allowed;
	}

/**
 * currentUrl function.
 * 
 * @access public
 * @return void
 */
	function currentUrl() {
		$pageURL = 'http';
		$pageURL .= "://";
		if(!empty($_SERVER['SERVER_PORT'])) {
			if($_SERVER["SERVER_PORT"] != "80") {
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$this->webroot;
			} else {
				$pageURL .= $_SERVER["SERVER_NAME"].$this->webroot;
			}
		} else {
			$pageURL .= 'www.grouppost.com'.$this->webroot;
		}
		return $pageURL;
	}


/**
 * generateRandom function.
 * 
 * @access public
 * @param int $length. (default: 10)
 * @return void
 */
	static function generateRandom($length = 10, $numbers = false) {
		$random = "";
		
		// define possible characters
		$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
		if($numbers) {
			$possible = "0123456789"; 
		}
		
		// set up a counter
		$i = 0; 
		
		// add random characters to $password until $length is reached
		while ($i < $length) { 
		
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		
		// we don't want this character if it's already in the password
		if (!strstr($random, $char)) { 
		$random .= $char;
		$i++;
		}
		
		}
		
		// done!
		return $random;
	}


/**
 * email function.
 * 
 * @access public
 * @param array $config. (default: array())
 * @return void
 */
	function email($config = array(), $message = "") {
		$settings = array(
			'to' => 'UNSET TO <info@greyback.net>',
			'cc' => array(),
			'bcc' => array(),
			'from' => array('info@greyback.net'=>'GreybackDeveloper'),
			'subject' => 'SUBJECT',
			'title' => 'TITLE',
			'template' => 'simple',
			'variables' => array()
		);

		$config = am($settings,$config);
		
		$config['variables']['currentUrl'] = Common::currentUrl();
		
		App::uses('CakeEmail', 'Network/Email');
		$email = new CakeEmail();
		$email->config('gmail');
		$email->from($config['from'])
			->to($config['to'])
			->subject($config['subject'])
			->template($config['template'])
			->emailFormat('html')
			->viewVars($config['variables']);
		if(!empty($config['replyTo'])) {
			$email->replyTo($config['replyTo']);
		}
		$email->send($message);
	}
	
	function link($url = "", $code = false) {
		App::uses('Link','Model');
		$Link = new Link();
		$link = $Link->lookup(array('link'=>$url),'short');
		if($link) {
			$short = $link;
		} else {
			$short = Common::generateRandom(6);
			$data = array(
				'Link' => array(
					'link' => $url,
					'short' => $short
				)
			);
			$Link->save($data);
		}
		if($code) {
			return $short;
		} else {
			return 'http://mclife.it/'.$short;
		}
	}

	function states($label = "") {
		return array(
			'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas',
			'CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware',
			'DC'=>'District Of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii',
			'ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas',
			'KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts',
			'MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana',
			'NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey',
			'NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota',
			'OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island',
			'SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas',
			'UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia',
			'WI'=>'Wisconsin','WY'=>'Wyoming'
		);
	}
}
?>

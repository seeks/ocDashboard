<?php

/*
 * super class for all widgets
 * generel methods
 * 
 * @author Florian Steffens
 * 
 */
class widget {
	
	protected $id = "";
	protected $name = "";
	protected $l;
	protected $user;
	protected $conf;
	protected $status;
	protected $errorMsg;
	protected $interval;
	protected $icon;
	protected $link;
	protected $cond;
	protected $scripts;
	protected $styles;
	
	function __construct($widgetConf) {
		$this->id = $widgetConf['id'];
		$this->name = $widgetConf['name'];
		$this->l = OC_L10N::get('ocDashboard');
		$this->user = OCP\User::getUser();
		$this->conf = json_decode($widgetConf['conf'], true);
		$this->status = 0;
		$this->errorMsg = "";
		$this->htmlHash = "";
		$this->html = "";
		$this->interval = $widgetConf['refresh']*1000; // in seconds
		$this->icon = $widgetConf['icon'];
		$this->link = $widgetConf['link'];
		$this->cond = $widgetConf['cond'];
		$this->scripts = $widgetConf['scripts'];
		$this->styles = $widgetConf['styles'];
	}
	
	
// --- PUBLIC ----------------------------------------
	
	/*
	 * @return returns all data for the actual widget
	 */
	public function getData() {
		if($this->checkConditions()) {
			$return = $this->getWidgetData();
			$this->loadScripts();
			$this->loadStyles();
		} else {
			$return = Array("error"=>"Missing required app.");
			$this->status = 4;
		}
				
		$return['id'] = $this->id;
		$return['status'] = $this->getStatus($return);
		$return['interval'] = $this->interval;
		$return['icon'] = $this->icon;
		$return['link'] = $this->link;
		$return['name'] = $this->name;
		return $return;
	}
		
// --- PROTECTED --------------------------------------

// --- PRIVATE ----------------------------------------
	
	/*
	 * loads all script that are defined in the config Array
	 */
	private function loadScripts() {
		if(isset($this->scripts) && $this->scripts != "") {
			foreach (explode(",", $this->scripts) as $script) {
				OCP\Util::addscript('ocDashboard', 'widgets/'.$this->id.'/'.$script);
			}
		}
	}
	
	
	/*
	 * loads all styles that are defined in the config Array
	 */
	private function loadStyles() {
		if(isset($this->styles) && $this->styles != "") {
			foreach (explode(",", $this->styles) as $style) {
				OCP\Util::addStyle('ocDashboard', 'widgets/'.$this->id.'/'.$style);
			}
		}
	}
	
	
	/*
	 * set hash to DB
	 * set and return status local
	 * 
	 * @param $data data for hash in method setHashAndStatus
	 * 
	 * @return status number
	 * 		0 = no status information
	 * 		1 = all okay 
	 * 		2 = something positiv happend (green)
	 * 		3 = something negativ happend (orange)
	 * 		4 = error (red)
	 * 		5 = dummy (yellow)
	 */
	private function getStatus($data) {
		$this->cleanHashs();
		$this->setHashAndStatus($data);
		return $this->status;
	}
	
	
	/*
	 * delete all hashs older than 1 day
	 */
	private function cleanHashs() {
		//$sql = "DELETE FROM `*PREFIX*ocDashboard_usedHashs` WHERE `timestamp` < '".(time()-60*60*24)."'";
		$sql = "DELETE FROM `*PREFIX*ocDashboard_usedHashs` WHERE `timestamp` < ?";
		$query = \OCP\DB::prepare($sql);
		$params = Array(time()-60*60*24);
		if(!$query->execute($params)) {
			OCP\Util::writeLog('ocDashboard',"Can't delete usedHashs", \OCP\Util::WARN);
		}
	}
	
	
	/*
	 * set status (is the hash new? => status = 2)
	 * writes Hash in DB, next time we know if it was used or it is new
	 * 
	 * @param $data data for hash
	 */
	private function setHashAndStatus($data) {
		$hash = sha1(json_encode($data));

		// hash exists in DB ?
		$sql = 'SELECT * FROM `*PREFIX*ocDashboard_usedHashs` WHERE usedHash = ? AND widget = ? AND user = ? LIMIT 1;';
		$params = Array($hash,$this->id,$this->user);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($params)->fetchRow();
				
		// if not in DB, write to DB
		if(!$result) {
			$sql2 = 'INSERT INTO `*PREFIX*ocDashboard_usedHashs` (id,usedHash,widget,user,timestamp) VALUES (\'\',?,?,?,?); ';
			$params = Array($hash,$this->id,$this->user,time());
			$query2 = \OCP\DB::prepare($sql2);
			$result2 = $query2->execute($params);
			if($this->status < 3) {
				$this->status = 2;
				OCP\Util::writeLog('ocDashboard',"Could not write hash to db.", \OCP\Util::WARN);
			}
		}
	}
	
	
	/*
	 * @param $field name of fild
	 * @return default value for field from conf array
	 */
	protected function getDefaultValue ($field) {
		foreach ($this->conf as $conf) {
			if($conf['id'] == $field) {
				return $conf['default'];
			} else {
				return null;
			}
		}
	}

	
	/*
	 * @param $widget widget name
 	 * @return true if all conditon apps are availible
	 */
	private function checkConditions() {
		if(isset($this->cond) && $this->cond != "") {
			return \OCP\App::isEnabled($this->cond);
		} else {
			return true;
		}
	}
}

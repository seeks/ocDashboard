<?php

/*
 * check for new mails
 * copyright 2013
 * 
 * @version 0.1
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */
class mailcheck extends widget implements interfaceWidget {

	private $maxStrLenReference = 23; // character
	private $maxStrLenFrom = 18; // character
	private $numberShowMails = 7;
	private $newMails = Array();
	private $error = "";
	
	
	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		if(empty($this->newMails)) {
			$this->getNewMails();
		}	
		
		// max number show new mails
		$size=count($this->newMails);
			
		if($size > $this->numberShowMails) {
			$numberAllMails = $size;
			$size = $this->numberShowMails;
		} else {
			$numberAllMails = "";
		}
		
		$mails = Array();
		for($i = 0; $i < $size; $i++){
			$mails[] = Array(
					"from" => $this->cutString($this->isodecode($this->newMails[$i]->from), $this->maxStrLenFrom),
					"subject" => $this->cutString($this->isodecode($this->newMails[$i]->subject), $this->maxStrLenReference)
					);
		}
		
		if($this->error != "") {
			$return = Array("error" => $this->error);
			$this->status = 3;
		} else {
			$return = Array(
					"mails" => $mails,
					"numberAllMails" => $numberAllMails
					);
		}

		return $return;
	}
		
	// ======== END INTERFACE METHODS =============================

	
	/*
	 * @return alls new mails, newest first
	 */
	private function getNewMails() {
		$strConnect = "";
		$mail = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_mail","");
		$user = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_user","");
		$server = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_server","");
		$password = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_password","");
		$port = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_port","");
		$folder = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_folder","");
		$ssl = (OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_ssl")=='0')? 1: 0;
		$protocol = (OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_mailcheck_protocol")==1)? "POP3": "IMAP";
		
		if($ssl) {
			$sslAdd = "/ssl";
		} else {
			$sslAdd = "";
		}
		
		if($protocol=='IMAP') {
			if($port=='') {
				$port='143';
			}
			$strConnect='{'.$server.':'.$port.$sslAdd.'}'.$folder;
		} else {
			$strConnect='{'.$server.':'.$port.'/pop3'.$sslAdd.'}'.$folder;
		}
		
		// connect and get mails
		$mailbox = @imap_open($strConnect, $user, $password);
		
		if($mailbox) {
			$mails = array_reverse(imap_fetch_overview($mailbox,"1:*", FT_UID)); // fetch a overview about mails
			imap_close($mailbox);
		
			foreach($mails as $mail) {
				if($mail->seen == 0 && $mail->deleted == 0) {
					$this->newMails[] = $mail;
				}
			}
		} else {
			OCP\Util::writeLog("ocDashboard",$strConnect,OCP\Util::DEBUG);
			$this->error = $this->l->t("Connection error. <br />Are the settings correct?");
		}
	}
	
	
	/*
	 * @return Mail Connection
	 */
	function mailLogin($host,$port,$user,$pass,$folder="INBOX",$ssl=false)
	{
		$ssl=($ssl==false)?"/novalidate-cert":"";
		return (imap_open("{"."$host:$port/pop3$ssl"."}$folder",$user,$pass));
	}
	
	
	/*
	 * @return string max $maxStrLen character
	 */
	private function cutString($string, $len) {
		if(strlen($string) > $len) {
			return substr($string, 0, ((int)$len-3))."...";
		}
		else {
			return $string;
		}
	}
	
	
	/*
	 * @param $text
	 * @return decode mail head information
	 */
	private function isodecode($text){
		$elements = imap_mime_header_decode($text);
		for ($i=0; $i<count($elements); $i++) {
			$rslt .= $elements[$i]->text;
		}
		return utf8_encode($rslt);
	}
}
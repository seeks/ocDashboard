<?php
/*
 * displays tasks from Taskapp by ownCloud
 * copyright 2013
 *
 * @version 0.1
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */
class tasks extends widget implements interfaceWidget {

	private $tasks = Array();
	
	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		if(empty($this->tasks))
			$this->getTasks();
		return Array("tasks" => $this->tasks);
	}	
	
	// ======== END INTERFACE METHODS =============================

	
	/*
	 * called by ajaxService
	 * 
	 * @param $id of task
	 * @return if success
	 */
	public function markAsDone($id) {
		$sql = 'SELECT * FROM `*PREFIX*clndr_objects` as obj,`*PREFIX*clndr_calendars` as cal WHERE obj.id = ? AND cal.id = obj.calendarid LIMIT 1';
		$params = Array($id);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($params)->fetchRow();

		// is there already an value about PERCENT-COMPLETE
		$isAlreadyPrepared = false;
		foreach (explode("\n", $result['calendardata']) as $d) {
			if(substr($d, 0,17) == "PERCENT-COMPLETE:") {
				$isAlreadyPrepared = true;
			}
		}
		
		if(!$isAlreadyPrepared) {
			$tmp = "";
			foreach (explode("\n", $result['calendardata']) as $d) {
				$tmp .= $d."\n";
				if(substr($d, 0,8) == "SUMMARY:") {
					$tmp .= "PERCENT-COMPLETE:100\n";
					$tmp .= "COMPLETED;VALUE=DATE-TIME;TZID=UTC:".date("Ymd",time())."T".date("hms",time())."\n";
				}
			}
		} else {
			$tmp = "";
			foreach (explode("\n", $result['calendardata']) as $d) {
				if(substr($d, 0,17) == "PERCENT-COMPLETE:") {
					$tmp .= "PERCENT-COMPLETE:100\n";
					//$tmp .= "COMPLETED;VALUE=DATE-TIME;TZID=UTC:".date("Ymd",time())."T".date("hms",time())."\n";
				} else {
					$tmp .= $d."\n";
				}
			}
		}
		
		$sql = 'UPDATE `*PREFIX*clndr_objects`
		SET `calendardata` = ? 
		WHERE `id` = ?';
		$params = Array($tmp,$id);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($params);

		if($result) {
			return true;
		} else {
			return false;
		}
	}
	
	
	/*
	 * gets and holds all open tasks local
	*/
	private function getTasks () {
		$sort = Array("DESC","ASC");
		$sortValue = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_tasks_sort",0);
		$sql = 'SELECT *, obj.id as tid FROM `*PREFIX*clndr_objects` as obj,`*PREFIX*clndr_calendars` as cal WHERE objecttype = ? AND NOT calendardata LIKE ? AND userid = ? AND cal.id = obj.calendarid ORDER BY lastmodified '.$sort[$sortValue].';';
		$args = array("VTODO","%PERCENT-COMPLETE:100%",$this->user);

		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($args);
	
		while($row = $result->fetchRow()) {
			$task = Array();
	
			foreach (explode("\n", $row['calendardata']) as $d) {
				if(substr($d, 0,9) == "PRIORITY:") {
					$tmp = explode(":", $d);
					$task['priority'] = $tmp[1];
				}
			}
			$task['summary'] = $row['summary'];
			$task['tid'] = $row['tid'];
			$this->tasks[] = $task;
		}
	}
	
}
<?php

/*
 * displays new from newsreader by ownCLoud
 * copyright 2013
 *
 * @version 0.1
 * @date 01-08-2013
 * @author Florian Steffens (flost@live.no)
 */
class newsreader extends widget implements interfaceWidget {

	private $news = Array(); // this news should be displayed
	private $allNews = 0; // this is the number of all unred news
	private $newsNumber = 0; // this is the actual number from $news in the list $allNews (for output news 4/22 "four of twentytwo")
	
	// ======== INTERFACE METHODS ================================
	
	/*
	 * @return Array of all data for output
	 * this array will be routed to the subtemplate for this widget 
	 */
	public function getWidgetData() {
		$this->getNextNews();
		return $this->news;
	}	
	
// ======== END INTERFACE METHODS =============================
	
	
	/*
	 * this is called by the ajaxService from frontend
	 * has to be public!
	 * 
	 * @param $data dummy
	 * @return true if mark success 
	 */
	public function markAsRead($data) {
		//return true;
		$id = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId");
		$sql = 'UPDATE `*PREFIX*news_items`
		SET `status` = 0
		WHERE `id` = ?';
		$params = Array($id);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($params);

		if($result) {
			return true;
		} else {
			return false;
		}
	}

	
	/*
	 * get the next news from DB and safe it local in object
	 */
	private function getNextNews() {
		$lastId = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId");
		$lastId = is_numeric($lastId) ? $lastId : 0;

		$maxAgeNews = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_maxAge");
		$maxAgeNews = is_numeric($maxAgeNews) ? $maxAgeNews : 0;
		$maxAgeNews = $maxAgeNews * 60 * 60; // from hours to seconds
		$timezoneadd = OCP\Config::getUserValue($this->user, "ocDashboard", "ocDashboard_calendar_timezoneAdd",0);
		$oldestAcceptedNews = time()-$maxAgeNews-$timezoneadd;
		
		$sql = "SELECT items.id as id, items.title as headline, items.url as url, pub_date as pubdate, body as content, favicon_link as fav
					FROM `*PREFIX*news_items` as items JOIN
						 `*PREFIX*news_feeds` as feeds ON items.feed_id = feeds.id
					WHERE 
						items.status > 0 AND 
						items.pub_date > ? AND 
						user_id = ?
					ORDER BY 
						pub_date";
		$params = Array($oldestAcceptedNews,$this->user);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($params);
				
		$foundLastId = false;
		$n = 0;
		$news = Array();
		$firstNews = Array();
		$temp = Array();
		while ($row = $result->fetchRow()) {
			$n++;
				
			// add time from settings for correct timedisplay
			$row['pubdate'] = $row['pubdate']+60*60*$timezoneadd;
			
			if(empty($firstNews)) {
				$firstNews = $row;
				$firstNews['actual'] = $n;
			}
			
			if($foundLastId) {
				$news = $row;
				$news['actual'] = $n;
				$foundLastId = false;
			}
			
			if($row['id'] == $lastId) {
				$foundLastId = true;
			}			
		}
		
		// begin at start
		if(empty($news)) {
			$news = $firstNews;
		}
		
		$news['num'] = $n; 
		$this->news = $news;
		
		OCP\Config::setUserValue($this->user, "ocDashboard", "ocDashboard_newsreader_lastItemId", $news['id']);
	}
			
}
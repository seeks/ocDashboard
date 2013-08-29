<?php

class widgets {
	
		/* all availible widgets
		* 
		* Array( 
		* 	widget id (small letters) 
		* 	auto refresh every x seconds (0=no refresh)
		* 	icon id (in img folder; always start with 'icon')
		* 	conf options as json
		* 		generel:
		* 			id (unique)
		* 			name (description)
		* 			default (for name)
		* 			type (look at fields) 
		* 		fields: 
		* 			string recommendet: id, name, default
		* 			radio  recommendet: options (as array)
		* 			password
		* 			label (normal text)
		*		optional:
		*			tooltip (shown by mouseover)
		* 	cond condition App
		* 	scripts comma separated (in folder js, without ".js")
		* 	styles comma seperated (in folder css, without ".css")
		* 	link (url for click on headline)
		* )
		* 
		*/
		static $widgets = Array(
							Array(
									'id' 		=>	"clock",
									'name'		=>	"Clock",
									'refresh'	=>	0,
									'icon'		=>	"",	
									'conf'		=>	"",
									'cond' 		=>	"",
									'scripts'	=>	"coolclock,ocClockSkin,excanvas",
									'styles'	=>	"",
									'link'		=>	""
								),
							Array(
									'id' 		=>	"calendar",
									'name'		=>	"Calender",
									'refresh'	=>	60,
									'icon'		=>	"iconCal.png",
									'conf'		=>	'[{"name":"correct time (add x hours)","type":"string","id":"timezoneAdd","default":"0"}]',
									'cond' 		=>	"calendar",
									'scripts'	=>	"",
									'styles'	=>	"",
									'link'		=>	"index.php/apps/calendar/"
							),
							Array(
									'id' 		=>	"weather",
									'name'		=>	"Weather",
									'refresh'	=>	3600,
									'icon'		=>	"iconWeather.png",	
									'conf'		=>	'[{"name":"City Code","type":"string","id":"city","default":"656958","tooltip":"You can get the code for your city here: <a href=\"http://weather.yahoo.com\" target=\"_blank\">http://weather.yahoo.com/</a><br />Look for your city and open the weather information for it.<br />Look at the URL. It should end like /city-xxxxxx/.<br />Type in here the City Code \"xxxxxx\"."},{"id":"unit","type":"radio","options":["&deg;F / mph","&deg;C / kmh"],"name":"Unit"}]',
									'cond' 		=>	"",
									'scripts'	=>	"",
									'styles'	=>	"",
									'link'		=>	""
								),
							Array(
									'id' 		=>	"tasks",
									'name'		=>	"Tasks",
									'refresh'	=>	60,
									'icon'		=>	"iconTasks.png",	
									'conf'		=>	'[{"id":"sort","type":"radio","options":["new tasks first","old tasks first"],"name":"Sortierung"}]',
									'cond' 		=>	"tasks",
									'scripts'	=>	"",
									'styles'	=>	"",
									'link'		=>	"index.php/apps/tasks"
								),
							Array(
									'id' 		=>	"mailcheck",
									'name'		=>	"Mail Check",
									'refresh'	=>	120,
									'icon'		=>	"iconMail.png",	
									'conf'		=>	'[{"name":"E-Mail","type":"string","id":"mail","default":"name@domain.tld"},{"name":"Server","type":"string","id":"server","default":"imap.server.tld"},{"name":"User","type":"string","id":"user","default":"Username"},{"name":"Password","type":"password","id":"password","default":""},{"name":"Port","type":"string","id":"port","default":"143"},{"name":"Folder","type":"string","id":"folder","default":"INBOX"},{"id":"ssl","type":"radio","options":["Yes","No"],"name":"Use SSL","value":"0"},{"id":"protocol","type":"radio","options":["IMAP","POP3"],"name":"Protocol","value":"0"}]',
									'cond' 		=>	"",
									'scripts'	=>	"",
									'styles'	=>	"",
									'link'		=>	"index.php/apps/roundcube"
							),
							Array(
									'id' 		=>	"newsreader",
									'name'		=>	"Newsreader",
									'refresh'	=>	120,
									'icon'		=>	"iconNews.png",	
									'conf'		=>	'[{"name":"correct time (add x hours)","type":"string","id":"timezoneAdd","default":"0"},{"name":"Max age of news to show (hours)","type":"string","id":"maxAge"}]',
									'cond' 		=>	"news",
									'scripts'	=>	"",
									'styles'	=>	"",
									'link'		=>	"index.php/apps/news/"
								)
					  );
		
		/*
		 * @param $id id of widget
		 * @return widgetArray
		 */
		public static function getWidgetConfigById ($id) {
			foreach (widgets::$widgets as $w) {
				if($w['id'] == $id) {
					return $w;
				}
			}
			return null;
		}
}
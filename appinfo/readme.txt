Readme ocDashboard
******************

license
=======

generel license is AGPL
but this is NO COMMERCIAL
there are some pics and sources for private use only
if you want to use it commercial, ask me


contact
=======

you can send me feedback and ideas to
Florian Steffens
flost@live.no


Development
===========

Add new widget
--------------
1. Just put your class in the lib/widgets folder.
2. You have to implement the interfaceWidget.php interface.
3. Then add your widget configuration in the array at appinfo/widgetConfigs.php.
4. You also need to put your template in the templates/widget/[widgetid].php.
5. You may add an icon in the folder "img", scripts in folder "js/widgets/[widgetId]/" and styles "css/widgets/[widgetId]"


Front End Ajax Service
----------------------
call via javascript: ajaxService([widgetId],[public Method in widget Class],[value (parameter for the method)],[callbackfunction])
Example: ajaxService('newsreader','markAsRead','',function() {loadWidget('newsreader');})

generell function for loading/wait: 
showWaitSymbol()
hideWaitSymbol 


Ideas for more Widgets
-----------------
Tv Programm
internal bookmarks
Twitter Feeds
Facebook News
last modified files
internal chat news
calculator
translater
notice
disk free for oc
Ebay
fuel consumption
fetch worktime
stock prices
folder observer, alert when somethin changed

 
Tested Browser
--------------
Chrom
Firefox
Safarie

no IE (sorry no time)


Development Problems
--------------------
date format with L10N ???
api for unit tests ?
use newsreader api


knowen bugs
-----------
Chrome
	newsreader function "mark as read" doesn't work

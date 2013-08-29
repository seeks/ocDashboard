<?php

OCP\JSON::callCheck();
OCP\User::checkLoggedIn();
OCP\App::checkAppEnabled('ocDashboard');

OC::$CLASSPATH['widgets'] = 'ocDashboard/appinfo/widgetConfigs.php';
OC::$CLASSPATH['factory'] = 'ocDashboard/lib/factory.php';

$user = OCP\User::getUser();
$id=isset($_GET['id'])?$_GET['id']:$_POST['id'];
$method=isset($_GET['method'])?$_GET['method']:$_POST['method'];
$value=isset($_GET['value'])?$_GET['value']:$_POST['value'];


// if widget is enabled
if (OCP\Config::getUserValue($user, "ocDashboard", "ocDashboard_".$id) == "yes") {
	$widgetConf = widgets::getWidgetConfigById($id);
	$widget = factory::getWidget($widgetConf);
	$result = "";
	if (method_exists($widget, $method)) {
		$result = $widget->$method($value);
	}
	
	if ($result!="") {
		$RESPONSE["response"] = $result;
		$RESPONSE["success"] = true;
	} else {
		$RESPONSE["success"] = false;
	}
} else {
	$RESPONSE["success"] = false;
}
die(json_encode($RESPONSE));

?>

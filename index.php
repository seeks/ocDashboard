<?php

// Look up other security checks in the docs!
\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('ocDashboard');
\OCP\App::setActiveNavigationEntry( 'ocDashboard' );

OCP\Util::addscript('ocDashboard', 'ocDashboard');
OCP\Util::addscript('ocDashboard', 'ajaxService');
OCP\Util::addStyle('ocDashboard', 'ocDashboard');

$user = OCP\User::getUser();

$w = Array();
OC::$CLASSPATH['widgets'] = 'ocDashboard/appinfo/widgetConfigs.php';
OC::$CLASSPATH['factory'] = 'ocDashboard/lib/factory.php';
OC::$CLASSPATH['api'] = 'ocDashboard/lib/api.php';

foreach (widgets::$widgets as $widget) {
	// if widget is enabled
	if (OCP\Config::getUserValue($user, "ocDashboard", "ocDashboard_".$widget['id']) == "yes") {
		$w[] = factory::getWidget($widget)->getData();
	}
}

//if all deactivated
if(empty($w)) {
	$l=new OC_L10N('ocDashboard');
	$w[0]['error'] = $l->t("You can configure this site in your personal settings.");
	$w[0]['id'] = "";
}

$tpl = new OCP\Template("ocDashboard", "main", "user");
$tpl->assign('widgets', $w);
$tpl->printPage();
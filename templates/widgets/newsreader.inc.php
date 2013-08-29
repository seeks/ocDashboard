<?php 
// add number of shown news and "mark as read" click
if(isset($additionalparams['num']) && $additionalparams['num'] != "") { ?>
	 
	 <div class="ocDashboard newsreader counter"><?php p($additionalparams['actual'].'/'.$additionalparams['num']); ?> <a onclick="javascript:showWaitSymbol('newsreader'); ajaxService('newsreader','markAsRead','',function() {loadWidget('newsreader');});" href="#" title="<?php print_unescaped($l->t("Mark as read.")); ?>">&#10003;</a></div>

<?php } ?>

<?php
// add favicon from source website
if(isset($additionalparams['fav']) && $additionalparams['fav'] != "") {
	$style = "background-image: url('".$additionalparams['fav']."'); background-repeat: no-repeat; background-position: 0px 2px; background-size: 18px 18px; padding-left: 21px;";
} else {
	$style = "";
}
?>

	<div class='ocDashboard newsreader items'><h2 style="<?php print_unescaped($style); ?>"><a target='_blank' href="<?php print_unescaped($additionalparams["url"]); ?>"><?php p($additionalparams['headline']); ?></a></h2>

<?php
// show date only if there are news
if ($additionalparams['headline'] != "") {
	if(OC_L10N::findLanguage() == "de" || OC_L10N::findLanguage() == "de_DE") { ?>
	
		<div class='ocDashboard newsreader date'><?php p(date("d.m.y", $additionalparams['pubdate'])); ?> - <?php print_unescaped(date("G:i", $additionalparams['pubdate'])); ?> Uhr</div>
	
	<?php } else { ?>
	
		<div class='ocDashboard newsreader date'><?php p(date("F j", $additionalparams['pubdate'])); ?><sup><?php print_unescaped(date("S", $additionalparams['pubdate'])); ?></sup> <?php print_unescaped(date("Y, g:i a", $additionalparams['pubdate'])); ?></div>
	
	<?php }
} ?>
 
	<div class='newsItem'><?php print_unescaped($additionalparams['content']); ?></div>
</div>
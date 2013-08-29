<?php 
	$colors = Array(9 => "rgb(50,180,50)", 5 => "blue", 1 => "red");
?>
		
<div class='ocDashboard tasks items'>
			
<?php 
	foreach ($additionalparams['tasks'] as $task) {
		if(isset($task['priority']) && $task['priority'] != "") {
			$style = ' style="color: '.$colors[$task['priority']].'" ';
		} else {
			$style = "";
		}
		?>
		
		<div class='ocDashboard tasks item' <?php print_unescaped($style); ?>>
        	<a onclick="javascript:$(this).text('x&nbsp;');showWaitSymbol('<?php print_unescaped($additionalparams['id']); ?>');ajaxService('tasks','markAsDone','<?php print_unescaped($task['tid']); ?>',function(res) {loadWidget('tasks');});" href="#" title="<?php print_unescaped($l->t("Mark as done.")); ?>">&#10003;&nbsp;</a>
        	<?php p($task['summary']); ?>
        </div>
    <?php 
	}	
	?>
</div>
<div id="ocDashboard">

<?php 

	//print_r($_['widgets']);
	
	foreach ( $_['widgets'] as $widget) {
		// if error, just show error message
		if(isset($widget['error']) && $widget['error'] != "") {
		?>
			
			<div class="dashboardItem" id="<?php print_unescaped($widget['id']); ?>" style="display: none;"  data-interval="<?php print_unescaped($widget['interval']); ?>" data-status="<?php print_unescaped($widget['status']); ?>">
			<div class="ocDashboard head"><?php print_unescaped($l->t($widget['name'])); ?></div>
			<?php print_unescaped($l->t($widget['error'])); ?>
			</div>
			
		<?php
		} else {
			// base domain
			$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
			$sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
			$protocol = substr($sp, 0, strpos($sp, "/")) . $s;
			$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
			$x = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
			$y = explode("index.php", $x);
			$base = $y[0];
			
			// add icon if availible
			if($widget['icon'] != "") {
				$style = "background-image: url('".$base."apps/ocDashboard/img/".$widget['icon']."'); background-repeat: no-repeat; background-position: 280px 0px; background-size: 35px 35px;";
			} else {
				$style = "";
			}
			?>
			
			<div class="dashboardItem" id="<?php print_unescaped($widget['id']); ?>" style="display: none; <?php print_unescaped($style); ?>"  data-interval="<?php print_unescaped($widget['interval']); ?>" data-status="<?php print_unescaped($widget['status']); ?>">
			
			<!--  add wait symbol -->
			<div class="ocDashboard inAction <?php print_unescaped($widget['id']); ?>">&nbsp;</div>
			
			<?php
			
			// add reload button
			if ($widget['interval']) {
				$reload = "<span>&nbsp;&#8635;</span>";
			} else {
				$reload = "";
			}
			
			if(isset($widget['link']) AND $widget['link'] != "") { 
			?>
				<div class="ocDashboard head"><a href="<?php print_unescaped($base.$widget['link']); ?>"><?php print_unescaped($l->t($widget['name'])); ?></a><?php print_unescaped($reload); ?></div>
			<?php 
			} else {
			?>
				<div class="ocDashboard head"><?php print_unescaped($l->t($widget['name'])); ?><?php print_unescaped($reload); ?></div>
			<?php 
			}
			?>
			
			
			<?php			
			print_unescaped($this->inc('/widgets/'.$widget['id'].'.inc', $widget));
			?>
			
			</div>
			
		<?php
		}
	}

?>

</div>

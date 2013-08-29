<form id="ocDashboardSettings"> 
	<fieldset class="personalblock"> 
	<legend>
		<strong><?php p($l->t('Dashboard Settings')); ?></strong>
	</legend> 
	
	<?php 
		foreach ($_['widgets'] as $widget) {
			$id = $widget['widget']['id'];
			$name = $widget['widget']['name'];
			echo '<div class="widgetItem '.$id.'">';
			$enable = ($widget['enable'] == "yes")?"checked":"";
    		echo '<input type="checkbox" name="ocDashboard_'.$id.'" id="ocDashboard_'.$id.'" class="checkbox" value="1" '.$enable.' />';
			echo '<label class="widgetName" for="ocDashboard_'.$id.'" >&nbsp;&nbsp;'.$l->t($name).'</label>';
			echo '<br /><br />';

			if(isset($widget['conf']) && !empty($widget['conf'])) {
				foreach ($widget['conf'] as $c) {
					// tooltip
					if(isset($c['tooltip']) && $c['tooltip'] != "") {
						$span = "<span>".$l->t($c['tooltip'])."</span>";
						$classTooltipLabel = "tooltipLabel";
					} else {
						$span = "";
						$classTooltipLabel = "";
					}
					
					switch ($c['type']) {
						case 'string':					
							$value = ($c['value'] != "")?$c['value']:$c['default'];
							echo '<label for="ocDashboard_'.$id.'_'.$c['id'].'" class="'.$classTooltipLabel.'">'.$l->t($c['name']);
							echo $span;
							echo '</label>';
							echo '<input type="text" value placeholder="'.$l->t($value).'" name="ocDashboard_'.$id.'_'.$c['id'].'" id="ocDashboard_'.$id.'_'.$c['id'].'" />';
							break;
						case 'radio':
							echo '<label class="'.$classTooltipLabel.'">&nbsp;&nbsp;'.$l->t($c['name']);
							echo $span;
							echo '</label>';
							foreach ($c['options'] as $k => $option) {
								($k == $c['value']) ? $checked = "checked": $checked = ""; // if this is actual choosen
								echo '&nbsp;&nbsp;&nbsp;<input type="radio" id="ocDashboard_'.$id.'_'.$c['id'].'_'.$k.'" name="ocDashboard_'.$id.'_'.$c['id'].'" value="'.$k.'" class="ocDashboard_'.$id.'_'.$c['id'].'" '.$checked.' /><label for="ocDashboard_'.$id.'_'.$c['id'].'_'.$k.'">'.$l->t($option).'</label>';
							}
							break;
						case 'password':
							echo '<label for="ocDashboard_'.$id.'_'.$c['id'].'" class="'.$classTooltipLabel.'">'.$l->t($c['name']);
							echo $span;
							echo '</label>';
							echo '<input type="password" value placeholder="'.$l->t($c['default']).'" name="ocDashboard_'.$id.'_'.$c['id'].'" id="ocDashboard_'.$id.'_'.$c['id'].'" />';
							break;
						case 'label':
							echo '<div class="ocDashboard_label">'.$l->t($c['name']).'</div>';
							break;
					}
	
				} 
			}    		

    		echo '</div>';
		}
		echo $l->t('<p style="padding-top: 20px;">There are some background colors for the widgets.<ul><li>red: Error</li><li>orange: missconfiguration</li><li>green: there is something new</li><li></li></ul></p>');
	?>
	
	</fieldset> 
</form>
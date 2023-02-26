<?php

# Define the options menu
function pts_option_menu() {
    global $plName;

    if (!current_user_can('manage_options')) return;

	// if (function_exists('current_user_can')) {
	// 	if (!current_user_can('manage_options')) return;
	// } else {
	// 	global $user_level;
	// 	get_currentuserinfo();
	// 	if ($user_level < 8) return;
    // }


	if (function_exists('add_options_page')) {
		add_options_page($plName, $plName, "manage_options", __FILE__, 'pts_options_page');
	}
}
# Install the option in the WordPress configuration menu
add_action('admin_menu', 'pts_option_menu');













# Prepare the default set of options
$default_options['pts_start'] = '00:00';
$default_options['pts_end'] = '23:59';
$default_options['pts_infosize'] = 'parcial';
$default_options['pts_allowstats'] = 'yes';


// the plugin options are stored in the options table under the name of the plugin file sans extension
add_option(PTS_OPTION_NAME, $default_options);

// This method displays, stores and updates all the options
function pts_options_page(){
	global $wpdb;
	global $plName;
	global $plUrl;
    global $pts_debug;
    global $pts_show_donate;

	# insert Google analytics code to monitor plugin usage.
	add_action('admin_footer', 'pts_insertAnalytics',12);


	$bit = explode("&",$_SERVER['REQUEST_URI']);
	// This bit stores any updated values when the Update button has been pressed
	if ( isset( $_POST['update_options'] ) && isset( $_POST['my_form_nonce'] ) ) {

		if ( ! wp_verify_nonce( $_POST['my_form_nonce'], 'my_form_action' ) ) {
			// Handle invalid nonce
			die( 'Invalid nonce. Plsea contact the plugin author.');
		}

		// This will process the request and update the options


        // print_r($_POST);

		# loads before change with post values...
		$pts_options = get_option(PTS_OPTION_NAME);

		// Fill up the options array as necessary
		$pts_options['pts_start'] = htmlspecialchars($_POST['pts_start']); // like having business hours
		$pts_options['pts_end'] = htmlspecialchars($_POST['pts_end']);

		$pts_options['pts_0'] = htmlspecialchars($_POST['pts_0']);
		$pts_options['pts_1'] = htmlspecialchars($_POST['pts_1']);
		$pts_options['pts_2'] = htmlspecialchars($_POST['pts_2']);
		$pts_options['pts_3'] = htmlspecialchars($_POST['pts_3']);
		$pts_options['pts_4'] = htmlspecialchars($_POST['pts_4']);
		$pts_options['pts_5'] = htmlspecialchars($_POST['pts_5']);
		$pts_options['pts_6'] = htmlspecialchars($_POST['pts_6']);

		$pts_options['pts_infosize'] = htmlspecialchars($_POST['pts_infosize']);

		$pts_options['pts_allowstats'] = htmlspecialchars($_POST['pts_allowstats']);


		# if all weeks are NO... change the monday to YES
		$allNo = 0;
		for($i=0;$i<7;$i++){
			if($pts_options['pts_'.$i] == 'no'){
				$allNo += 1;
			}
			else{
				break;
			}
		}
		if($allNo == 7){
			$pts_options['pts_1'] = 'Yes';
		}


        # set the default values if they do not match the hh:mm
        if(!preg_match('/\d{2}:\d{2}/',$pts_options['pts_start'])){
            $pts_options['pts_start'] = '00:00';
        }

        if(!preg_match('/\d{2}:\d{2}/',$pts_options['pts_end'])){
            $pts_options['pts_end'] = '23:59';
        }


		$time = explode(":",$pts_options['pts_start']);
        $pts_options['pts_start'] = date("H:i",mktime($time[0],$time[1],0,9,11,2001)); // convert overruns

		$time = explode(":",$pts_options['pts_end']);
		$pts_options['pts_end'] = date("H:i",mktime($time[0],$time[1],0,9,11,2001));

		// store the option values under the plugin filename
		update_option(PTS_OPTION_NAME, $pts_options);

		// Show a message to say we've done something
		if($allNo == 7){
			echo '<div class="updated"><p>' . __('You must check "Yes" for at least 1 day of week! ', 'pts') . '</p></div>';
		}
		else{
			echo '<div class="updated"><p>' . __('Options saved!', 'pts') . '</p></div>';
		}

	} else {
		$pts_options = get_option(PTS_OPTION_NAME);
	}


	# OPTIONS \ ADMIN SCREEN


	?>
		<div class="wrap">


		<h2 title="<?php
		_e('Plugin version','pts');
		echo ': ';
		echo pts_get_version()
		?>"><?php echo ucwords(str_replace('-', ' ', PTS_OPTION_NAME)) .' - '. __('Options', 'pts'); ?></h2>


        <?php

?>
<h3><?php
echo __("I am working on this plugin for 10+ years. <br />Please make a donation!<br />It is a hell of an incentive for me!",'pts');
?></h3>
<?php

	$howMuch = pts_donateRandomValueString();
	if($pts_show_donate){
		echo('<div>');

			echo '<div style="margin:10px 0">';
				echo pts_donateHTMLButton($howMuch);
			echo('</div>');

		echo('</div>');

	echo __('Believe-me... even <strong>$'. $howMuch. ' per month</strong> will make me super happy... :)',  'pts');
	echo '<br>';
	echo '<br>';



	}

        ?>

		<form method="post" action="">

		<fieldset class="options">

		<?php
		if($pts_debug){
			echo '<h3><strong style="color:red;">'.$plName.' - <span style="text-decoration:blink">Debug active!</span></strong></h3>';
		}
		?>

		<h3 style="margin-top:5px;"><?php _e('Which days of week posts are allowed to be auto-scheduled? <br>(The schedule happens only when you click the "Pub. to Schedule" button!)',  'pts')?></h3>

		<?php _e('Put 0 in a day when you do not want posts to be scheduled!',  'pts')?>

		<p>
		<?php _e('Example: if you put 0 on Sunday, this plugin will never schedule a post to be published on Sundays. <br> But still, if you want to schedule an article to be published in a Sunday, just schedule it using the standard schedule button of WordPress and it will be published on the date you choose, ignoring all options below!<br>',  'pts')?>
		<br>
		<?php _e('For each day, set how many posts will be scheduled!',  'pts')?>
		</p>



		<?php
			$days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
		?>

		<table>
			<?php
			$iday = 0;
			foreach($days as $day){

                $day_value = $pts_options["pts_$iday"];

                if(!$day_value){
                    $day_value = 'no';
                }

			?>

				<tr valign="top">
					<th scope="row" style="padding:5px;"><?php _e(ucfirst($day), 'pts') ?>:</th>

					<td style="padding:5px;">
						<input
							type="text"
							id="<?php echo $day; ?>"
							name="<?php echo "pts_$iday"; ?>"
                            value="<?php
                                if ($day_value == 'no'){
                                    echo '0';
                                }
                                else if ($day_value == 'yes') {
                                    echo '1';
                                }
                                else {
                                    # default to display the actual value!
                                    echo $day_value ;
                                }
                            ?>"
							style="width: 40px;"/>
					</td>

				</tr>


			<?php

				$iday += 1;
			}

			?>

		</table>


		<h3 style="margin-top:10px;"><?php _e('Specify the time interval in which you want to have your posts scheduled!',  'pts')?></h3>

		<p>
		<?php _e('Example: posts will only be scheduled to be published in this time interval.<br> But still, if you do via WordPress schedule button, you can schedule for any time you want!',  'pts')?>
		</p>


		<table class="optiontable">
			<tr valign="top">
				<th scope="row"><?php _e('Start Time', 'pts') ?>:</th>
				<td><input name="pts_start" type="text" id="start" value="<?php echo $pts_options['pts_start']; ?>" size="10" /><?php _e(' (defaults to 00:00)', 'pts') ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('End Time', 'pts') ?>:</th>
				<td><input name="pts_end" type="text" id="end" value="<?php echo $pts_options['pts_end']; ?>" size="10" /><?php _e(' (defaults to 23:59)', 'pts') ?>
				</td>
			</tr>



		</table>


		<?php






		$msgTimeWrong = '

		<h3 style="margin-top:20px;">'. __('Your WordPress timezone settings might be incorrect!', 'pts').
		'</h3>'
		 . __('The date and time we detect : ') . '<span style="color:blue;font-weight:bold;">'
		.date(get_option('date_format').', '.get_option('time_format'),current_time('timestamp', $gmt = 1)).
		'</span>';

		/*
		<p>__("If this is not your local time, you have to ", 'pts') ?> <a title="" href="options-general.php"><?php _e('configure the correct the timezone for your WordPress installation', 'pts') ?></a>. <br>
		<?php _e('With wrong time configurations, how can the posts be properly scheduled?', 'pts') ?>
		<br>
		<?php _e('If your timezone is right (remember the daylight saving time), you must check the clock with your host.', 'pts') ?>
		</p>
		*/





		$msgTimeOK = __('Your timezone configuration and server time seems to be OK!','pts').' <span style="color:green;font-weight:bold;">'.date(get_option('date_format').', '.get_option('time_format'),current_time('timestamp', $gmt = 0)).'</span>';


		$msgTimeWrong = '<h3 style="margin-top:20px;">'
		. __('Your WordPress timezone settings might be incorrect!', 'pts').
		'</h3>'
		. __('According to your web server','pts') .
		', '
		. __('the GMT time is: ','pts') . ' <span style="color:blue;font-weight:bold;">'.date(get_option('date_format').', '.get_option('time_format'),current_time('timestamp', $gmt = 1)).'.</span>'.
		'<br>'
		. __('The timezone configured in your','pts').' <a target="_blank" href="options-general.php">'.__('WordPress settings','pts').'</a> '.__('is','pts') .': <span style="color:blue;font-weight:bold;">'.get_option('gmt_offset').', </span>'.
		'<br>'
		. __('so your server think that is your local time is: ','pts') . ' <span style="color:red;font-weight:bold;">'.date(get_option('date_format').', '.get_option('time_format'),current_time('timestamp', $gmt = 0)).'</span> ... '
		. __('but this is different from time on you machine now!','pts').
		'<br>'
		. __('If the difference is not too big (less than 2h or 3h) you problably will not have side effects and the plugin should work fine!','pts').
		'<br>'
		. __('Othewise, with big time differences, you can have issues with the real time that each post will be scheduled!','pts').
		'<br>'
		. __('Sometimes you have to set a different timezone to compensate daylight saving time or a missconfigured server time! ','pts').

		'<br>'
		. __('If you can, change the timezone to correct this, refresh this page and this message will be shown anymore!','pts')
		;




		# javascript to compare the times...
		echo pts_createJsToCompareTime($msgTimeWrong,$msgTimeOK);

		# div usada para reportar hora incorreta...
		echo '<div style="padding-left:30px;" id="divjsCT"></div>';

		echo '<script type="text/javascript">
				jsCompareTimes();
			</script>';

		?>




		<h3 style="margin-top:20px;"><?php _e('How much information you want to see near the "Publish" button (on post edit screen)?', 'pts') ?></h3>
		<table>
			<tr valign="top">
				<td style="padding:5px;">
					<input type="radio" name="pts_infosize" id="pts_infosize_all" value="all"<?php if ($pts_options['pts_infosize'] == 'all') echo ' checked'; ?>>
					<?php
					_e(' Show all information available!','pts');
					echo '<br/>';
					_e(' I want to see how this plugin works!','pts');
					echo '<br/>';
					_e(' (Might be a lot of text! Good for debugging purposes or enthusiats!)', 'pts');
					 ?>
					 />
				</td>

				<tr valign="top">
				<td style="padding:5px;">
					<input type="radio" name="pts_infosize" id="pts_infosize_parcial" value="parcial"<?php if ($pts_options['pts_infosize'] != 'all') echo ' checked'; ?>>
					<?php
					_e(' Just do the magic!','pts');
					echo '<br/>';
					_e(' Only show the calculated "auto schedule date" for the post!','pts');
					?>
					/>
				</td>
			</tr>
		</table>
		</fieldset>




		<h3><?php
		echo __('Do you like this plugin?','pts');
		?></h3>

		<ul>
			<?php


			$twiterMessage = __(
			"I don't have to worry anymore with scheduling post. Plugin $plName does it for me! Works like a charm! ($plUrl)"
			,'pts');

			$twiterMessage = str_replace(' ','%20',$twiterMessage);
			echo '<ul>';

			echo '<li><a target="_blank" href="http://twitter.com/home?status='.$twiterMessage.'">'.__('Tweet','pts').'</a> '.__('about it','pts').'!</li>';


			echo '<li><a target="_blank" href="'.$plUrl.'">'.__('Rate it','pts').'</a> '.__('on the repository!','pts').'</li>';




			$langavailable = array();
			array_push($langavailable,'en','pt-BR');
			if(! in_array(get_bloginfo('language'),$langavailable)){
				echo '<li><a target="_blank" href="'.$plUrl.'">'.__('Help with translation!','pts').'</a>: <br>'.

					__('We dont have this plugin translated to your language yet!','pts').
					' ('.
					get_bloginfo('language').
					' )'.
					'<br>'.

					__('The languages already translated are: ','pts');
					echo '<li>';
					echo '<ol>';
					foreach($langavailable as $lang){
						echo('<li>');
						echo($lang);
						echo('</li>');
				 	}
					echo '</ol>';
					echo '</li>';
					'</li>';
					echo '<li>';

					 _e('If you speak any of these and are native in','pts');
					 echo(' ');
					 echo get_bloginfo('language');
					 echo(', ');
					 _e('help translating this plugin to you language!','pts');

			}
			echo '</ul>';


			?>
		</ul>




		<h3 style="margin-top:20px;"><?php _e('Statistics', 'pts') ?></h3>
		<?php
		_e('Help make this plugin even better!','pts');
		echo '<br>';
		_e('Allow plugin usage statistics to be shared with its developer.','pts');
		echo '<br>';
		?>

		<td style="padding:5px;">
			<input type="radio" name="pts_allowstats" id="pts_allowstats" value="yes" <?php if ($pts_options['pts_allowstats'] != 'no') echo ' checked'; ?>/><?php _e('Yes', 'pts') ?>
			<input type="radio" name="pts_allowstats" id="pts_allowstats" value="no" <?php if ($pts_options['pts_allowstats'] == 'no') echo ' checked'; ?>/><?php _e('No', 'pts') ?>
		</td>


		<br/>



		<?php
		if(($pts_options['pts_statistics_total_work']) > 3){
			echo '<h3 style="margin-top:20px;">'.__('Did it save you a lot of time?','pts').'</h3>';
			if($pts_options['pts_statistics_total_work'] > 20){
				echo __('Ohh yes... it certainly did!','pts');
				echo '<br>';
			}
			echo __('Since you installed this plugin','pts');
			echo ', ';
			echo '<strong>';
			echo $pts_options['pts_statistics_total_work'];
			echo ' ';
			echo __(' posts were automatically scheduled, saving your time!', 'pts');
			//echo __(' posts were automatically scheduled, saving your time! So...', 'pts');
			echo '</strong>';
			echo '<br>';
			echo '<br>';
		}


		?>


		<?php
			// This prints out all hidden setting fields to avoid CRSF attacks
			wp_nonce_field( 'my_form_action', 'my_form_nonce' );
		?>

		<div class="submit"><input type="submit" name="update_options" value="<?php _e('Save all changes', 'pts') ?>"  style="font-weight:bold;" /></div>
		</form>

	</div>

<?php
}

?>
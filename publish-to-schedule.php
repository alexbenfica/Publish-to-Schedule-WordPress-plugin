<?php
/*
Plugin Name: Publish to Schedule
Plugin URI: http://wordpress.org/extend/plugins/publish-to-schedule/ 
Description: Just write! Let this plugins AUTO-schedule all posts for you! Configure once, use forever!
Version: 4.0.05
Author: Alex Benfica
Author URI: https://br.linkedin.com/in/alexbenfica
License: GPL2 
 
Copyright 2012-2016  Publish to Schedule  (email : alexbenfica@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


 
# useful for translating, whrere you can createa .po file from .php file. 
# POedit not working very well...
# http://www.icanlocalize.com/tools/php_scanner
# load domains for translations from english...
load_textdomain('pts', dirname(__FILE__).'/lang/' . get_locale() . '.mo');


$plName = 'Publish to Schedule';
$plUrl = 'http://wordpress.org/extend/plugins/publish-to-schedule/';


# activate debug
$pts_debug = False;

$pts_show_donate = False;

# url for paypal donation.
$pts_donateURL = 'https://www.paypal.com/cgi-bin/webscr?business=alexbenfica@gmail.com&cmd=_donations&item_name=PublishToSchedule&no_note=0&lc='
	.__('US',  'pts').
	'&currency_code='
	.__('USD',  'pts');








#Actions that change post status...
#http://codex.wordpress.org/Post_Status_Transitions

#All possible post status in Jan 2012...

#'new' - When there's no previous status
#'publish' - A published post or page
#'pending' - post in pending review
#'draft' - a post in draft status
#'auto-draft' - a newly created post, with no content
#'future' - a post to publish in the future
#'private' - not visible to users who are not logged in
#'inherit' - a revision. see get_children.
#'trash' - post is in trashbin. added with Version 2.9.

# set the interesting status when plugin will do its magic...



$possibleStatus = array();
array_push($possibleStatus,'new');
array_push($possibleStatus,'pending');
array_push($possibleStatus,'draft');
array_push($possibleStatus,'auto-draft');

# create actions for each one ...
foreach($possibleStatus as $status) {
	add_action($status.'_to_publish','pts_do_publish_schedule',1);	
}







# change the name os publish button...
function pts_change_publish_button( $translation, $text ) {
	if ( $text == 'Publish' )
		return __('Pub. to Schedule','pts');

	return $translation;
}








# return the actual version of this plugin
function pts_get_version() {
	$plugin_data = get_plugin_data( __FILE__ );
	$plugin_version = $plugin_data['Version'];
	return $plugin_version;
}





# insert Google Analytics code to monitor plugin utilization.
function pts_insertAnalyticsCode($getCode = False){
	
	$options = get_option(basename(__FILE__, ".php"));
	
	# do not collect statististcs if now allowed... 	
	if($options['pts_allowstats'] == 'No'){		
		return '';
	}
	
	$analyticsCode = "<script type=\"text/javascript\">
	var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-28857513-1']);
		_gaq.push(['_trackPageview']);	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
		_gaq.push(['_setCustomVar', 1,'Site URL','".get_option('home')."', 1 ]);
		_gaq.push(['_setCustomVar', 2,'Articles scheduled','".$options['pts_statistics_total_work']."',1]); 	
		_gaq.push(['_setCustomVar', 3,'WP Language','".get_bloginfo('language')."',1]);
	</script>";
	
	
	if($getCode){
		return $analyticsCode;
	}
	else{	
		print $analyticsCode;	
	}
}















# creates a js function that will compare the cliet time with the server time, passed as variables...
function pts_createJsToCompareTime($HTMLWrong,$HTMLOK){


	$HTMLWrong = trim($HTMLWrong);
	$HTMLOK = trim($HTMLOK);
	
	# minutes...
	$maxAllowedDif = 20;	
	
	# seconds...
	$phplocal = current_time('timestamp', $gmt = 0);	
	
	# minutes...
	$phplocal = $phplocal / 60;
	
	# minutes in a day...
	$phplocal = $phplocal % 1440;
	
	
	$jsCT = '
	
	<script type="text/javascript">	

	
	function jsCompareTimes(){	
		d = new Date();						
		var currentHours = d.getHours();
		var currentMinutes = d.getMinutes();
		
		
		
		var jsLocal = currentHours*60 + currentMinutes;
		var phpLocal = '.$phplocal.';
		
		var maxAllowedDif = '.$maxAllowedDif.';				
		
		difference_in_minutes = Math.abs(jsLocal - phpLocal);				
		
		//alert("diference: " + difference_in_minutes + "\nphpLocal:"+ phpLocal + "\n_jsLocal: "+ jsLocal);
		
		// ignores big differences as being 23 to 00 hour
		if(difference_in_minutes > 60*12){
			difference_in_minutes = 0;
		}		
			
		if (difference_in_minutes > maxAllowedDif){
			//alert("Server time is wrong");			
			document.getElementById("divjsCT").innerHTML=\''.$HTMLWrong.'\';
		}	
		else{
			//alert("Server time is OK! " + difference_in_minutes);
			document.getElementById("divjsCT").innerHTML=\''.$HTMLOK.'\';
			
		}
		
	}		
		
	</script>
	
	
	
	';


	return $jsCT;
}












# create a button using only HTML, for donation.
function pts_donateHTMLButton($url){
	
	global $pts_show_donate;
	
	if(!$pts_show_donate){
		return '';
	}
	
	$butdon = 
	'
	
	<div 
	style="
	background-color:#FFD879;
	height:16px;
	width:80px;
	border-radius: 7px;
	text-align:center;
	color:#305958;
	font-weight:bold;
	float:right;
	">	
	<a target="_blank" href="'.$url.'" title="'
		.__('Please donate. Even 1 dollar will help me a lot! Seriously!',  'pts').
		'">	
		'.__('Donate',  'pts').'	
		</a>
	</div>
	
	';
	return $butdon;
}












# show information near the publish button...
function pts_postInfo(){
	global $post;	
	global $pts_donateURL;
	global $plName;
	global $pts_debug;
	
	
	if($pts_debug){
		echo '<div class="misc-pub-section misc-pub-section-last">';
		echo '<div style="margin: 0 0 5px 0">';
		echo '<strong style="color:red;">'.$plName.' - <span style="text-decoration:blink">Debug active!</span></strong>';
		echo '</div>';
		echo '</div>';		
	}
	
	
	# do not show info for published posts...
	if($post->post_status == 'publish'){
		return;
	}	
	
	# do not show info for scheduled posts...
	if($post->post_status == 'future'){
		return;
	}	

	# do not show info for pages...
	if($post->post_type != 'post'){
		return;
	}	
	
	
	
	# only change the text of publish button when plugin is active...
	add_filter( 'gettext', 'pts_change_publish_button', 10, 2 );	
	
	
	# insert Google analytics code to monitor plugin usage.
	add_action('admin_footer', 'pts_insertAnalyticsCode',12);

	
	
	
	
	echo '<div class="misc-pub-section misc-pub-section-last" style="font-size:11px;">';
	
	
	echo '<div style="margin: 0 0 5px 0">';
	echo '<strong title="'.__('Plugin version','pts').': '.pts_get_version().'">'.$plName.'</strong>';
	
	
	
		
	# show donate button	
	echo pts_donateHTMLButton($pts_donateURL);
	
	
	
	echo '</div>';
	

	# if time is wrong... warn...



	
	# show diferent messages for admin and non admin users...
	if(current_user_can('install_plugins')){
		$msgTimeWrong = '<div style="margin: 0 0 7px 0"><span style="color:red">'.
		__('Your WordPress timezone settings might be incorrect!','pts').
		'</span>  ( <a href="options-general.php?page=publish-to-schedule/publish-to-schedule.php" target="_blank">'.
		__('See details','pts').'</a> )</div>';
	}
	else{
		$msgTimeWrong = '<div style="margin: 0 0 7px 0"><span style="color:red">'.
		__('Your WordPress timezone settings might be incorrect!','pts').
		'</span>  ( '.
		__('Please tell the blog admin!','pts').
		'</a> )</div>';
			
	}
	
	
	 
	
	
	/*
	<a href="">'
	.__('details!','').
	'</a>';
	$msgTimeOK = '';
	*/
	
	echo pts_createJsToCompareTime($msgTimeWrong,$msgTimeOK);					
	# div usada para reportar hora incorreta...		
	echo '<div style="padding-left:20px;" id="divjsCT"></div>';
	
	echo '<script type="text/javascript">	
			jsCompareTimes();
		</script>';
	
	echo pts_findNextSlot($post);		
	
	
	echo '</div>';
}
add_action( 'post_submitbox_misc_actions', 'pts_postInfo' );









function pts_getMaxPostsDay($datetimeCheck){

	global $options;
	

	# id day of week is allowed... (replaces <BBB>)
	$opt = 'pts_'.date('w',$datetimeCheck);
	
	/*
	print_r($datetimeCheck);	
	print_r($options);
	print_r($opt);
	echo '<br>';
	*/
	
	# translate the old style option  no\yes para 0\1+
	if($options[$opt] == 'no'){
		return 0;	
	}
	if($options[$opt] == 'yes'){
		return 1;	
	}
	if($options[$opt] != ''){
		return $options[$opt];
	}
	else{
		return 1;
	}	
}









# return the next date and time for post.
function pts_findNextSlot($post,$changePost = False){
	global $wpdb;
	global $table_prefix;
	global $pts_debug;
	

	# if is a draft or pending with a date in future, means that it were published already, mas back to draft or pending...
	if(($post->post_status == 'draft') or ($post->post_status == 'pending')){		
		if(   strtotime($post->post_date)   >     strtotime(date(current_time('mysql', $gmt = 0)))    ){
			$msg = '';
			$msg .= __('Post already scheduled for a future date!',  'pts');
			$msg .= '<br>';
			$msg .= __('In this case, the plugin will do nothing!',  'pts');
			if($changePost == False){
				return $msg;			
			}
			else{
				return null;
			}			
		}		
	}

	
	# load plugin configurations...	
	$options = get_option(basename(__FILE__, ".php"));

	# get start and end minutes from 0 to 1440-1
	$startMinute =  date('H',strtotime($options['pts_start'])) * 60 + date('i',strtotime($options['pts_start']));;
	$endMinute = date('H',strtotime($options['pts_end'])) * 60 + date('i',strtotime($options['pts_end']));;

	$msg = '';
	
	
	# dates from today...
	$startDate = date('Ymd', strtotime(current_time('mysql', $gmt = 0)));
	
	
	
	if($pts_debug and True){
		$msg .= 'DEBUG: $startDate = ' . $startDate . '<br>';
	}
	
	if($pts_debug and True){
		$msg .= 'DEBUG: $options = ' . print_r($options,True) . '<br>';		
	}
	
	
	
	$sql = '
		select 
			ID,			
			DATE_FORMAT(post_date,"%Y%m%d") as dtfind,
			post_author,
			post_date,
			post_date_gmt,
			post_title,
			post_status,
			guid 
		from '. $table_prefix . 'posts 
		where ID <> '.$post->ID.' 
			and post_status in ("publish","future") 
			and post_type = "post" 
			and post_date >= "'. $startDate .'" 
			order by post_date ASC
		
	
	';	
	$recentPosts = $wpdb->get_results($sql);
	
	$maxDaysFuture = 5000;


	if($pts_debug and True){
		$msg .= $sql;		
	}

	
	
	
	# next dates allowed to publish...
	for($offset=0;$offset<$maxDaysFuture;$offset+=1){
		
		# must be set every run of this for...
		$cssDayAllowed = 'color:green; text-decoration:none;';
		$cssDayForbid = 'color:red; text-decoration:line-through;';
		$cssDayTooLate = 'color:green; text-decoration:line-through;';
	
		$datetimeCheck = strtotime(current_time('mysql', $gmt = 0) . ' + '.$offset.' days');	
		$dt = date("Ymd",$datetimeCheck);				
		$msg .=  '' . date(get_option('date_format'),$datetimeCheck) . ' - <span style="<BBB>"> '.__(date("l",$datetimeCheck),'pts').'</span><CCC><DDD><EEE><br>';
		

		$maxPostsThisDay = pts_getMaxPostsDay($datetimeCheck);
		$nPostsDay = 0;

	
	
		# if there are no posts in the day...
		if(count($recentPosts)){
			
			
			
			$thereArePosts = False;
			
			foreach($recentPosts as $rp){
				
				if($rp->dtfind == $dt){					
					$thereArePosts = True;
					$nPostsDay += 1;
							
					
					# garante o agendamento para hoarios posteriores no mesmo dia.		
					#$startMinute =  date('H',$rp->post_date) * 60 + date('i',$rp->post_date);
					#echo date('i',$rp->post_date);
					#echo $startMinute . '<br>';							
					
					#break;
				}
			}
			
			
			
			
			
			if($nPostsDay >= $maxPostsThisDay){
				
				$msgThereIsPost	= '';
				
				if(($nPostsDay == 1) & ($maxPostsThisDay == 1)){		
					$msgThereIsPost .= ' | ' . __('post at ','pts');
					$msgThereIsPost .= ' ';				
					$msgThereIsPost .= '<a title="'.__('Edit post',  'pts').' : '.$rp->post_title.
						'" target="_blank" href="post.php?post='.$rp->ID.'&action=edit">'.
					date(get_option('time_format'),strtotime($rp->post_date)).'</a>';
				}
				else{
					$msgThereIsPost .= " ($nPostsDay "  . __('of','pts') . ' '. "$maxPostsThisDay)";
				}
				 
				$msg = str_replace('<CCC>',$msgThereIsPost,$msg);				
				# default style for positive day of week
				$msg = str_replace('<BBB>',$cssDayAllowed,$msg);				
				$msg = str_replace('<EEE>','',$msg);
				
				continue;
			}
			else{
				$msg = str_replace('<CCC>','',$msg);
			}
		}
		else{
			$msg = str_replace('<CCC>','',$msg);
		}
		
	

	
		
		
				
		if($nPostsDay >= $maxPostsThisDay){			
			# change style for not allowed
			$msg = str_replace('<BBB>',$cssDayForbid,$msg);
			$msg = str_replace('<EEE>','',$msg);
			continue;
		}	
	
		#choose the start time that will be used to sort the post time...
		$startSort = $startMinute;	
		
		/*
		if($pts_debug and True){
			$msg .= 'DEBUG: $dt = ' . $dt . '<br>';
			$msg .= 'DEBUG: date("Ymd",$startDate) = ' . date("Ymd",strtotime($startDate)) . '<br>';
		}
		*/
		
		
		$msgDayAvailble = '';
		
		$msgDayAvailble .= " (   $nPostsDay  "  . __('of','pts') . ' '. "$maxPostsThisDay ) ";
		
		$msgDayAvailble .= ' | <strong>' . __('Availble day!','pts') . '</strong>';				
		
		# if the day is today... check to see if there is time to publish within the time window configured...
		if($dt == date("Ymd",strtotime($startDate))){
			#$msg .=  '- esta data e hoje! Ainda da tempo?<br>';
			# http://codex.wordpress.org/Function_Reference/current_time
			$nowLocal = current_time('mysql', $gmt = 0); 
			# gete user local time in minutes...
			$nowTotalMinutes =  date('H',strtotime($nowLocal)) * 60 + date('i',strtotime($nowLocal));;
			
			if($nowTotalMinutes > $endMinute){
				$msgTooLateToday = ' | ' . __('Too late to publish','pts');				
				$msg = str_replace('<BBB>',$cssDayTooLate,$msg);
				$msg = str_replace('<DDD>',$msgTooLateToday,$msg);
				
				#$msg .=  '- Hoje mas ja passou da hora de publicar.<br>';
				continue;
			}			
			if($nowTotalMinutes < $startMinute){
				#$msg .=  '- OK! Artigo sera agendado. <br>';
				$msg = str_replace('<EEE>',$msgDayAvailble,$msg);
			}			
			if($nowTotalMinutes >= $startMinute){
				#$msg .=  '- OK! Artigo sera agendado. <br>';
				$msg = str_replace('<EEE>',$msgDayAvailble,$msg);
				$startSort = $nowTotalMinutes;
			}						
		}
		else{
			$msg = str_replace('<EEE>',$msgDayAvailble,$msg);
			#$msg .=  '- OK! Artigo sera agendado. <br>';
		}		
		
		$msg = str_replace('<BBB>',$cssDayAllowed,$msg);
		
		
		
		
		
		
		# replaces if were not replaced before...
		$msg = str_replace('<DDD>','',$msg);
		
		
		# find the time... randon!
		# even not necessary... but using seed for rand... 
		# using post-id to guarante the same time after click post...
		# http://www.php.net/manual/pt_BR/function.srand.php		
		srand(intval(sqrt($post->ID) * 10000));
				
		$minutePublish = rand($startSort,$endMinute);		
		if($minutePublish==0){
			#avoid divide by zero on module (%)...
			$minutePublish += 1;
		}				
		
		
		/*
		if($pts_debug and False){			
			$msg .= 'DEBUG: $datetimeCheck = ' . date("Ymd",$datetimeCheck) . '<br>';
			$msg .= 'date("Ymd",strtotime($startDate)) = ' .  date("Ymd",strtotime($startDate))  . '<br>';
		}
		*/
		
		
		# if next date is today... and it is the first post... publish 3 minute in future!
		if((date("Ymd",$datetimeCheck) == date("Ymd",strtotime($startDate)) & ($nPostsDay == 0))){
			$minutePublish = $startSort + 3;
		}
		
		$dthrPublish = date("Y-m-d",$datetimeCheck) .' '.  intval($minutePublish/60) .':'. $minutePublish%60;		
		
		
		/*
		if($pts_debug){
			# sets the publish time to 1 minute in the future... to test cron!
			#$dthrPublish = date('Y-m-d H:i',strtotime(current_time('mysql', $gmt = 0) . ' + 65 minutes'));						
		}
		*/
		
		
		
		
		# parcial message... not complete.
		$msgT = '';				
		
		$msgByPass =  __('To publish in a different date and bypass the plugin, first choose the schedule date from the WordPress controls above and then click the Schedule button!',  'pts');
		
		#$msgByPass = '<span style="font-size:11px;">' .  $msgByPass . '</span>';		
		#$msgT .= '<br>';		
		

		$msgT .=  '<p title="'.$msgByPass.'">';
		$msgT .= __('Will be schedule to','pts') . ': <br>';
		$msgT .= '<strong>';
		$msgT .= __(date("l",strtotime($dthrPublish)),'pts') . ', ' . date(get_option('date_format'),strtotime($dthrPublish)) . ' '. __('at','') .' ' . date(get_option('time_format') , strtotime($dthrPublish));
		$msgT .= '</strong>';
		$msgT .= '</p>';				
		
		#$msgT .= '<br>';		
	
	
		# uses only to debug and show logs on main screen...
		if(!$changePost){		
			if($options['pts_infosize'] == 'all'){
				return $msg . $msgT;
			}
			else{
				return $msgT;
			}
			
		}
		else{
			# statistics to show how many post the plugin helps to schedule...
			if(array_key_exists('pts_statistics_total_work',$options)){
				$options['pts_statistics_total_work'] = $options['pts_statistics_total_work'] + 1;
			}
			else{
				$options['pts_statistics_total_work'] = 1;
			}
			update_option(basename(__FILE__, ".php"), $options);
			return $dthrPublish;		
		}
	}		
	
	if(!$changePost){
		return 
			__('Could not find a suitable date to this post!','pts').'<br>'.
			__('No changes will be made to this post date!','pts').'<br>'.
			__('Something is wrong!','pts').'<br>'.
			__('Please contact the plugin developer!','pts');
	}
	else{
		return null;		
	}
	
}













# this is where the magic happens... :)
function pts_do_publish_schedule($post){
	global $wpdb;
	global $pts_debug;	
	
	$newDate = pts_findNextSlot($post,True);

	# do nothing if cant find date...
	if($newDate == null){
		return $post;
	}

	# do nothing for pages! Should act only on post!
	if($post->post_type != 'post'){
		return;
	}	
	
	
	
	# changes post_date and pos_date_gmt
	$post->post_date = $newDate;
	
	# sum the timezone offset to get the right gmt time...
	$gmt_offset = get_option('gmt_offset') * (-1);
	
	# treatment to deal with things like GMT-2:30...
	$gmt_offsetHours = intval($gmt_offset); 
	$gmt_offsetMinutes = ($gmt_offset - $gmt_offsetHours) * 60;	
	# add the plus signal to concatenate on string time math below... the minus comes by default...
	if($gmt_offsetHours > 0){
		$gmt_offsetHours = '+'.$gmt_offsetHours; 
	}
	if($gmt_offsetMinutes > 0){
		$gmt_offsetMinutes = '+'.$gmt_offsetMinutes; 
	}
	
	$post->post_date_gmt =  date('Y-m-d H:i:s', strtotime($newDate .' ' .$gmt_offsetHours. ' hours ' .$gmt_offsetMinutes. ' minutes' ));
	
	if($pts_debug){
		echo 'Date and GMT date for the new post:<br>';
		echo $post->post_date;
		echo '<br>';
		echo $post->post_date_gmt;
		echo '<br>';
	}
	
	
	
	# changes post_status to be scheduled...
	$post->post_status = 'future';
	
	wp_update_post($post);		
	return $post;	
}












#	Define the options menu
function pts_option_menu() {
	global $plName;	
	if (function_exists('current_user_can')) {
		if (!current_user_can('manage_options')) return;
	} else {
		global $user_level;
		get_currentuserinfo();
		if ($user_level < 8) return;
	}
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
add_option(basename(__FILE__, ".php"), $default_options);

// This method displays, stores and updates all the options
function pts_options_page(){
	global $wpdb;
	global $plName;
	global $plUrl;
	global $pts_debug;

	# insert Google analytics code to monitor plugin usage.
	add_action('admin_footer', 'pts_insertAnalyticsCode',12);
	
	
	$bit = explode("&",$_SERVER['REQUEST_URI']);
	$url = $bit[0];
	$action = $bit[1];
	$id = $bit[2];
	// This bit stores any updated values when the Update button has been pressed
	if (isset($_POST['update_options'])) {
		
		# loads before change with post values...
		$options = get_option(basename(__FILE__, ".php"));
		
		// Fill up the options array as necessary					
		$options['pts_start'] = $_POST['pts_start']; // like having business hours
		$options['pts_end'] = $_POST['pts_end'];		
		
		$options['pts_0'] = $_POST['pts_0'];
		$options['pts_1'] = $_POST['pts_1'];
		$options['pts_2'] = $_POST['pts_2'];
		$options['pts_3'] = $_POST['pts_3'];
		$options['pts_4'] = $_POST['pts_4'];
		$options['pts_5'] = $_POST['pts_5'];
		$options['pts_6'] = $_POST['pts_6'];
		
		$options['pts_infosize'] = $_POST['pts_infosize'];
		
		$options['pts_allowstats'] = $_POST['pts_allowstats'];
		
		
		# if all weeks are NO... change the monday to YES
		$allNo = 0;
		for($i=0;$i<7;$i++){
			if($options['pts_'.$i] == 'no'){
				$allNo += 1;
			}
			else{
				break;
			}
		}
		if($allNo == 7){
			$options['pts_1'] = 'Yes';
		}
		
		
		
		while (strlen($options['pts_start']) < 5) $options['pts_start'] = "0" . $options['pts_start'];
		while (strlen($options['pts_end']) < 5) $options['pts_end'] = "0" . $options['pts_end'];		
		if (!gmdate('H:i',$options['pts_start'])) $options['pts_start'] = '00:00'; //guarantee a valid time
		if (!gmdate('H:i',$options['pts_end'])) $options['pts_end'] = '23:59';
		$time = explode(":",$options['pts_start']);
		if (strlen($time[0]) < 2) $time[0] = '0' . $time[0];
		if (strlen($time[1]) < 2) $time[1] = '0' . $time[1];
		$options['pts_start'] = date("H:i",mktime($time[0],$time[1],0,9,11,2001)); // convert overruns
		$time = explode(":",$options['pts_end']);
		if (strlen($time[0]) < 2) $time[0] = '0' . $time[0];
		if (strlen($time[1]) < 2) $time[1] = '0' . $time[1];
		$options['pts_end'] = date("H:i",mktime($time[0],$time[1],0,9,11,2001));
		
		// store the option values under the plugin filename
		update_option(basename(__FILE__, ".php"), $options);
		
		// Show a message to say we've done something
		if($allNo == 7){
			echo '<div class="updated"><p>' . __('You must check "Yes" for at least 1 day of week! ', 'pts') . '</p></div>';	
		}
		else{
			echo '<div class="updated"><p>' . __('Options saved!', 'pts') . '</p></div>';	
		}		
		
	} else {
		$options = get_option(basename(__FILE__, ".php"));
	}
	
	
	
	
	
	
	
	
	
	
	

	
	# OPTIONS SCREEN
	//now we drop into html to display the option page form
	?>
		<div class="wrap">
		
		
		<h2 title="<?php 
		_e('Plugin version','pts');
		echo ': ';
		echo pts_get_version() 
		?>"><?php echo ucwords(str_replace('-', ' ', basename(__FILE__, ".php"))) .' - '. __('Options', 'pts'); ?></h2>
		


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
			$days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
			
			/*
			$a = _e('Sunday','pts');
			$a = _e('Monday','pts');
			$a = _e('Tuesday','pts');
			$a = _e('Wednesday','pts');
			$a = _e('Thursday','pts');
			$a = _e('Friday','pts');
			$a = _e('Saturday','pts');
			*/
			
		?>
		
		
		<table>	
			
			<?php
			$iday = 0;
			foreach($days as $day){
				#echo $day;
				
			?>
				
				<tr valign="top">
					<th scope="row" align="left" style="padding:5px;"><?php _e(ucfirst($day), 'pts') ?>:</th>
					
					<td style="padding:5px;">					
						<input 
							type="text" 
							id="<?php echo $day; ?>"
							name="<?php echo "pts_$iday"; ?>" 
							value="<?php if ($options["pts_$iday"] == 'no') echo '0'; else if ($options["pts_$iday"] == 'yes') echo '1'; else echo $options["pts_$iday"]; ?>" 
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
				<th scope="row" align="left"><?php _e('Start Time', 'pts') ?>:</th>
				<td><input name="pts_start" type="text" id="start" value="<?php echo $options['pts_start']; ?>" size="10" /><?php _e(' (defaults to 00:00)', 'pts') ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" align="left"><?php _e('End Time', 'pts') ?>:</th>
				<td><input name="pts_end" type="text" id="end" value="<?php echo $options['pts_end']; ?>" size="10" /><?php _e(' (defaults to 23:59)', 'pts') ?>
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
					<input type="radio" name="pts_infosize" id="pts_infosize_all" value="all"<?php if ($options['pts_infosize'] == 'all') echo ' checked'; ?>>
					<?php 
					_e(' Show all information availble!','pts'); 
					echo '<br/>';
					_e(' I want to see how this plugin works!','pts'); 
					echo '<br/>';
					_e(' (Might be a lot of text! Good for debugging purposes or enthusiats!)', 'pts'); 
					 ?>
					 />					
				</td>
				
				<tr valign="top">
				<td style="padding:5px;">
					<input type="radio" name="pts_infosize" id="pts_infosize_parcial" value="parcial"<?php if ($options['pts_infosize'] != 'all') echo ' checked'; ?>>
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
			
			$langAvailble = array();
			array_push($langAvailble,'en','pt-BR');
			if(! in_array(get_bloginfo('language'),$langAvailble)){
				echo '<li><a target="_blank" href="'.$plUrl.'">'.__('Help with translation!','pts').'</a>: <br>'.
					
					__('We dont have this plugin translated to your language yet!','pts').
					' ('.
					get_bloginfo('language').
					' )'.
					'<br>'.
					
					__('The languages already translated are: ','pts');
					echo '<li>';
					echo '<ol>';
					foreach($langAvailble as $lang){
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
			<input type="radio" name="pts_allowstats" id="pts_allowstats" value="yes" <?php if ($options['pts_allowstats'] != 'no') echo ' checked'; ?>/><?php _e('Yes', 'pts') ?>
			<input type="radio" name="pts_allowstats" id="pts_allowstats" value="no" <?php if ($options['pts_allowstats'] == 'no') echo ' checked'; ?>/><?php _e('No', 'pts') ?>
		</td>
		
				
		<br/>


		
		

		<?php		
		if($options['pts_statistics_total_work'] > 3){
			echo '<h3 style="margin-top:20px;">'.__('Did it save you a lot of time?','pts').'</h3>';
			if($options['pts_statistics_total_work'] > 20){
				echo __('Ohh yes... it certainly did!','pts');
				echo '<br>';
			}
			echo __('Since you installed this plugin','pts');
			echo ', ';
			echo '<strong>';
			echo $options['pts_statistics_total_work'];
			echo ' ';
			echo __(' posts were automatically scheduled, saving your time!', 'pts');
			//echo __(' posts were automatically scheduled, saving your time! So...', 'pts');
			echo '</strong>';
			echo '<br>';
			echo '<br>';
		}
		


		
		if($pts_show_donate){
			global $pts_donateURL;
			#echo '...';
			echo '<a href="'.$pts_donateURL.'" style="font-weight:bold;font-size:15px;" 
			title="Donate some money (opens in a new window)!" 
			target="_blank">';		
			echo __('Consider making a donation',  'pts');			
			echo '</a> ? ';			
			echo '<br>';
			echo '<br>';
			echo __('Trust-me - even 1 dollar will make me happy... but you can choose any amount! :)',  'pts');
		}		
		
		?>			
		
		<div class="submit"><input type="submit" name="update_options" value="<?php _e('Save all changes', 'pts') ?>"  style="font-weight:bold;" /></div>		
		</form>		
		
	</div>
	<?php	
}
$options = get_option(basename(__FILE__, ".php"));




















	
// Add settings link on plugin page
function pts_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page='.plugin_basename(__FILE__).'">' . __('Settings','pts') .'</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
} 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'pts_settings_link' );	

	
	



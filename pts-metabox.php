<?php


# Meta box on post edit page
function pts_post_metabox(){
    add_meta_box(
        // Unique ID
        'pts_post_metabox',
        // Box title
        'Publish to Schedule',
        // Content callback, must be of type callable
        'pts_post_metabox_callback',
        // Page type to create meta page
        'post',
        // Put metabox at the left side
        'side'
    );
}


add_action('add_meta_boxes', 'pts_post_metabox');



# Show data on sidebar from publish to schedule plugin, now compatible with Guttenberg!
function pts_post_metabox_callback($post){
    ?>
    <div id="pts_post_metabox">
        <?php
        pts_postInfo();
        ?>
    </div>
    <?php
}







# change the name of the publish button on post screen
function pts_change_publish_button($translation, $text) {
    if ($text == 'Publish') {
        return __('Pub. to Schedule', 'pts');
    }
    return $translation;
}






# show information near the publish button...
function pts_postInfo(){
	global $post;
	global $pts_donateURL;
	global $plName;
	global $pts_debug;



    // if(gutenberg_is_active()){
    //     echo "Gutenberg editor not supported!<br>
    //     Classic editor is much more user friendly!";
    //     return;
    // }


	if($pts_debug){
		echo '<div class="misc-pub-section misc-pub-section-last">';
		echo '<div style="margin: 0 0 5px 0">';
		echo '<strong style="color:red;">'.$plName.' - <span style="text-decoration:blink">Debug active!</span></strong>';
		echo '</div>';
		echo '</div>';
	}


    $show_elements = True;

    # do not show info for published posts...
    if($post->post_status == 'publish'){
        echo 'Post is already published';
        $show_elements = False;
    }

    # do not show info for scheduled posts...
    if($post->post_status == 'future'){
        echo 'Post is already scheduled';
        $show_elements = False;
    }

    # do not show info for pages...
    if($post->post_type != 'post'){
        echo 'Page schedule is not available';
        $show_elements = False;
    }

    // if(!$show_elements){
        echo '<div
            style="
                height:120px;
            ">';
        echo 'Saving you time since 2011!';
        echo '<br />';
        echo 'How many hours saved already?';
        echo '<br />';
        echo '<br />';
        echo pts_donateHTMLButton(pts_donateRandomValueString());
        echo '</div>';
        // return;
    // }


	# only change the text of publish button when plugin is active... (uses translation to do it)
	add_filter( 'gettext', 'pts_change_publish_button', 10, 2 );

	# action to insert Google analytics code to monitor plugin usage.
	add_action('admin_footer', 'pts_insertAnalytics',12);


	echo '<div class="misc-pub-section misc-pub-section-last" style="font-size:11px;">';


	echo '<div style="margin: 0 0 5px 0">';


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



    echo pts_createJsToCompareTime($msgTimeWrong,'');

	# report wrong time or wrong timezone selected on WordPress if compared with with server time.
    echo '<div style="padding-left:20px;" id="divjsCT"></div>
        <script type="text/javascript">
			jsCompareTimes();
        </script>
    ';

	echo pts_findNextSlot($post);

    echo '</div>';


    echo 'Hit the Publish button to <strong>schedule</strong> this post!';
}

# removed as it does now work on gutenberg new editor.
#add_action( 'post_submitbox_misc_actions', 'pts_postInfo' );

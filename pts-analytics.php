<?php


# insert Google Analytics code to monitor plugin utilization.

function pts_insertAnalytics($getCode = False) {

    $pts_options = get_option(PTS_OPTION_NAME);

    # do not collect statististcs if now allowed... 	
    if ($pts_options['pts_allowstats'] == 'No') {
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
		_gaq.push(['_setCustomVar', 1,'Site URL','" . get_option('home') . "', 1 ]);
		_gaq.push(['_setCustomVar', 2,'Articles scheduled','" . $pts_options['pts_statistics_total_work'] . "',1]); 	
		_gaq.push(['_setCustomVar', 3,'WP Language','" . get_bloginfo('language') . "',1]);
	</script>";


    if ($getCode) {
        return $analyticsCode;
    } else {
        print $analyticsCode;
    }
}

<?php



# create a button using only HTML, for donation.
function pts_donateHTMLButton($float = 'right'){

	
    global $pts_show_donate;
	
	if(!$pts_show_donate){
		return '';
	}
	
	$donate_button = 
	'
	
	<div 
	style="
	    background-color:#FFD879;
	    height:22px;
        width:80px;
        font-size: 18px;
        padding: 4px;
	    border-radius: 3px;
	    text-align:center;
        color:#305958;
	    font-weight:bold;
        float:'.$float.';
        margin: 4px 10px 10px 0;
        
	">	
	<a target="_blank" href="'.PTS_DONATE_URL.'" title="'
		.__('Please donate. Even 1 dollar will help me a lot! Seriously!',  'pts').
		'">	
		'.__('Donate',  'pts').'	
		</a>
	</div>

	';
	return $donate_button;
}



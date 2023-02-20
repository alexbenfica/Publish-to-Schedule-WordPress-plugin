<?php

function pts_donateRandomValueString(){
	return number_format(rand(190,330)/100, 2, '.', '');
}


# create a button using only HTML, for donation.
function pts_donateHTMLButton($howMuch){
    global $pts_show_donate;

	if(!$pts_show_donate){
		return '';
	}

	// $donate_button ='
	// <script
	// 	type="text/javascript"
	// 	src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js"
	// 	data-name="bmc-button"
	// 	data-slug="FQNxAqVUTo"
	// 	data-color="#FFDD00"
	// 	data-emoji="🍕"  data-font="Cookie"
	// 	data-text="Buy me a pizza ($'. $howMuch .')"
	// 	data-outline-color="#000000"
	// 	data-font-color="#000000"
	// 	data-coffee-color="#ffffff"
	// ></script>
	// ';

	$donate_button ='<a
		href="https://www.buymeacoffee.com/FQNxAqVUTo"
		target="_blank"
	>
		<img src="https://img.buymeacoffee.com/button-api/?text=Buy me a coffee&emoji=&slug=FQNxAqVUTo&button_colour=FFDD00&font_colour=000000&font_family=Comic&outline_colour=000000&coffee_colour=ffffff" />
	</a>';
	return $donate_button;
}

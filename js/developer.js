 jQuery(document).ready(function() {
   jQuery('#page').after('<div id="responsive-helper"><span id="width">Width</span> x <span id="height">Height</span></div>');
 });

jQuery(window).resize(function() {

	var the_width = jQuery(window).width();
	var the_height = jQuery(window).height();

	jQuery('#width').text(the_width);
	jQuery('#height').text(the_height);

});	
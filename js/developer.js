var start_width = jQuery(window).width();
var start_height = jQuery(window).height();

jQuery(document).ready(function() {
  jQuery('#page').after('<div id="responsive-helper"><span id="width">' + start_width + '</span> x <span id="height">' + start_height +'</span></div>');
});

jQuery(window).resize(function() {

	var the_width = jQuery(window).width();
	var the_height = jQuery(window).height();

	jQuery('#width').text(the_width);
	jQuery('#height').text(the_height);

});	
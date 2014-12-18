jQuery(document).ready(function(){
	jQuery('#mc_message').css({
		position: 'absolute',
		margin: '3px 0 0 -415px'
	});
	jQuery('#mc_message').hover(function(){
		jQuery(this).fadeOut('slow');
	});
});
<?php
global $avia_config;

##############################################################################
# Display the sidebar
##############################################################################

$default_sidebar = true;
$sidebar_pos = avia_layout_class('main', false);
$sidebar = "";

if(strpos($sidebar_pos, 'sidebar_left')  !== false) $sidebar = 'left';
if(strpos($sidebar_pos, 'sidebar_right') !== false) $sidebar = 'right';

//filter the sidebar position (eg woocommerce single product pages always want the same sidebar pos)
$sidebar = apply_filters('avf_sidebar_position', $sidebar);

//if the layout hasnt the sidebar keyword defined we dont need to display one
if(empty($sidebar)) return;



echo "<div class='sidebar sidebar_".$sidebar." ".avia_layout_class( 'sidebar', false )." units'>";

	echo "<div class='inner_sidebar extralight-border'>";

	//Display a subnavigation for pages that is automatically generated, so the users doesnt need to work with widgets
	$av_sidebar_menu = avia_sidebar_menu(false);
	if($av_sidebar_menu)
	{
		echo $av_sidebar_menu;
		$default_sidebar = false;
	}
	
	
	$the_id = @get_the_ID();
	$custom_sidebar = "";
	if(!empty($the_id) && is_singular())
	{
		$custom_sidebar = get_post_meta($the_id, 'sidebar', true);
	}

	if($custom_sidebar)
	{
		dynamic_sidebar($custom_sidebar);
		$default_sidebar = false;
	}
	else
	{
		if(empty($avia_config['currently_viewing'])) $avia_config['currently_viewing'] = 'page';

		// general shop sidebars
		if ($avia_config['currently_viewing'] == 'shop' && dynamic_sidebar('Shop Overview Page') ) : $default_sidebar = false; endif;

		// single shop sidebars
		if ($avia_config['currently_viewing'] == 'shop_single') $default_sidebar = false;
		if ($avia_config['currently_viewing'] == 'shop_single' && dynamic_sidebar('Single Product Pages') ) : $default_sidebar = false; endif;

		// general blog sidebars
		if ($avia_config['currently_viewing'] == 'blog' && dynamic_sidebar('Sidebar Blog') ) : $default_sidebar = false; endif;

		// general pages sidebars
		if ($avia_config['currently_viewing'] == 'page' && dynamic_sidebar('Sidebar Pages') ) : $default_sidebar = false; endif;

		// forum pages sidebars
		if ($avia_config['currently_viewing'] == 'forum' && dynamic_sidebar('Forum') ) : $default_sidebar = false; endif;

	}

	//global sidebar
	if (dynamic_sidebar('Displayed Everywhere')) : $default_sidebar = false; endif;



	//default dummy sidebar
	if ($default_sidebar)
	{
		 avia_dummy_widget(2);
		 avia_dummy_widget(3);
		 avia_dummy_widget(4);
	}

	echo "</div>";

echo "</div>";







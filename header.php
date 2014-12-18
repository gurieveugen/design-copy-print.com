<?php
	global $avia_config;

	$style 		= $avia_config['box_class'];
	$responsive	= avia_get_option('responsive_layout','responsive');
	$blank 		= isset($avia_config['template']) ? $avia_config['template'] : "";
	$headerS	= !$blank ? avia_header_setting() : "";
	$headerMenu = $responsive ? avia_get_option('header_menu','mobile_drop_down') : "";

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo " html_$style ".$responsive." ".$headerS;?> ">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php

	/*
	 * outputs a rel=follow or nofollow tag to circumvent google duplicate content for archives
	 * located in framework/php/function-set-avia-frontend.php
	 */
	 if (function_exists('avia_set_follow')) { echo avia_set_follow(); }


	 /*
	 * outputs a favicon if defined
	 */
	 if (function_exists('avia_favicon'))    { echo avia_favicon(avia_get_option('favicon')); }

?>


<!-- page title, displayed in your browser bar -->
<title><?php if(function_exists('avia_set_title_tag')) { echo avia_set_title_tag(); } ?></title>


<!-- add feeds, pingback and stuff-->
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="alternate" type="application/rss+xml" title="<?php echo get_bloginfo('name'); ?> RSS2 Feed" href="<?php avia_option('feedburner',get_bloginfo('rss2_url')); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- google fonts -->
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>

<!-- CUSTOM CSS LINKS -->

<link href='http://www.design-copy-print.com/wp-content/themes/enfold/DCP-custom.css' rel='stylesheet' type='text/css'>

<!--[if IE]>
<link href='http://www.design-copy-print.com/wp-content/themes/enfold/custom-styles-IE.css' rel='stylesheet' type='text/css'>
<![endif]-->

<!--[if lte IE 8]>
	<style>
		.logo img { max-height: 35px!important; }
	</style>
<![endif]-->

<!-- mobile setting -->
<?php

if( strpos($responsive, 'responsive') !== false ) echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';

?>


<?php

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */

	wp_head();
?>




</head>




<body id="top" <?php body_class($style." ".$avia_config['font_stack']." ".$blank); ?>>

	<div id='wrap_all'>

		<?php if(!$blank){ ?>

        <div id='header' class=' header_color <?php avia_is_dark_bg('header_color'); echo " ".$headerMenu; ?>'>

		<?php

		$social_args = array('outside'=>'ul', 'inside'=>'li', 'append' => '');

		//subheader, only display when the user chooses a social header
		if(strpos($headerS,'social_header') !== false)
		{
		?>
		<div id='header_meta' class='container_wrap container_wrap_meta'>

		      <div class='container'>
		      <?php
            		/*
            		*	display the themes social media icons, defined in the wordpress backend
            		*   the avia_social_media_icons function is located in includes/helper-social-media-php
            		*/

            		if(strpos($headerS,'bottom_nav_header') === false) avia_social_media_icons($social_args);

            		//display the small submenu
					echo "<div class='sub_menu'>";

					$args = array('theme_location'=>'avia2', 'fallback_cb' => '', 'container'=>'', 'echo' =>false);
					$nav  = wp_nav_menu($args);
					echo $nav;

					$phone = avia_get_option('phone');
					$phone_class = !empty($nav) ? "with_nav" : "";
					if($phone) echo "<div class='phone-info {$phone_class}'>{$phone}</div>";

					/*
					* Hook that can be used for plugins and theme extensions like the wpml language selector
					*/
		      		do_action('avia_meta_header');

					echo "</div>";

            	?>
					<!-- <form action="#" class="form-signup cf">
						<label>Sign up for our newsletter/offers</label>
						<input type="text" value="" placeholder="Email address">
						<input type="submit" value="SIGNUP">
					</form> -->
					<?php get_sidebar( 'forms' ); ?>
		      </div>
		</div>

		<?php } ?>
		
		
					
		<div  id='header_main' class='container_wrap container_wrap_logo'>
				
				<?php do_action('ava_main_header'); ?>
				
				<div class='container'>
				
					<?php 
					/*
					*	display the theme logo by checking if the default logo was overwritten in the backend.
					*   the function is located at framework/php/function-set-avia-frontend-functions.php in case you need to edit the output
					*/
					echo avia_logo(AVIA_BASE_URL.'images/layout/logo.png', false, 'strong');
					
					/*
					* Hook that can be used for plugins and theme extensions like the wpml language selector
					*/
						if(strpos($headerS,'social_header') !== false && strpos($headerS,'bottom_nav_header') !== false) avia_social_media_icons($social_args);
						
					/*
					*	display the main navigation menu
					*   modify the output in your wordpress admin backend at appearance->menus
					*/	
						$extraOpen = $extraClose = "";
						if(strpos($headerS,'bottom_nav_header') !== false){ $extraClose = "</div></div><div id='header_main_alternate' class='container_wrap'><div class='container'>";  }
					 
					 	echo $extraClose;
    					echo "<div class='main_menu' data-selectname='".__('Select a page','avia_framework')."'>";
    					$args = array('theme_location'=>'avia', 'fallback_cb' => 'avia_fallback_menu', 'walker' => new avia_responsive_mega_menu());
    					wp_nav_menu($args); 
    					
    					echo "</div>";
				    ?>
				<!-- end container-->
				</div>
		
				
		
		<!-- end container_wrap-->		
		</div>
		
		<div class='header_bg'></div>

			
	<!-- end header -->		
    </div>

	<?php } //end blank check ?>	
	<div id='main'>

<?php if( is_front_page()): ?>
	<div class="header-banner"><div class="container"><div class="header-banner-inside"><p class="head-p1">design-copy-print, Sussex based graphic design agency</p><p class="head-p2"><span style="color: rgb(66,66,66);">DESIGN SOLUTIONS...</span> RIGHT ON YOUR DOORSTEP.</p></div></div></div>
<?php else: ?>
<div class="header-banner"><div class="container"><div class="header-banner-inside"></div></div></div>
<?php endif ?>
<?php if(is_user_logged_in() && is_front_page()): ?>
<script src="<?php bloginfo('template_directory') ?>/js/mediaelement/jquery.flexslider-min.js"></script>
<script>
	jQuery(function(){
		jQuery('.b-gallery').flexslider({
			selector: ".slides > li",
			animation: "slide",
			slideshowSpeed: 7000,
			animationSpeed: 600,
			controlNav: false,
			prevText: "<",
			nextText: ">"
		});
	});
</script>
<div class="container">
	<div class="b-section-gallery cf">
		<div class="b-gallery">
			<ul class="slides cf">
				<li><a href="#"><img src="http://placehold.it/720x500"></a></li>
				<li><a href="#"><img src="http://placehold.it/720x500"></a></li>
				<li><a href="#"><img src="http://placehold.it/720x500"></a></li>
			</ul>
		</div>
		<ul class="b-images-aside">
			<li><a href="#"><img src="http://placehold.it/300x160"></a></li>
			<li><a href="#"><img src="http://placehold.it/300x160"></a></li>
			<li><a href="#"><img src="http://placehold.it/300x160"></a></li>
		</ul>
	</div>
</div>
<?php endif; ?>
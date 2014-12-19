<?php

class SliderPostType{
    //                                       __  _          
    //     ____  _________  ____  ___  _____/ /_(_)__  _____
    //    / __ \/ ___/ __ \/ __ \/ _ \/ ___/ __/ / _ \/ ___/
    //   / /_/ / /  / /_/ / /_/ /  __/ /  / /_/ /  __(__  ) 
    //  / .___/_/   \____/ .___/\___/_/   \__/_/\___/____/  
    // /_/              /_/                                 
    private $name;
    private $tax_name;
    private $options;
    private $tax_options;

	//                    __  __              __    
	//    ____ ___  ___  / /_/ /_  ____  ____/ /____
	//   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
	//  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
	// /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/  
	public function __construct()
	{
        add_image_size('slider', 720, 500, true);
        add_image_size('slider-min', 300, 160, true);

		$n             = 'Front page slider';
		$this->name    = 'front_page_slider';
		$this->options = array(
            'label'              => $n,
            'singular_name'      => $n,
            'public'             => true,
            'publicly_queryable' => true,
            'query_var'          => true,            
            'rewrite'            => true,
            'capability_type'    => 'post',
            'hierarchical'       => true,
            'menu_position'      => null,
            'supports'           => array('title', 'thumbnail'),
            'has_archive'        => true
        );
		add_action('init', array(&$this, 'registerPostType'));
        add_action('init', array(&$this, 'registerTaxonomy'));
	}	

    public function registerTaxonomy()
    {
        $this->tax_name = 'slider_category';
        $name           = ucwords('Slider category');
        $name_plural    = 'Slider categories';
        
        $labels = array(
            'name'                       => __($name_plural),
            'singular_name'              => __($name),
            'search_items'               => __('Search '.$name_plural),
            'popular_items'              => __('Popular '.$name_plural),
            'all_items'                  => __('All '.$name_plural),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit '.$name),
            'update_item'                => __('Update '.$name),
            'add_new_item'               => __('Add New '.$name),
            'new_item_name'              => __('New '.$name.' Name' ),
            'separate_items_with_commas' => __('Separate '.$name_plural.' with commas' ),
            'add_or_remove_items'        => __('Add or remove '.$name_plural ),
            'choose_from_most_used'      => __('Choose from the most used '.$name_plural ),
            'not_found'                  => __('No '.$name_plural.' found.' ),
            'menu_name'                  => __($name_plural),
        );

        $this->tax_options = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'rewrite'               => array( 'slug' => $this->tax_name ),
            'post_type'             => 'front_page_slider'
        );

        register_taxonomy($this->tax_name,$this->tax_options['post_type'], $this->tax_options);
    }                                             

	/**
     * Registers a new post type in the WP db.
     */
    public function registerPostType()
    {        
       register_post_type($this->name, $this->options);
    }

    public static function getHTML()
    {
        // ==============================================================
        // Slides
        // ==============================================================
        $args_slides = array(
            'posts_per_page'   => -1,
            'offset'           => 0,
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'front_page_slider',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => true,
            'tax_query' => array(
                array(
                    'taxonomy' => 'slider_category',
                    'field'    => 'slug',
                    'terms'    => 'slider'
                )
            )
        );
        $slides = get_posts($args_slides);
        $slides_list = array();
        if(count($slides))
        {
            foreach ($slides as $slide) 
            {
                if(has_post_thumbnail( $slide->ID ))
                {
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider' );
                    $slides_list[] = sprintf('<li><a href="#"><img src="%s" alt="Slide"></a></li>', $thumb[0]);
                }
            }
        }
        // ==============================================================
        // Widgets
        // ==============================================================
        


        ob_start();
        ?>
        <div class="container">
            <div class="b-section-gallery cf">
                <div class="b-gallery">
                    <ul class="slides cf">
                        <?php echo implode('', $slides_list); ?>
                    </ul>
                </div>
                <ul class="b-images-aside">
                    <li><a href="#"><img src="http://placehold.it/300x160"></a></li>
                    <li><a href="#"><img src="http://placehold.it/300x160"></a></li>
                    <li><a href="#"><img src="http://placehold.it/300x160"></a></li>
                </ul>
            </div>
        </div>
        <?php
        
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}
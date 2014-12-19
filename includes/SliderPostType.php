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
        // ==============================================================
        // Actions
        // ==============================================================
		add_action('init', array(&$this, 'registerPostType'));
        add_action('init', array(&$this, 'registerTaxonomy'));
        add_action('admin_init', array(&$this, 'configureMetaBox'));
	}	

    /**
     * Configure meta box    
     */
    public function configureMetaBox()
    {   
        add_action('save_post', array(&$this, 'savePost'));           
        add_meta_box('additional_info', 'Additional info', array(&$this, 'getMetaBox'), 'front_page_slider', 'normal', 'default');
    }

    /**
     * Render meta box
     * PRINT CONTROLS HTML CODE
     * @param  object $post
     * @param  array $data
     */
    public function getMetaBox($post)
    {
        wp_nonce_field(plugin_basename(__FILE__), 'jw_nonce');

        $meta   = get_post_custom($post->ID);      
        $ai_url = isset($meta['ai_url']) ? $meta['ai_url'][0] : '';
        ?>
        <label for="">Slide URL</label>
        <input type="text" name="ai_url" value="<?php echo $ai_url; ?>" class="widefat">
        <?php
    }  

    /**
     * When a post saved/updated in the database, this methods updates the meta box params in the db as well.
     */
    public function savePost($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ($_POST && !wp_verify_nonce($_POST['jw_nonce'], plugin_basename(__FILE__))) return;

        if(isset($_POST['ai_url']))
        {
            update_post_meta($post_id, 'ai_url', $_POST['ai_url']);
        }    
        else
        {
            delete_post_meta($post_id, 'ai_url');
        }
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
                    $url = (string) get_post_meta($slide->ID, 'ai_url', true);
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($slide->ID), 'slider' );
                    $slides_list[] = sprintf('<li><a href="%s"><img src="%s" alt="Slide"></a></li>', $url, $thumb[0]);
                }
            }
        }
        // ==============================================================
        // Widgets
        // ==============================================================
        $args_widgets = array(
            'posts_per_page'   => 3,
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
                    'terms'    => 'image-widgets'
                )
            )
        );
        $widgets = get_posts($args_widgets);
        $widgets_list = array();
        if(count($widgets))
        {
            foreach ($widgets as $widget) 
            {
                if(has_post_thumbnail( $widget->ID ))
                {
                    $url = (string) get_post_meta($widget->ID, 'ai_url', true);
                    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($widget->ID), 'slider-min' );
                    $widgets_list[] = sprintf('<li><a href="%s"><img src="%s" alt="Slide"></a></li>', $url, $thumb[0]);
                }
            }
        }


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
                    <?php echo implode('', $widgets_list); ?>
                </ul>
            </div>
        </div>
        <?php
        
        $var = ob_get_contents();
        ob_end_clean();
        return $var;
    }
}
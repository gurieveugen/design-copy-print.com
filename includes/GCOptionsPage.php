<?php
class GCOptionsPage{
    //                          __              __      
    //   _________  ____  _____/ /_____ _____  / /______
    //  / ___/ __ \/ __ \/ ___/ __/ __ `/ __ \/ __/ ___/
    // / /__/ /_/ / / / (__  ) /_/ /_/ / / / / /_(__  ) 
    // \___/\____/_/ /_/____/\__/\__,_/_/ /_/\__/____/  
    const PARENT_PAGE = 'themes.php';
    //                __  _                 
    //   ____  ____  / /_(_)___  ____  _____
    //  / __ \/ __ \/ __/ / __ \/ __ \/ ___/
    // / /_/ / /_/ / /_/ / /_/ / / / (__  ) 
    // \____/ .___/\__/_/\____/_/ /_/____/  
    //     /_/                              
    private $options;

    //                    __  __              __    
    //    ____ ___  ___  / /_/ /_  ____  ____/ /____
    //   / __ `__ \/ _ \/ __/ __ \/ __ \/ __  / ___/
    //  / / / / / /  __/ /_/ / / / /_/ / /_/ (__  ) 
    // /_/ /_/ /_/\___/\__/_/ /_/\____/\__,_/____/
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        add_submenu_page(self::PARENT_PAGE, __('Theme options'), __('Theme options'), 'administrator', __FILE__, array($this, 'create_admin_page'), 'favicon.ico'); 
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        $this->options = $this->getAllOptions();       

        ?>
        <div class="wrap">
            <?php screen_icon(); ?>                 
            <form method="post" action="options.php">
            <?php                
                settings_fields('gc_options_page');   
                do_settings_sections(__FILE__);
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get all options
     */
    public function getAllOptions()
    {
        return get_option('gcoptions');
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting('gc_options_page', 'gcoptions', array($this, 'sanitize'));
        add_settings_section('default_settings', __('Options'), null, __FILE__); 

        add_settings_field('sign_up_text', __('Sign up text'), array($this, 'sign_up_text_callback'), __FILE__, 'default_settings');
        add_settings_field('sign_up_text_color', __('Sign up Color'), array($this, 'sign_up_text_color_callback'), __FILE__, 'default_settings');
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = array();     

        if(isset($input['sign_up_text'])) $new_input['sign_up_text']     = strip_tags($input['sign_up_text']);
        if(isset($input['sign_up_text_color'])) $new_input['sign_up_text_color']     = strip_tags($input['sign_up_text_color']);

        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sign_up_text_callback()
    {
        printf('<input type="text" id="sign_up_text" name="gcoptions[sign_up_text]" value="%s" />', isset($this->options['sign_up_text']) ? esc_attr($this->options['sign_up_text']) : '');
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function sign_up_text_color_callback()
    {
        printf('<input type="text" id="sign_up_text_color" name="gcoptions[sign_up_text_color]" value="%s" />', isset($this->options['sign_up_text_color']) ? esc_attr($this->options['sign_up_text_color']) : '');
    }
}
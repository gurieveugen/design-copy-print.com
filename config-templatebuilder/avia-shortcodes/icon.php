<?php
/**
 * Textblock
 * Shortcode which creates a text element wrapped in a div
 */
// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

if ( !class_exists( 'av_font_icon' ) )
{
    class av_font_icon extends aviaShortcodeTemplate
    {
        /**
         * Create the config array for the shortcode button
         */
        function shortcode_insert_button()
        {
            $this->config['name']       = 'Font Icon';
            $this->config['order']      = 100;
            $this->config['shortcode']  = 'av_font_icon';
            $this->config['inline']     = true;
            $this->config['html_renderer']  = false;
            $this->config['tinyMCE']    = array('tiny_only'=>true, 'templateInsert'=>'[av_font_icon color="{{color}}" icon="{{icon}}" size="{{size}}"]');
        }

        /**
         * Popup Elements
         *
         * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
         * opens a modal window that allows to edit the element properties
         *
         * @return void
         */
        function popup_elements()
        {
            $this->elements = array(

                array(
                    "name"  => __("Font Icon",'avia_framework' ),
                    "desc"  => __("Select an Icon bellow",'avia_framework' ),
                    "id"    => "icon",
                    "type"  => "iconfont",
                    "font"  => "entypo-fontello",
                    "folder"=> AviaBuilder::$path['assetsURL']."fonts/",
                    "chars" => AviaBuilder::$path['pluginPath'].'assets/fonts/entypo-fontello-charmap.php',
                    "std"   => "1"),

                array(
                    "name"  => __("Icon Color", 'avia_framework' ),
                    "desc"  => __("Here you can set the  color of the icon. Enter no value if you want to use the standard font color.", 'avia_framework' ),
                    "id"    => "color",
                    "type"  => "colorpicker"),

                array(
                    "name"  => __("Icon Size", 'avia_framework' ),
                    "desc"  => __("Enter the font size in px, em or %", 'avia_framework' ),
                    "id"    => "size",
                    "type"  => "input"));
        }


        /**
         * Frontend Shortcode Handler
         *
         * @param array $atts array of attributes
         * @param string $content text within enclosing form of shortcode element
         * @param string $shortcodename the shortcode found, when == callback name
         * @return string $output returns the modified html string
         */
        function shortcode_handler($atts, $content = "", $shortcodename = "", $meta = "")
        {
            //this is a fix that solves the false paragraph removal by wordpress if the dropcaps shortcode is used at the beginning of the content of single posts/pages
            global $post, $avia_add_p;

            $add_p = "";
            if(isset($post->post_content) && strpos($post->post_content, '[av_font_icon') === 0 && $avia_add_p == false && is_singular())
            {
                $add_p = "<p>";
                $avia_add_p = true;
            }

            extract(shortcode_atts(array(
                'icon'     => '1',
                'color'    => '',
                'size'     => ''
            ), $atts));

            $icon_el = $this->elements[0];
            $chars = $icon_el['chars'];
            $font  = $icon_el['font'];
            if(!is_array($chars))
            {
                include($icon_el['chars']);
            }

            $display_char = isset($chars[($icon - 1)]) ? $chars[($icon - 1)] : $chars[0];

            $color = !empty($color) ? "color:{$color};" : '';

            if(!empty($size) && is_numeric($size)) $size .= 'px';
            $size = !empty($size) ? "font-size:{$size};line-height:{$size};" : '';

            //this is the actual shortcode
            $output  = $add_p.'<span class="'.$shortcodename.' avia-font-'.$font.'" style="'.$color.$size.'" >';
            $output .= $display_char;
            $output .= '</span>';


            return $output;
        }

    }
}

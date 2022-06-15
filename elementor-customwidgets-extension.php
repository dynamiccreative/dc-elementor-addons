<?php

/**
* Plugin Name: Elementor Custom Widgets Extension
* Description: DC addons Elementor - Widgets 
* Plugin URI: https://github.com/bastiendc/dc-elementor-addons
* Version: 0.27
* Author: Dynamic Creative
* Author URI: https://www.dynamic-creative.com
* Text Domain: elementor-customwidgets-extension
* Domain Path: /languages
* GitHub Plugin URI: https://github.com/bastiendc/dc-elementor-addons
* Primary Branch: main
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/**
* ELEMENTOR
*/
function add_elementor_widget_categories( $elements_manager ) {
    $elements_manager->add_category(
        'dc-addons',
        [
        'title' => 'DC ADDONS',
        'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'add_elementor_widget_categories' );

/* Languages */
function my_plugin_locale() {
    load_plugin_textdomain('elementor-customwidgets-extension', false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
add_action( 'plugins_loaded', 'my_plugin_locale' );

class Elementor_CustomWidgets_Extension {
    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function init() {
        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [ $this, 'init_widgets' ]);
        //
        add_action('elementor/editor/after_enqueue_scripts', [ $this, 'widget_styles_admin' ]);
        add_action('elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ]);
        add_action('elementor/frontend/before_enqueue_scripts', [ $this, 'widget_scripts' ]);
    }

    public function widget_styles_admin() { 
        wp_enqueue_style( 'dcael-css-admin',  plugin_dir_url( __FILE__ ) . 'admin-el.css' );
    }

    public function widget_styles() { 
        wp_enqueue_style( 'dcael-css',  plugin_dir_url( __FILE__ ) . 'dcael.css' );
    }

    public function widget_scripts() { 
        wp_enqueue_script( 'dcael-js',  plugin_dir_url( __FILE__ ) . 'dcael.js' );
    }

    public function init_widgets() {
        // Include Widget files
        require_once('widgets/loop-post.php' );
        require_once('widgets/widget-icon-text.php' );
        require_once('widgets/widget-icon-text-v2.php' );
        require_once('widgets/widget-bulle.php' );
        require_once('widgets/widget-bloc-carre.php' );
        require_once('widgets/widget-gallery-swiper.php' );
        require_once('widgets/slider-post.php' );
        require_once('widgets/widget-scrollmagic.php' );
        require_once('widgets/widget-category-box.php' );
        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Loop_Post() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Icon_Text() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Icon_Text_v2() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Bulle() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Bloc_Carre() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Gallery_Swiper() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Slider_Post() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Scrollmagic() );
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Categorie_Box() );

    }
}

Elementor_CustomWidgets_Extension::instance()->init();

<?php

/**
 * Plugin Name: Elementor Custom Widgets Extension
 * Description: DC addons Elementor - Widgets 
 * Plugin URI: https://github.com/bastiendc/dc-elementor-addons
 * Version: 0.22
 * Author: Dynamic Creative
 * Author URI: https://www.dynamic-creative.com
 * Text Domain: elementor-customwidgets-extension
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

class Elementor_CustomWidgets_Extension {

   /**
    * Instance
    *
    * @since 1.0.0
    *
    * @access private
    * @static
    *
    * @var Elementor_Test_Extension The single instance of the class.
    */
   private static $_instance = null;

   /**
    * Instance
    *
    * Ensures only one instance of the class is loaded or can be loaded.
    *
    * @since 1.0.0
    *
    * @access public
    * @static
    *
    * @return Elementor_Test_Extension An instance of the class.
    */
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
      wp_enqueue_style( 'dcael-css-admin',  plugins_url() . '/dc-elementor-addons/admin-el.css' );
   }

   public function widget_styles() { 
      wp_enqueue_style( 'dcael-css',  plugins_url() . '/dc-elementor-addons/dcael.css' );
   }
    
    public function widget_scripts() { 
      wp_enqueue_script( 'dcael-js',  plugins_url() . '/dc-elementor-addons/dcael.js' );
   }
   
   public function init_widgets() {

      // Include Widget files
      require_once('widgets/loop-post.php' );
      require_once('widgets/widget-icon-text.php' );
      require_once('widgets/widget-icon-text-v2.php' );
      require_once('widgets/widget-temoin.php' );
      require_once('widgets/widget-gallery-slick.php' );
      //require_once('widgets/widget-gallery-photo.php' );
      //require_once('widgets/widget-slider-bloc.php' );
      require_once('widgets/widget-slider-temoin.php' );
      require_once('widgets/widget-bulle.php' );
      require_once('widgets/widget-bloc-picto-text.php' ); 
      require_once('widgets/widget-bloc-carre.php' );
      // Register widget
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Loop_Post() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Icon_Text() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Icon_Text_v2() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Temoin() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Gallery_Slick() );
      //\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Gallery_Photo() );
      //\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Slider_Bloc() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Slider_Temoin() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Bulle() );
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Bloc_Picto_Text() ); 
      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Widget_Bloc_Carre() ); 

   }
}

Elementor_CustomWidgets_Extension::instance()->init();
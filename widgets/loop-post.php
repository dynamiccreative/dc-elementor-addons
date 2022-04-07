<?php
/**
 * DCAEL Loop Post.
 *
 * @version 0.11
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Poste Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.1
 */
class Widget_Loop_Post extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'loop_post';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( '<span style="color:#1463DD;">Loop Post</span>', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'dae-icon eicon-post-list';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['dc-addons'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'loop', 'post', 'archive' ];
	}
	
	/**
	 * Get all the existing files
	 *
	 * @return array
	 */
	function get_loop_templates(){
		$templates = array();
		
		$template_files = array(
			'loop*.php'
		);
		
		$template_dirs = array( get_stylesheet_directory() );
		//$template_dirs = apply_filters( 'siteorigin_panels_postloop_template_directory', $template_dirs );
		$template_dirs = array_unique( $template_dirs );
		foreach( $template_dirs  as $dir ){
			foreach( $template_files as $template_file ) {
				foreach( (array) glob($dir.'/'.$template_file) as $file ) {
					if( file_exists( $file ) ) $templates[] = str_replace($dir.'/', '', $file);
				}
			}
		}
		
		//$templates = array_unique( apply_filters( 'siteorigin_panels_postloop_templates', $templates ) );
		foreach ( $templates as $template_key => $template)  {
			$invalid = false;

			// Ensure the provided file has a valid name and path
			if ( validate_file( $template ) != 0 ) {
				$invalid = true;
			}

			// Don't expect non-PHP files
			if ( substr( $template, -4 ) != '.php' ) {
				$invalid = true;
			}

			$template = locate_template( $template );
			if ( empty( $template ) || $invalid ) {
				unset( $templates[ $template_key ] );
			}
		}
		// Update array indexes to ensure logical indexing
		sort( $templates );
		sort( $templates );
		
		return $templates;
	}
	
	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$templates = $this->get_loop_templates();
		$tab = [];

		// Pushes an empty template..
		$tab[ 'none' ] = __( 'Pas de template', 'mywidget' );
		
		foreach($templates as $template) :
			$headers = get_file_data( locate_template($template), array(
								'loop_name' => 'Loop Name',
							) );
			$txt = esc_html(!empty($headers['loop_name']) ? $headers['loop_name'] : $template);
			$tab[ esc_attr($template) ] = $txt;
		endforeach;
		
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Poste', 'mywidget' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        
		$this->add_control(
			'loop_page',
			[
				'label' => __( 'Loop file', 'mywidget' ),
				'type' => Controls_Manager::SELECT,
				//'default' => 5,
				'options' => $tab
			]
		);
		
        $this->add_control(
			'cat',
			[
				'label' => __( 'Catégorie', 'mywidget' ),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( '...', 'mywidget' ),
			]
		);

		$this->add_control(
			'nb_post',
			[
				'label' => __( 'Nombre de post', 'mywidget' ),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( '-1', 'mywidget' ),
			]
		);
        
		$this->end_controls_section();

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		// Pick only the selected template if pre defined in the combo.
		if( $settings['loop_page'] != 'none' ){
			echo "<!-- Use template « ".$settings['loop_page']." » -->\n";
            global $tax; global $nb_post;
            $tax = $settings['cat']; $nb_post = $settings['nb_post'];
			include_once get_stylesheet_directory().'/'. $settings['loop_page'];			
			}
	}

}
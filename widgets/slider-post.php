<?php
/**
 * DCAEL Slider Post.
 *
 * @version 0.1
 */

/*
add_filter( 'excerpt_more', [ $this, 'filter_excerpt_more' ], 20 );
add_filter( 'excerpt_length', [ $this, 'filter_excerpt_length' ], 20 );
*/
namespace Elementor;
//namespace DcElementorAddons\Widgets;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use ElementorPro\Base\Base_Widget;
/*use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use ElementorPro\Base\Base_Widget;
use ElementorPro\Modules\QueryControl\Module as Module_Query;
use ElementorPro\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Controls_Manager;*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//require_once( plugin_dir_url( __DIR__ ).'base/common-widget.php');

/**
 * Elementor Poste Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.1
 */
class Widget_Slider_Post extends Widget_Base {

    //use Common_Widget;  
    //use DcElementorAddons\Base\DC_Common_Widget;

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_script('swiper', plugins_url().'/elementor/assets/lib/swiper/swiper.min.js');
	    wp_register_script( 'dc-slider-post-js', plugin_dir_url( __DIR__ ).'js/dc-slider-post.js', [ 'elementor-frontend', 'swiper' ], '1.0.0', true );
	    wp_register_style( 'dc-slider-post-css', plugin_dir_url( __DIR__ ).'css/dc-slider-post.css','1.0.0', true );
	  }

	public function get_script_depends() {
		return array( 'dc-slider-post-js' );
	}

	public function get_style_depends() {
		return array('dc-slider-post-css');
	}

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
		return 'slider_post';
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
		return __( 'Slider Post', 'elementor' );
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
		return [ 'loop', 'post', 'slider', 'cpt' ];
	}
	
	/**
	 * Get post type / taxonomie
	 *
	 * @return array
	 */
	function dc_get_post_types() {

		$post_types = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);

		$options = array();

		foreach ( $post_types as $post_type ) { 
			$options[ $post_type->name ] = $post_type->label;
		}

		// Deprecated 'Media' post type.
		$dels = array("Fichier média", "Pages", "Page d’atterrissage", "Mes modèles", "Templates");
		foreach ( $dels as $del ) {
			$key = array_search( $del, $options, true );
			if ( $key ) {
				unset( $options[ $key ] );
			}
		}
		
		return $options;
	}

	function dc_get_taxonomy( $post_type ) {

		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		$data       = array();

		foreach ( $taxonomies as $tax_slug => $tax ) {
			if ( ! $tax->public || ! $tax->show_ui ) {
				continue;
			}

			$data[ $tax_slug ] = $tax;
		}

		return $taxonomies;
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
		
		
		$this->start_controls_section(
			'query_section',
			[
				'label' => __( 'Query', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$post_types = $this->dc_get_post_types();

		$this->add_control(
			'post_type_filter',
			array(
				'label'       => __( 'Post Type', 'elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'post',
				//'label_block' => true,
				'options'     => $post_types,
				//'separator'   => 'after',
				//'condition'   => array(	'query_type' => 'custom',),
			)
		);



		foreach ( $post_types as $key => $type ) {
			// Get all the taxanomies associated with the post type.
			$taxonomy = $this->dc_get_taxonomy( $key );
			

			if ( ! empty( $taxonomy ) ) {
				// Get all taxonomy values under the taxonomy.
                //$list_tax = array();
				foreach ( $taxonomy as $index => $tax ) {
                    //$list_tax[] = $tax->name;
					$terms = get_terms( $index );

					$related_tax = array();

					if ( ! empty( $terms ) ) {
						foreach ( $terms as $t_index => $t_obj ) {
							//var_dump($t_obj->slug);
							$related_tax[ $t_obj->slug ] = $t_obj->name;
						}
						$this->add_control(
							$index . '_' . $key . '_filter_rule',
							array(
					
								'label'       => sprintf( __( '%s Filter Rule', 'elementor' ), $tax->label ),
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								//'label_block' => true,
								'options'     => array(
									
									'IN'     => sprintf( __( 'Match %s', 'elementor' ), $tax->label ),
									
									'NOT IN' => sprintf( __( 'Exclude %s', 'elementor' ), $tax->label ),
								),
								'condition'   => array(
									'post_type_filter' => $key,
									//'query_type'       => 'custom',
								),
							)
						);

						// Add control for all taxonomies.
						$this->add_control(
							'tax_' . $index . '_' . $key . '_filter',
							array(
								/* translators: %s label */
								'label'       => sprintf( __( '%s Filter', 'elementor' ), $tax->label ),
								'type'        => Controls_Manager::SELECT2,
								'multiple'    => true,
								'default'     => '',
								//'label_block' => true,
								'options'     => $related_tax,
								'condition'   => array(
									'post_type_filter' => $key,
									//'query_type'       => 'custom',
								),
								'separator'   => 'after',
							)
						);
					}
				}
			}
		}

		$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Nombre d\'article', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -1,
				'max' => 30,
				'default' => 10,
			]
		);

		$this->add_control(
			'ignore_sticky_posts',
			array(
				'label'        => __( 'Ignore Sticky Posts', 'elementor' ),
				'description'  => __( 'Note: Sticky-posts ordering is visible on frontend only.', 'elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'elementor' ),
				'label_off'    => __( 'No', 'elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					//'query_type' => 'custom',
				),
				'separator'   => 'after',
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'     => __( 'Order by', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => array(
					'date'       => __( 'Date', 'elementor' ),
					'title'      => __( 'Title', 'elementor' ),
					'rand'       => __( 'Random', 'elementor' ),
					'menu_order' => __( 'Menu Order', 'elementor' ),
				),
				'condition' => array(
					//'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => __( 'Order', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'desc',
				'options'   => array(
					'desc' => __( 'Descending', 'elementor' ),
					'asc'  => __( 'Ascending', 'elementor' ),
				),
				'condition' => array(
					//'query_type' => 'custom',
				),
			)
		);
                
		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'colonne',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Colonne', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );
        
        $this->add_control(
			'colonne_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-v-align-middle',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .swiper-slide' => 'align-items: {{VALUE}};',
				],
                'condition' => ['colonne' => 'yes',],
			]
		);

		$this->add_control(
            'display_excerpt',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Excerpt', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'separator' => 'before',
            )
        );

        $this->add_control(
			'meta_data',
			[
				'label' => esc_html__( 'Meta Data', 'elementor-pro' ),
				//'label_block' => true,
				'type' => Controls_Manager::SELECT2,
				'default' => [ 'date', 'comments' ],
				'multiple' => true,
				'options' => [
					'author' => esc_html__( 'Author', 'elementor-pro' ),
					'date' => esc_html__( 'Date', 'elementor-pro' ),
					'time' => esc_html__( 'Time', 'elementor-pro' ),
					'comments' => esc_html__( 'Comments', 'elementor-pro' ),
					'modified' => esc_html__( 'Date Modified', 'elementor-pro' ),
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label' => esc_html__( 'Separator Between', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => '///',
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data span + span:before' => 'content: "{{VALUE}}"',
				],
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_control(
            'meta_top',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Position Top', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        /*$this->add_control(
			'show_title',
			[
				'label' => esc_html__( 'Show Title', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_off' => esc_html__( 'Off', 'elementor-pro' ),
				'label_on' => esc_html__( 'On', 'elementor-pro' ),
			]
		);*/

        $this->add_control(
			'title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
				'separator' => 'before',
				//'condition' => ['show_title' => 'yes',],
			]
		);
        
        $this->add_control(
            'display_cat',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display category', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
            )
        );
        
        $this->add_control(
            'cat_img',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display category on Image', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => ['display_cat' => 'yes',],
            )
        );
        
        $this->add_control(
			'cat_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .item-cat' => 'justify-content: {{VALUE}};',
				],
                'condition' => ['display_cat' => 'yes','cat_img' => 'yes'],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'image_section',
			[
				'label' => __( 'Image', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
			]
		);

		$this->add_control(
            'image_full',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('100%', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_section',
			[
				'label' => __( 'Slider', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
            'nb_slide',
            array(
                'label' => __('Slide per view', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 3],
                'range' => array(
                    	'px' => [
						'min' => 1,
						'max' => 8,
					]
                ),
            )
        );

        $this->add_responsive_control(
            'padding_slide',
            array(
                'label' => __('Space between slide', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 0],
                'range' => array(
                    	'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 5,
					]
                ),
                'condition' => [
					'nb_slide[size]!' => 1,
				],
            )
        );

        $this->add_control(
            'display_navigation',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Navigation', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->add_control(
            'display_pagination',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Pagination', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Autoplay', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        $this->add_control(
            'loop',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Infinite Loop', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        $this->add_control(
            'centered',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Centrer', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'slider_nav_section',
			[
				'label' => __( 'Navigation', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => ['display_navigation' => 'yes'],
			]
		);
    
		$this->add_control(
            'navigation_icon_prev',
            array(
                'label' => __('Previous slide icon', 'elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => array(
                    'value' => 'fas fa-chevron-left',
                    'library' => 'solid',
                ),
                'condition' => array(
                    'display_navigation' => 'yes',
                ),
            )
        );

        $this->add_control(
            'navigation_icon_next',
            array(
                'label' => __('Next slide icon', 'elementor'),
                'type' => Controls_Manager::ICONS,
                'default' => array(
                    'value' => 'fas fa-chevron-right',
                    'library' => 'solid',
                ),
                'condition' => array(
                    'display_navigation' => 'yes',
                ),
            )
        );
        
        $this->add_control(
            'navigation_horizontal_position',
            array(
                'label' => __('Horizontal position', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'slider-nav-horizontal-left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-order-start',
                    ),
                    'slider-nav-horizontal-centerin' => array(
                        'title' => __('Center In', 'elementor'),
                        'icon' => 'eicon-h-align-center',
                    ),
                    'slider-nav-horizontal-centerout' => array(
                        'title' => __('Center Out', 'elementor'),
                        'icon' => ' eicon-h-align-stretch',
                    ),
                    'slider-nav-horizontal-right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-order-end',
                    ),
                ),
                'default' => 'slider-nav-horizontal-centerin',
                //'prefix_class' => 'slider-nav-horizontal-',
                'toggle' => true,
                'condition' => array(
	                'display_navigation' => 'yes',
                ),
            )
        );

        $this->add_control(
            'navigation_vertical_position',
            array(
                'label' => __('Vertical position', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'slider-nav-vertical-top' => array(
                        'title' => __('Top', 'elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'slider-nav-vertical-center' => array(
                        'title' => __('Center', 'elementor'),
                        'icon' => 'eicon-v-align-stretch',
                    ),
                    'slider-nav-vertical-bottom' => array(
                        'title' => __('Bottom', 'elementor'),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'default' => 'slider-nav-vertical-center',
                //'prefix_class' => 'slider-nav-vertical-',
                'toggle' => true,
                'condition' => array(
	                'display_navigation' => 'yes',
                ),
            )
        );

		$this->end_controls_section();
        
        // STYLES
		$this->start_controls_section(
			'slide_style',
			[
				'label' => esc_html__( 'Slide', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_responsive_control(
			'img_spacing',
			[
				'label' => esc_html__( 'Spacing Image', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-img' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
                'separator'   => 'after',
                'condition' => ['colonne!' => 'yes',],
			]
		);

		$this->add_control(
			'titre_color',
			[
				'label' => esc_html__( 'Title Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .item-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'titre_text',
                'label' => __( 'Title', 'elementor' ),
				'selector' => '{{WRAPPER}} .item-title',
				
            ]
        );

        $this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Description Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .item-description' => 'color: {{VALUE}};',
				],
				'condition' => array(
                    'display_excerpt' => 'yes',
                ),
                'separator'   => 'before',
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_text',
                'label' => __( 'Description', 'elementor' ),
				'selector' => '{{WRAPPER}} .item-description',
				'condition' => array(
                    'display_excerpt' => 'yes',
                ),
            ]
        );

        $this->add_control(
			'heading_meta_style',
			[
				'label' => esc_html__( 'Meta', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data' => 'color: {{VALUE}};',
				],
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_control(
			'meta_separator_color',
			[
				'label' => esc_html__( 'Separator Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data span:before' => 'color: {{VALUE}};',
				],
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .elementor-post__meta-data',
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_responsive_control(
			'meta_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-post__meta-data' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'meta_data!' => [],
				],
			]
		);

		$this->add_responsive_control(
			'colonne_spacing',
			[
				'label' => esc_html__( 'Column spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => ['size' => 0],
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .item-ct' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'colonne' => 'yes',
				],
			]
		);

		$this->end_controls_section();
        
        $this->start_controls_section(
			'cat_style',
			[
				'label' => esc_html__( 'Category', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['display_cat' => 'yes'],
			]
		);
        
        $this->add_control(
			'cat_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-name' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'cat_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-cat' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'condition' => ['cat_img!' => 'yes',],
			]
		);
        
        $this->add_responsive_control(
			'cat_spacing_img',
			[
				'label' => esc_html__( 'Spacing Top', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-cat' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
                //'condition' => ['cat_img' => 'yes',],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .cat-name',
			]
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'cat_background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .cat-name',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cat_border',
				'selector' => '{{WRAPPER}} .cat-name',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cat_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .cat-name' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cat_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);    

		$this->end_controls_section();
        
        $this->start_controls_section(
			'bouton_style',
			[
				'label' => esc_html__( 'Bouton', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				//'condition' => ['display_cat' => 'yes'],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bt_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .item-btn',
			]
		);
        
        $this->start_controls_tabs( 'tabs_button_style', [
			//'condition' => $args['section_condition'],
		] );
        $this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
				//'condition' => $args['section_condition'],
			]
		);
        
        $this->add_control(
			'bt_color',
			[
				'label' => esc_html__( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-btn' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'bt_background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .item-btn',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);
        
        $this->end_controls_tab();
        $this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
				//'condition' => $args['section_condition'],
			]
		);
        $this->add_control(
			'hover_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-btn:hover, {{WRAPPER}} .item-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'button_background_hover',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .item-btn:hover, {{WRAPPER}} .item-btn:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'bt_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .item-btn:hover, {{WRAPPER}} .item-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_responsive_control(
			'bt_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-link' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
                'separator' => 'before',
			]
		);
		
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bt_border',
				'selector' => '{{WRAPPER}} .item-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bt_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'bt_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);    

		$this->end_controls_section();

		$this->start_controls_section(
			'nav_style',
			[
				'label' => esc_html__( 'Navigation', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => ['display_navigation' => 'yes'],
			]
		);
        
        $this->add_control(
            'navigation_color',
            array(
                'label' => __('Color navigation', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#282828',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}; border-color:{{VALUE}};',
                ),
                'condition' => array(
	                'display_navigation' => 'yes',
                ),
            )
        );

        $this->add_control(
			'navigation_view',
			[
				'label' => esc_html__( 'View', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'elementor' ),
					'framed' => esc_html__( 'Framed', 'elementor' ),
				],
				'default' => 'default',
				'prefix_class' => 'elementor-view-',
			]
		);

        $this->add_control(
			'navigation_shape',
			[
				'label' => esc_html__( 'Shape navigation', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'elementor' ),
					'square' => esc_html__( 'Square', 'elementor' ),
				],
				'default' => 'circle',
				'condition' => [
					'navigation_view!' => 'default',
				],
				'prefix_class' => 'elementor-shape-',
				'separator' => 'after',
			]
		);
        
        $this->add_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					//'navigation' => [ 'arrows', 'both' ],
				],
			]
		);
        
		$this->end_controls_section();

	}

	protected function render_meta_data() {
		/** @var array $settings e.g. [ 'author', 'date', ... ] */
		$settings = $this->get_settings_for_display( 'meta_data' );
		if ( empty( $settings ) ) {
			return;
		}
		?>
		<div class="elementor-post__meta-data">
			<?php
			if ( in_array( 'author', $settings ) ) {
				$this->render_author();
			}

			if ( in_array( 'date', $settings ) ) {
				$this->render_date_by_type();
			}

			if ( in_array( 'time', $settings ) ) {
				$this->render_time();
			}

			if ( in_array( 'comments', $settings ) ) {
				$this->render_comments();
			}
			if ( in_array( 'modified', $settings ) ) {
				$this->render_date_by_type( 'modified' );
			}
			?>
		</div>
		<?php
	}

	protected function render_author() {
		?>
		<span class="elementor-post-author">
			<?php the_author(); ?>
		</span>
		<?php
	}

	/**
	 * @deprecated since 3.0.0 Use `Skin_Base::render_date_by_type()` instead
	 */
	protected function render_date() {
		// _deprecated_function( __METHOD__, '3.0.0', 'Skin_Base::render_date_by_type()' );
		$this->render_date_by_type();
	}

	protected function render_date_by_type( $type = 'publish' ) {
		?>
		<span class="elementor-post-date">
			<?php
			switch ( $type ) :
				case 'modified':
					$date = get_the_modified_date();
					break;
				default:
					$date = get_the_date();
			endswitch;
			/** This filter is documented in wp-includes/general-template.php */
			// PHPCS - The date is safe.
			echo apply_filters( 'the_date', $date, get_option( 'date_format' ), '', '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?>
		</span>
		<?php
	}

	protected function render_time() {
		?>
		<span class="elementor-post-time">
			<?php the_time(); ?>
		</span>
		<?php
	}

	protected function render_comments() {
		?>
		<span class="elementor-post-avatar">
			<?php comments_number(); ?>
		</span>
		<?php
	}

	protected function render_title() {
		/*if ( ! $this->get_settings( 'show_title' ) ) {
			return;
		}*/


		$tag = $this->get_settings( 'title_tag' );
		?>
		<<?php Utils::print_validated_html_tag( $tag ); ?> class="item-title">
		<?php the_title(); ?>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
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

		// QUERY
		$post_type = ( isset( $settings['post_type_filter'] ) && '' !== $settings['post_type_filter'] ) ? $settings['post_type_filter'] : 'post';
		$query_args = array(
			'post_type'			=> $post_type,
			'posts_per_page'	=> $settings['posts_per_page'],
		);
		$query_args['orderby']             = $settings['orderby'];
		$query_args['order']               = $settings['order'];
		$query_args['ignore_sticky_posts'] = ( isset( $settings['ignore_sticky_posts'] ) && 'yes' === $settings['ignore_sticky_posts'] ) ? 1 : 0;

		// Get all the taxanomies associated with the post type.
		$taxonomy = $this->dc_get_taxonomy( $post_type ); 

		if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {
			// Get all taxonomy values under the taxonomy.
			foreach ( $taxonomy as $index => $tax ) {
				if ( ! empty( $settings[ 'tax_' . $index . '_' . $post_type . '_filter' ] ) ) {
					$operator = $settings[ $index . '_' . $post_type . '_filter_rule' ];

					$query_args['tax_query'][] = array(
						'taxonomy' => $index,
						'field'    => 'slug',
						'terms'    => $settings[ 'tax_' . $index . '_' . $post_type . '_filter' ],
						'operator' => $operator,
					);
                    
				}
			}
		}
        
        

		// SLIDER
		$nav = $settings['display_navigation'] === 'yes' ? array('nextEl' => '.els-next-'.$this->get_id(),'prevEl' => '.els-prev-'.$this->get_id()) : false;
		$pag = $settings['display_pagination'] === 'yes' ? array('el' => '.swiper-pagination','clickable' => true,) : false;
		$loop = $settings['loop'] === 'yes' ? true : false;
		$excerpt = $settings['display_excerpt'] === 'yes' ? true : false;
		$autoplay = $settings['autoplay'] === 'yes' ? $autoplay_linear_animation : false;
		$centered = $settings['centered'] === 'yes' ? true : false;
		$metatop = $settings['meta_top'] === 'yes' ? true : false;
		$colonne = $settings['colonne'] === 'yes' ? 'item-colonne d-md-flex' : '';
		$full_width = $settings['image_full'] === 'yes' ? 'width:100%; height:auto;' : '';
        $display_cat = $settings['display_cat'] === 'yes' ? true : false;
        $cat_img = $settings['cat_img'] === 'yes' ? true : false;

		$nbSlideDesktop = $settings['nb_slide']['size'];
		$nbSlideTablet = $settings['nb_slide_tablet']['size'];
		$nbSlideMobile = $settings['nb_slide_mobile']['size'];
		if (!is_numeric($nbSlideTablet)) $nbSlideTablet = $nbSlideDesktop;
		if (!is_numeric($nbSlideMobile)) $ndSlideMobile = $nbSlideDesktop;

		$espSlideDesktop = $settings['padding_slide']['size'];
		$espSlideTablet = $settings['padding_slide_tablet']['size'];
		$espSlideMobile = $settings['padding_slide_mobile']['size'];
		if (!is_numeric($espSlideTablet)) $espSlideTablet = $espSlideDesktop;
		if (!is_numeric($espSlideMobile)) $espSlideMobile = $espSlideDesktop;
		$breakpoints = array(
            	1024 => array(
            		'slidesPerView' => $nbSlideDesktop,
            		'spaceBetween' => $espSlideDesktop
            	),
            	768 => array(
            		'slidesPerView' => $nbSlideTablet,
            		'spaceBetween' => $espSlideTablet
            	),
            	360 => array(
            		'slidesPerView' => $ndSlideMobile,
            		'spaceBetween' => $espSlideMobile
            	));
		if ($settings['nb_slide']['size'] == 1) $breakpoints = false;

		$swiper_options = array(
		    'slidesPerView' => $settings['nb_slide']['size'],
		    'navigation'    => $nav,
		    'pagination'    => $pag,
            'autoplay' => $autoplay,
            'spaceBetween' => $settings['padding_slide']['size'],
            'loop' => $loop,
            'breakpoints' => $breakpoints,
            'centeredSlides' => $centered,
	    );

	    // ATTRIBUTES
	    $this->add_render_attribute( array(
        	'wrapper' => array(
                'class' => array(
                    'dc-slider-post elementor-image-carousel-wrapper swiper-container'
                ),
                'data-swiper-options' => wp_json_encode($swiper_options),
            ),
            'container' => array(
        		'id' => 'dc-slider-post-'.$this->get_id(),
                'class' => array('dc-slider-container', $settings['navigation_vertical_position'], $settings['navigation_horizontal_position'], $linear_animation)
        	),
        	'prev_container'  => array(
                'class' => array(
                    'elementor-swiper-button elementor-swiper-button-prev els-prev-'.$this->get_id()
                 ) 
            ),
            'prev' => array(
                'class' => array(
                    $settings['navigation_icon_prev']['value']
                )
            ),
            'next_container' => array(
	            'class' => array(
		            'elementor-swiper-button elementor-swiper-button-next els-next-'.$this->get_id()
	            )
            ),
            'next' => array(
	            'class' => array(
		            $settings['navigation_icon_next']['value']
	            )
            ),
            'slide' => array(
            	'class' => array (
            		'swiper-slide', $colonne
            	)
            ),
            'button' => array(
                'class' => array( 'item-btn', 'elementor-animation-' . $settings['hover_animation'] )
            )
            
        ));

		// RENDER
    	?>
    	<div <?php echo $this->get_render_attribute_string('container'); ?>>
    		<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
	        	<div class="elementor-image-carousel swiper-wrapper">
	        		<?php
	        		$loop = new \WP_Query( $query_args );
			        while ( $loop->have_posts() ) : $loop->the_post(); 
                    
                    $tab_cat = array(); $s_cat = '<div class="item-cat d-flex">';
                    if ($display_cat) {
                        if ($post_type == 'post') {
                            $category = get_the_category();
                            $tab_cat[] = $category[0]->cat_name;
                        } else {
                            $taxo = $this->dc_get_taxonomy($post_type );
                            foreach ( $taxo as $index => $tax ) {
                                //var_dump($tax->name);
                                $terms = get_the_terms( get_the_ID(), $tax->name );
                                if ($terms) {
                                    //var_dump($terms[0]->name);
                                    $tab_cat[] = $terms[0]->name;
                                    /*foreach ( $terms as $t_index => $t_obj ) {
                                        //var_dump($t_obj->slug);
                                        var_dump($t_obj->name);
                                    }*/
                                }
                                //var_dump($terms);
                            }
                        }
                        foreach ( $tab_cat as $cat => $c) {
                            $s_cat .= '<div class="cat-name">'.$c.'</div>';
                        }
                        $s_cat .= '</div>';
                        //print($s_cat);
                    }
        
        
			        	/*if ($post_type == 'post') {
			        		$category = get_the_category(); 
							echo $category[0]->cat_name;
						} else {
							//$post = get_post( $post );
							echo get_the_ID();//$post->ID;
							echo $post_type;
							//$terms = get_the_terms( get_the_ID(),  );
							//var_dump($terms);
							//echo $first_term_name;
							if (!empty($terms)) {
							   if(!is_wp_error( $terms )) {
							       //this line effectively returns the first available term object if there is one.
							       echo $terms[0]->name;
							   } else {
							       echo $terms->name; //This returns the WP_Error object
							   }       
							}
						}*/
					?>
					<div <?php echo $this->get_render_attribute_string('slide'); ?>>
		        		<div class="item-img">
                            <?php the_post_thumbnail($settings['thumbnail_size'], ['style' => $full_width]); ?>
                            <?php if ($cat_img && $tab_cat && $display_cat) {
                            echo $s_cat;
                            }?>
                        </div>
		        		<div class="item-ct">
                            <?php 
                                if($tab_cat && $display_cat && !$cat_img) {
                                    echo $s_cat;
                                }
                            ?>
			        		<?php 
			        			if ($metatop) $this->render_meta_data();
			        			$this->render_title();
			        			if (!$metatop) $this->render_meta_data();
			        		?>
			        		<?php if ($excerpt) { ?>
			        		<div class="item-description"><?php the_excerpt(); ?></div>
			        		<?php } ?>
			        		<div class="item-link"><a href="<?php the_permalink(); ?>" <?php echo $this->get_render_attribute_string('button'); ?>><?php _e("En savoir plus", "dynamic-child"); ?></a></div>
		        		</div>
		        	</div>
	        		<?php
    				endwhile;
    				?>
	        	</div>
	        	<?php if ($settings['display_pagination'] === 'yes') { ?>
				<div class="swiper-pagination"></div>
				<?php } ?>
			</div>

			<?php if($settings['display_navigation'] === 'yes') { ?>
			<div <?php echo $this->get_render_attribute_string('next_container'); ?>><i <?php echo $this->get_render_attribute_string('next'); ?>></i></div>
            <div <?php echo $this->get_render_attribute_string('prev_container'); ?>><i <?php echo $this->get_render_attribute_string('prev'); ?>></i></div>
        	<?php } ?>
		</div>
		<?php		
        		
	}

}
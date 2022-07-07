<?php
/**
 * DCAEL Slider Repeater.
 *
 * @version 0.1
 */

namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Widget_Slider_Repeater extends Widget_Base {

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_script('swiper', plugins_url().'/elementor/assets/lib/swiper/swiper.min.js');
	    wp_register_script( 'dc-slider-repeater-js', plugin_dir_url( __DIR__ ).'js/dc-slider-repeater.js', [ 'elementor-frontend', 'swiper' ], '1.0.0', true );
	    wp_register_style( 'dc-slider-repeater-css', plugin_dir_url( __DIR__ ).'css/dc-slider-repeater.css','1.0.0', true );
	  }

	public function get_script_depends() {
		return array( 'dc-slider-repeater-js' );
	}

	public function get_style_depends() {
		return array('dc-slider-repeater-css');
	}

	public function get_name() {
		return 'slider_repeater';
	}
	public function get_title() {
		return __( 'Slider Repeater', 'elementor' );
	}
	public function get_icon() {
		return 'dae-icon eicon-slides';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
        
        
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Slider Content', 'elementor' ),
			]
		);
		
        $repeater = new Repeater();
        
        $repeater->add_control(
            'bloc_title', [
                'label' => __( 'Title', 'elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Box Title' , 'elementor' ),
            ]
        );

        $repeater->add_control(
            'bloc_desc',
            [
                'label' => __( 'Description', 'elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'title' => __( 'Enter some text', 'elementor' ),
            ]
        );

        $repeater->add_control(
            'bloc_image',
            [
                'label' => __( 'Choose Image', 'elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );
               
        $repeater->add_control(
            'bloc_url',
            [
                'label' => __( 'Url', 'elementor' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true
                ],
                'show_external' => false,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => __( 'Repeater List', 'elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'bloc_title' => __( 'Title #1', 'elementor' ),
                    ],
                    [
                        'bloc_title' => __( 'Title #2', 'elementor' ),
                    ],
                ],
                'title_field' => '{{{ bloc_title }}}',
            ]
        );

        


		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'separator' => 'before',
			]
		);
        
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
            ]
        );

        $this->add_control(
            'bloc_color',
            array(
                'label' => __('Background color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .item-text' => 'background-color: {{VALUE}};',
                ),
            )
        );
        
		
		$this->end_controls_section();

		$this->start_controls_section(
		    'section_options',
		    array(
			    'label' => esc_html__('Slider options', 'elementor')
		    )
	    );


        $slides_per_view = range( 1, 10 );
        $slides_per_view = array_combine( $slides_per_view, $slides_per_view );

        $this->add_responsive_control(
            'nb_slide',
            [
                'type' => Controls_Manager::SELECT,
                'label' => esc_html__( 'Slides Per View', 'elementor-pro' ),
                'options' => [ '' => esc_html__( 'Default', 'elementor-pro' ) ] + $slides_per_view,
                'frontend_available' => true,
            ]
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
					'nb_slide!' => 1,
				],
				'frontend_available' => true,
            )
        );

        $this->add_control(
			'pagination_options',
			[
				'label' => esc_html__( 'Pagination', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'display_pagination',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->add_control(
			'style_pagination',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'bullets' => esc_html__( 'Bullets', 'elementor' ),
					'fraction' => esc_html__( 'Fraction', 'elementor' ),
					'progressbar' => esc_html__( 'Progressbar', 'elementor' ),
				],
				'default' => 'bullets',
				'condition' => [
					'display_pagination' => 'yes',
				],
			]
		);

		$this->add_control(
			'navigation_options',
			[
				'label' => esc_html__( 'Navigation', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'display_navigation',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display', 'elementor'),
                'label_on' => __('Show', 'elementor'),
                'label_off' => __('Hide', 'elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
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
        
        $this->add_responsive_control(
            'padding_bouton',
            array(
                'label' => __('Space Button Horizontal', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 25],
                'range' => array(
                    	'px' => [
						'min' => 0,
						'max' => 100,
					]
                ),
                'condition' => [
					'display_navigation' => 'yes',
				],
                'selectors' => [
					'{{WRAPPER}} .slider-nav-horizontal-centerout .elementor-swiper-button-next' => 'right: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slider-nav-horizontal-centerout .elementor-swiper-button-prev' => 'left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
				
            )
        );
        
        $this->add_responsive_control(
            'padding_bouton_vertical',
            array(
                'label' => __('Space Button Vertical', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 25],
                'range' => array(
                    	'px' => [
						'min' => 0,
						'max' => 100,
					]
                ),
                'condition' => [
					'display_navigation' => 'yes',
				],
                'selectors' => [
					'{{WRAPPER}} .slider-nav-vertical-bottom .elementor-swiper-button' => 'bottom: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slider-nav-vertical-top .elementor-swiper-button' => 'top: -{{SIZE}}{{UNIT}};',
				],
				
            )
        );
        
        $this->add_responsive_control(
            'padding_bouton_between',
            array(
                'label' => __('Space Between Button', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 25],
                'range' => array(
                    	'px' => [
						'min' => 0,
						'max' => 200,
					]
                ),
                'condition' => [
					'display_navigation' => 'yes',
				],
                'selectors' => [
					'{{WRAPPER}} .slider-nav-horizontal-right .elementor-swiper-button-prev' => 'left:auto; right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slider-nav-horizontal-left .elementor-swiper-button-next' => 'right:auto; left: {{SIZE}}{{UNIT}};',
				],
				
            )
        );

        $this->add_control(
			'other_options',
			[
				'label' => esc_html__( 'Options', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
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
            'delay',
            array(
                'label' => __('Autoplay speed(delay between slides)', 'elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '3000',
                'condition' => array(
                    'autoplay' => 'yes'
                ),
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label' => __('Speed Animation', 'elementor'),
                'type' => Controls_Manager::NUMBER,
                'default' => '1000',
            )
        );

        

        /*$this->add_control(
            'speed',
            array(
                'label' => __('Speed of transitions', 'elementor'),
                'type' => Controls_Manager::NUMBER,
                'return_value' => 'yes',
                'default' => '300',
            )
        );*/


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
			'effect',
			[
				'label' => esc_html__( 'Effect', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'elementor' ),
					'fade' => esc_html__( 'Fade', 'elementor' ),
				],
				'condition' => [
					'nb_slide' => 1,
				],
			]
		);

        $this->add_control(
            'scrollbar',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Scrollbar', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
					'nb_slide!' => 1, 'loop!' => 'yes',
				],
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

        $this->add_control(
            'linear_animation',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Linear animation', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        $this->end_controls_section();

        /**/

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Slider', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'navigation_style',
            [
                'label' => esc_html__( 'Navigation', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'navigation_color',
            array(
                'label' => __('Color', 'elementor'),
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
					//'stacked' => esc_html__( 'Stacked', 'elementor' ),
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
                'separator' => 'after',
				'condition' => [
					'navigation_view!' => 'default',
				],
			]
		);

        $this->add_control(
            'pagination_color',
            array(
                'label' => __('Color pagination', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#282828',
                'selectors' => array(
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ),
                'condition' => array(
	                'display_pagination' => 'yes',
                ),
            )
        );

        $this->add_control(
            'pagination_size',
            array(
                'label' => __('Size pagination', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 6],
                'range' => array(
                    	'px' => [
						'min' => 6,
						'max' => 16,
					]
                ),
                'selectors' => array(
                    '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
	                'display_pagination' => 'yes', 'style_pagination' => 'bullets',
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
			'section_content_text',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
            'content_style',
            [
                'label' => esc_html__( 'Style', 'elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Bottom', 'elementor' ),
                    'opacity' => esc_html__( 'Opacity', 'elementor' ),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-style-',
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label' => esc_html__( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .item-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'ct_text_color',
			[
				'label' => esc_html__( 'Title Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .item-title a' => 'color: {{VALUE}};',
				],
                'separator' => 'before',
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ct_title_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .item-title',
            ]
        );
        
        $this->add_responsive_control(
			'ct_text_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-title' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'ct_desc_color',
            [
                'label' => esc_html__( 'Description Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .item-desc' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'ct_desc_typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
                ],
                'selector' => '{{WRAPPER}} .item-desc',
            ]
        );

		$this->end_controls_section();

        /*bouton*/
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
        
        $this->add_control(
            'bt_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-btn' => 'background-color: {{VALUE}};',
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

        /*$this->add_group_control(
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
        );*/

        $this->add_control(
            'bt_bg_color_hover',
            [
                'label' => esc_html__( 'Background Color', 'elementor-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-btn:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .item-btn:focus' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .item-link' => 'padding-top: {{SIZE}}{{UNIT}};',
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


	}

    protected function render_title($l, $t) {
        $tag = $this->get_settings( 'title_tag' );
        ?>
        <<?php Utils::print_validated_html_tag( $tag ); ?> class="item-title">
        <?php if ($l) { ?><a href="<?php echo $l;?>"> <?php } ?><?php echo $t; ?><?php if ($l) { ?></a> <?php } ?>
        </<?php Utils::print_validated_html_tag( $tag ); ?>>
        <?php
    }
    
	protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_text = $settings['some_text'];
        $size_img = $settings['image_size'];

		$nav = $settings['display_navigation'] === 'yes' ? array('nextEl' => '.els-next-'.$this->get_id(),'prevEl' => '.els-prev-'.$this->get_id()) : false;
		$pag = $settings['display_pagination'] === 'yes' ? array('el' => '.swiper-pagination','clickable' => true, 'type' => $settings['style_pagination']) : false;
		$scrollbar = $settings['scrollbar'] === 'yes' ? array('el' => '.swiper-scrollbar','draggable' => true) : false;
		$fadeEffect = $settings['effect'] === 'fade' ? array('crossFade' => true) : false;
		$linear_animation = $settings['linear_animation'] === 'yes' ? 'linear-animation' : '';
		$autoplay_linear_animation = $settings['linear_animation'] === 'yes' ? array('delay' => 0, 'disableOnInteraction' => true) : array('delay' => $settings['delay']);
		$linear_animation_touch = $settings['linear_animation'] === 'yes' ? false : true;

		$loop = $settings['loop'] === 'yes' ? true : false;
		$autoplay = $settings['autoplay'] === 'yes' ? $autoplay_linear_animation : false;
		$centered = $settings['centered'] === 'yes' ? true : false;

        $swiper_options = array(
		    //'direction'     => 'vertical',
		    'slidesPerView' => $settings['nb_slide'],
		    'navigation'    => $nav,
		    'pagination'    => $pag,
            'autoplay' => $autoplay,
            'scrollbar' => $scrollbar,
            'spaceBetween' => $settings['padding_slide']['size'],
            'speed' => (int)$settings['autoplay_speed'],
            'loop' => $loop,
            //'breakpoints' => $breakpoints,
            'effect' => $settings['effect'], 
            'fadeEffect' => $fadeEffect,
            'centeredSlides' => $centered,
            'allowTouchMove' => $linear_animation_touch,
            'simulateTouch' => $linear_animation_touch,
	    );


        $this->add_render_attribute( array(
        	'wrapper' => array(
                'class' => array(
                    'dc-slider-repeater elementor-image-carousel-wrapper swiper-container',   
                ),
                'data-swiper-options' => wp_json_encode($swiper_options),
                'data-text' => $custom_text
            ),
            'container' => array(
        		'id' => 'dc-slider-repeater-'.$this->get_id(),
        		'class' => array('dc-slider-repeater-container', $settings['navigation_vertical_position'], $settings['navigation_horizontal_position'], $linear_animation)
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
            'button' => array(
                'class' => array( 'item-btn', 'elementor-animation-' . $settings['hover_animation'] )
            )
        ));
        
       
		?>
        
		<div <?php echo $this->get_render_attribute_string('container'); ?>>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<div class="elementor-image-carousel swiper-wrapper">
					<?php
					$slides = [];
                    foreach (  $settings['list'] as $item ) {
                        $url = $item['bloc_url']['url'];
                        ?>
                        <div class="swiper-slide">
                            <div class="ct">
                                <div class="item-img">
                                    <?php if ($url) { ?><a href="<?php echo $url; ?>"> <?php } ?>
                                    <?php echo wp_get_attachment_image( $item['bloc_image']['id'], $settings['thumbnail_size'] ); ?>
                                    <?php if ($url) { ?></a><?php } ?>
                                </div> 
                                <div class="item-text d-flex"><div class="m-auto">                  
                                    <?php if($item['bloc_desc']) { ?><div class="item-desc"><?php echo $item['bloc_desc']; ?></div><?php } ?>
                                    <?php if ($url) { ?>
                                    <div class="item-link">
                                        <div <?php echo $this->get_render_attribute_string('button'); ?>><?php _e("Read More", "elementor-customwidgets-extension"); ?></div>
                                    </div>
                                    <?php } ?>
                                </div></div>
                            </div>
                            <?php $this->render_title($url, $item['bloc_title']); ?>
                        </div>
                        <?php
                    } ?>
					
				</div>
				<?php if ($settings['display_pagination'] === 'yes') { ?>
				<div class="swiper-pagination"></div>
				<?php } ?>

				<?php if ($settings['scrollbar'] === 'yes') { ?>
				<div class="swiper-scrollbar"></div>
				<?php } ?>

			</div>
			<?php if($settings['display_navigation'] === 'yes') {?>
			<div <?php echo $this->get_render_attribute_string('next_container'); ?>><i <?php echo $this->get_render_attribute_string('next'); ?>></i></div>
            <div <?php echo $this->get_render_attribute_string('prev_container'); ?>><i <?php echo $this->get_render_attribute_string('prev'); ?>></i></div>
        	<?php } ?>
		</div>
        
		
		<?php
	}

}
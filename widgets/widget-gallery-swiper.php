<?php
/**
 * DCAEL Gallery Swiper.
 *
 * @version 0.1
 */

//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Widget_Gallery_Swiper extends Widget_Base {

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_script('swiper', plugins_url().'/elementor/assets/lib/swiper/swiper.min.js');
	    wp_register_script( 'dc-gallery-swiper', plugin_dir_url( __DIR__ ).'js/dc-gallery-swiper.js', [ 'elementor-frontend', 'swiper' ], '1.0.0', true );
	    wp_register_style( 'dc-gallery-swiper-css', plugin_dir_url( __DIR__ ).'css/dc-gallery-swiper.css','1.0.0', true );
	  }

	public function get_script_depends() {
		return array( 'dc-gallery-swiper' );
	}

	public function get_style_depends() {
		return array('dc-gallery-swiper-css');
	}

	public function get_name() {
		return 'gallery_swiper';
	}
	public function get_title() {
		return __( 'Gallery Swiper', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
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
		
		/*$this->add_control(
			'some_text',
			[
				'label' => __( 'Classe CSS', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
			]
		);*/
       
        $this->add_control(
			'carousel',
			[
				'label' => __( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'separator' => 'none',
			]
		);
        
		$this->add_control(
			'caption_type',
			[
				'label' => esc_html__( 'Caption', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'None', 'elementor' ),
					'title' => esc_html__( 'Title', 'elementor' ),
					'caption' => esc_html__( 'Caption', 'elementor' ),
					'description' => esc_html__( 'Description', 'elementor' ),
				],
			]
		);
        
        $this->add_control(
            'caption_img',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Display Caption on Image', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => ['caption_type!' => '',],
            )
        );
		
		$this->end_controls_section();

		$this->start_controls_section(
		    'section_options',
		    array(
			    'label' => esc_html__('Slider options', 'elementor')
		    )
	    );

	    $this->add_responsive_control(
            'nb_slide',
            array(
                'label' => __('Slide per view', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 1],
                //'frontend_available' => true,
                //'label_block' => true,
                //'render_type' => 'template',
                'range' => array(
                    	'px' => [
						'min' => 1,
						'max' => 8,
					]
                ),
                //'selectors' => ['{{WRAPPER}}' => '--e-image-carousel-slides-to-show: {{VALUE}}',],
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
					'nb_slide[size]' => 1,
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
					'nb_slide[size]!' => 1, 'loop!' => 'yes',
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
			'section_caption',
			[
				'label' => esc_html__( 'Caption', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_type!' => '',
				],
			]
		);

		$this->add_control(
			'caption_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bloc-caption' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption_text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-caption' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'caption_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bloc-caption' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
                //'condition' => ['cat_img!' => 'yes',],
			]
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'caption_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'caption_typography',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'global' => [
							//'default' => Global_Colors::COLOR_ACCENT,
						],
					],
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'caption_border',
				'selector' => '{{WRAPPER}} .elementor-image-carousel-caption',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'caption_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'caption_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-image-carousel-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();


	}

	private function get_image_caption( $attachment ) {
		$caption_type = $this->get_settings_for_display( 'caption_type' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $attachment['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	/*private function get_setting_value($name)
    {
        if (isset($this->settings[$name]) && !empty($this->settings[$name])) {
            return $this->settings[$name];
        }

        return false;
    }
    
    private function get_prepared_settings() {
        return array(
        	'pagination' => $this->get_setting_value('display_pagination') === 'yes' ? array('el' => '.swiper-pagination') : false,
        	'navigation' => $this->get_setting_value('display_navigation') === 'yes' ? array('nextEl' => '.els-next-'.$this->get_id(),'prevEl' => '.els-prev-'.$this->get_id()) : false,
       	);
    }*/
    
	protected function render() {
        $settings = $this->get_settings_for_display();
        //$prepared_settings = $this->get_prepared_settings();
        $custom_text = $settings['some_text'];
        $size_img = $settings['image_size'];
		// get our input from the widget settings.
		//$custom_text = ! empty( $instance['some_text'] ) ? $instance['some_text'] : ' (no text was entered ) ';
		//$post_count = ! empty( $instance['posts_per_page'] ) ? (int)$instance['posts_per_page'] : 5;
        //var_dump($settings('display_navigation'));

		$nav = $settings['display_navigation'] === 'yes' ? array('nextEl' => '.els-next-'.$this->get_id(),'prevEl' => '.els-prev-'.$this->get_id()) : false;
		$pag = $settings['display_pagination'] === 'yes' ? array('el' => '.swiper-pagination','clickable' => true, 'type' => $settings['style_pagination']) : false;
		$scrollbar = $settings['scrollbar'] === 'yes' ? array('el' => '.swiper-scrollbar','draggable' => true) : false;
		$fadeEffect = $settings['effect'] === 'fade' ? array('crossFade' => true) : false;
		$linear_animation = $settings['linear_animation'] === 'yes' ? 'linear-animation' : '';
		$autoplay_linear_animation = $settings['linear_animation'] === 'yes' ? array('delay' => 0, 'disableOnInteraction' => true) : array('delay' => $settings['delay']);
		$linear_animation_touch = $settings['linear_animation'] === 'yes' ? false : true;
        $caption_img = $settings['caption_img'] === 'yes' ? 'caption_top' : '';

		$loop = $settings['loop'] === 'yes' ? true : false;
		$autoplay = $settings['autoplay'] === 'yes' ? $autoplay_linear_animation : false;//array('delay' => 0, 'disableOnInteraction' => true)
		$centered = $settings['centered'] === 'yes' ? true : false;

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
            		'slidesPerView' => $nbSlideMobile,
            		'spaceBetween' => $espSlideMobile
            	));
		if ($settings['nb_slide']['size'] == 1) $breakpoints = false;
		/*if ($nbSlideDesktop != $nbSlideTablet) {
			array_push($breakpoints, array(1024 => array('slidesPerView' => 2)));
		}*/
		//var_dump($breakpoints);
		//var_dump($nav);
		//print($settings['autoplay']);
        $swiper_options = array(
		    //'direction'     => 'vertical',
		    'slidesPerView' => $settings['nb_slide']['size'],
		    'navigation'    => $nav,
		    'pagination'    => $pag,
            'autoplay' => $autoplay,
            'scrollbar' => $scrollbar,
            'spaceBetween' => $settings['padding_slide']['size'],
            'speed' => (int)$settings['autoplay_speed'],
            'loop' => $loop,
            'breakpoints' => $breakpoints,
            'effect' => $settings['effect'], 
            'fadeEffect' => $fadeEffect,
            'centeredSlides' => $centered,
            'allowTouchMove' => $linear_animation_touch,
            'simulateTouch' => $linear_animation_touch,
	    );


        $this->add_render_attribute( array(
        	'wrapper' => array(
                'class' => array(
                    'dc-slider-swiper elementor-image-carousel-wrapper swiper-container',
                    $caption_img,
	                
                ),
                //'data-autoplay-hover' => $prepared_settings['autoplay'] ? $prepared_settings['autoplay_pause_on_hover'] : 'null',
                'data-swiper-options' => wp_json_encode($swiper_options),
                'data-text' => $custom_text
            ),
            'container' => array(
        		'id' => 'dc-slider-swiper-'.$this->get_id(),
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
        ));
        

		if ( empty( $settings['carousel'] ) ) {
			return;
		}

		$slides = [];
        foreach ( $settings['carousel'] as $index => $attachment ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );

			$image_html = '<img class="swiper-slide-image w-100" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '" />';

			//$link = $this->get_link_url( $attachment, $settings );
			$image_caption = $this->get_image_caption( $attachment );
            $slide_html = '<div class="swiper-slide">' . $image_html;
            if ( ! empty( $image_caption ) ) {
				$slide_html .= '<div class="bloc-caption"><figcaption class="elementor-image-carousel-caption">' . wp_kses_post( $image_caption ) . '</figcaption></div>';
			}
            $slide_html .= '</div>';
            $slides[] = $slide_html;
        }
        
        if ( empty( $slides ) ) {
			return;
		}
        
		?>
		<div <?php echo $this->get_render_attribute_string('container'); ?>>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<div class="elementor-image-carousel swiper-wrapper">
					
					<?php echo implode( '', $slides ); ?>
					
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
	/*
<div class="elementor-swiper-button elementor-swiper-button-prev">
				<?php $this->render_swiper_button( 'previous' ); ?>
				<span class="elementor-screen-only"><?php echo esc_html__( 'Previous', 'elementor' ); ?></span>
			</div>
			<div class="elementor-swiper-button elementor-swiper-button-next">
				<?php $this->render_swiper_button( 'next' ); ?>
				<span class="elementor-screen-only"><?php echo esc_html__( 'Next', 'elementor' ); ?></span>
			</div>
	*/
	/*private function render_swiper_button( $type ) {
		$direction = 'next' === $type ? 'right' : 'left';

		$icon_value = 'fas fa-angle-' . $direction;

		Icons_Manager::render_icon( [
			'library' => 'fa-solid',
			'value' => $icon_value,
		], [ 'aria-hidden' => 'true' ] );
	}*/
}
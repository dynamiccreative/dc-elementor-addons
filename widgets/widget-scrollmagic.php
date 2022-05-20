<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Widget_Scrollmagic extends Widget_Base {

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_script('scrollmagic', 'https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js');
        wp_register_script( 'dc-scrollmagic-gsap', plugin_dir_url( __DIR__ ).'js/lib/animation.gsap.js', [ 'elementor-frontend', 'scrollmagic' ], '1.0.0', true );
        wp_register_script( 'dc-scrollmagic-gsap-ind', plugin_dir_url( __DIR__ ).'js/lib/debug.addIndicators.js', [ 'elementor-frontend', 'scrollmagic' ], '1.0.0', true );
	    wp_register_script( 'dc-scrollmagic-js', plugin_dir_url( __DIR__ ).'js/dc-scrollmagic.js', [ 'elementor-frontend', 'scrollmagic', 'dc-scrollmagic-gsap' ], '1.0.0', true );
        
	    wp_register_style( 'dc-scrollmagic-css', plugin_dir_url( __DIR__ ).'css/dc-scrollmagic.css','1.0.0', true );
	  }

	public function get_script_depends() {
		return array( 'dc-scrollmagic-js', 'dc-scrollmagic-gsap','dc-scrollmagic-gsap-ind' );
	}

	public function get_style_depends() {
		return array('dc-scrollmagic-css');
	}

	public function get_name() {
		return 'scrollmagic';
	}
	public function get_title() {
		return __( 'Scrollmagic', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-import-export';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
        
        
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Scollmagic', 'elementor' ),
			]
		);
		
       
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
				'name' => 'thumbnail',
				'separator' => 'none',
			]
		);

        $this->add_control(
            'full_width',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('100%', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );
        
        
        $this->add_responsive_control(
            'duration',
            array(
                'label' => __('Height Trigger (px)', 'elementor'),
                'type' => Controls_Manager::NUMBER,
                //'default' => '300',
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => 300,
                'tablet_default' =>200,
                'mobile_default' => 100,
            )
        );

        $this->add_control(
            'repaires',
            array(
                'type' => Controls_Manager::SWITCHER,
                'label' => __('Repaires', 'elementor'),
                'label_on' => __('Yes', 'elementor'),
                'label_off' => __('No', 'elementor'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );
		
		$this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Image', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'img_align',
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
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .dc-scrollmagic' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

	}

	protected function render() {
        $settings = $this->get_settings_for_display();
        $size_img = $settings['image_size'];

        //$repaires = $settings['repaires'] === 'yes' ? true : false;
        $full_width = $settings['full_width'] === 'yes' ? 'width:100%; height:auto;' : '';
        $durations = array($settings['duration'], $settings['duration_tablet'], $settings['duration_mobile']);
        /*$swiper_options = array(
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
	    );*/

        $slides = [];
        foreach ( $settings['carousel'] as $index => $attachment ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );

            $slides[] = $image_url;
        }
        

        $this->add_render_attribute( array(
        	'wrapper' => array(
                'class' => array(
                    'dc-scrollmagic'
                ),
                'data-images' => wp_json_encode($slides),
                'data-trigger' => '#trigger-'.$this->get_id(),
                'data-duration' => $settings['duration'],
                'data-repaires' => $settings['repaires'],
                'data-durations' => wp_json_encode($durations),
            ),
            'container' => array(
        		'id' => 'dc-scrollmagic-'.$this->get_id(),
        		'class' => 'dc-scrollmagic-container',
        	),
            'img' => array(
                'class' => 'myimg',
                'style' => array(
                    $full_width,
                )
            ),
        ));
        
		if ( empty( $settings['carousel'] ) ) {
			return;
		}
        
        if ( empty( $slides ) ) {
			return;
		}
        $loaderImg = plugin_dir_url( __DIR__ ).'img/loader.svg';
		?>

        <div id="trigger-<?php echo $this->get_id(); ?>"></div>
		<div <?php echo $this->get_render_attribute_string('container'); ?>>
            <div class="sm-loader"><img src="<?php echo $loaderImg; ?>" width="38" height="38"/></div>
			<div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
				<img <?php echo $this->get_render_attribute_string('img'); ?> src="<?php echo $slides[0]; ?>">
			</div>
		</div>
		<?php
	}

}
<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Bulle extends Widget_Base {
	
	
	public function get_name() {
		return 'widget-bulle';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Widget Bulle</span>', 'elementor-custom-element' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-featured-image';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Bloc Bulle', 'elementor' ),
			]
		);
		

		
		$this->add_control(
            'bulle_color',
            [
                'label' => __( 'Bulle Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bulle' => 'background-color: {{VALUE}};',
				],
            ]
        );
		
		$this->add_control(
            'bulle_size',
            [
                'label' => __( 'Bulle size px', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
						'min' => 150,
                        'max' => 300,
                    ],
                ],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .bulle' => 'border-radius: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		
	
		$this->add_control(
			'text',
			[
				'label' => __( 'Texte', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor' ),
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Text', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_control(
            'txt_color',
            [
                'label' => __( 'Couleur du texte', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .txt' => 'color: {{VALUE}};',
				],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => __( 'Typography', 'elementor' ),
                //'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .txt',
            ]
        );
		
		$this->end_controls_section();
		
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
		?>

		<div class="widget-bulle">
			<div class="bulle d-flex text-center">
				<div class="txt m-auto"><?php echo $settings['text']; ?></div>
			</div>
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
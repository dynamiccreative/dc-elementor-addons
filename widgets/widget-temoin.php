<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Temoin extends Widget_Base {
		
	public function get_name() {
		return 'widget-temoin';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Widget Temoin</span>', 'elementor-custom-element' );
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
				'label' => esc_html__( 'Carte Témoin', 'elementor' ),
			]
		);
		
		/*$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);*/
		
		/*$this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon' => 'color: {{VALUE}};',
				],
            ]
        );*/
		
		/*$this->add_control(
            'icon_size',
            [
                'label' => __( 'Icon size px', 'elementor' ),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
						'min' => 10,
                        'max' => 40,
                    ],
                ],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
                'condition' => [
                    'icon!' => '',
                ],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );*/
		
		$this->add_control(
			'logo',
			[
				'label' => __( 'Logo', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
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
		
		
		$this->add_control(
			'nom',
			[
				'label' => __( 'Nom', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter Name', 'elementor' ),
			]
		);
		
		$this->add_control(
			'societe',
			[
				'label' => __( 'Société', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter Society', 'elementor' ),
			]
		);
		
		$this->end_controls_section();
		
		/*$this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Text', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_control(
            'txt_color',
            [
                'label' => __( 'Text Color', 'elementor' ),
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
                'name' => 'icon_size',
                'label' => __( 'Typography', 'elementor' ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .txt',
            ]
        );
		
		$this->end_controls_section();*/
		
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
		?>

		<div class="d-sm-flex align-items-center widget-temoin">
			<div class="logo text-center"><img src="<?php echo $settings['logo']['url']; ?>"></div>
			<div class="txt">
				<?php echo $settings['text']; ?>
				<div class="d-flex">
				<b><?php echo $settings['nom']; ?></b>
				<?php echo $settings['societe']; ?>
				</div>
			</div>
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
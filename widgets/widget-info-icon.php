<?php
/**
 * DCAEL Info Icon.
 *
 * @version 0.1
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Widget_Info_Icon extends Widget_Base {
	
	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    //wp_register_script( 'dc-info-icon-js', plugin_dir_url( __DIR__ ).'js/dc-info-icon.js', [ 'elementor-frontend', 'swiper' ], '1.0.0', true );
	    wp_register_style( 'dc-info-icon-css', plugin_dir_url( __DIR__ ).'css/dc-info-icon.css','1.0.0', true );
	}

	/*public function get_script_depends() {
		return array( 'dc-info-icon-js' );
	}*/

	public function get_style_depends() {
		return array('dc-info-icon-css');
	}

	
	public function get_name() {
		return 'widget_info_icon';
	}
	public function get_title() {
		return __( 'Info Icon', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-alert';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
			]
		);

		$this->add_control(
			'my_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				/*'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],*/
				//'fa4compatibility' => 'icon',
			]
		);

       $this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Text Info', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

       	$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'show_external' => true,
			]
		);

		$this->add_control(
			'box_height',
			[
				'label' => esc_html__( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 32,
						'max' => 64,
					],
				],
				'default' => ['unit' => 'px', 'size' => 32],
				'selectors' => [
					'{{WRAPPER}} .ct' => 'height: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wi-icon span' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Content', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style', [] );

        $this->start_controls_tab(
			'tab_info_normal',
			[
				'label' => esc_html__( 'Normal', 'elementor' ),
				//'condition' => $args['section_condition'],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'info_background',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ct',
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
			'tab_info_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
				//'condition' => $args['section_condition'],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'info_background_hover',
				'label' => esc_html__( 'Background', 'elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .ct:hover, {{WRAPPER}} .ct:focus',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
				],
			]
		);

		$this->add_control(
			'info_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'info_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ct:hover, {{WRAPPER}} .ct:focus' => 'border-color: {{VALUE}};',
				],
				//'separator' => 'after',
			]
		);

		$this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wi-txt' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wi-txt a' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .wi-txt',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'info_border',
				'selector' => '{{WRAPPER}} .ct',
				'separator' => 'before',
			]
		);

        $this->add_control(
			'info_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .ct' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		/*$this->add_responsive_control(
			'info_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ct' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				//'separator' => 'before',
			]
		);*/

        $this->end_controls_section();

        $this->start_controls_section(
            'icon_style',
            [
                'label' => __( 'Icon', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wi-icon span' => 'color: {{VALUE}};',
				],

			]
		);

		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => 16,
						'max' => 48,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wi-icon span' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);


        $this->end_controls_section();

		
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
		$icon = $settings['my_icon']['value'];
		$url = $settings['link']['url'];
		$target = $settings['link']['is_external'] ? ' target="_blank"' : '';
        $nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
 
		?>

		<div class="widget-info-icon">
			<div class="ct d-flex">
				<div class="wi-icon"><span class="icon <?php echo $icon; ?>"></span></div>
				<div class="wi-txt">
					<?php if ($url) { echo '<a href="' . $settings['link']['url'] . '"' . $target . $nofollow . '>'; } ?>	
					<?php echo $settings['text']; ?>
					<?php if ($url) { echo '</a>'; } ?>	
				</div>
			</div>
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
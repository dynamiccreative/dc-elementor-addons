<?php
/**
 * DCAEL Icon text.
 *
 * @version 0.11
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Icon_Text extends Widget_Base {
	
	
	public function get_name() {
		return 'widget-icon-text';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Widget Icon Text</span>', 'elementor-custom-element' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-bullet-list';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Icon Text', 'elementor' ),
			]
		);
        
        $this->add_control(
			'icon_text',
			[
				'label' => __( 'Icon text', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
			]
		);
		
		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				//'label_block' => true,
				//'default' => '',
			]
		);
		
		$this->add_control(
            'icon_color',
            [
                'label' => __( 'Icon Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon' => 'color: {{VALUE}};',
				],
            ]
        );
		
		$this->add_control(
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
                /*'condition' => [
                    'icon!' => '',
                ],*/
				'selectors' => [
					'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
		
		$this->add_control(
			'text',
			[
				'label' => __( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor' ),
			]
		);
		
        $this->add_control(
			'link',
			[
				'label' => __( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => true,
				],
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
                //'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .txt',
            ]
        );
		
		$this->end_controls_section();
		
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
        $icon = $settings['icon']['value'];
        $txt_icon = $settings['icon_text'];
        $target = $settings['link']['is_external'] ? ' target="_blank"' : '';
        $nofollow = $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
        $url = $settings['link']['url'];
        
        if($settings['icon_text'] != "") {
            $s = '<span class="icon">'.$txt_icon.'</span>';
        } else {
            $s = '<span class="icon ic '.$icon.'"></span>';
        }
		?>

		<div class="d-inline-flex align-items-center widget-icontext">
			<div class="rr-icon me-2 d-flex justify-content-center align-items-center"><?php echo $s; ?></div>
			<div class="txt"><?php echo $settings['text']; ?></div>
			<?php if ($url) { echo '<a href="' . $settings['link']['url'] . '"' . $target . $nofollow . '></a>'; } ?>
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}
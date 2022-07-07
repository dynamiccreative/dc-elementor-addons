<?php
/**
 * DCAEL Icon text V2.
 *
 * @version 0.1
 */

namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
class Widget_Icon_Text_v2 extends Widget_Base {
	
	
	public function get_name() {
		return 'widget-icon-text-v2';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Widget Icon Text V2</span>', 'elementor-custom-element' );
	}
	public function get_icon() {
		return 'dae-icon eicon-bullet-list';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Icon Text V2', 'elementor' ),
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
			'titre',
			[
				'label' => __( 'Titre', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
			]
		);
		
		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => esc_html__( 'List Item', 'elementor' ),
				'default' => esc_html__( 'List Item', 'elementor' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'fa-solid',
				],
				'fa4compatibility' => 'icon',
			]
		);

		$this->add_control(
			'icon_list',
			[
				'label' => esc_html__( 'Items', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'List Item #1', 'elementor' ),
						'selected_icon' => [
							'value' => 'fas fa-circle',
							'library' => 'fa-solid',
						],
					],
					[
						'text' => esc_html__( 'List Item #2', 'elementor' ),
						'selected_icon' => [
							'value' => 'fas fa-circle',
							'library' => 'fa-solid',
						],
					],
					[
						'text' => esc_html__( 'List Item #3', 'elementor' ),
						'selected_icon' => [
							'value' => 'fas fa-circle',
							'library' => 'fa-solid',
						],
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
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
					'{{WRAPPER}} .txt_color' => 'color: {{VALUE}};',
				],
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'icon_size',
                'label' => __( 'Typography', 'elementor' ),
                //'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .titre',
            ]
        );
		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style_top',
			[
				'label' => esc_html__( 'Icon Top', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color_top',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .icon svg' => 'fill: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_top',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 40,
				],
				'range' => [
					'px' => [
						'min' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-icon-list-item:hover .elementor-icon-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-icon' => '--e-icon-list-icon-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
        $icon = $settings['icon']['value'];
        $titre = $settings['titre'];

		$this->add_render_attribute( 'icon_list', 'class', 'elementor-icon-list-items' );
		$this->add_render_attribute( 'list_item', 'class', 'elementor-icon-list-item my-2' );
		?>

		<div class="widget-icontextv2">
			<div class="text-center mb-2">
				<div class="rr-icon"><span class="ic <?php echo $icon; ?>"></span></div>
				<div class="titre txt_color"><?php echo $titre; ?></div>
			</div>
			<ul <?php $this->print_render_attribute_string( 'icon_list' ); ?>>
				<?php
				foreach ( $settings['icon_list'] as $index => $item ) :
					$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );

					$this->add_render_attribute( $repeater_setting_key, 'class', 'elementor-icon-list-text txt_color' );

					$this->add_inline_editing_attributes( $repeater_setting_key );
					$migration_allowed = Icons_Manager::is_migration_allowed();
					?>
					<li <?php $this->print_render_attribute_string( 'list_item' ); ?>>
						<?php
						
						// add old default
						if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
							$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
						}

						$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
						$is_new = ! isset( $item['icon'] ) && $migration_allowed;
						if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) :
							?>
							<span class="elementor-icon-list-icon">
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
								} else { ?>
										<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
								<?php } ?>
							</span>
						<?php endif; ?>
						<span <?php $this->print_render_attribute_string( $repeater_setting_key ); ?>><?php $this->print_unescaped_setting( 'text', 'icon_list', $index ); ?></span>
						
					</li>
					<?php
				endforeach;
				?>
			</ul>
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}

<?php

namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Widget_Categorie_Box extends Widget_Base {

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_style( 'dc-categorie-box-css', plugin_dir_url( __DIR__ ).'css/dc-categorie-box.css','1.0.0', true );
	  }

	public function get_style_depends() {
		return array('dc-categorie-box-css');
	}


	public function get_name() {
		return 'categorie_box';
	}
	public function get_title() {
		return __( 'Categorie Box', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-gallery-grid';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
               
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Box', 'elementor' ),
			]
		);

		$this->add_control(
			'bouton_text', [
				'label' => __( 'Button text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Read more' , 'elementor-custom-element' ),
			]
		);
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
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
				//'separator' => 'before',
			]
		);

		/*$this->add_responsive_control(
            'nb_col',
            array(
                'label' => __('Colonne', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    	'px' => [
						'min' => 1,
						'max' => 4,
					]
                ),
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 1,
					'unit' => 'px',
				],
            )
        );*/

        /*$this->add_responsive_control(
			'nb_col',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Colonne', 'elementor' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'plugin-name' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'plugin-name' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'plugin-name' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'devices' => [ 'desktop', 'tablet' ],
				'prefix_class' => 'content-align-%s',
			]
		);*/

		$this->add_control(
			'nb_col',
			array(
				'label'     => __( 'Colonne', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'col-md-6',
				'options'   => array(
					'col-12'       => __( '1', 'elementor' ),
					'col-md-6'      => __( '2', 'elementor' ),
					'col-md-6 col-lg-4'       => __( '3', 'elementor' ),
					'col-md-6 col-lg-3' => __( '4', 'elementor' ),
				),
				//'selector' => '{{WRAPPER}} .item',
				'condition' => array(
					//'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'gutter',
			[
				'label' => esc_html__( 'Gutters', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],
				'default' => ['unit' => 'px', 'size' => 3],

			]
		);

		$this->add_control(
            'image_position',
            array(
                'label' => __('Image position', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => array(
                    'image-left' => array(
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-order-start',
                    ),
                    'image-top' => array(
                        'title' => __('Top', 'elementor'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'image-right' => array(
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-order-end',
                    ),
                ),
                'default' => 'image-left',
                //'prefix_class' => 'slider-nav-vertical-',
                'toggle' => true,
            )
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

		$this->end_controls_section();

		/**STYLE**/

		$this->start_controls_section(
			'ct_style',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				//'condition' => ['display_cat' => 'yes'],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typo',
				'label' => __( 'Title', 'elementor' ),
				'selector' => '{{WRAPPER}} .item-title a',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
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
                'separator' => 'after',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label' => esc_html__( 'Description Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item-desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typo',
				'label' => __( 'Description', 'elementor' ),
				'selector' => '{{WRAPPER}} .item-desc',
			]
		);

		$this->add_responsive_control(
			'desc_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .item-desc' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
                'separator' => 'after',
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .item .ct' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'elementor' ),
				'selector' => '{{WRAPPER}} .item .ct',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'box_border',
				'selector' => '{{WRAPPER}} .item .ct',
				//'separator' => 'before',
			]
		);
		$this->add_control(
			'box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .item .ct' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .ct' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
				'label' => esc_html__( 'Color Text', 'elementor-pro' ),
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
				//'separator' => 'before',
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
		<a href="<?php echo $l;?>"><?php echo $t; ?></a>
		</<?php Utils::print_validated_html_tag( $tag ); ?>>
		<?php
	}

	protected function render() {
        $settings = $this->get_settings_for_display();

        $colonne = $settings['image_position'] != 'image-top' ? 'item-colonne d-md-flex flex-wrap' : 'd-flex flex-column';
        $img_right_class = $settings['image_position'] === 'image-right' ? 'image-right' : '';

        // ATTRIBUTES
	    $this->add_render_attribute( array(
	    	'widget' => array(
	    		'class' => array('elementor-categorie-box row', $img_right_class, 'g-'.$settings['gutter']['size']),
	    	),
	    	'item' => array(
	    		'class' => array($settings['nb_col'],'item'),
	    	),
	    	'ct' => array(
	    		'class' => array($colonne,'ct'),
	    	),
	    ));

		if ( $settings['list'] ) {
			?>
			<div <?php echo $this->get_render_attribute_string('widget'); ?>>  
			<?php
			foreach (  $settings['list'] as $item ) {
				$url = $item['bloc_url']['url'];
                ?>
                <div <?php echo $this->get_render_attribute_string('item'); ?>>
                    <div <?php echo $this->get_render_attribute_string('ct'); ?>>
                		<div class="item-img">
                			<a href="<?php echo $url; ?>">
                        	<?php echo wp_get_attachment_image( $item['bloc_image']['id'], $settings['thumbnail_size'] ); ?>
                        	</a>
                        </div> 
                        <div class="item-text d-flex flex-column">                 	
                    		<?php $this->render_title($url, $item['bloc_title']); ?>
                        	<div class="item-desc"><?php echo $item['bloc_desc']; ?></div>
                        	<div class="item-link mt-auto">
                        		<a class="item-btn" href="<?php echo $url; ?>"><?php echo $settings['bouton_text']; ?></a>
                        	</div>
                        </div>                 
                    </div>
                </div>
                <?php
			}
			?>
			</div>
			<?php
		}
	}
}
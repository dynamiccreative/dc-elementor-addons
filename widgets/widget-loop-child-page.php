<?php
/**
 * DCAEL Loop Child Page.
 *
 * @version 0.1
 */

namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
class Widget_Loop_Child_Page extends Widget_Base {
	
	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_style( 'dae-loop-child-page-css', plugin_dir_url( __DIR__ ).'css/dae-loop-child-page.css','1.0.0', true );
  	}

  	public function get_style_depends() {
		return array('dae-loop-child-page-css');
	}

	
	public function get_name() {
		return 'widget-loop-child-page';
	}
	public function get_title() {
		return __( 'Loop Child Page', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-bullet-list';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
		$wp_additional_image_sizes = wp_get_additional_image_sizes();
        $sizes = get_intermediate_image_sizes();
        $tab = [];
        //
		$tab[ 'none' ] = __( 'Pas de size', 'elementor-addons-project' );
        
        foreach($sizes as $size) :
            if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $txt = get_option( $size . '_size_w' ).'x'.get_option( $size . '_size_h' );
            } elseif ( isset( $wp_additional_image_sizes[ $size ] ) ) {
                $txt =  $wp_additional_image_sizes[ $size ]['width'].'x'.$wp_additional_image_sizes[ $size ]['height'];
            }
        
			$tab[ esc_attr($size) ] = $size.' '.$txt;
		endforeach;
        

		$this->start_controls_section(
			'section_url',
			[
				'label' => esc_html__( 'Loop Child Page', 'elementor' ),
			]
		);

		$this->add_control(
			'image_size',
			[
				'label' => __( 'Image size', 'elementor-addons-project' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => $tab
			]
		);
        
        /*
		$this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
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
			'link',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
			]
		);
        
        $this->add_control(
			'prefix',
			[
				'label' => esc_html__( 'Prefix', 'elementor' ),
				'type' => Controls_Manager::TEXT,
			]
		);

		*/
		$this->end_controls_section();
        
        $this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        /*
		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a .elementor-icon-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} a .elementor-icon-icon svg' => 'fill: {{VALUE}};'
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
					'{{WRAPPER}} a.elementor-icon-item:hover .elementor-icon-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} a.elementor-icon-item:hover .elementor-icon-icon svg' => 'fill: {{VALUE}};'
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
					'{{WRAPPER}} .elementor-icon-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-icon-icon svg' => 'width: {{SIZE}}{{UNIT}}; height:auto; vertical-align: middle;',
				],
			]
		);
		*/
        
        $this->end_controls_section();

        /*
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-icon-item .elementor-icon-text' => 'color: {{VALUE}};',
				],
				'global' => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label' => esc_html__( 'Hover', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a.elementor-icon-item:hover .elementor-icon-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_indent',
			[
				'label' => esc_html__( 'Text Indent', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-text' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_alignment',
			[
				'type' => Controls_Manager::CHOOSE,
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .elementor-icon-text',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .elementor-icon-text',
			]
		);

		$this->end_controls_section();
		*/
	}
	protected function render( $instance = [] ) {
		$settings = $this->get_settings_for_display();
		$size_img = $settings['image_size'];
        
		global $post;
	    $id = ( is_page() && $post->post_parent ) ? $post->post_parent : $post->ID;
	    $childpages = get_pages( 'sort_column=menu_order&title_li=&child_of=' . $id . '&echo=0' );
	    ?>
	    <div class="widget-loop-child-page row">
	    <?php
	    foreach ( $childpages as $page ) {
	    	?>
	    	<div class="item col-lg-4 col-md-6 text-center"><div class="ct">
	    		<div class="item-img"><a href="<?php echo get_page_link( $page->ID ); ?>"><?php echo get_the_post_thumbnail( $page->ID, $size_img, array() ); ?></a></div>
	    		<h3 class="item-title"><a href="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></a></h3>
	    	</div></div>	
	    	<?php
	    }
		?>	
		</div>

		<?php
	}
	protected function content_template() {}
	public function render_plain_content( $instance = [] ) {}
}

<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Bloc_Picto_Text extends Widget_Base {
	public function get_name() {
		return 'slider_bloc_picto_text';
	}
	public function get_title() {
		return __( '<span style="color:#fff; background:#1463DD; font-weight:500; padding:5px;">Bloc Picto/Text</span>', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-slider-push';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
        
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Bloc Picto Text', 'elementor' ),
			]
		);
		
		$this->add_control(
			'classe', [
				'label' => __( 'Classe', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Classe' , 'elementor' ),
				'label_block' => true,
			]
		);
       
		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
	
		$this->add_control(
			'title', [
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Title' , 'elementor' ),
				'label_block' => true,
			]
		);
	
        $this->add_control(
			'description', [
				'label' => __( 'Description', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'Description' , 'elementor' ),
				'label_block' => true,
			]
		);

        
        $this->add_control(
			'url_bloc',
			[
				'label' => __( 'Url', 'elementor' ),
				'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true
                ],
                'show_external' => true,
				'default' => [
					'url' => '',
				],
			]
		);
        
        $this->add_control(
            'btn_color',
            [
                'label' => __( 'Button/Title Color', 'elementor' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .btn-info' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .desc a' => 'color: {{VALUE}};'
				],
            ]
        );
        
		$this->end_controls_section();
        
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __( 'Title', 'elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_txt',
                'label' => __( 'Description', 'elementor' ),
                //'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .title',
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
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_txt',
                'label' => __( 'Description', 'elementor' ),
                //'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .desc',
            ]
        );
        
		$this->end_controls_section();
         
	}
        
	protected function render() {
        $settings = $this->get_settings_for_display();
		$target = $settings['url_bloc']['is_external'] ? ' target="_blank"' : '';
        $image_url_th = wp_get_attachment_image_url($settings['image']['id'], '');

		?>
		<div class="elementor-bloc-icon-text h-100 <?php echo $settings['title']; ?>">
			<div class="ct h-100 d-flex flex-column">
				<div class="ct-img"><img src="<?php echo $image_url_th; ?>"/></div>
				<div class="ct-txt mt-2">
					<h2 class="title"><?php echo $settings['title']; ?></h2>
					<div class="desc py-4"><?php echo $settings['description']; ?></div>
				</div>  
                <?php if($settings['url_bloc']['url']) { ?>
				<div class="mt-auto"><a class="btn btn-info stretched-link" href="<?php echo $settings['url_bloc']['url']; ?>" <?php echo $target; ?>><span>En savoir plus</span></a></div>
                <?php } ?>
			</div>
		</div>
		<?php
		
	}
}
<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Slider_Temoin extends Widget_Base {
	public function get_name() {
		return 'slider_temoin';
	}
	public function get_title() {
		return __( '<span style="color:#fff; background:#1463DD; font-weight:500; padding:5px;">Slider Temoin</span>', 'elementor' );
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
				'label' => esc_html__( 'Slider Temoin', 'elementor' ),
			]
		);
                
        $repeater = new Repeater();
		
        $repeater->add_control(
			'list_title', [
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'elementor' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_content', [
				'label' => __( 'Content', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => __( 'List Content' , 'elementor' ),
				'show_label' => false,
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
						'list_title' => __( 'Title #1', 'elementor' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'elementor' ),
					],
					[
						'list_title' => __( 'Title #2', 'elementor' ),
						'list_content' => __( 'Item content. Click the edit button to change this text.', 'elementor' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();
	}
        
	protected function render() {
        $settings = $this->get_settings_for_display();
                
		if ( $settings['list'] ) {
            
			?>
			<div class="elementor-slider-temoin slider_style_bottom">
				<div class="slider_temoin">
			<?php
			foreach (  $settings['list'] as $item ) {
                ?>
                <div class="item elementor-repeater-item-<?php echo $item['_id'];?>">
                    <div class="ct">
						<h2 class="titre"><?php echo $item['list_title']; ?></h2>
						<div class="desc py-4"><?php echo $item['list_content']; ?></div>               
                    </div>
                </div>
                <?php
			}
			?>
				</div>
			</div>
			<?php
		}
	}
}
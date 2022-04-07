<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Slider_Bloc extends Widget_Base {
	public function get_name() {
		return 'slider_bloc';
	}
	public function get_title() {
		return __( '<span style="color:#fff; background:#1463DD; font-weight:500; padding:5px;">Slider Bloc</span>', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-slider-push';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
        $wp_additional_image_sizes = wp_get_additional_image_sizes();
        $sizes = get_intermediate_image_sizes();
        $tab = [];
        // Pushes an empty template..
		$tab[ 'none' ] = __( 'Pas de size', 'elementor-custom-element' );
        
        foreach($sizes as $size) :
            if ( in_array( $size, array( 'thumbnail', 'medium', 'large' ) ) ) {
                $txt = get_option( $size . '_size_w' ).'x'.get_option( $size . '_size_h' );
            } elseif ( isset( $wp_additional_image_sizes[ $size ] ) ) {
                $txt =  $wp_additional_image_sizes[ $size ]['width'].'x'.$wp_additional_image_sizes[ $size ]['height'];
            }
        
			$tab[ esc_attr($size) ] = $size.' '.$txt;
		endforeach;
        
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Slider Bloc', 'elementor' ),
			]
		);
        
        $this->add_control(
			'image_size',
			[
				'label' => __( 'Image size', 'elementor-custom-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => $tab
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

		$repeater->add_control(
			'list_image',
			[
				'label' => __( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
        
        $repeater->add_control(
			'list_image2',
			[
				'label' => __( 'Choose Image 2', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
        
        $repeater->add_control(
			'url_slide',
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
        $target = $settings['url_slide']['is_external'] ? ' target="_blank"' : '';
        $size_img = $settings['image_size'];
        
		if ( $settings['list'] ) {
            
			?>
			<div class="elementor-slider-bloc slider_style_bottom">
                
                <div class="ct-slider">
                    <div class="slider_bloc">
			<?php
			foreach (  $settings['list'] as $item ) {
                $image_url_th = wp_get_attachment_image_url($item['list_image']['id'], $size_img);
                $image_url_th2 = wp_get_attachment_image_url($item['list_image2']['id'], $size_img);
                ?>
                <div class="item elementor-repeater-item-<?php echo $item['_id'];?>">
                    <div class="ct row">
                        <div class="col-sm-4 order-2 order-sm-1">
                            <h2 class="titre_flamme"><?php echo $item['list_title']; ?></h2>
                            <div class="desc py-4"><?php echo $item['list_content']; ?></div>
                            <div><a class="btn btn-primary" href="<?php echo $item['url_slide']['url']; ?>" <?php echo $target; ?>>En savoir plus</a></div>
                        </div>
                        <div class="col-sm-8 d-flex order-1 order-sm-2 mb-2 mb-sm-0">
                            <div class="img1 col"><img src="<?php echo $image_url_th; ?>"/></div>
                            <div class="img2 col ps-4"><img src="<?php echo $image_url_th2; ?>"/></div>
                        </div>                        
                    </div>
                </div>
                <?php
			}
			?>
            </div></div>
			<div class="compteur d-flex"><div class="txt my-auto"></div></div>
			</div>
			<?php
		}
	}
	//protected function content_template() {}
	//public function render_plain_content( $instance = [] ) {}
}
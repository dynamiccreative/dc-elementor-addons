<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Bloc_Carre extends Widget_Base {
	public function get_name() {
		return 'Bloc Carre';
	}
	public function get_title() {
		return __( '<span style="color:#fff; background:#1463DD; font-weight:500; padding:5px;">Bloc Carre</span>', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-gallery-grid';
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
				'label' => esc_html__( 'Bloc Carre', 'elementor' ),
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
			'bloc_title', [
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'List Title' , 'elementor' ),
				'label_block' => true,
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
	}
        
	protected function render() {
        $settings = $this->get_settings_for_display();
        $target = $settings['bloc_url']['is_external'] ? ' target="_blank"' : '';
        $size_img = $settings['image_size'];

		if ( $settings['list'] ) {
			?>
			<div class="elementor-bloc-carre row g-3">  
			<?php
			foreach (  $settings['list'] as $item ) {
				$url = $item['bloc_url']['url'];
                $image_url_th = wp_get_attachment_image_url($item['bloc_image']['id'], $size_img);
                ?>
                <div class="item elementor-repeater-item-<?php echo $item['_id'];?>">
                    <div class="ct d-flex" style="background-image:url(<?php echo $image_url_th; ?>);">
                        <div class="mt-auto">
							<?php if ($url) { echo '<a href="'.$url.'" '.$target.'>'; } ?>
                            <h3><?php echo $item['bloc_title']; ?></h3>
							<?php if ($url) { echo '</a>'; } ?>
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
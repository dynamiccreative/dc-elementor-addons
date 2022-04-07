<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Gallery_Slick extends Widget_Base {
	public function get_name() {
		return 'gallery_slick';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Gallery Slick</span>', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-slides';
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
				'label' => esc_html__( 'Gallery Slick', 'elementor' ),
			]
		);
		
		$this->add_control(
			'some_text',
			[
				'label' => __( 'Classe CSS', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
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
        
		/*$this->add_control(
			'posts_per_page',
			[
				'label' => __( 'Number of Posts', 'elementor-custom-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 5,
				'options' => [
					1 => __( 'One', 'elementor-custom-element' ),
					2 => __( 'Two', 'elementor-custom-element' ),
					5 => __( 'Five', 'elementor-custom-element' ),
					10 => __( 'Ten', 'elementor-custom-element' ),
				]
			]
		);*/
        
        $this->add_control(
			'carousel',
			[
				'label' => __( 'Add Images', 'elementor' ),
				'type' => Controls_Manager::GALLERY,
				'default' => [],
			]
		);
		
		$this->end_controls_section();
	}
    
    /*function _get_all_image_sizes() {
        global $_wp_additional_image_sizes;

        $default_image_sizes = get_intermediate_image_sizes();

        foreach ( $default_image_sizes as $size ) {
            $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
            $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
            $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
        }

        if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
            $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
        }

        return $image_sizes;
    }*/
    
	protected function render() {
        $settings = $this->get_settings_for_display();
        $custom_text = $settings['some_text'];
        $size_img = $settings['image_size'];
		// get our input from the widget settings.
		//$custom_text = ! empty( $instance['some_text'] ) ? $instance['some_text'] : ' (no text was entered ) ';
		//$post_count = ! empty( $instance['posts_per_page'] ) ? (int)$instance['posts_per_page'] : 5;
        
        
        

		if ( empty( $settings['carousel'] ) ) {
			return;
		}

		$slides = [];
        foreach ( $settings['carousel'] as $index => $attachment ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], $size_img, $settings );

			$image_html = '<img class="slick-slide-image" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '" />';

			//$link = $this->get_link_url( $attachment, $settings );
            $slide_html = '<div class="item">' . $image_html;
            
            $slide_html .= '</div>';
            $slides[] = $slide_html;
        }
        
        if ( empty( $slides ) ) {
			return;
		}
        
		?>
		<div class="elementor-gallery-slick slider_style_bottom">
			<div class="wgs <?php echo esc_html( $custom_text );?>">
				<?php echo implode( '', $slides ); ?>
			</div>
			<div class="compteur d-flex"><div class="txt my-auto"></div></div>
		</div>
		
		<?php
	}
	//protected function content_template() {}
	//public function render_plain_content( $instance = [] ) {}
}
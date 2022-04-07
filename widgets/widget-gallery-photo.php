<?php
//https://code.elementor.com/classes/elementor-widget_image_carousel/
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Widget_Gallery_Photo extends Widget_Base {
	public function get_name() {
		return 'gallery_photo';
	}
	public function get_title() {
		return __( '<span style="color:#1463DD;">Gallery Photo</span>', 'elementor' );
	}
	public function get_icon() {
		// Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
		return 'dae-icon eicon-gallery-justified';
	}
	
	public function get_categories() {
		return ['dc-addons'];
	}
	
	protected function register_controls() {
        /*$wp_additional_image_sizes = wp_get_additional_image_sizes();
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
		endforeach;*/
        
		$this->start_controls_section(
			'section_my_custom',
			[
				'label' => esc_html__( 'Gallery Photo', 'elementor' ),
			]
		);
		
        $this->add_control(
			'titre_text',
			[
				'label' => __( 'Titre', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
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
			'bt_text',
			[
				'label' => __( 'Bouton', 'elementor-custom-element' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => __( 'Enter some text', 'elementor-custom-element' ),
			]
		);
        
        /*$this->add_control(
			'image_size',
			[
				'label' => __( 'Image size', 'elementor-custom-element' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'thumbnail',
				'options' => $tab
			]
		);*/
        
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
        $bt_text = $settings['bt_text'];
        $titre_text = $settings['titre_text'];
        //$size_img = $settings['image_size'];
		// get our input from the widget settings.
		//$custom_text = ! empty( $instance['some_text'] ) ? $instance['some_text'] : ' (no text was entered ) ';
		//$post_count = ! empty( $instance['posts_per_page'] ) ? (int)$instance['posts_per_page'] : 5;
        
        
        

		if ( empty( $settings['carousel'] ) ) {
			return;
		}

		$slides = [];
        foreach ( $settings['carousel'] as $index => $attachment ) {
            $image_url_th = wp_get_attachment_image_url($attachment['id'], 'image-slider-full'); //Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );
            $image_url = wp_get_attachment_image_url($attachment['id'], 'large');
            $image_caption = wp_get_attachment_caption( $attachment['id'] );
            //Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'large', $settings );

			$image_html = '<img class="slick-slide-image" src="' . esc_attr( $image_url_th ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '" /></a>';

			//$link = $this->get_link_url( $attachment, $settings );
            $slide_html = '<div class="item"><a href="'.esc_attr( $image_url ).'" data-elementor-lightbox-slideshow="'.esc_html( $custom_text ).'" data-title="My Gallery">' . $image_html;
            
            if ($image_caption == "©") {
                $slide_html .= '<div class="copy">'.__("All rights reserved", "dynamic-child").'</div>';
            }
            
            $slide_html .= '</a></div>';
            $slides[] = $slide_html;
        }
        
        if ( empty( $slides ) ) {
			return;
		}
        
		?>
        
        <div class="widget-gallery-photo">
            <div class="bt-lb"><button type="button" class="d-inline-flex align-items-center btn btn-info btn-gallery icon"><span class="icon-photo"></span><?php echo $bt_text; ?></button></div>

            <div class="lb-widget <?php echo esc_html( $custom_text );?>">
                <button type="button" class="btn-close"><i class="eicon-close"></i></button>
                <div class="container-1260">
                    <?php if($titre_text) { ?><div class="titre text-center"><?php echo $titre_text; ?></div><?php } ?>
                    <div class="d-flex justify-content-center"><div class="ct d-flex flex-wrap">
                    <?php echo implode( '', $slides ); ?>
                    </div></div>
                </div>
            </div>
		</div>
		
		<?php
	}
	//protected function content_template() {}
	//public function render_plain_content( $instance = [] ) {}
}
<?php
/**
 * DCAEL Fluent Form.
 *
 * @version 0.12
 */

namespace Elementor;

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

/**
 * Elementor Poste Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.1
 */
class Widget_Fluent_Form extends Widget_Base {

	public function __construct($data = [], $args = null) {
	    parent::__construct($data, $args);
	    wp_register_style( 'dc-fluent-form-css', plugin_dir_url( __DIR__ ).'css/dc-fluent-form.css','1.0.0', true );
	  }
    
	public function get_style_depends() {
		return array('dc-fluent-form-css');
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'dc_fluent_form';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Fluent Form Styler', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'dae-icon eicon-form-horizontal';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return ['dc-addons'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'form', 'fluent' ];
	}
	
	/**
	 * Get Fluent form list
	 *
	 * @return array
	 */
    public static function get_fluent_forms() {

		$forms = array();

		if ( function_exists( 'wpFluentForm' ) ) {

			$ff_list = wpFluent()->table( 'fluentform_forms' )
					->select( array( 'id', 'title' ) )
					->orderBy( 'id', 'DESC' )
					->get();

			if ( $ff_list ) {

				$forms[0] = esc_html__( 'Select', 'dcael' );
				foreach ( $ff_list as $form ) {
					$forms[ $form->id ] = $form->title . ' (' . $form->id . ')';
				}
			} else {

				$forms[0] = esc_html__( 'No Forms Found!', 'dcael' );
			}
		}

		return $forms;
	}
    
     
	
	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		
		
		$this->start_controls_section(
			'query_section',
			[
				'label' => __( 'Query', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);


		$this->add_control(
			'form_id',
			array(
				'label'   => __( 'Select Form', 'dcael' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->get_fluent_forms(),
				'default' => '0',
			)
		);

    
		$this->end_controls_section();

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Form Fields', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
        
        
        $this->add_responsive_control(
			'form_input_padding',
			array(
				'label'              => __( 'Padding', 'dcael' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px' ),
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform select.ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform .select2-selection' => 'padding-top: calc( {{TOP}}{{UNIT}} - 2{{UNIT}} ); padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: calc( {{BOTTOM}}{{UNIT}} - 2{{UNIT}} ); padding-left: {{LEFT}}{{UNIT}};',
				),
				'separator'          => 'after',
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'form_input_bgcolor',
			array(
				'label'     => __( 'Background Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fafafa',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-net-label,
					{{WRAPPER}} .dcael-ff-style .fluentform .select2-selection' => 'background-color:{{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_label_color',
			array(
				'label'     => __( 'Label Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--label label,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input + span,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-section-title,
					{{WRAPPER}} .dcael-ff-style .ff-section_break_desk,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff_tc_checkbox +  div.ff_t_c' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_input_color',
			array(
				'label'     => __( 'Input Text / Placeholder Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_TEXT,
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control::-webkit-input-placeholder, {{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform input[type=checkbox]:checked:before,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-net-label span,
					{{WRAPPER}} .dcael-ff-style .dcael-ff-select-custom:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-ratings.jss-ff-el-ratings label.active svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_required_color',
			array(
				'label'     => __( 'Required Asterisk Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--label.ff-el-is-required.asterisk-right label:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--label.ff-el-is-required.asterisk-left label::before' => 'color: {{VALUE}};'
				),
			)
		);

		
        
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ff_border',
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff_net_table tbody tr td,
					{{WRAPPER}} .dcael-ff-style .fluentform .select2-selection',
				//'separator' => 'before',
			]
		);

		/*$this->add_control(
			'ff_border_color',
			array(
				'label'     => __( 'Border Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'ff_style' => 'underline',
				),
				'default'   => '#c4c4c4',
				'selectors' => array(
					'{{WRAPPER}}.dcael-ff-style-underline .fluentform .ff-el-form-control,
					{{WRAPPER}}.dcael-ff-style-underline .fluentform .ff-el-form-check-input,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff_net_table tbody tr td,
					{{WRAPPER}} .dcael-ff-style .fluentform .select2-selection' => 'border-color: {{VALUE}};',
				),
			)
		);*/

		/*$this->add_control(
			'ff_border_active_color',
			array(
				'label'     => __( 'Border Active Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'input_border_style!' => 'none',
					'ff_style'            => 'box',
				),
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform input:focus,
					{{WRAPPER}} .dcael-ff-style .fluentform select:focus,
					{{WRAPPER}} .dcael-ff-style .fluentform textarea:focus,
					{{WRAPPER}} .dcael-ff-style .fluentform input[type=checkbox]:checked:before' => 'border-color: {{VALUE}};',
				),
			)
		);*/
        
        $this->add_responsive_control(
			'form_border_radius',
			array(
				'label'              => __( 'Rounded Corners', 'dcael' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px' ),
				'default'            => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-control,
					{{WRAPPER}} .dcael-ff-style .fluentform input[type=checkbox],
					{{WRAPPER}} .dcael-ff-style .fluentform .select2-selection' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_net_table tbody tr td:first-of-type' => 'border-radius: {{TOP}}{{UNIT}} 0 0 {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_net_table tbody tr td:last-child' => 'border-radius: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} 0;',
					'{{WRAPPER}} .dcael-ff-style .fluentform .iti__selected-flag' => 'border-top-left-radius:{{LEFT}}{{UNIT}}; border-bottom-left-radius:{{LEFT}}{{UNIT}};',
				),
				'frontend_available' => true,
			)
		);


		$this->end_controls_section();
        
        $this->start_controls_section(
			'ff_radio_check_style',
			array(
				'label' => __( 'Radio & Checkbox', 'dcael' ),
			)
		);

		$this->add_control(
			'ff_radio_check_size',
			array(
				'label'      => _x( 'Size', 'dcael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 15,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input'  => 'width: {{SIZE}}{{UNIT}}!important; height:{{SIZE}}{{UNIT}}; font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
				),
				'separator'  => 'after',
			)
		);

		$this->add_control(
			'ff_radio_check_bgcolor',
			array(
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-group input[type="checkbox"]' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-group input[type="radio"]' => 'background-color: {{VALUE}};',
				),
				'default'   => '#fafafa',
			)
		);

		$this->add_control(
			'ff_checked_color',
			array(
				'label'     => __( 'Selected Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					//'{{WRAPPER}} .dcael-ff-style .ff-el-group input:checked[type="checkbox"]' => ' background-image: url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 20 20\'%3e%3cpath fill=\'none\' stroke=\'{{VALUE}}\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'3\' d=\'M6 10l3 3l6-6\'/%3e%3c/svg%3e");',
				),
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_SECONDARY,
                ],
			)
		);

		$this->add_control(
			'ff_select_color',
			array(
				'label'     => __( 'Label Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input + span,
					{{WRAPPER}}.dcael-ff-check-yes .dcael-ff-style .fluentform .ff_tc_checkbox +  div.ff_t_c' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ff_check_border_color',
			array(
				'label'     => __( 'Border Color', 'dcael' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eaeaea',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ff_check_border_width',
			array(
				'label'      => __( 'Border Width', 'dcael' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'    => array(
					'size' => '1',
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_control(
			'ff_check_border_radius',
			array(
				'label'      => __( 'Checkbox Rounded Corners', 'dcael' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .ff-el-group input[type="checkbox"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
					'unit'   => 'px',
				),
			)
		);
        
        $this->add_control(
			'ff_check_spacing',
			[
				'label' => esc_html__( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_tc_checkbox .ff-el-form-check-input' => 'margin-right: calc({{SIZE}}{{UNIT}} + 5px);',
				],
                'separator' => 'before',
			]
		);

		$this->add_control(
			'ff_check_margin',
			[
				'label' => esc_html__( 'Margin Top', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default'   => ['size' => '1','unit' => 'px'],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-group input[type="checkbox"]' => 'margin-top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-group input[type="radio"]' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();
        
        
        /*BUTTON*/
        $this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Button', 'elementor' ),
			)
		);

		$this->add_control(
			'ff_buttons',
			array(
				'label' => __( 'Submit And Navigation Button', 'elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'              => __( 'Submit Button Alignment', 'elementor' ),
				'type'               => Controls_Manager::CHOOSE,
				'options'            => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'            => 'left',
				'condition'          => array(
					//'form_title_option' => 'yes',
				),
				'prefix_class'       => 'dcael-ff-button-align-',
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper' => 'text-align: {{VALUE}};',
				),
				'toggle'             => false,
				'frontend_available' => true,
			)
		);

		/*$this->add_control(
			'btn_size',
			array(
				'label'        => __( 'Size', 'uael' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'sm',
				'options'      => array(
					'xs' => __( 'Extra Small', 'uael' ),
					'sm' => __( 'Small', 'uael' ),
					'md' => __( 'Medium', 'uael' ),
					'lg' => __( 'Large', 'uael' ),
					'xl' => __( 'Extra Large', 'uael' ),
				),
				'prefix_class' => 'uael-ff-btn-size-',
			)
		);*/

		$this->add_responsive_control(
			'ff_button_padding',
			array(
				'label'              => __( 'Padding', 'elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'frontend_available' => true,
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'elementor' ),
			)
		);

		$this->add_control(
			'button_text_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'btn_background_color',
				'label'          => __( 'Background Color', 'elementor' ),
				'types'          => array( 'classic', 'gradient' ),
                'exclude'        => array( 'image' ),
				'selector'       => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
				{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'btn_border',
				'label'       => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
				{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary',
			)
		);

		$this->add_responsive_control(
			'btn_border_radius',
			array(
				'label'              => __( 'Border Radius', 'elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%' ),
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'frontend_available' => true,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit,
				{{WRAPPER}} .step-nav button.ff-btn-secondary',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'elementor' ),
			)
		);

		$this->add_control(
			'btn_hover_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit:hover,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ff_button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit:hover,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'button_background_hover_color',
				'label'    => __( 'Background Color', 'elementor' ),
				'types'    => array( 'classic', 'gradient' ),
                'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit:hover,
				{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary:hover',
			)
		);

		$this->add_control(
			'button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_submit_btn_wrapper button.ff-btn-submit:hover,
					{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'ff_secondary_button',
			array(
				'label'     => __( 'Upload Button', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'ff_secondary_button_padding',
			array(
				'label'              => __( 'Padding', 'elementor' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', 'em', '%' ),
				'selectors'          => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'frontend_available' => true,
			)
		);

		$this->start_controls_tabs( 'tabs_secondary_button_style' );

		$this->start_controls_tab(
			'tab_secondary_button_normal',
			array(
				'label' => __( 'Normal', 'elementor' ),
			)
		);

		$this->add_control(
			'secondary_button_text_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'secondary_button_bg_color',
			array(
				'label'     => __( 'Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'secondary_button_border',
				'label'       => __( 'Border', 'elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn',
			)
		);

		$this->add_responsive_control(
			'secondary_button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'secondary_button_box_shadow',
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_secondary_button_hover',
			array(
				'label' => __( 'Hover', 'elementor' ),
			)
		);

		$this->add_control(
			'secondary_button_hover_color',
			array(
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ff_secondary_button_hover_border_color',
			array(
				'label'     => __( 'Border Hover Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'secondary_button_background_hover_color',
				'label'    => __( 'Background Color', 'elementor' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn:hover',
			)
		);

		$this->add_control(
			'secondary_button_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_border!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
        
        /*ERROR MESSAGE*/
        $this->start_controls_section(
			'form_error_field',
			array(
				'label' => __( 'Success / Error Message', 'elementor' ),
			)
		);
		$this->add_control(
			'form_error',
			array(
				'label' => __( 'Field Validation', 'elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ff_message_typo',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-is-error .error',
			)
		);

		$this->add_control(
			'form_error_msg_color',
			array(
				'label'     => __( 'Message Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff0000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-is-error .error' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'field_validation_padding',
			array(
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-is-error .error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_error_field_background',
			array(
				'label'        => __( 'Advanced Settings', 'elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'elementor' ),
				'label_off'    => __( 'No', 'elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'prefix_class' => 'dcael-ff-error-',
			)
		);

			$this->add_control(
				'form_error_field_bgcolor',
				array(
					'label'     => __( 'Field Background Color', 'elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => array(
						'form_error_field_background!' => '',
					),
					'selectors' => array(
						'{{WRAPPER}}.dcael-ff-error-yes .dcael-ff-style .fluentform .ff-el-is-error .ff-el-form-control' => 'background-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'form_error_border_color',
				array(
					'label'     => __( 'Highlight Border Color', 'elementor' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ff0000',
					'condition' => array(
						'form_error_field_background!' => '',
					),
					'selectors' => array(
						'{{WRAPPER}}.dcael-ff-error-yes .dcael-ff-style .fluentform .ff-el-is-error .ff-el-form-control' => 'border-color: {{VALUE}};',
						'{{WRAPPER}}.dcael-ff-error-yes .dcael-ff-style .fluentform .ff-el-is-error .ff-el-form-control' => 'border: {{input_border_size.BOTTOM}}px {{input_border_style.VALUE}} {{VALUE}} !important;',
						'{{WRAPPER}}.dcael-ff-error-yes .dcael-ff-style .fluentform .ff-el-is-error .ff-el-form-control' => 'border-width: 0 0 {{ff_border_bottom.SIZE}}px 0 !important; border-style: solid; border-color:{{VALUE}};',
					),
				)
			);

		$this->add_control(
			'form_success_message',
			array(
				'label'     => __( 'Form Success Validation', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'success_align',
			array(
				'label'        => __( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'   => array(
						'title' => __( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'      => 'left',
				'selectors'    => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'text-align: {{VALUE}};',
				),
				'toggle'       => false,
				'prefix_class' => 'dcael-ff-message-align-',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'ff_success_validation_typo',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success',
			)
		);

		$this->add_responsive_control(
			'form_valid_message_padding',
			array(
				'label'      => __( 'Padding', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '10',
					'bottom' => '10',
					'left'   => '10',
					'right'  => '10',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'form_success_message_color',
			array(
				'label'     => __( 'Message Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success'   => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_valid_bgcolor',
			array(
				'label'     => __( 'Message Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_border_size',
			array(
				'label'      => __( 'Border Size', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'    => '2',
					'bottom' => '2',
					'left'   => '2',
					'right'  => '2',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'border-top: {{TOP}}{{UNIT}}; border-right: {{RIGHT}}{{UNIT}}; border-bottom: {{BOTTOM}}{{UNIT}}; border-left: {{LEFT}}{{UNIT}}; border-style: solid;',
				),
			)
		);

		$this->add_control(
			'form_valid_border_color',
			array(
				'label'     => __( 'Border Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'form_valid_border_radius',
			array(
				'label'      => __( 'Rounded Corners', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-message-success' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
        
        
        /*STEP*/
        $this->start_controls_section(
			'section_step',
			array(
				'label' => __( 'Step', 'elementor' ),
			)
		);
        
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'step_typo',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::before',
			)
		);
        
        $this->add_control(
			'step_width',
			[
				'label' => esc_html__( 'Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 30],
				'range' => [
					'px' => [
                        'min' => 30,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
			'step_border_radius',
			array(
				'label'      => __( 'Rounded Corners', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

        $this->add_control(
			'step_title',
			array(
				'label'     => __( 'Title', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'step_typo_title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li span',
			)
		);

		/*$this->add_control(
			'step_color_title',
			array(
				'label'     => __( 'Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'step_color_title_active',
			array(
				'label'     => __( 'Color Active', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.active span' => 'color: {{VALUE}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_completed span' => 'color: {{VALUE}};',
				),
			)
		);*/

		$this->add_control(
			'step_default_message',
			array(
				'label'     => __( 'Step Default', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
        
        $this->add_control(
			'step_bgcolor_default',
			array(
				'label'     => __( 'Step Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::before' => 'background-color: {{VALUE}};',
				),
			)
		);
        
        $this->add_control(
			'step_color_default',
			array(
				'label'     => __( 'Step Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color_default',
			array(
				'label'     => __( 'Title', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li' => 'color: {{VALUE}};'
				),
			)
		);
        
        $this->add_control(
			'step_active_message',
			array(
				'label'     => __( 'Step Active', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
        
        $this->add_control(
			'step_bgcolor_active',
			array(
				'label'     => __( 'Step Background Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_active::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_completed::before' => 'background-color: {{VALUE}};',
				),
			)
		);
        
        $this->add_control(
			'step_color_active',
			array(
				'label'     => __( 'Step Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_active::before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_completed::before' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color_active',
			array(
				'label'     => __( 'Title', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#008000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_active' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_completed' => 'color: {{VALUE}};',
				),
			)
		);
        
        
        $this->add_control(
			'step_line',
			array(
				'label'     => __( 'Line', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
        
        $this->add_control(
			'line_top',
			[
				'label' => esc_html__( 'Top', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
                'default' => ['unit' => 'px', 'size' => 9],
				'range' => [
					'px' => [
                        'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::after' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => __( 'Line Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li::after' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'line_color_active',
			array(
				'label'     => __( 'Line Color Active', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#007bff',
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_active:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-step-titles li.ff_completed:after' => 'background: {{VALUE}};'
				),
			)
		);
        
        
        $this->end_controls_section();
        
        // STYLES
		$this->start_controls_section(
			'space_style',
			[
				'label' => esc_html__( 'Spacing', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_responsive_control(
			'form_fields_margin',
			array(
				'label'      => __( 'Fields Bottom', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_group_margin',
			array(
				'label'      => __( 'Group Bottom', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'form_label_margin_bottom',
			array(
				'label'      => __( 'Label Bottom', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--label' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'form_typo',
			array(
				'label' => __( 'Typography', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_input_typo',
			array(
				'label' => __( 'Form Fields', 'elementor' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'form_label_typography',
				'label'    => __('Label Typography', 'elementor'),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-input--label label,
					{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check-input + span',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'input_typography',
				'label'    => __( 'Text Typography', 'elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .ff-el-input--content input:not([type="radio"]):not([type="checkbox"]):not([type="submit"]):not([type="button"]):not([type="image"]):not([type="file"]),
				{{WRAPPER}} .dcael-ff-style .ff-el-input--content textarea,
				{{WRAPPER}} .dcael-ff-style .fluentform select,
				{{WRAPPER}} .dcael-ff-style .dcael-ff-select-custom',
			)
		);

		$this->add_control(
			'btn_typography_label',
			array(
				'label'     => __( 'Button', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'btn_typography',
				'label'    => __( 'Typography', 'elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .ff_submit_btn_wrapper button.ff-btn-submit,
				{{WRAPPER}} .dcael-ff-style .fluentform .step-nav button.ff-btn-secondary,
				{{WRAPPER}} .dcael-ff-style .fluentform .ff_upload_btn',
			)
		);

		$this->add_control(
			'typo_label',
			array(
				'label'     => __( 'Label', 'elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_grpd_typography',
				'label'    => __( 'Typography GRPD', 'elementor' ),
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				),
				'selector' => '{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check.ff-el-tc .ff_t_c',
			)
		);

		$this->add_control(
			'label_grpd_color',
			array(
				'label'     => __( 'Color GRPD', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global' => [
					'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff-el-form-check.ff-el-tc .ff_t_c' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'label_grpd_margin',
			[
				'label' => esc_html__( 'Margin Top GRPD', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default'   => ['size' => '1','unit' => 'px'],
				'range' => [
					'px' => [
						'min' => -10,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dcael-ff-style .fluentform .ff_tc_checkbox .ff-el-form-check-input[type="checkbox"]' => 'margin-top: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();
        
	}
    
       
    /* Search (GLOBAL) COLOR */
    protected function render_color_picto($p){
        $cpicto = $this->get_settings( 'ff_checked_color' );
        //var_dump(explode("id", $p)[1]);
        if (!$cpicto) {
            $kit = Plugin::$instance->kits_manager->get_active_kit_for_frontend();
            $kit_settings = $kit->get_settings_for_display( 'system_colors' );
            $idColor = explode("id=", $p)[1];
            for($i = 0; $i < count($kit_settings); $i++) {
                if ($kit_settings[$i]['_id'] == $idColor ) {
                   $cpicto = $kit_settings[$i]['color'];
                };
            };
        }   
        $r = str_replace("#", "%23", $cpicto);
        return $r;
    }
	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
        //
        $border = !$settings['ff_border_width'] ? 'border-none' : '';
        $border_button = $settings['btn_border_border'] != '' ? '' : 'btn-border-none';
        //$border_button_upload = $settings['btn_upload_border_border'] != '' ? '' : 'btn-upload-border-none';

	    // ATTRIBUTES
        $this->add_render_attribute( array(
            'wrapper' => array(
                'class' => array(
                    'dcael-ff-style', $border, $border_button,
                ),
            ),
        ));
        
		// RENDER
    	?>

        <style>.dcael-ff-style .ff-el-group input:checked[type="checkbox"] {background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='<?php echo $this->render_color_picto($settings["__globals__"]['ff_checked_color']); ?>' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");}.dcael-ff-style .ff-el-group input:checked[type="radio"] {background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='<?php echo $this->render_color_picto($settings["__globals__"]['ff_checked_color']); ?>'/%3e%3c/svg%3e");}</style>
    	<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <?php if ( $settings['form_id'] ) {
                
				$shortcode_extra = '';
				$shortcode_extra = apply_filters( 'dcael_ff_shortcode_extra_param', '', absint( $settings['form_id'] ) );

				echo do_shortcode( '[fluentform id=' . absint( $settings['form_id'] ) . $shortcode_extra . ']' );
			} ?>
		</div>
		<?php		
        		
	}
    
}
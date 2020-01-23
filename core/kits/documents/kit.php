<?php
namespace Elementor\Core\Kits\Documents;

use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Utils;
use Elementor\Core\Schemes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Kit extends PageBase {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['has_elements'] = false;
		$properties['user_role'] = 'design';

		return $properties;
	}

	public function get_name() {
		return 'kit';
	}

	public static function get_title() {
		return __( 'Kit', 'elementor' );
	}

	public function get_preview_url() {
		$url = parent::get_preview_url();
		if ( isset( $_GET['elementor-location'] ) ) {
			$url = add_query_arg( 'elementor-location', $_GET['elementor-location'], $url );
		}

		return $url;
	}

	public static function get_editor_panel_config() {
		$config = parent::get_editor_panel_config();
		$config['default_route'] = 'panel/global/style';

		return $config;
	}

	public function get_initial_config() {
		$config = parent::get_initial_config();

		unset( $config['elements'] );

		return $config;
	}

	public function get_css_wrapper_selector() {
		return 'body';
	}

	/**
	 * @since 2.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->register_document_controls();

		$this->add_body_section();
		$this->add_typography_section();
		$this->add_buttons_section();
		$this->add_form_fields_section();
		$this->add_images_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $this );
	}

	protected function get_post_statuses() {
		return [
			'draft'   => sprintf( '%s (%s)', __( 'Disabled', 'elementor' ), __( 'Draft', 'elementor' ) ),
			'publish' => __( 'Published', 'elementor' ),
		];
	}

	private function add_element_controls( $label, $prefix, $selector ) {
		$this->add_control(
			$prefix . '_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => $label,
				'separator' => 'before',
			]
		);

		$this->add_control(
			$prefix . '_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => $prefix . '_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => $selector,
			]
		);
	}

	private function add_body_section() {
		$this->start_controls_section(
			'section_body',
			[
				'label' => __( 'Background', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'body_background',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
				],
			]
		);

		$this->end_controls_section();
	}

	private function add_buttons_section() {
		$this->start_controls_section(
			'section_buttons',
			[
				'label' => __( 'Buttons', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'button_typography',
				'scheme' => Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'button_text_shadow',
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} button:hover, {{WRAPPER}} button:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} button:hover, {{WRAPPER}} button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_hover_border',
				'selector' => '{{WRAPPER}} button:hover, {{WRAPPER}} button:focus',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_hover_box_shadow',
				'selector' => '{{WRAPPER}} button:hover, {{WRAPPER}} button:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	private function add_typography_section() {
		$this->start_controls_section(
			'section_typography',
			[
				'label' => __( 'Typography', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'body_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'body_typography',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_responsive_control(
			'paragraph_spacing',
			[
				'label' => __( 'Paragraph Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} p' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'vw' ],
			]
		);

		$this->add_control(
			'link_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Link', 'elementor' ),
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_link_style' );

		$this->start_controls_tab(
			'tab_link_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'link_normal_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'link_normal_typography',
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'link_hover_typography',
				'selector' => '{{WRAPPER}} a:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Headings.
		$this->add_element_controls( __( 'H1', 'elementor' ), 'h1', '{{WRAPPER}} h1' );
		$this->add_element_controls( __( 'H2', 'elementor' ), 'h2', '{{WRAPPER}} h2' );
		$this->add_element_controls( __( 'H3', 'elementor' ), 'h3', '{{WRAPPER}} h3' );
		$this->add_element_controls( __( 'H4', 'elementor' ), 'h4', '{{WRAPPER}} h4' );
		$this->add_element_controls( __( 'H5', 'elementor' ), 'h5', '{{WRAPPER}} h5' );
		$this->add_element_controls( __( 'H6', 'elementor' ), 'h6', '{{WRAPPER}} h6' );

		$this->end_controls_section();
	}

	private function add_form_fields_section() {
		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_label_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Label', 'elementor' ),
			]
		);

		$this->add_control(
			'form_label_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'form_label_typography',
				'selector' => '{{WRAPPER}} label',
			]
		);

		$this->add_control(
			'form_field_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Field', 'elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => __( 'Typography', 'elementor' ),
				'name' => 'form_field_typography',
				'selector' => '{{WRAPPER}} input, {{WRAPPER}} textarea',
			]
		);

		$this->start_controls_tabs( 'tabs_form_field_style' );

		$this->start_controls_tab(
			'tab_form_field_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_form_field_state_tab_controls( 'form_field', '{{WRAPPER}} input, {{WRAPPER}} textarea' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_form_field_focus',
			[
				'label' => __( 'Focus', 'elementor' ),
			]
		);

		$this->add_form_field_state_tab_controls( 'form_field_focus', '{{WRAPPER}} input:focus, {{WRAPPER}} textarea:focus' );

		$this->add_control(
			'form_field_focus_transition_duration',
			[
				'label' => __( 'Transition Duration', 'elementor' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} input, {{WRAPPER}} textarea' => 'transition: {{SIZE}}ms',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_images_section() {
		$this->start_controls_section(
			'section_images',
			[
				'label' => __( 'Images', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_image_style' );

		$this->start_controls_tab(
			'tab_image_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_hover_border',
				'selector' => '{{WRAPPER}} img:hover',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} img:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_hover_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_hover_css_filters',
				'selector' => '{{WRAPPER}} img:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_hover_box_shadow',
				'exclude' => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} img:hover',
			]
		);

		$this->add_control(
			'image_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} img' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	private function add_form_field_state_tab_controls( $prefix, $selector ) {
		$this->add_control(
			$prefix . '_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			$prefix . '_background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					$selector => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => $prefix . '_border',
				'selector' => $selector,
			]
		);

		$this->add_control(
			$prefix . '_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					$selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$prefix . '_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					$selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => $prefix . '_box_shadow',
				'selector' => $selector,
			]
		);
	}
}
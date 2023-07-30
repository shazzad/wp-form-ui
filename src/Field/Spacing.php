<?php
namespace Shazzad\WpFormUi\Field;

class Spacing extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'spacing';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$data = $this->parseData( $this->data );
		extract( $data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf(
				'<div class="%1$s"%2$s>',
				$this->createElementClass( 'wf-field-wrap', $id, $type, $class ),
				$this->getAttr()
			);
		}

		$html .= $field_before;

		// label
		$html .= $label_wrap_before;
		$html .= $this->labelHtml( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s">', $this->createElementClass( 'wf-field-input-wrap', $id, $type ) );
		}

		$html .= $input_before;

		if ( empty( $value ) ) {
			$value = $default;
		}

		if ( ! is_array( $value ) ) {
			$value = [];
		}

		$html .= '<table class="wf_spacing_table"><thead><tr>';

		foreach ( $choices as $k => $l ) {
			$html .= '<th class="wf_col wf_w50">' . $l . '</th>';
		}

		if ( ! empty( $unit_choices ) ) {
			$html .= sprintf( '<th class="wf_col wf_w60">%s</th>', 'Unit' );
		}

		$html .= '</tr></thead><tbody>';

		// load existing fields
		foreach ( $choices as $k => $l ) {
			$html .= '<td class="wf_col wf_w50">';
			$child_field = [ 
				'name'       => $name . '[' . $k . ']',
				'type'       => 'text',
				'field_wrap' => false,
				'label_wrap' => false,
				'input_wrap' => false,
				'value'      => isset( $value[ $k ] ) ? $value[ $k ] : ''
			];

			$field = $form->create_field( $child_field );
			$html .= $field->get_html( $form );
			$html .= '</td>';
		}

		if ( ! empty( $unit_choices ) ) {
			$k           = 'units';
			$html .= '<td class="wf_col wf_w60">';
			$child_field = [ 
				'name'       => $name . '[' . $k . ']',
				'type'       => 'select',
				'choices'    => $unit_choices,
				'field_wrap' => false,
				'label_wrap' => false,
				'input_wrap' => false,
				'value'      => isset( $value[ $k ] ) ? $value[ $k ] : ''
			];

			$field = $form->create_field( $child_field );
			$html .= $field->get_html( $form );
			$html .= '</td>';
		}

		$html .= '</tbody></table>';

		$html .= $input_after;

		if ( isset( $desc ) ) {
			if ( ! empty( $desc ) ) {
				$html .= sprintf(
					'<div class="%1$s">%2$s</div>',
					$this->createElementClass( 'wf-field-input-desc', $id, $type ),
					$desc
				);
			}
		}

		if ( $input_wrap ) {
			$html .= '</div>';
		}

		$html .= $field_after;

		if ( $field_wrap ) {
			$html .= '</div>';
		}

		return $html;
	}
}

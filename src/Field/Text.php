<?php
namespace Shazzad\WpFormUi\Field;

class Text extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'text';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$data = $this->sanitize_data( $this->data );
		extract( $data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->form_pitc_class( 'wf-field-wrap', $id, $type, $class ), $attr );
		}

		$html .= $field_before;

		// label
		$html .= $label_wrap_before;
		$html .= $this->form_field_label( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->form_pitc_class( 'wf-field-input-wrap', $id, $type ), $input_wrap_class, $input_wrap_attr );
		}
		$html .= $input_before;

		$html .= sprintf(
			'<input class="%1$s %5$s" id="%2$s" name="%3$s" value="%4$s" type="%7$s"%6$s />',
			$this->form_pitc_class( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $input_attr, $type
		);

		$html .= $input_after;

		if ( isset( $desc ) ) {
			if ( ! empty( $desc ) ) {
				$html .= sprintf(
					'<div class="%1$s">%2$s</div>',
					$this->form_pitc_class( 'wf-field-input-desc', $id, $type ),
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

<?php
namespace Shazzad\WpFormUi\Field;

class Checkbox extends Field {

	public function __construct( $data = [] ) {
		$data['type'] = 'checkbox';
		parent::__construct( $data );
	}

	public function get_html( $form ) {
		$data = $this->parseData( $this->data );
		extract( $data );

		if ( ! isset( $input_value ) ) {
			$input_value = 'yes';
		}
		if ( ! isset( $input_label ) ) {
			$input_label = $desc;
		}

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->createElementClass( 'wf-field-wrap', $id, $type, $class ), $attr );
		}

		$html .= $field_before;

		// label
		$html .= $label_wrap_before;
		$html .= $this->labelHtml( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->createElementClass( 'wf-field-input-wrap', $id, $type ), $input_wrap_class, $input_wrap_attr );
		}

		$html .= $input_before;

		$checked = ! empty( $value ) && $value === $input_value ? ' checked="checked"' : '';
		$html .= sprintf(
			'<label for="%1$s">
				<input id="%1$s" name="%2$s" value="%3$s" type="checkbox"%4$s%6$s /> %5$s
			</label>',
			$id, $name, $input_value, $checked, $input_label, $input_attr
		);

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

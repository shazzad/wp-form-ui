<?php
namespace Shazzad\WpFormUi\Field;

class Text extends Field {

	public function __construct( $data = [] ) {
		if ( ! isset( $data['type'] ) ) {
			$data['type'] = 'text';
		}

		parent::__construct( $data );
	}

	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );
		extract( $this->data );

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
		$html .= $this->labelHtml( $this->data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf(
				'<div class="%1$s %2$s"%3$s>',
				$this->createElementClass( 'wf-field-input-wrap', $id, $type ),
				$input_wrap_class,
				$input_wrap_attr
			);
		}
		$html .= $input_before;

		$html .= sprintf(
			'<input class="%1$s %5$s" id="%2$s" name="%3$s" value="%4$s" type="%6$s"%7$s />',
			$this->createElementClass( 'wf-field', $id, $type ),
			esc_attr( $id ),
			esc_attr( $name ),
			esc_attr( $value ),
			esc_attr( $input_class ),
			esc_attr( $type ),
			$this->getInputAttr(),
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

<?php
namespace Shazzad\WpFormUi\Field;

class Textarea extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'textarea';
		parent::__construct( $data );
	}

	public function parseData( $data ) {
		$data = parent::parseData( $data );

		foreach ( [ 'rows', 'cols' ] as $allowed_attr ) {
			if ( isset( $data[ $allowed_attr ] ) ) {
				$data['input_attrs'][ $allowed_attr ] = $data[ $allowed_attr ];
			}
		}

		return $data;
	}

	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );

		extract( $this->data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->createElementClass( 'wf-field-wrap', $id, $type, $class ), $attr );
		}

		$html .= $field_before;

		// label
		$html .= $label_wrap_before;
		$html .= $this->labelHtml( $this->data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->createElementClass( 'wf-field-input-wrap', $id, $type ), $input_wrap_class, $input_wrap_attr );
		}

		$html .= $input_before;
		$html .= sprintf(
			'<textarea id="%2$s" class="%1$s %5$s" name="%3$s"%6$s>%4$s</textarea>',
			$this->createElementClass( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $this->getInputAttr()
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

		$html .= '</div>';

		return $html;
	}
}

<?php
namespace Shazzad\WpFormUi\Field;

class Datetime extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'datetime';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );
		if ( ! empty( $this->data['input_class'] ) ) {
			$this->data['input_class'] .= ' date_input';
		} else {
			$this->data['input_class'] = 'date_input';
		}

		if ( ! empty( $this->data['input_attr'] ) ) {
			$this->data['input_attr'] .= ' data-format="' . $this->data['datetime_format'] . '" data-formatDate="' . $this->data['date_format'] . '"';
		} else {
			$this->data['input_attr'] = 'data-format="' . $this->data['datetime_format'] . '" data-formatDate="' . $this->data['date_format'] . '"';
		}

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
		$html .= $this->labelHtml();

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
			'<input class="%1$s %5$s" id="%2$s" name="%3$s" value="%4$s" type="%7$s"%6$s />',
			$this->createElementClass( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $this->getInputAttr(), 'text'
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

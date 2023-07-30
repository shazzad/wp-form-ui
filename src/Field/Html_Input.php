<?php
namespace Shazzad\WpFormUi\Field;

class Html_Input extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'html_input';
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
			$html .= sprintf(
				'<div class="%1$s %2$s"%3$s>',
				$this->createElementClass( 'wf-field-input-wrap', $id, $type ),
				$input_wrap_class,
				$input_wrap_attr
			);
		}
		$html .= $input_before;
		$html .= $input_html;
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

?>



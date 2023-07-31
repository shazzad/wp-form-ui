<?php
namespace Shazzad\WpFormUi\Field;

class Heading extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'heading';

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

		if ( ! empty( $heading ) ) {
			$html .= sprintf(
				'<div class="%1$s">%2$s</div>',
				$this->createElementClass( 'wf-field-heading', $id, $type ),
				$heading,
			);
		}

		$html .= $field_after;

		if ( $field_wrap ) {
			$html .= '</div>';
		}

		return $html;
	}
}

<?php
namespace Shazzad\WpFormUi\Field;

class Hidden extends Field {

	public function __construct( $data = [] ) {
		$data['type'] = 'hidden';
		parent::__construct( $data );
	}

	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );
		extract( $this->data );

		$html = $before;
		$html .= sprintf(
			'<input class="%1$s %5$s" id="%2$s" name="%3$s" value="%4$s" type="%7$s"%6$s />',
			$this->createElementClass( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $this->getInputAttr(), $type
		);

		return $html;
	}
}

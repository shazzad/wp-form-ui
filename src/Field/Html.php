<?php
namespace Shazzad\WpFormUi\Field;

class Html extends Field {

	public function __construct( $data = [] ) {
		$data['type'] = 'html';
		parent::__construct( $data );
	}

	public function get_html( $form ) {
		if ( ! isset( $this->html ) ) {
			return '';
		}

		return $this->html;
	}
}

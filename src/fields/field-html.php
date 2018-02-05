<?php
class SF_Field_Html extends SF_Field {
	function __construct( $data = array() ) {
		$data['type'] = 'html';
		parent::__construct( $data );
	}
	public function get_html( $form ){
		return $this->html;
	}
}

?>
<?php
class SF_Field_Html extends SF_Field {
	function __construct( $data = array() ) {
		$data['type'] = 'html';
		parent::__construct( $data );
	}
	function get_html(){
		return sf_form_field_html( $this->to_array() );
	}
	function render(){
		echo $this->get_html();
	}
}

?>
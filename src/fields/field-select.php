<?php
class SF_Field_Select extends SF_Field {
	function __construct( $settings = array() ) {
		$settings['type'] = 'select';
		parent::__construct( $settings );
	}
	function get_html(){
		return sf_form_field_html( $this->to_array() );
	}
	function render(){
		echo $this->get_html();
	}
}

?>
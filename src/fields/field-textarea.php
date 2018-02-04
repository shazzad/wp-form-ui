<?php
class SF_Field_Textarea extends SF_Field {
	function __construct( $settings = array() ) {
		$settings['type'] = 'textarea';
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
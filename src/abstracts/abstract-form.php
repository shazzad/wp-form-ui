<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class SF_Form implements ArrayAccess {

	private $data = array();
	protected $rendered = false;

	function __construct( $data = array() ) {
		$this->data = $data;
	}
	function set_settings( $settings = array() ) {
		$this->settings = $settings;
	}
	function set_values( $values = array() ) {
		$this->values = $values;
	}
	public function add_fields( $fields ) {
		foreach( $fields as $field ){
			$this->add_field( $field );
		}
	}
	public function add_field( $data ) {
		if( ! isset($data['type']) ){
			$data['type'] = 'html';
		}
		$class_name = 'SF_Field_'. ucwords( str_replace( '-', '_', $data['type']) );
		if( ! class_exists( $class_name ) ){
			$class_name = 'SF_Field_Html';
		}

		$this->data['fields'][] = new $class_name( $data );
	}
	public function render(){}
	public function get_html(){}

	public function &__get ($key) {
        return $this->data[$key];
    }
	public function __set($key,$value) {
        $this->data[$key] = $value;
    }
	public function __isset ($key) {
        return isset($this->data[$key]);
    }
	public function __unset($key) {
        unset($this->data[$key]);
    }
	public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }
	public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }
	public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }
}

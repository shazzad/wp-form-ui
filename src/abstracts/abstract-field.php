<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class SF_Field implements ArrayAccess {
	private $data = array();
	function __construct( $data = array() ) {
		$this->data = $data;
	}
	public function to_array(){
		return $this->data;
	}
	public function render(){}
	public function validate(){
		return false;
	}
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

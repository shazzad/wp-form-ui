<?php
namespace Wpform\Form;

abstract class Form implements \ArrayAccess
{
	public $data = array(
		'settings' 	=> array(),
		'values' 	=> array(),
		'fields' 	=> array(),
		'rendered' 	=> false
	);
	public $conditional_fields = [];

	public function __construct($data = array())
	{
		if (isset($data['settings'])) {
			$this->set_settings($data['settings']);
		}
		if (isset($data['values'])) {
			$this->set_values($data['values']);
		}
		if (isset($data['fields'])) {
			$this->add_fields($data['fields']);
		}
	}
	public function set_settings( $settings = array() ) {
		$this->settings = $settings;
	}
	public function set_values( $values = array() ) {
		$this->values = $values;
	}
	public function add_fields( $fields ) {
		foreach( $fields as $field ){
			$this->add_field($field);
		}
	}

	public function set_setting( $key, $val = null ) {
		$this->settings[$key] = $val;
	}
	public function set_value( $key, $val = null ) {
		$this->values[$key] = $val;
	}
	public function add_field( $data ) {
		$this->data['fields'][] = $this->create_field( $data );
	}
	public function create_field( $data ) {
		if (! isset($data['type'])) {
			$data['type'] = 'html';
		}
		if (! isset($data['priority'])) {
			$data['priority'] = 10;
		}
		$class_name = '\\Wpform\\Field\\'. ucwords( str_replace( '-', '_', $data['type']) );
		if( ! class_exists( $class_name ) ){
			$class_name = '\\Wpform\\Field\\Html';
		}

		return new $class_name( $data, $this );
	}
	public function render()
	{
		if (! $this->rendered) {
			$this->handle_fields_conditional_logics();
			uasort($this->data['fields'], array($this, 'order_by_priority'));
			$this->rendered = true;
		}

		echo $this->toHtml();
		echo $this->formJs();
	}

	public function formJs()
	{
		?><script type="application/javascript">
		if (! window['wf_form_conditional_logic']) {
			window['wf_form_conditional_logic'] = {};
		}
		window['wf_form_conditional_logic']["<?php echo $this->settings['id']; ?>"] = <?php echo json_encode($this->conditional_fields); ?>;
        </script><?php
	}
	
	public function handle_fields_conditional_logics()
	{
		$this->conditional_fields = [];
		$field_conditions = [];
		foreach ($this->data['fields'] as $field) {
			if (isset($field['conditional_logics'])) {
				$conditional_logics = $field['conditional_logics'];
				$this->conditional_fields[$field['key']] = $conditional_logics;

				foreach ($conditional_logics['rules'] as $rule) {
					$field_key = $rule['key'];
					if (! isset($field_conditions[$field_key])) {
						$field_conditions[$field_key] = [$field['key']];
					} else {
						$field_conditions[$field_key][] = $field['key'];
					}
				}
			}
		}

		foreach ($field_conditions as $field_key => $fields) {
			$index = $this->get_field_index($field_key);
			if (! isset($this->data['fields'][$index]['input_attrs'])) {
				$this->data['fields'][$index]['input_attrs'] = [
					'onchange' => 'wf_apply_rules("'.$this->settings['id'].'",'.json_encode($fields).');',
					//'onkeyup' => 'clearTimeout(__wf_timeout_handle); __wf_timeout_handle = setTimeout("wf_apply_rules("'.$this->settings['id'].'",'.json_encode($fields).')", 300);'
				];
			} else {
				$this->data['fields'][$index]['input_attrs']['onchange'] = 'wf_apply_rules("'.$this->settings['id'].'",'.json_encode($fields).');';
			}

			if (isset($this->data['fields'][$index]['class'])) {
				$this->data['fields'][$index]['class'] .= ' has-logic';
			} else {
				$this->data['fields'][$index]['class'] = 'has-logic';
			}
		}
		#print_r($this->data['fields']);
		#die();
	}
	public function get_field_index($key)
	{
		foreach ($this->data['fields'] as $i => $field) {
			if ($field['key'] == $key) {
				return $i;
			}
		}
		return false;
	}

	public function order_by_priority( $a, $b )
	{
		if( !isset($a['priority']) || !isset($b['priority']) ) return -1;
		if( $a['priority'] == $b['priority'] ) return 0;
	    return ($a['priority'] < $b['priority']) ? -1 : 1;
	}

	// usability
	public function toHtml(){}
	public function toArray(){
		return $this->data;
	}
	public function toJson(){
		return json_encode( $this->data );
	}
	// no magic
	public function __sleep() {
        return array_keys($this->data);
	}
	public function __wakeup() {}
	public function __toString(){
		#echo 'field '. $this->data['type'];
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
	// array access
    public function offsetGet($offset) {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
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
}

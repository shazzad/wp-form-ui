<?php
namespace Shazzad\WpFormUi\Form;

abstract class Base implements \ArrayAccess {
	public $data = array(
		'settings' => array(),
		'values'   => array(),
		'fields'   => array(),
		'rendered' => false
	);
	public $conditional_fields = [];

	public function __construct( $data = array() ) {
		if ( isset( $data['settings'] ) ) {
			$this->setSettings( $data['settings'] );
		}
		if ( isset( $data['values'] ) ) {
			$this->setValues( $data['values'] );
		}
		if ( isset( $data['fields'] ) ) {
			$this->addFields( $data['fields'] );
		}
	}

	/**
	 * Set form settings
	 * 
	 * @param array $settings
	 */
	public function setSettings( $settings = array() ) {
		$this->settings = $settings;

		return $this;
	}

	public function setValues( $values = array() ) {
		$this->values = $values;

		return $this;
	}

	public function addFields( $fields ) {
		foreach ( $fields as $field ) {
			$this->addField( $field );
		}

		return $this;
	}

	public function setSetting( $key, $val = null ) {
		$this->settings[ $key ] = $val;
	}

	public function setValue( $key, $val = null ) {
		$this->values[ $key ] = $val;
	}

	public function addField( $data ) {
		$this->data['fields'][] = $this->makeField( $data );
	}

	public function makeField( $data ) {
		if ( ! isset( $data['type'] ) ) {
			$data['type'] = 'html';
		}

		if ( ! isset( $data['priority'] ) ) {
			$data['priority'] = 10;
		}

		$class_name = '\\Shazzad\WpFormUi\\Field\\' . str_replace( ' ', '_', ucwords( str_replace( '_', ' ', $data['type'] ) ) );
		if ( ! class_exists( $class_name ) ) {
			$class_name = '\\Shazzad\WpFormUi\\Field\\Html';
		}

		return new $class_name( $data, $this );
	}

	public function render() {
		if ( ! $this->rendered ) {
			// $this->handleConditionalLogics();
			uasort( $this->data['fields'], array( $this, 'sortByPriority' ) );
		}

		echo $this->toHtml();

		$this->rendered = true;

		// @todo Fix conditional logic.
		// echo $this->formJs();
	}

	public function formJs() {
		?>
		<script type="application/javascript">
			if (!window['wf_form_conditional_logic']) {
				window['wf_form_conditional_logic'] = {};
			}


			window['wf_form_conditional_logic']["<?php echo $this->settings['id']; ?>"] = <?php echo json_encode( $this->conditional_fields ); ?>;
		</script>
		<?php
	}

	public function handleConditionalLogics() {
		$this->conditional_fields = [];
		$field_conditions         = [];
		foreach ( $this->data['fields'] as $field ) {
			if ( isset( $field['conditional_logics'] ) ) {
				$conditional_logics                        = $field['conditional_logics'];
				$this->conditional_fields[ $field['key'] ] = $conditional_logics;

				foreach ( $conditional_logics['rules'] as $rule ) {
					$field_key = $rule['key'];
					if ( ! isset( $field_conditions[ $field_key ] ) ) {
						$field_conditions[ $field_key ] = [ $field['key'] ];
					} else {
						$field_conditions[ $field_key ][] = $field['key'];
					}
				}
			}
		}

		foreach ( $field_conditions as $field_key => $fields ) {
			$index = $this->findFieldIndex( $field_key );
			if ( ! isset( $this->data['fields'][ $index ]['input_attrs'] ) ) {
				$this->data['fields'][ $index ]['input_attrs'] = [ 
					'onchange' => 'wf_apply_rules("' . $this->settings['id'] . '",' . json_encode( $fields ) . ');',
					//'onkeyup' => 'clearTimeout(__wf_timeout_handle); __wf_timeout_handle = setTimeout("wf_apply_rules("'.$this->settings['id'].'",'.json_encode($fields).')", 300);'
				];
			} else {
				$this->data['fields'][ $index ]['input_attrs']['onchange'] = 'wf_apply_rules("' . $this->settings['id'] . '",' . json_encode( $fields ) . ');';
			}

			if ( isset( $this->data['fields'][ $index ]['class'] ) ) {
				$this->data['fields'][ $index ]['class'] .= ' has-logic';
			} else {
				$this->data['fields'][ $index ]['class'] = 'has-logic';
			}
		}
		#print_r($this->data['fields']);
		#die();
	}

	public function findFieldIndex( $key ) {
		foreach ( $this->data['fields'] as $i => $field ) {
			if ( $field['key'] == $key ) {
				return $i;
			}
		}
		return false;
	}

	public function sortByPriority( $a, $b ) {
		if ( ! isset( $a['priority'] ) || ! isset( $b['priority'] ) ) {
			return -1;
		}
		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}

		return ( $a['priority'] < $b['priority'] ) ? -1 : 1;
	}

	public function toHtml() {
		return '';
	}

	public function toArray() {
		return $this->data;
	}

	public function toJson() {
		return json_encode( $this->data );
	}

	/**
	 * Convert field to string.
	 */
	public function __toString() {
		return $this->toHtml();
	}

	/**
	 * Serialize data property only.
	 */
	public function __sleep() {
		return array( 'data' );
	}

	/**
	 * Get data property.
	 * 
	 * @param string $key
	 */
	public function &__get( $key ) {
		return $this->data[ $key ];
	}

	/**
	 * Set data property.
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	/**
	 * Check if data property is set.
	 * 
	 * @param string $key
	 */
	public function __isset( $key ) {
		return isset( $this->data[ $key ] );
	}

	/**
	 * Unset data property.
	 * 
	 * @param string $key
	 */
	public function __unset( $key ) {
		unset( $this->data[ $key ] );
	}

	/**
	 * ArrayAccess
	 * 
	 * @param string $offset
	 */
	public function offsetGet( $offset ) {
		return isset( $this->data[ $offset ] ) ? $this->data[ $offset ] : null;
	}

	/**
	 * ArrayAccess
	 * 
	 * @param string $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value ) {
		if ( is_null( $offset ) ) {
			$this->data[] = $value;
		} else {
			$this->data[ $offset ] = $value;
		}
	}

	/**
	 * ArrayAccess
	 * 
	 * @param string $offset
	 */
	public function offsetExists( $offset ) {
		return isset( $this->data[ $offset ] );
	}

	/**
	 * ArrayAccess
	 * 
	 * @param string $offset
	 */
	public function offsetUnset( $offset ) {
		unset( $this->data[ $offset ] );
	}
}

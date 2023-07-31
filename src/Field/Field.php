<?php
namespace Shazzad\WpFormUi\Field;

use Shazzad\WpFormUi\Form\Base;

abstract class Field implements \ArrayAccess {

	/**
	 * Field data
	 * 
	 * @var array
	 */
	public $data = [];

	/**
	 * Form object
	 * 
	 * @var Base
	 */
	protected $form = null;

	/**
	 * Constructor
	 * 
	 * @param array $data Field data.
	 * @param Base $form Form object.
	 */
	public function __construct( $data = [], Base $form = null ) {
		$this->data = $data;
		$this->form = $form;
	}

	public function labelHtml() {
		extract( $this->data );
		$html = '';

		if ( ! empty( $label ) ) {
			if ( $label_wrap ) {
				$html .= sprintf( '<div class="%1$s">', $this->createElementClass( 'wf-field-label-wrap', $id, $type ) );
			}
			$html .= $label_before;

			if ( isset( $input_attrs['required'] ) && $input_attrs['required'] ) {
				$label .= '<span class="req">*</span>';
			}

			// radio checkbox would use span, not label
			if ( in_array( $type, array( 'text', 'textarea', 'select', 'url', 'number' ) ) ) {
				$html .= sprintf( '<label class="%1$s" for="%2$s">%3$s</label>', $this->createElementClass( 'wf-field-label', $id, $type ), $id, $label );
			} else {
				$html .= sprintf( '<span class="%1$s">%2$s</span>', $this->createElementClass( 'wf-field-label', $id, $type ), $label );
			}

			$html .= $label_after;

			// label description.
			if ( ! empty( $label_desc ) ) {
				$html .= sprintf(
					'<div class="%1$s">%2$s</div>',
					$this->createElementClass( 'wf-field-label-desc', $id, $type ),
					$label_desc
				);
			}

			if ( $label_wrap ) {
				$html .= '</div>';
			}
		}

		return $html;
	}

	/**
	 * Create a unique class name for an element
	 * 
	 * @param string $pref Prefix for the class name
	 * @param string $id ID of the field.
	 * @param string $type Type of the field.
	 * @param string $class Extra class name.
	 */
	public function createElementClass( $pref = '', $id = '', $type = '', $class = '' ) {
		$return = "{$pref}";
		if ( ! empty( $id ) ) {
			$return .= " {$pref}-id-{$id}";
		}
		if ( ! empty( $type ) ) {
			$return .= " {$pref}-type-{$type}";
		}
		if ( ! empty( $class ) ) {
			$return .= " {$class}";
		}

		return trim( esc_attr( $return ) );
	}

	public function createFieldId( $raw_id = '' ) {
		$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $raw_id );
		$sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '_', $sanitized );
		$sanitized = str_replace( '__', '_', $sanitized );
		$sanitized = trim( $sanitized, '_' );

		return $sanitized;
	}

	public function parseData( $data ) {
		$defaults = [ 
			'type'              => 'html',
			'name'              => '',
			'label'             => '',
			'html'              => '',
			'desc'              => '',
			'default'           => '',
			'value'             => '',

			'id'                => '',
			'class'             => '',
			'style'             => '',
			'attrs'             => [],

			'before'            => '',
			'after'             => '',

			'field_wrap'        => true,
			'field_before'      => '',
			'field_after'       => '',

			'label_wrap'        => true,
			'label_wrap_before' => '',
			'label_before'      => '',
			'label_after'       => '',
			'label_desc'        => '',

			'input_wrap'        => true,
			'input_wrap_before' => '',
			'input_wrap_class'  => '',
			'input_wrap_attr'   => '',
			'input_before'      => '',
			'input_after'       => '',
			'input_class'       => '',
			'input_html'        => '',
			'input_attrs'       => [],
			'input_style'       => ''
		];

		$data = array_merge( $defaults, $data );

		if ( empty( $data['id'] ) && false !== $data['id'] ) {
			$data['id'] = $this->createFieldId( $data['name'] );
		}

		if ( ! array_key_exists( 'value', $data ) ) {
			$data['value'] = $data['default'];
		}

		$known_input_attrs = [ 
			'rows',
			'cols',
			'placeholder',
			'spellcheck',
			'autocomplete',
			'autofocus',
			'disabled',
			'maxlength',
			'minlength',
			'max',
			'min',
		];

		foreach ( $known_input_attrs as $attr ) {
			if ( isset( $data[ $attr ] ) ) {
				$data['input_attrs'][ $attr ] = esc_attr( $data[ $attr ] );
			}
		}

		// simply include a pre option for choices fields.
		if ( in_array( $data['type'], array( 'select', 'select_multi', 'select2', 'checkboxes', 'radio' ) ) ) {
			if ( isset( $data['options'] ) ) {
				$data['choices'] = $data['options'];
			}
			if ( isset( $data['options_pre'] ) ) {
				$data['choices_pre'] = $data['options_pre'];
			}

			if ( isset( $data['choices_pre'] ) && ! empty( $data['choices_pre'] ) && is_array( $data['choices_pre'] ) ) {
				$_choices = $data['choices_pre'];
				if ( ! empty( $data['choices'] ) ) {
					foreach ( $data['choices'] as $index => $choice ) {
						$_choices[ $index ] = $choice;
					}
				}
				$data['choices'] = $_choices;
			}

			if ( empty( $data['choices'] ) ) {
				$data['choices'] = [];
			}
		}

		// escape text and hidden field values to pass double or single quote
		if ( in_array( $data['type'], array( 'hidden', 'text', 'url' ) ) ) {
			$data['value'] = @htmlspecialchars( $data['value'] );
		}

		return $data;
	}

	/**
	 * Get the field input/control attribute.
	 */
	public function getInputAttr() {
		$buff = '';

		if ( ! empty( $this->data['input_attrs'] ) ) {
			foreach ( $this->data['input_attrs'] as $name => $value ) {
				$buff .= ' ' . $name . '="' . esc_attr( $value ) . '"';
			}
		}

		return trim( $buff );
	}

	/**
	 * Get the field attribute.
	 */
	public function getAttr() {
		$buff = '';

		if ( ! empty( $this->data['attrs'] ) ) {
			foreach ( $this->data['attrs'] as $name => $value ) {
				$buff .= ' ' . $name . '="' . esc_attr( $value ) . '"';
			}
		}

		return trim( $buff );
	}

	/**
	 * Render the field html.
	 */
	public function render() {
		echo $this->toHtml();
	}

	/**
	 * Parent class will call this method to render the field.
	 */
	public function toHtml() {
		return '';
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

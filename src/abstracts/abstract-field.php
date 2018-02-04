<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class SF_Field implements ArrayAccess {
	public $data = array();
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
	// prefix id type class
	public function form_field_label( $data ){
		extract( $data );
		$html = '';

		if( !empty($label) ){
			if( $label_wrap ){
				$html .= sprintf( '<div class="%1$s">', $this->form_pitc_class('sfflw', $id, $type) );
			}
			$html .= $label_before;

			if( isset($input_attrs['required']) && $input_attrs['required'] ){
				$label .= '<span class="req">*</span>';
			}

			// radio checkbox would use span, not label
			if( in_array($type, array('radio', 'checkbox', 'image', 'image_src', 'html_input', 'style') ) ){
				$html .= sprintf( '<span class="%1$s">%2$s</span>', $this->form_pitc_class('sffl', $id, $type), $label );
			}
			else{
				$html .= sprintf( '<label class="%1$s" for="%2$s">%3$s</label>', $this->form_pitc_class('sffl', $id, $type), $id, $label );
			}
	
			$html .= $label_after;
			if( $label_wrap ){
				$html .= '</div>';
			}
		}
	
		return $html;
	}
	// prefix id type class
	public function form_pitc_class( $pref = '', $id = '', $type = '', $class = '' ){
		$return = "{$pref}";
		if( !empty($id) ){
			$return .= " {$pref}i_{$id}";
		}
		if( !empty($type) ) { 
			$return .= " {$pref}t_{$type}";
		}
		if( !empty($class) ){ 
			$return .= " {$class}"; 
		}
		return trim( esc_attr( $return ) );
	}
	// sanitize id
	public function form_field_id( $raw_id = '' ){
		$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $raw_id );
		$sanitized = preg_replace( '/[^A-Za-z0-9_-]/', '_', $sanitized );
		$sanitized = str_replace( '__', '_', $sanitized );
		$sanitized = trim( $sanitized, '_' );
		return $sanitized;
	}

	public function sanitize_data( $data ){
		$defaults = array(
			'type'				=> 'html',
			'name' 				=> '',
			'label' 			=> '',
			'html'				=> '',
			'desc'				=> '',
			'default' 			=> '',
			'value' 			=> '',

			'id' 				=> '',
			'class'				=> '',
			'style' 			=> '',
			'attrs' 			=> array(),
	
			'before'			=> '',
			'after'				=> '',
	
			'field_wrap'		=> true,
			'field_before'		=> '',
			'field_after'		=> '',
	
			'label_wrap'		=> true,
			'label_wrap_before' => '',
			'label_before'		=> '',
			'label_after'		=> '',
	
			'input_wrap'		=> true,
			'input_wrap_before'	=> '',
			'input_wrap_class'	=> '',
			'input_wrap_attr'	=> '',
			'input_before'		=> '',
			'input_after'		=> '',
			'input_class'		=> '',
			'input_html'		=> '',
			'input_attrs'		=> array(),
			'input_style'		=> ''
		);

		$data = array_merge( $defaults, $data );

		if( empty($data['id']) && false !== $data['id'] ){
			$data['id'] = $this->form_field_id( $data['name'] );
		}

		if( ! isset($data['value']) || '' === $data['value'] ) {
			$data['value'] = $data['default'];
		}

		$data['attr'] = '';
		foreach( $data['attrs'] as $an => $av ) {
			$data['attr'] .= ' '. $an .'="'. esc_attr($av) .'"';
		}
		$data['attr'] = trim( $data['attr'] );

		$data['input_attr'] = '';
		foreach( $data['input_attrs'] as $an => $av ) {
			$data['input_attr'] .= ' '. $an .'="'. esc_attr($av) .'"';
		}

		// simply include a pre option for combo fields.
		if( in_array($data['type'], array('select', 'select_multi', 'select2', 'checkbox', 'radio') ) ){
			if( isset($data['choices_pre']) && !empty($data['choices_pre']) && is_array($data['choices_pre']) ){
				$_choices = $data['choices_pre'];
				if( ! empty($data['choices']) ){
					foreach( $data['choices'] as $index => $choice ){
						$_choices[$index] = $choice;
					}
				}
				$data['choices'] = $_choices;
			}
			if( empty($data['choices']) ){
				$data['choices'] = array();
			}
		}

		// escape text and hidden field values to pass double or single quote
		if( in_array($data['type'], array('hidden', 'text') ) ){
			$data['value'] = @htmlspecialchars( $data['value'] );
		}

		return $data;
	}
	public function form_field_html(){}
	public function form_field_html2(){
		$data = $this->sanitize_data( $this->data );
		extract( $data );
	
		$html .= $before;
	
		if( ! in_array($type, array('html', 'hidden') ) && $field_wrap ){
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->form_pitc_class('sffw', $id, $type, $class), $attr );
		}
	
		$html .= $field_before;

		switch( $type ):
	
		case "hidden":
			$html .= sprintf( '<input class="%1$s %5$s" id="%2$s" name="%3$s" value="%4$s" type="hidden" />', $this->form_pitc_class('sff', $id, $type), $id, $name, $value, $input_class );
		break;
	
		case "text":
		case "email":
		case "password":
		case "number":
		case "url":
	
		case "image":
		case "image_src":
		case "text_combo":
	
		case "view":
		case "html_input":
	
		case "textarea":
		case "select":
		case "select_multi":
		case "select2":
		case "radio":
		case "checkbox":

			// label
			$html .= $label_wrap_before;
			$html .= $this->form_field_label( $data );

			// description
			if( ! empty($desc) ){
				$html .= sprintf( '<div class="%1$s">%2$s</div>', $this->form_pitc_class('sffdw', $id, $type), $desc );
			}

			// input
			$html .= $input_wrap_before;
			if( $input_wrap ){
				$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->form_pitc_class('sffew', $id, $type), $input_wrap_class, $input_wrap_attr );
			}

			$html .= $input_before;

			if( $type == 'view' ){
				$html .= $value;
			}
			elseif( $type == 'image' ){
				$image = '';
				if( !isset($size) ){
					$size = 'thumbnail';
				}
	
				if( isset($src_url) && !empty($src_url) ){
					$image = sprintf('<img src="%s" />', $src_url);
				}
				if( $value ){
					$icon = ! wp_attachment_is_image( $value );
					if( $img = wp_get_attachment_image($value, $size, $icon) ){
						$image = $img;
					}
				}

				if( ! isset($submit) || empty($submit) ) {
					$submit = ' file';
				}

				$html .= sprintf( 
					'<input class="%1$s %5$s" id="%2$s_input" name="%3$s" value="%4$s" type="hidden" />
					<div id="%2$s_img" data-size="%8$s">%6$s</div>
					<a href="#" rel="%2$s" class="button sff_image_btn" data-field="id">Choose%7$s</a>
					<a href="#" rel="%2$s" class="button sff_image_remove_btn" data-field="id">Remove%7$s</a>', 
					$this->form_pitc_class('sff', $id, $type), $id, $name, $value, $input_class, $image, $submit, $size
				);
			}
	
			elseif( $type == 'image_src' ){
				$image = '';
				if( $value ) {
					$image = sprintf('<img src="%s" class="image_preview" />', $value);
				}

				$html .= sprintf( 
					'<input class="%1$s %5$s" rel="%2$s" id="%2$s_input" name="%3$s" value="%4$s" type="text" />
					<div id="%2$s_img" data-size="full">%6$s</div>
					<a href="#" rel="%2$s" class="button sff_image_btn" data-field="url">Choose file</a>
					<a href="#" rel="%2$s" class="button sff_image_remove_btn" data-field="url">Remove file</a>', 
					$this->form_pitc_class('sff', $id, $type), $id, $name, $value, $input_class, $image
				);
			}
	
			elseif( ! empty($input_html) ){
				$html .= $input_html;
			}
	
			$html .= $input_after;
	
			if( $input_wrap ){
				$html .= '</div>';
			}
			break;
	
		default:
			break;
	
		endswitch;
	
		$html .= $field_after;
	
		if( isset($desc_after) ){
			if( ! empty($desc_after) ){
				$html .= sprintf( '<div class="%1$s">%2$s</div>', $this->form_pitc_class('sffdaw', $id, $type), $desc_after );
			}
		}
	
		if( ! in_array($type, array('html', 'hidden') ) && $field_wrap ){
			$html .= '</div>';
		}
	
		return $html;
	}

	// usability
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

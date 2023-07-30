<?php
namespace Shazzad\WpFormUi\Field;

class Repeater2 extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'repeater2';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$data = $this->sanitize_data( $this->data );
		extract( $data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->form_pitc_class( 'wf-field-wrap', $id, $type, $class ), $attr );
		}

		$html .= $field_before;

		// label
		$html .= $label_wrap_before;
		$html .= $this->form_field_label( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s">', $this->form_pitc_class( 'wf-field-input-wrap', $id, $type ) );
		}

		$html .= $input_before;

		if ( empty( $value ) ) {
			$value = $default;
		}

		$total_columns = 0;
		foreach ( $fields as $key => $rf ) {
			if ( in_array( $rf['type'], array( 'text', 'number', 'html', 'select' ) ) ) {
				++$total_columns;
			}
			if ( ! empty( $rf['name'] ) ) {
				$fields[ $key ]['name']        = $data['name'] . "[KEY][" . $rf['name'] . "]";
				$fields[ $key ]['option_name'] = $rf['name'];
			}
			if ( empty( $rf['id'] ) ) {
				$rf['id'] = $fields[ $key ]['id'] = $rf['name'];
			}
			if ( ! empty( $rf['id'] ) ) {
				$fields[ $key ]['id'] = $data['id'] . "_KEY_" . $rf['id'];
			}
			if ( empty( $rf['class'] ) ) {
				$fields[ $key ]['class'] = $rf['id'];
			}
		}

		$key = $data['key'];

		$html .= '<div id="wf_repeated_' . $key . '" class="wf_repetable" data-parent="' . $key . '">';

		// load existing fields
		if ( ! empty( $value ) && is_array( $value ) ) {
			$i = 1;

			foreach ( $value as $_value ) {
				$hiddens = '';
				$row_key = 'row-' . $i;

				$html .= '<div class="wf_row">';

				foreach ( $fields as $repeat_field ) {

					$repeat_field['name'] = str_replace( 'KEY', $row_key, $repeat_field['name'] );

					$option_name = $repeat_field['option_name'];
					if ( isset( $_value[ $option_name ] ) ) {
						$repeat_field['value'] = $_value[ $option_name ];
					}

					#if (in_array($repeat_field['type'], array('hidden'))) {
					#	$field = $form->create_field($repeat_field);
					#	$hiddens .= $field->get_html($form);
					#}
					#elseif (in_array($repeat_field['type'], array('text', 'number', 'html', 'select'))) {
					#$html .= '<td class="wf_col '. $repeat_field['class'] .'">';

					#$repeat_field['id'] = $data['id'] ."_KEY_";
					#$repeat_field['field_wrap'] = false;
					#$repeat_field['label_wrap'] = false;
					#$repeat_field['input_wrap'] = false;

					$field = $form->create_field( $repeat_field );
					$html .= $field->get_html( $form );
					#$html .= '</td>';
					#}
				}

				$html .= '<p>';
				$html .= '<a href="#" class="wf_repeater2_remove" data-parent="' . $key . '">Remove</a>';
				#$html .= $hiddens;
				$html .= '</p>';
				$html .= '</div>';

				++$i;
			}
		}

		$html .= '</div>';

		$hiddens = '';

		$html .= '<div id="wf_repeater_' . $key . '" class="wf_repeater" data-parent="' . $key . '">';
		$html .= '<div class="wf_row">';
		foreach ( $fields as $repeat_field ) {
			#if (in_array($repeat_field['type'], array('hidden'))) {
			#	$field = $form->create_field($repeat_field);
			#	$hiddens .= $field->get_html($form);
			#}
			#elseif (in_array($repeat_field['type'], array('text', 'number', 'html', 'select'))) {
			#$html .= '<td class="wf_col '. $repeat_field['class'] .'">';

			#$repeat_field['id'] = false;
			#$repeat_field['field_wrap'] = false;
			#$repeat_field['label_wrap'] = false;
			#$repeat_field['input_wrap'] = false;
			$field = $form->create_field( $repeat_field );
			$html .= $field->get_html( $form );
			#$html .= '</td>';
			#}
		}
		$html .= '<div>';
		$html .= '<a href="#" class="wf_repeater2_remove" data-parent="' . $key . '">Remove</a>';
		#$html .= $hiddens;
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';

		$html .= '<p><a href="#" class="wf_repeater2_add" data-parent="' . $key . '">Add Item</a></p>';

		$html .= $input_after;

		if ( isset( $desc ) ) {
			if ( ! empty( $desc ) ) {
				$html .= sprintf(
					'<div class="%1$s">%2$s</div>',
					$this->form_pitc_class( 'wf-field-input-desc', $id, $type ),
					$desc
				);
			}
		}

		if ( $input_wrap ) {
			$html .= '</div>';
		}

		$html .= $field_after;

		if ( $field_wrap ) {
			$html .= '</div>';
		}

		return $html;
	}
}

?>


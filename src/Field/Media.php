<?php
namespace Shazzad\WpFormUi\Field;

class Media extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'media';
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

		// description
		if ( ! empty( $desc ) ) {
			$html .= sprintf( '<div class="%1$s">%2$s</div>', $this->form_pitc_class( 'wf-field-desc-wrap', $id, $type ), $desc );
		}

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->form_pitc_class( 'wf-field-input-wrap', $id, $type ), $input_wrap_class, $input_wrap_attr );
		}
		$html .= $input_before;

		$preview = '';
		if ( ! isset( $size ) ) {
			$size = 'thumbnail';
		}

		if ( isset( $src_url ) && ! empty( $src_url ) ) {
			$preview = sprintf( '<img src="%s" />', $src_url );
		}
		if ( $value ) {
			$icon = ! wp_attachment_is_image( $value );
			if ( $img = wp_get_attachment_image( $value, $size, $icon ) ) {
				$preview = $img;
			}
		}

		if ( ! isset( $submit ) || empty( $submit ) ) {
			$submit = ' file';
		}

		$html .= sprintf(
			'<input class="%1$s %5$s" id="%2$s_input" name="%3$s" value="%4$s" type="hidden" />
				<div id="%2$s_preview" data-size="%8$s">%6$s</div>
				<a href="#" rel="%2$s" class="button wf-field_image_btn" data-field="id">Choose%7$s</a>
				<a href="#" rel="%2$s" class="button wf-field_image_remove_btn" data-field="id">Remove%7$s</a>',
			$this->form_pitc_class( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $preview, $submit, $size
		);

		$html .= $input_after;
		if ( $input_wrap ) {
			$html .= '</div>';
		}

		$html .= $field_after;

		if ( isset( $desc_after ) ) {
			if ( ! empty( $desc_after ) ) {
				$html .= sprintf( '<div class="%1$s">%2$s</div>', $this->form_pitc_class( 'wf-field-desc-after-wrap', $id, $type ), $desc_after );
			}
		}

		if ( $field_wrap ) {
			$html .= '</div>';
		}

		return $html;
	}
}

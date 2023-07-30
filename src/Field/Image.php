<?php
namespace Shazzad\WpFormUi\Field;

class Image extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'image';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$data = $this->parseData( $this->data );
		extract( $data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf( '<div class="%1$s"%2$s>', $this->createElementClass( 'wf-field-wrap', $id, $type, $class ), $attr );
		}

		$html .= $field_before;
		// label
		$html .= $label_wrap_before;
		$html .= $this->labelHtml( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf( '<div class="%1$s %2$s"%3$s>', $this->createElementClass( 'wf-field-input-wrap', $id, $type ), $input_wrap_class, $input_wrap_attr );
		}
		$html .= $input_before;

		$image = '';
		if ( ! isset( $size ) ) {
			$size = 'thumbnail';
		}

		if ( isset( $src_url ) && ! empty( $src_url ) ) {
			$image = sprintf( '<img src="%s" />', $src_url );
		}
		if ( $value ) {
			$icon = ! wp_attachment_is_image( $value );
			if ( $img = wp_get_attachment_image( $value, $size, $icon ) ) {
				$image = $img;
			}
		}

		if ( ! isset( $submit ) || empty( $submit ) ) {
			$submit = ' file';
		}

		$html .= sprintf(
			'<input class="%1$s %5$s" id="%2$s_input" name="%3$s" value="%4$s" type="hidden" />
				<div id="%2$s_img" data-size="%8$s">%6$s</div>
				<a href="#" rel="%2$s" class="button wf-field_media_btn" data-field="id">Choose%7$s</a>
				<a href="#" rel="%2$s" class="button wf-field_media_remove_btn" data-field="id">Remove%7$s</a>',
			$this->createElementClass( 'wf-field', $id, $type ), $id, $name, $value, $input_class, $image, $submit, $size
		);

		$html .= $input_after;

		if ( isset( $desc ) ) {
			if ( ! empty( $desc ) ) {
				$html .= sprintf(
					'<div class="%1$s">%2$s</div>',
					$this->createElementClass( 'wf-field-input-desc', $id, $type ),
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


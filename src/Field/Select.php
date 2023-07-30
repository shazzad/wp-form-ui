<?php
namespace Shazzad\WpFormUi\Field;

class Select extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'select';
		parent::__construct( $data );
	}
	public function get_html( $form ) {
		$data = $this->parseData( $this->data );
		extract( $data );

		$html = $before;

		if ( $field_wrap ) {
			$html .= sprintf(
				'<div class="%1$s"%2$s>',
				$this->createElementClass( 'wf-field-wrap', $id, $type, $class ),
				$this->getAttr()
			);
		}

		$html .= $field_before;
		// label
		$html .= $label_wrap_before;
		$html .= $this->labelHtml( $data );

		// input
		$html .= $input_wrap_before;
		if ( $input_wrap ) {
			$html .= sprintf(
				'<div class="%1$s %2$s"%3$s>',
				$this->createElementClass( 'wf-field-input-wrap', $id, $type ),
				$input_wrap_class,
				$input_wrap_attr
			);
		}
		$html .= $input_before;
		$html .= sprintf(
			'<select class="%1$s %5$s" id="%2$s" name="%3$s"%4$s>',
			$this->createElementClass( 'wf-field', $id, $type ),
			$id,
			$name,
			$this->getInputAttr(),
			$input_class
		);

		foreach ( $choices as $key => $label ) {
			if ( empty( $label ) ) {
				continue;
			} elseif ( is_array( $label ) && isset( $label['optgroup_open'] ) ) {
				$html .= $label['optgroup_open'];
				continue;
			} elseif ( is_array( $label ) && isset( $label['optgroup_close'] ) ) {
				$html .= $label['optgroup_close'];
				continue;
			}

			$child_input_attr  = '';
			$child_input_class = '';
			$_label            = $label;

			if ( is_array( $_label ) && isset( $_label['child_input_before'] ) ) {
				$html .= $_label['child_input_before'];
			}

			if ( isset( $label->id ) && isset( $label->name ) ) {
				$key   = $label->id;
				$label = $label->name;
			} elseif ( $label instanceof WF_Data ) {
				$key   = $label->get_id();
				$label = $label->get_name();
			} elseif ( isset( $label['key'] ) && isset( $label['name'] ) ) {
				$key               = $label['key'];
				$label             = $label['name'];
				$child_input_attr  = isset( $_label['input_attr'] ) ? $_label['input_attr'] : '';
				$child_input_class = isset( $_label['input_class'] ) ? $_label['input_class'] : '';
			} elseif ( is_array( $label ) ) {
				$child_input_attr = isset( $label['attr'] ) ? $label['attr'] : '';
				$label            = $l['label'];
			}

			$selected = esc_attr( $value ) == esc_attr( $key ) ? ' selected="selected"' : '';
			$html .= sprintf(
				'<option value="%1$s"%2$s class="%4$s" %5$s>%3$s</option>',
				$key, $selected, $label, $child_input_class, $child_input_attr
			);

			if ( is_array( $_label ) && isset( $_label['child_input_after'] ) ) {
				$html .= $_label['child_input_after'];
			}
		}
		$html .= '</select>';
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


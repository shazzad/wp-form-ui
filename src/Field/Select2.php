<?php
namespace Shazzad\WpFormUi\Field;

class Select2 extends Field {

	protected $accepts = [ 
		'choices',
	];

	public function __construct( $data = [] ) {
		$data['type'] = 'select2';
		parent::__construct( $data );
	}

	public function get_html( $form ) {
		if ( isset( $this->data['multiple'] ) && $this->data['multiple'] ) {
			if ( ! isset( $this->data['input_attrs'] ) ) {
				$this->data['input_attrs'] = [];
			}
			$this->data['input_attrs']['multiple'] = 'multiple';
		}

		$select2 = [];
		if ( ! empty( $this->data['select2'] ) ) {
			$select2 = $this->data['select2'];
		}
		if ( ! isset( $select2['placeholder'] ) ) {
			$select2['placeholder'] = __( 'Select an item' );
		}
		$select2['allowclear'] = true;

		if ( ! empty( $select2['data'] ) ) {
			$select2['src'] = site_url( '/wp-json/swpfu/v1/' . $select2['data'] );
		}

		if ( ! empty( $this->data['value'] ) ) {
			$select2['value'] = $this->data['value'];
		}

		if ( ! isset( $select2['minimumInputLength'] ) ) {
			/* if data is being fetched from source, minumum 2 character input needed by default */
			if ( ! empty( $select2['src'] ) ) {
				$select2['minimumInputLength'] = 2;
			} else {
				$select2['minimumInputLength'] = 0;
			}
		}

		if ( ! isset( $this->data['input_attrs'] ) ) {
			$this->data['input_attrs'] = [];
		}
		$this->data['input_attrs']['data-s2'] = json_encode( $select2 );

		$this->data = $this->parseData( $this->data );

		##echo '<pre>';
		#print_r($data);
		#die();

		extract( $this->data );

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
		$html .= $this->labelHtml();

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

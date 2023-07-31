<?php
namespace Shazzad\WpFormUi\Field;

class Section extends Field {
	public function __construct( $data = [] ) {
		$data['type'] = 'section';

		parent::__construct( $data );
	}

	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );
		extract( $this->data );

		if ( isset( $collapsible ) ) {
			$class .= ' wf-collapsible';
		}
		if ( isset( $collapsed ) ) {
			$class .= ' wf-collapsed';
		}

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

		foreach ( $this->data['fields'] as $field ) {
			$field = $form->makeField( $field );
			if ( isset( $field->name ) && '' != $field->name && ! isset( $field->value ) ) {
				$name = isset( $field->option_name ) ? $field->option_name : $field->name;
				if ( array_key_exists( $name, $form->values ) ) {
					$field->value = $form->values[ $name ];
				} else {
					$field->value = '';
				}
			}

			$html .= $field->get_html( $form );
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

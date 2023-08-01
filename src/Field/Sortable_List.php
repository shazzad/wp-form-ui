<?php
/**
 * Sortable list field
 * 
 * @package WpFormUi
 */

namespace Shazzad\WpFormUi\Field;

/**
 * Sortable_List class
 */
class Sortable_List extends Field {

	protected $accepts = [ 
		'choices',
	];

	/**
	 * Constructor
	 * 
	 * @param array $data Field data.
	 * 
	 * $data = [
	 * 	'type' => 'sortable_list',
	 * 	'choices' => [],
	 *  'sorted' => true,
	 *  ... other field args
	 * ];
	 */

	public function __construct( $data = [] ) {
		$data['type']   = 'sortable_list';
		$data['sorted'] = true;

		parent::__construct( $data );
	}

	public function get_html( $form ) {
		$this->data = $this->parseData( $this->data );

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


		$html .= "<ol class='wf-sortable-list'>";
		foreach ( $choices as $key => $label ) {
			if ( empty( $label ) ) {
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

			$child_input_attr .= ' ' . $this->getInputAttr();

			$html .= sprintf(
				'<li class="wf-sortable-list-item %4$s"%5$s>
					<input name="%1$s[]" value="%2$s" type="hidden" />%3$s
				</li>',
				$name, $key, $label, $child_input_class, $child_input_attr
			);

			if ( is_array( $_label ) && isset( $_label['child_input_after'] ) ) {
				$html .= $_label['child_input_after'];
			}
		}
		$html .= "</ol>";

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

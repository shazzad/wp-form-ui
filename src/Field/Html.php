<?php
namespace W4dev\Wpform\Field;

class Html extends Field
{
	public function __construct($data = [])
    {
		$data['type'] = 'html';
		parent::__construct($data);
	}
	public function get_html($form)
    {
		return $this->html;
	}
}

?>
<p align="center">
<a href="https://packagist.org/packages/shazzad/wpform"><img src="https://poser.pugx.org/shazzad/wpform/license" alt="license"></a> 
<a href="https://packagist.org/packages/shazzad/wpform"><img src="https://poser.pugx.org/shazzad/wpform/v/stable" alt="Latest Stable Version"></a>
</p>

# WPForm

A Form library for WordPress plugin & theme development.

## Installation

### Using composer
```
$ composer require w4devinc/wpform
```

### Using git clone
```
$ git clone https://github.com/w4devinc/wpform.git
```

## Basic Usage

Define the base url, relative to the package path

```
if (! class_exists('Wpform\Api\Api')) {
    /* In plugin */
    Wpform\Api\Api::$base_url = plugin_dir_url(__FILE__) .'/vendor/w4devinc/wpform/src';

    /* In parent theme */
    Wpform\Api\Api::$base_url = get_template_directory_uri() .'/vendor/w4devinc/wpform/src';

    /* In child theme */
    Wpform\Api\Api::$base_url = get_stylesheet_directory_uri() .'/vendor/w4devinc/wpform/src';
}
```

Then, render form

```
/** field values */
$values = [
    'action' => 'do_something'
];

/** fields */
$fields = [
	[
		'priority'        => 10,
		'key'             => 'id',
		'name'            => 'id',
		'type'            => 'hidden'
	],
	[
		'priority'        => 11,
		'key'             => 'action',
		'name'            => 'action',
		'type'            => 'hidden'
	],
	[
		'priority'        => 12,
		'key'             => 'select-field',
		'name'            => 'select-field',
		'type'            => 'select',
		'label'           => __('Select field'),
		'choices'         => []
	],
	[
		'priority'        => 13,
		'key'             => 'text-field',
		'name'            => 'text-field',
		'type'            => 'text',
		'label'           => __('Text field')
	],
	[
		'priority'        => 14,
		'key'             => 'repeater-field',
		'name'            => 'repeater-field',
		'type'            => 'repeater',
		'label'           => __('Repeater field'),
		'fields'          => [
			[
				'key'            => 'type',
				'name'           => 'type',
				'type'           => 'select',
				'label'          => __('Type'),
				'choices'        => []
			],
			[
				'key'            => 'name',
				'name'           => 'name',
				'type'           => 'text',
				'label'          => __('Name'),
			],
			[
				'key'            => 'address',
				'name'           => 'address',
				'type'           => 'textarea',
				'label'          => __('Address'),
			]
		],
		'values'          => ! empty($values['repeater-field']) ? $values['repeater-field'] : []
	]
];

/* form settings */
$settings     = [
    'ajax'            => true,
    'action'          => admin_url('admin-ajax.php'),
    'id'              => 'my-form',
    'button_text'     => __('Update', 'textdomain'),
    'loading_text'    => __('Updating', 'textdomain')
];

$form = new \Wpform\Form\Simple(compact(['settings', 'fields', 'values']));
$form->render();
```

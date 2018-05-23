<p align="center">
<a href="https://packagist.org/packages/shazzad/wpform"><img src="https://poser.pugx.org/shazzad/wpform/license" alt="license"></a> 
<a href="https://packagist.org/packages/shazzad/wpform"><img src="https://poser.pugx.org/shazzad/wpform/v/stable" alt="Latest Stable Version"></a>
</p>

# WPForm

A Form library for WordPress plugin & theme development.


# Quick Start

Clone the repository

```
$ git clone https://github.com/shazzad/wpform.git
```

Define the base url relative to the package path

```
if (! class_exists('Wpform\Api\Api')) {
    /* In plugin */
    Wpform\Api\Api::$base_url = plugin_dir_url(__FILE__) .'/vendor/shazzad/wpform/src';

    /* In parent theme */
    Wpform\Api\Api::$base_url = get_template_directory_uri() .'/vendor/shazzad/wpform/src';

    /* In child theme */
    Wpform\Api\Api::$base_url = get_stylesheet_directory_uri() .'/vendor/shazzad/wpform/src';
}
```

Render form -
```
$values = [
    'action' => 'do_something'
];
$fields = [];
    
$priority = 10;
$fields['id'] = array(
    'priority'        => $priority,
    'key'             => 'id',
    'name'            => 'id',
    'type'            => 'hidden'
);
++ $priority;
$fields['action'] = array(
    'priority'        => $priority,
    'key'             => 'action',
    'name'            => 'action',
    'type'            => 'hidden'
);
++ $priority;
$fields[] = array(
    'priority'        => $priority,
    'key'             => 'select-field',
    'name'            => 'select-field',
    'type'            => 'select',
    'label'           => __('Select field'),
    'choices'         => []
);
++ $priority;
$fields[] = array(
    'priority'        => $priority,
    'key'             => 'text-field',
    'name'            => 'text-field',
    'type'            => 'text',
    'label'           => __('Text field')
);
++ $priority;
$fields[] = array(
    'priority'        => $priority,
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
);


$settings     = array(
    'ajax'            => true,
    'action'          => admin_url('admin-ajax.php'),
    'id'              => 'my-form',
    'button_text'     => __('Update', 'ocn'),
    'loading_text'    => __('Updating', 'ocn')
);

$form = new \Wpform\Form\Simple(compact(['settings', 'fields', 'values']));
$form->render();
```
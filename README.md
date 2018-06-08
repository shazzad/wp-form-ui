# WPForm

A Form library for WordPress plugin & theme development.

### Installing

Using composer
```
$ composer require w4devinc/wpform
```

Using git clone
```
$ git clone https://github.com/w4devinc/wpform.git
```

## Basic Usage

### 1. Define the base url, relative to the package path

```php
if (class_exists('W4dev\Wpform\Api')) {
    /* In plugin */
    W4dev\Wpform\Api::init(plugin_dir_url(__FILE__) .'/vendor/w4devinc/wpform/src');

    /* In parent theme */
     W4dev\Wpform\Api::init(get_template_directory_uri() .'/vendor/w4devinc/wpform/src');

    /* In child theme */
     W4dev\Wpform\Api::init(get_stylesheet_directory_uri() .'/vendor/w4devinc/wpform/src');
}
```

### 2. Render form

```php
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
    /* setting the ajax parameter to true will make the form submission through ajax */
    'ajax'            => true,
    
    /* this is the form action url, setting this to admin-ajax.php file will allow you 
    to use wp_ajax_ action to handle submission */
    'action'          => admin_url('admin-ajax.php'),
    'id'              => 'my-form',
    'button_text'     => __('Update', 'textdomain'),
    'loading_text'    => __('Updating', 'textdomain')
];

$form = new \Wpform\Form\Simple(compact(['settings', 'fields', 'values']));
$form->render();
```

### 3. Handle submission

```php
add_action('wp_ajax_do_something', function(){
    $data = stripslashes_deep($_POST);
    unset($data['action']);

    /* do something with data */
    # update_option('my_settings', $data);

    @error_reporting(0);
    header('Content-type: application/json');

    // TODO - replace with wp_json response functions
    echo json_encode([
        'status' => 'ok',
        'html' => __('Form saved')
    ]);
    die('');
});
```

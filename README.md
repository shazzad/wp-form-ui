# WPForm

A Form library for WordPress plugin & theme development.

### Installing

Using composer
```
$ composer require shazzad/wp-form-ui
```

Using git clone
```
$ git clone https://github.com/shazzad/wp-form-ui
```

## Basic Usage

### 1. Define the base url, relative to the package path

```php
use Shazzad\WpFormUi;

/* In plugin */
WpFormUi\Provider::init(plugin_dir_url(__FILE__) .'/vendor/shazzad/wp-form-ui/src');

/* In parent theme */
WpFormUi\Provider::init(get_template_directory_uri() .'/vendor/shazzad/wp-form-ui/src');

/* In child theme */
WpFormUi\Provider::init(get_stylesheet_directory_uri() .'/vendor/shazzad/wp-form-ui/src');
```

### 2. Enqueue CSS & Js

```php
use Shazzad\WpFormUi;
add_action('wp_enqueue_scripts', function(){
    WpFormUi\Provider::enqueue_form_scripts();
});
```

### 3. Render form

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

### 4. Handle submission

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

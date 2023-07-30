# WP Form UI

A Form library to rended forms for WordPress plugin & theme development.

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

/* In plugin / theme */
WpFormUi\Provider::setup();
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
use Shazzad\WpFormUi;

/** field values */
$values = [
    'id' => 1234
    'select-field' => 'option-1',
    'text-field' => 'some text',
    'repeater-field' => [
        [
            'type' => 'type-1',
            'name' => 'name-1',
            'address' => 'address-1'
        ],
        [
            'type' => 'type-2',
            'name' => 'name-2',
            'address' => 'address-2'
        ]
    ]
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
    'action'          => admin_url('admin-ajax.php?action=do_something'),
    'id'              => 'my-form',
    'button_text'     => __('Update', 'textdomain'),
    'loading_text'    => __('Updating', 'textdomain')
];

$form = new WpFormUi\Form\Form();
$form->set_settings($settings);
$form->set_values($values);
$form->set_fields($fields);
$form->render();
```

### 4. Handle submission

```php
add_action('wp_ajax_do_something', function(){
    $data = stripslashes_deep($_POST);

    /* do something with data */
    # update_option('my_settings', $data);

    wp_send_json([
        'success' => true,
        'message' => __('Form saved')
    ]);
});

// if you want to handle submission for non logged in users
add_action('wp_ajax_nopriv_do_something', function(){
    // ... same as above
});
```

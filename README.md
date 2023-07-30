# WP Form UI

WP Form UI is a powerful form library designed to simplify form rendering for WordPress plugin and theme development. This library provides a straightforward way to create and manage forms while ensuring flexibility and extensibility.

## Installation

You can install WP Form UI using either Composer or by cloning the repository from GitHub.

### Using composer
```bash
$ composer require shazzad/wp-form-ui
```

### Using Git Clone
```bash
$ git clone https://github.com/shazzad/wp-form-ui
```

## Basic Usage

Follow these steps to integrate WP Form UI into your plugin or theme:

### 1. Define the base URL relative to the package path

In your plugin or theme, add the following code:

```php
use Shazzad\WpFormUi;

WpFormUi\Provider::setup();
```

### 2. Enqueue CSS & Js

To enqueue the required CSS and JS files, add the following code:

```php
use Shazzad\WpFormUi;

add_action('wp_enqueue_scripts', function(){
    WpFormUi\Provider::enqueue_form_scripts();
});
```

### 3. Render the Form

To render the form, you need to define the form fields, their values, and other settings. Here's an example:

```php
use Shazzad\WpFormUi;

// Field values
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

// Form fields
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

// Form settings
$settings     = [
    'ajax'            => true, // Set to true for AJAX form submission
    'action'          => admin_url('admin-ajax.php?action=do_something'),
    'id'              => 'my-form',
    'button_text'     => __('Update'),
    'loading_text'    => __('Updating'),
    'success_text'    => __('Form saved'),
];

$form = new WpFormUi\Form\Form();
$form->set_settings($settings);
$form->set_values($values);
$form->set_fields($fields);
$form->render();
```

### 4. Handle Form Submission

```php
add_action('wp_ajax_do_something', function(){
    $data = stripslashes_deep($_POST);

    // Process the form data and update settings, e.g., update_option('my_settings', $data);

    wp_send_json([
        'success' => true,
        'message' => __('Form saved')
    ]);
});

// Handle submission for non-logged-in users
add_action('wp_ajax_nopriv_do_something', function(){
    // ... same as above
});
```

That's it! You have now successfully integrated WP Form UI into your WordPress plugin or theme. Customize the form fields and settings as per your requirements.

## Contributing

We welcome contributions from the community! If you find a bug, have a feature request, or want to contribute in any other way, please feel free to open an issue or submit a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

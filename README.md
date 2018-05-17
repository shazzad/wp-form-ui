# WPForm
A Form library for WordPress plugin & theme development.

### Implementation - in plugin
```
if (! class_exists('Wpform\Api\Api')) {
	// user WpForm
	include_once( dirname(__FILE__) . '/vendor/wpform/src/Autoloader.php' );
	// define base url for wpform
	Wpform\Api\Api::$base_url = plugin_dir_url(__FILE__) .'/vendor/wpform/src';
}
```

### Implementation - in theme
```
if (! class_exists('Wpform\Api\Api')) {
	// user WpForm
	include_once( dirname(__FILE__) . '/vendor/wpform/src/Autoloader.php' );
	// define base url for wpform
	Wpform\Api\Api::$base_url = get_template_directory_uri() .'/vendor/wpform/src';
}
```
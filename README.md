# WPForm
A Form class, that can to be used in WordPress plugin & theme development.

### Implementation
```
if (! class_exists('Wpform\Api\Api')) {
	// user WpForm
	include_once( dirname(__FILE__) . '/vendor/wpform/src/Autoloader.php' );
	// define base url for wpform
	Wpform\Api\Api::$base_url = plugin_dir_url(__FILE__) .'/vendor/wpform/src';
}
```
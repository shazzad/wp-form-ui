<?php
namespace W4dev\Wpform;

class Api
{
	public static $initialized = false;
	public static $base_url;
	public static function register_form_scripts()
	{
		foreach (\W4dev\Wpform\Assets::$assets as $asset) {
			if ('js' == $asset['type']) {
				wp_register_script(
					$asset['id'], 
					self::$base_url .'/Asset/'. $asset['path'], 
					$asset['dependencies'], 
					$asset['version'], 
					true
				);
			} elseif ('css' == $asset['type']) {
				wp_register_style(
					$asset['id'], 
					self::$base_url .'/Asset/'. $asset['path'], 
					$asset['dependencies'], 
					$asset['version']
				);
			}
		}
	}

	public static function enqueue_form_scripts()
	{
		wp_enqueue_style(['wf_form']);
		wp_enqueue_script(['wf_form']);
	}
}

?>
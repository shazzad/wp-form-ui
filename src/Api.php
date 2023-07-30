<?php
namespace Shazzad\WpFormUi;

class Api {
	protected static $initialized = false;
	protected static $base_url;

	public static function init( $base_url = '' ) {
		if ( ! self::$initialized ) {
			self::$initialized = true;
			self::$base_url    = $base_url;

			add_action( 'wp_enqueue_scripts', [ get_class(), 'register_form_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ get_class(), 'register_form_scripts' ] );
			add_action( 'rest_api_init', [ get_class(), 'register_apis' ] );
		}
	}

	public static function register_form_scripts() {
		foreach ( \Shazzad\WpFormUi\Assets::$assets as $asset ) {
			if ( 'js' == $asset['type'] ) {
				wp_register_script(
					$asset['id'],
					self::$base_url . '/Asset/' . $asset['path'],
					$asset['dependencies'],
					$asset['version'],
					false
				);
			} elseif ( 'css' == $asset['type'] ) {
				wp_register_style(
					$asset['id'],
					self::$base_url . '/Asset/' . $asset['path'],
					$asset['dependencies'],
					$asset['version']
				);
			}
		}
	}

	public static function enqueue_form_scripts() {
		wp_enqueue_style( [ 'wf_form' ] );
		wp_enqueue_script( [ 'wf_form' ] );
	}

	public static function register_apis() {
		$users = new Api\Users();
		$users->register_routes();
	}
}

?>


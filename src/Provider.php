<?php
namespace Shazzad\WpFormUi;

use Shazzad\WpFormUi\Assets;

class Provider {
	protected static $initialized = false;

	protected static $base_url;

	public static function setup( $base_url = '' ) {
		if ( ! self::$initialized ) {
			self::$initialized = true;

			if ( empty( $base_url ) ) {
				$base_url = plugin_dir_url( dirname( __FILE__ ) );
			}

			self::$base_url = $base_url;

			add_action( 'wp_enqueue_scripts', [ get_class(), 'registerScripts' ] );
			add_action( 'admin_enqueue_scripts', [ get_class(), 'enqueueScripts' ] );
			// add_action( 'rest_api_init', [ get_class(), 'register_apis' ] );
		}
	}

	public static function registerScripts() {
		foreach ( Assets::$assets as $asset ) {
			if ( 'js' == $asset['type'] ) {
				wp_register_script(
					$asset['id'],
					self::$base_url . '/dist/' . $asset['type'] . '/' . $asset['path'],
					$asset['dependencies'],
					$asset['version'],
					false
				);
			} elseif ( 'css' == $asset['type'] ) {
				wp_register_style(
					$asset['id'],
					self::$base_url . '/dist/' . $asset['type'] . '/' . $asset['path'],
					$asset['dependencies'],
					$asset['version']
				);
			}
		}
	}

	public static function enqueueScripts() {
		wp_enqueue_style( 'wf_form' );
		wp_enqueue_script( 'wf_form' );
	}

	// public static function register_apis() {
	// 	$users = new Api\Users();
	// 	$users->register_routes();
	// }
}

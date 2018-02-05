<?php
if( ! defined('SF_DIR') ) {

	define( 'SF_DIR'				, dirname(__FILE__) . '/' );
	define( 'SF_VERSION'			, '1.1' );

	// abstract classes
	foreach( glob( SF_DIR . 'abstracts/abstract-*.php') as $file ) {
		include_once( $file );
	}
	// models
	foreach( glob( SF_DIR . 'models/model-*.php') as $file ) {
		include_once( $file );
	}
	// forms
	foreach( glob( SF_DIR . 'forms/form-*.php') as $file ) {
		include_once( $file );
	}
	// fields
	foreach( glob( SF_DIR . 'fields/field-*.php') as $file ) {
		include_once( $file );
	}
}

?>
<?php
namespace Wpform;

spl_autoload_register(function($className){
	$__dir = dirname (__FILE__) . '/';
	$file_path = '';

	if ('Wpform' == substr($className,0,6)) {
		$className = ltrim($className, 'Wpform\\');
		$fileName  = '';
		$namespace = '';
		if ($lastNsPos = strrpos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$fileName = str_replace('_', '-', strtolower($fileName));
		$fileName .= $className .'.php';

		if (file_exists($__dir . $fileName)) {
			$file_path = $__dir . $fileName;
		}
	}

	if ($file_path) {
		include($file_path);
	}
});
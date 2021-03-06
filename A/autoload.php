<?php
/**
 * Register autoloader by including this file. Loads framework classes with absolute path
 * and other classes with include path.
 *
 * @package A
 * @author Christopher Thompson
 */


function a_autoload($class)
{
	if (class_exists($class, false)) {
		return;
	}
	$dir = (0 === strpos($class, 'A_')) ? dirname(dirname(__FILE__)) . '/' : '';
	$path = $dir . str_replace(array('_','\\','-'), array('/','/','_'), $class) . '.php';
	if (file_exists($path)) {
		require $path;
	}
}

spl_autoload_register('a_autoload');

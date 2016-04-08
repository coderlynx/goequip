<?php

// The New Way
// Forma 1: Closure
spl_autoload_register(function($className) {
	//echo "Tratando de cargar la clase: " . $className . "<br>";
	if(is_readable($className . '.php')) {
		require $className . '.php';
	} else {
		return false;
	}
});

// Forma 2
function miAutoload($className) {
	//echo "Tratando de cargar la clase con el segundo autoload: " . $className . "<br>";
	if(is_readable('modelo/' . $className . '.php')) {
		require 'modelo/' . $className . '.php';
	} else {
		return false;
	}
}

spl_autoload_register('miAutoload');


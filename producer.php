<?php
spl_autoload_register(function($class){
	file_exists('./'.$class.'.php') && require('./'.$class.'.php');
});

try {
	$cir = new CircleQueue('order.cancel.new', 60);
	$cir -> enQueue(1490193800, ['order'=>'123456789']);
} catch (Exception $e) {
	die($e->getMessage());
}
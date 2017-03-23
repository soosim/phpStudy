<?php
spl_autoload_register(function($class){
	file_exists('./'.$class.'.php') && require('./'.$class.'.php');
});

try {
	$cir = new CircleQueue('order.cancel.new', 60);
	$cir -> enQueue(time()+60, ['order'=>time()]);
} catch (Exception $e) {
	die($e->getMessage());
}
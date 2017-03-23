<?php
spl_autoload_register(function($class){
	file_exists('./'.$class.'.php') && require('./'.$class.'.php');
});

$q = new CircleQueue('order.cancel.new', 60);
start:
$i = 0;

while (true) {
	$data = json_decode($q->deQueue($i), true);
	echo $i.':'.json_encode($data).PHP_EOL;
	if (!empty($data)) {
		# Deal some thing here
	}
	$i++;
	$q->redis->set('current.point', $i);
	sleep(1);
	if ($i >= $q->times) {
		goto start;
	}
}

<?php
spl_autoload_register(function($class){
	file_exists('./'.$class.'.php') && require('./'.$class.'.php');
});

$name = 'order.cancel.new';
$q = new CircleQueue($name, 60);

start:
$i = 0;
/**
Problem :
	1.消费者程序无法停止，若停止，执行时间将出现延迟（停止时间+0~60秒）
	2.延迟时间与队列长度相等时，可能出现BUG
	3.消费程序执行时间过长，会导致延时问题。（最好异步）
* @date 2017-03-23 11:57:17
*/

while (true) {
	$data = json_decode($q->deQueue($i), true);
	// 当前节点数据
	echo $i.':'.json_encode($data).PHP_EOL;
	if (!empty($data)) {
		foreach ($data as $key => &$value) {
			if ($value['circle'] == 0) {
				# Deal some thing here
				echo 'Dealing some thing...'.PHP_EOL;

				unset($data[$key]);
			} else {
				$value['circle']--;
			}
		}

		# 数据回队列
		$q->redis->hSet($name, $i, json_encode($data));
	}
	$i++;
	$q->redis->set('current.point', $i);
	sleep(1);
	if ($i >= $q->times) {
		goto start;
	}
}

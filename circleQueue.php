<?php
// +----------------------------------------------------------------------
// | 环形队列
// +----------------------------------------------------------------------
// | Copyright (c) 2016年 如此生活. All rights reserved.
// +----------------------------------------------------------------------
// | Author: Jinlong_Xie <soosim@qq.com>
// | Created: 2017-03-22 21:22:00
// +----------------------------------------------------------------------

class CircleQueue
{
	const HOST = 'localhost';
	const PORT = '6379';

	public $times;
	public $name;
	public $redis;

	public function __construct($name, $times='3600')
	{
		!$name && $this-> _exception('Invalid Name');
		$this->name = $name;
		$this->times = $times;
		$this->redis = new Redis();
		$this->_connectRedis();
		$this->createQueue();
	}

	# 创建环形队列
	private function createQueue()
	{
		if (!$this->redis->exists($this->name)) {
			$field = [];
			for ($i=0; $i < $this->times ; $i++) {
				$field[$i] = json_encode([]);
			}
			$this->redis->hMset($this->name, $field);
		} else {
			$this->redis->type($this->name) != 5 && $this-> _exception('Key exists and not Hash');
		}
	}

	/**
	* 入队
	* @param time 要检查的时间
	* @param data 检查的数据
	* @author Jinlong_Xie <soosim@qq.com>
	* @date 2017-03-22 21:52:16
	*/
	public function enQueue($time, $data)
	{
		$newTask = ['order' => $data['order']];
		# 当前轮询执行的节点
		$current = $this->redis->get('current.point');

		//相差秒数
		$intersect = $time - time();
		$intersect <= 0 && $this->_exception('Task time is have passed');

		# 计算位置
		if ($current + $intersect <= $this->times) {
			$newTask['circle'] = 0;
			$newTime = $current + $intersect;
		} else {
			$leftTime = $intersect - ($this->times - $current);

			$circle = floor($leftTime / $this->times);
			$newTime = $leftTime % $this->times;
			if ($newTime > $current) {
				++$circle;
			}
			$newTask['circle'] = $circle;
		}

		echo 'New Point:'.$newTime.PHP_EOL;
/*		echo 'Current:'.$current.PHP_EOL;
		echo '<pre>';
		    print_r($newTask);
*/
	    $existsTask = json_decode($this->redis->hGet($this->name, $newTime));
		$existsTask[] = $newTask;
		$this->redis->hSet($this->name, $newTime, json_encode($existsTask));
	}

	/**
	* 出队
	* @return array
	* @param clock
	* @author Jinlong_Xie <soosim@qq.com>
	* @date 2017-03-22 22:27:57
	*/
	public function deQueue($clock)
	{
		return $this->redis->hGet($this->name, $clock);
	}

	# 连接redis
	private function _connectRedis()
	{
		$this->redis->connect(self::HOST, self::PORT);
	}

	private function _exception($msg)
	{
		throw new Exception($msg);
	}
}
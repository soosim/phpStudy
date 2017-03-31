<?php
header("content-type:text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

/*$bulk = new MongoDB\Driver\BulkWrite;
$document = ['_id' => new MongoDB\BSON\ObjectID, 'name' => '菜鸟教程'];

$_id= $bulk->insert($document);

var_dump($_id);

$manager = new MongoDB\Driver\Manager("mongodb://172.16.1.20:27017");
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('test.runoaob', $bulk, $writeConcern);*/

$manager = new MongoDB\Driver\Manager("mongodb://172.16.1.20:27017");
$blu = new MongoDB\Driver\BulkWrite;
for ($i=0; $i < 1000; $i++) {
	$data = ['_id' => new MongoDB\BSON\ObjectID, 'name'=>'谢金龙','x'=>$i,'url'=>'www.soolife.cn'];
	$blu->insert($data);
}

$manager->executeBulkWrite('test.runoob', $blu);

$filter = ['x' => ['$gt' => 990]];
$options = [
	'projection' => ['_id'=>0],
	'sort' => ['x' => 1]
];

// 查询数据
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('test.runoob', $query);

foreach ($cursor as $value) {
	print_r($value);
}
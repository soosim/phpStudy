<?php
function xrange($a = 100){
	for ($i=0; $i < $a; $i++) {
		yield $i;
	}
}

$x = xrange();
foreach ($x as $value) {
	echo $value.PHP_EOL;
}
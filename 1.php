<?php
function xrange($a = 10000){
	for ($i=0; $i < $a; $i++) { 
		yield $i;
	}
}

/*$x = xrange();
foreach ($x as $value) {
	echo $value.PHP_EOL;
}*/
echo time() + 100;
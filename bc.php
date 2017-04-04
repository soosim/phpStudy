<?php
$limit = 1;
$price = 2.01;
$money = 0.01;
$coins = 2;

// Buy Qty
$qty = 2;

$res = array();
$res['amount'] = $price * $qty;
$res['amount'] = bcmul($price, $qty, 2);

$promo_qty = $limit;

$res['coins_amount'] = bcmul($coins, $promo_qty);
$res['money_amount'] = bcsub($res['amount'], $res['coins_amount'], 2);

echo '<pre>';
    print_r($res);exit;

$a = 0.01;
$b = 0.02;

echo $a + $b;

<?php
require_once 'jsonx.php';

$json=new JSONX('db/data.json');

//to save data in data.json file

$product=[
  ['id'=>1, 'name'=>'Nokia'],
  ['id'=>2, 'name'=>'iPhone'],
  ['id'=>3, 'name'=>'Samsung']
];

echo $json->node('home:title')->fetch();

echo $json->node('home:product:items')->where('id', '=', 2)->fetch();
//$json->node('product:items')->save($product);
//$json->node('product')->delete();
//echo $json->node('home:title')->fetch();

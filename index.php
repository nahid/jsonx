<?php
require 'jsonx.php';
$json=new JSONX('database/db.json');

// if($json->node('items')->save('monitor')){
// 	echo "successful";
// }

$items=[
	['id'=>1, 'cat'=>'shirt', 'name'=>'Half-Red-L'],
	['id'=>2, 'cat'=>'shirt', 'name'=>'Half-Red-M'],
	['id'=>3, 'cat'=>'shirt', 'name'=>'Half-Blue-M'],
	['id'=>4, 'cat'=>'pant', 'name'=>'Jeans-30'],
	['id'=>5, 'cat'=>'pant', 'name'=>'Jeans-35'],
	['id'=>6, 'cat'=>'t-shirt', 'name'=>'Winter-full-M'],
	['id'=>7, 'cat'=>'t-shirt', 'name'=>'Summer-half-M'],
	['id'=>8, 'cat'=>'shirt', 'name'=>'Thiny-Full-Checked-L'],
	['id'=>9, 'cat'=>'pant', 'name'=>'Gabardean-L']
];

// if($json->node('items')->save($items)){
// 	echo 'Saved';
// }

// var_dump($json->node('products')->where('name', '=', 'Keyboard')->fetch());

// $shirts=$json->node('items')->where('id', '=', 5)->fetch();

// foreach($shirts as $shirt){
// 	print_r($shirt);
// }


$shirts=$json->node('items')->where(array(array('cat', '=', 'pant'),array('name', '=', 'Gabardean-L'),array('id', '<', 4)))->fetch();

echo '<pre>';
print_r( $json );

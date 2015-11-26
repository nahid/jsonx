<?php
require 'jsonx.php';
$json=new JSONX('database/db.json');

// if($json->node('items')->save('monitor')){
// 	echo "successful";
// }

/*$items=['id'=>10, 'cat'=>'shoe', 'name'=>'Bata-Sleper'];

if($json->node('items')->save($items)){
	echo 'Saved';
}*/

//var_dump($json->node('products')->where('name', '=', 'Keyboard')->fetch());

$shirts=$json->node('items')->where('id', '=', 10)->fetch();

foreach($shirts as $shirt){
	echo '<li>'.$shirt['name'].'</li>';
}

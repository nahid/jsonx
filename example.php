<?php
require_once 'jsonx.php';

$json=new JSONX('db/data.json');

//to save data in data.json file
// $json->saveData('home:title', 'Welcome to home page');
// $json->saveData('contact:title', 'Contact Us');
 //$json->saveData('product:items', ["name"=>"plugin"]);
 $json->saveData('desc', 'nothings');

//delete json node
 $json->nodeDelete('contact');

//read data
//echo $json->getNodeValue('home:title');

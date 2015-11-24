<?php
require_once 'jsonx.php';

$json=new JSONX('db/data.json');

//to save data in data.json file
$json->saveData('home:title', 'Welcome to home page');
$json->saveData('contact:title', 'Contact Us');
$json->saveData('product:item:name', 'Template');

//read data
echo $json->getNodeValue('home:title');
<?php 
use WebTool\DB\PdoDB;

$dsn = 'mysql:host=127.0.0.1;dbname=test;charset=utf-8';
$user = 'root';
$pwd = '';

$db = new PdoDB($dsn,$user,$pwd);


var_dump($db);
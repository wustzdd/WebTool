<?php 

use WebTool\Curl;
// curl相关方法测试




$url = 'http://exercise.com/upload.php';
$data = [
	'name'	=>'xing',
	'age'	=>	23,
];

list($ret,$errno) = Curl::httpPost($url,$data);

var_dump($ret,$errno);

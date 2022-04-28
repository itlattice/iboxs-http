<?php
require_once '../vendor/autoload.php';
use iboxs\http\Http;
$result=Http::Client('http://post.itgz8.com/auth/test')->header(['aa:55a'])->post(['a'=>'s'],true,$status,$header);
var_dump($result);//请求结果
var_dump($status);//状态码
var_dump($header);//响应头
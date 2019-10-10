<?php
require_once '../../config.php';
require_once 'angeli.class.php';
$app=new angeli($config);
if(empty($_GET['type'])){
	die('错误');
}

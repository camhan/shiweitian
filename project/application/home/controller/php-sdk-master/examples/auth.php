<?php
require_once __DIR__ . '/../autoload.php';

use Qiniu\Auth;

// 用于签名的公钥和私钥. http://developer.qiniu.com/docs/v6/api/overview/security.html#aksk
$accessKey = '9TKV8eTKs9cpm6ZllO_8eGpsgNVonHoIXzNSgSmT';
$secretKey = 'JLqqfXqztdSGW-yqcuHttO8VAlxizQa5VFS6CgBy';

// 初始化签权对象。
$auth = new Auth($accessKey, $secretKey);

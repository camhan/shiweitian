<?php
require_once __DIR__ . '/../autoload.php';
set_time_limit(0);
// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;

// 需要填写你的 Access Key 和 Secret Key
$accessKey = '9TKV8eTKs9cpm6ZllO_8eGpsgNVonHoIXzNSgSmT';
$secretKey = 'JLqqfXqztdSGW-yqcuHttO8VAlxizQa5VFS6CgBy';

// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);

// 要上传的空间
$bucket = 'camhan';

// 生成上传 Token
$token = $auth->uploadToken($bucket);

// 要上传文件的本地路径
for ($i=1000; $i <1707; $i++) { 
	$filePath = 'B:/Cam1/wamp/imgs/'.$i.'.jpg';



// 上传到七牛后保存的文件名
$key = $i.'.jpg';

// 初始化 UploadManager 对象并进行文件的上传。
$uploadMgr = new UploadManager();

// 调用 UploadManager 的 putFile 方法进行文件的上传。
list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
echo "\n====> putFile result: \n";
if ($err !== null) {
    var_dump($err);
} else {
    var_dump($ret);
}

}
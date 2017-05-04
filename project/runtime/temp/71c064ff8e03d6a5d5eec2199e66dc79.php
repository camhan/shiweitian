<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:100:"B:\Cam1\wamp\phpStudy\WWW\php10\shiweitian\project\public/../application/admin\view\login\login.html";i:1492503315;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <base href="__PUBLIC__/admin/">
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/login.css" />
</head>
<body>
<div class="page">
    <div class="loginwarrp">
        <div class="logo">管理员登陆</div>
        <div class="login_form">
            <form id="Login" name="Login" method="post" onsubmit="" action="<?php echo url('login/land'); ?>">
                <li class="login-item">
                    <span>用户名：</span>
                    <input type="text" name="user" class="login_input">
                </li>
                <li class="login-item">
                    <span>密　码：</span>
                    <input type="password" name="pwd" class="login_input">
                </li>
                <div class="clearfix"></div>
                <li class="login-sub">
                    <input type="submit" name="Submit" value="登录" />
                </li>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/8
 * Time: 11:35
 */
namespace app\admin\controller;
use think\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use \think\Session;

class Login extends Controller{
    //登陆页面
    public function Login(){
        return $this->fetch('login');
    }
    public function Land(){
        $user=input('post.user');
        $pwd=md5(input('post.pwd'));
        $re=db('admin')->where('user',$user)->find();
        if($re) {
            if ($re['pwd'] == $pwd) {
                Session::set('name', "$user");
                Session::set('a_id',"$re[a_id]");
                $this->success('登陆成功', 'restaurant/index');
            }else{
                $this->success('密码错误', 'login');
            }
        }else{
            $this->success('用户名错误', 'login');
        }
    }
    public function out(){
        Session::delete('name');
        Session::delete('a_id');
      return  $this->success('退出成功', 'login/login');
    }
}
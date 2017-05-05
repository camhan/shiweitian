<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/8
 * Time: 11:35
 */
namespace app\home\controller;

use think\Controller;
use think\Db;
use \think\Session;
use \think\Request;
class Common extends Controller{
    public function _initialize()
    {
        $user = Session::get('user');
        $dish = Session::get('dish');
        if($user==""){
            if (!isset($dish) || empty($dish)) {
                $this->success('请先登录', 'login/login');
                die;
            }
        }else{
            if (!isset($user) || empty($user)) {
                $this->success('请先登录', 'login/login');
                die;
            }
        }
    }
}
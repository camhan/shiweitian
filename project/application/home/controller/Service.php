<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/4/16
 * Time: 20:12
 */
namespace app\home\controller;
use think\Controller;

class Service extends Common
{
    //QQ在线客服聊天
    public function index1(){
       return $this->fetch('service1');
    }


        public function index2(){
       return $this->fetch('service2');
    }
}
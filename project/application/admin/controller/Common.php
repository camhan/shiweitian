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
use \think\Request;
class Common extends Controller{
    public function _initialize(){
        $session=Session::get('name');
        if(!isset($session) || empty($session)){
            $this->success('非法登陆','login/login');
            die;
        }
        if(!$this->checkNode()){
            $this->error('权限不够','login/login');
        }
    }
    public function checkNode(){
        $user=Session::get('name');
        if($user=="admin"){
            return true;
        }
        $a_id=Session::get('a_id');
        $mynode=db('admin_role')->where("a_id='$a_id'")->find();
        if($mynode==""){
            return false;
        }
        $res=db('role')->where("r_id","in","$mynode[r_id]")->select();
        foreach($res as $key=>$val){
            $data[]=$val['node'];
        }
        $request= \think\Request::instance();
        $re = $request->controller();
        $res=$request->action();
        $node=$re."/".$res;
        if(in_array($node,$data)){
            return true;
        }else{
            return false;
        }
    }
}
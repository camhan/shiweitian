<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2017/4/16
 * Time: 20:12
 */
namespace app\home\controller;
use think\Controller;
use \think\Session;
class User extends Common
{

//个人资料修改页面;
    public function save(){
        //获取用户信息Id
    $user=Session::get('user');
    // $is=\think\Db::table('user')->where('account',"$account")->find();
    $userid=$user['u_id'];   //个人id;

        $info=\think\Db::table('user')->where('u_id',"$userid")->find();
        // echo $userid;die;
       // print_r($info);die;
        $this->assign('v', $info);
       return $this->fetch('user_save');
    }

//个人资料修改处理;
    public function savedo(){
        //获取该用户信息记录的自增Id
        $id=$_GET['id'];
        $account=isset($_POST['account'])?$_POST['account']:'';
        $tel=isset($_POST['tel'])?$_POST['tel']:'';
        $email=isset($_POST['email'])?$_POST['email']:'';
        $res=\think\Db::table('user')->where(" u_id=$id")->update(['account'=>$account,'tel'=>$tel,'email'=>$email]);
        if($res){
            $this->success('succeed!','Show/show1');
        }else{
            $this->error('failed!');
        }
    }
}
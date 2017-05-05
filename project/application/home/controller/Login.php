<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use \think\Session;
use app\home\model\Ucpaas;

class Login extends Controller
{
    
    //个人登陆/展示
    public function login(){
        if( request()->isPost() ) {
            $account=input('post.account');
            $password=md5(input('post.password'));
            $re=db('user')->where('account',$account)->find();
            if($re) {
                if ($re['password'] == $password) {
                    Session::set('user', $re);
                    $this->success('登陆成功', 'show/show1');
                }else{
                    $this->success('密码错误', 'login/login');
                }
            }else{
                $this->success('用户名错误', 'login/login');
            }
        }else{
            return $this->fetch('login/login');
        }
    }



    //饭店注册/展示
    public function register(){

        if( request()->isPost() ) {
            $data=input('post.');
            $data['add_time']=date('Y-m-d H:i:s');
            $data['password']=md5($data['password']);
            unset($data['password1']);
            $res=db('restaurant')->insert($data);
                if($res){
                    $this->success('注册成功', 'login/login');
                }
        }else{
            return $this->fetch('login/register');
        }
    }










    //个人注册
    public function register1(){
        $data=input('post.');
                                  // print_r($data);die;

        $checkmsg=$data['checkmsg'];

        $checknum=Session::get('checknum');

            if($checkmsg!=$checknum){
                $this->error('验证码错误','login/login');
            }
        $data['add_time']=date('Y-m-d H:i:s');
        $pwd=$data['password'];
        $data['password']=md5($pwd);
        unset($data['password1']);
        unset($data['checkmsg']);
        $res=db('user')->insert($data);
        if($res){
            $this->success('注册成功','login/login');
        }
    }

//随机数生成;
    public function checknum(){
            $tel=input('post.tel');
                                                 // echo $tel;die;  
         $num=rand(1111,9999);
         Session::set('checknum',$num);
                                            // echo Session::get('checknum');die;
        $options['accountsid']='65a2c163a179589a2637adab88a98ae9';
        $options['token']='3425c01c7374191130fe1df61ca0348a';
                                          // require_once('Ucpaas.class.php');
        $ucpass = new Ucpaas($options);
                                            echo $ucpass->getDevinfo('xml');
        $appId = "0b83444e69924d7d9fe8012a5b07d6f9";
        $to =$tel;
        $templateId = "43199";
        $param=$num;
            echo $ucpass->templateSMS($appId,$to,$templateId,$param);
    }  


    

    //饭店登陆
    public function login1(){
        $account=input('post.account');
        $password=md5(input('post.password'));
        $re=db('restaurant')->where('account',$account)->find();
        if($re) {
            if ($re['password'] == $password) {
                Session::set('dish', $re);
                $this->success('登陆成功', 'show/show2');
            }else{
                $this->success('密码错误', 'login/login');
            }
        }else{
            $this->success('用户名错误', 'login/login');
        }

    }


    // 前台退出登录
    public function loginout(){
        // 清除session（当前作用域）
        Session::clear();
        $this->success('退出成功','login/login');
    }


}

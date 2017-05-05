<?php
namespace app\admin\controller;
use think\Controller;
class User extends Common{
    //用户信息展示
    public function user(){
        $text=isset($_GET['text'])?$_GET['text']:'';
        // 查询符合条件的用户数据 并且每页显示3条数据
        if($text==''){
            $list = \think\Db::table('user')->paginate(3);
            $data=\think\Db::table('user')->select();
        }else{
            $list = \think\Db::table('user')->where('account',"$text")->paginate(3);
            $data=\think\Db::table('user')->where('account',"$text")->select();
        }
        $sum=ceil(count($data)/3);
        // 把分页数据赋值给模板变量list
        $this->assign(['data'=>$list,'text'=>$text,'sum'=>$sum]);
        // 渲染模板输出
        return $this->fetch('user');
        // $data=\think\Db::table('user')->select();
        // return view('user', ['data'=>$data]);
    }
    //搜索用户
    public function search(){
        $text=isset($_POST['text'])?$_POST['text']:'';
        // 查询符合条件的用户数据 并且每页显示3条数据
        $list = \think\Db::table('user')->where('account',"$text")->paginate(3);
        // 把分页数据赋值给模板变量list
        $this->assign('data', $list);
        // 渲染模板输出
        return $this->fetch('user');
    }
    //用户信息删除
    public function del(){
        $id=$_GET['id'];
        $res=\think\Db::table('user')->where(" u_id=$id")->delete();
        if($res){
            $this->success('succeed!','user');
        }else{
            $this->error('failed!');
        }
    }
    //用户信息修改
    public function save(){
        $id=$_GET['id'];
        $info=\think\Db::table('user')->where(" u_id=$id")->select();
        return view('save', ['info'=>$info]);
    }
    //将修改的用户信息入库
    public function savedo(){
        $id=$_GET['id'];
        $account=isset($_POST['account'])?$_POST['account']:'';
        $tel=isset($_POST['tel'])?$_POST['tel']:'';
        $email=isset($_POST['email'])?$_POST['email']:'';
        $res=\think\Db::table('user')->where(" u_id=$id")->update(['account'=>$account,'tel'=>$tel,'email'=>$email]);
        if($res){
            $this->success('succeed!','User/user');
        }else{
            $this->error('failed!');
        }
    }
}
?>
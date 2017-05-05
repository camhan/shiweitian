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

class Admin extends Common{
    //管理员列表
    public function index(){
        $page_per=3;
        $data=\think\Db::name('admin')->paginate($page_per);
        $datas=\think\Db::name('admin')->select();
        //一共多少条   多少页
        $nums=count($datas);
        $pages=ceil($nums/$page_per);
        return $this->fetch('index',array('data'=>$data,'pages'=>$pages));
    }
    //管理员添加页面
    public function add_list(){
        $res=Db('role')->select();
        return $this->fetch('add_list',array('res'=>$res));
    }
    //管理员添加
    public function add(){
        $res=input();
        $data['user']=$res['user'];
        $data['pwd']=$res['pwd'];
        $node=$res['node'];
        $ree['r_id']=implode(',',$node);
        $user=$data['user'];
        $re=\think\Db::name('admin')->where('user',$user)->find();
        if($re){
            $this->success('该用户已注册', 'add_list');
        }else{
            $data['pwd']=md5($data['pwd']);

            $a_id=\think\Db::name('admin')->insertGetId($data);
            $ree['a_id']=$a_id;
            $res=db('admin_role')->insert($ree);
            if($res){
                $this->success('新增成功', 'index');
//            return $this->redirect('index');
             }
        }

    }
    //管理员删除
    public function del(){
        $a_id=input('get.id');
        $res=\think\Db::table('admin')->where('a_id',$a_id)->delete();
        if($res){
            $this->success('删除成功', 'index');
        } else{
            $this->success('删除失败', 'index');
        }
    }
    //管理员修改页面
    public function upd(){
        $a_id=input('get.id');
        $data=db('admin')->where('a_id',$a_id)->find();
        return $this->fetch('upd',array('data'=>$data));
    }
    //管理员修改
    public function update(){
        $data=input();
        $data['pwd']=md5($data['pwd']);
        $a_id=$data['a_id'];
        $res=db('admin')->where('a_id',$a_id)->update($data);
        if($res){
            $this->success('修改成功', 'index');
        } else{
            $this->success('修改失败', 'index');
        }
    }
    //角色添加页面
    public function role(){
        return $this->fetch('role');
    }
    //角色添加
    public function role_add(){
        $data=input();
        $res=Db('role')->insert($data);
        if($res){
            return $this->success('添加成功','admin/role');
        }else{
            return $this->success('添加失败','admin/role');
        }
    }
}
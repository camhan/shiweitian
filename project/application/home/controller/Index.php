<?php
namespace app\home\controller;

use think\Controller;
use think\Db;

class Index extends Common
{

//首页饭店列表;
	public function index()
    {

		$type = db('type')->field('t_id,dish,p_id,price')->where('p_id=0')->select();

        $list= Db::table('restaurant')->field('r_id,r_img,address,account')->where('state=1')->select();
        $k =  $dish=input('post.k');
        //获取最火热(订单数量排行前五名)的饭店店铺图片进行展示
        $select='select r_id from user_order GROUP BY r_id ORDER BY COUNT(r_id) desc limit 5 ';
        $r_ids = Db::query($select);
        $ids='';
        for($i=0;$i<count($r_ids);$i++){
            $ids.=$r_ids[$i]['r_id'].',';
        }
//        $new_str=substr($ids,0,-1);
        //删除字符串最后一个字符
        $str=chop($ids,',');
        $sel= "select r_id,r_img from restaurant WHERE r_id IN($str)";
        $imgs = Db::query($sel);
        if($k){
            $list = array_slice($list,$k*5,5);
            echo json_encode($list);
        }else{
            $list = array_slice($list,0,5);
            $this->assign('imgs',$imgs);
            $this->assign('list',$list);
            $this->assign('page','');
            $this->assign('sum','');
            $this->assign('text','');
            $this->assign('type',$type);
            return $this->fetch('index');
        }
	}


//菜单列表;
     public function type()
     {
         $id=input('get.id');
         $dish=input('get.dish');
         if($id<11){
             $type = db('type')->field('t_id,dish,p_id,price')->where("p_id=$id")->select();
             $this->assign('type',$type);
             return $this->fetch('type');
         }else{
             $SQL = "select id,t_id,r.r_id,account,r_img,address from type_rest t INNER JOIN restaurant r on r.r_id=t.r_id";
             $list = Db::query($SQL);
             $num = count($list);
             $arr = array();
             for ($i=0;$i<$num;$i++){
                if(strpos($list[$i]['t_id'],$id)){
                    $arr[]=$list[$i];
                }
             }
             return $this->fetch('rest_list',['list'=>$arr,'dish'=>$dish]);
         }

     }
     //菜单搜素
    public function search(){
	    $text=input('get.text');
//        $SQL = "select id,t_id,r.r_id,account,r_img,address from restaurant WHERE account like '$text'";
//        $list = Db::query($SQL);
        $type = db('type')->field('t_id,dish,p_id,price')->where('p_id=0')->select();
        $where['account']=array('like',"%$text%");
        $list=  \think\Db::table('restaurant')->field('r_id,r_img,address,account')->where($where)->paginate(3);
        $lists=  \think\Db::table('restaurant')->field('r_id,r_img,address,account')->where($where)->select();
        //一共多少页
        $sum=ceil(count($lists)/3);
//        echo Db::table('restaurant')->getLastSql();
//        print_r($list);die;
//        $k =  $dish=input('post.k');;
//        if($k){
//            $list = array_slice($list,$k*5,5);
//            echo json_encode($list);
//        }else{
//            $list = array_slice($list,0,5);
        //获取最火热(订单数量排行前五名)的饭店店铺图片进行展示
        $select='select r_id from user_order GROUP BY r_id ORDER BY COUNT(r_id) desc limit 5';
        $r_ids = Db::query($select);
        $ids='';
        for($i=0;$i<count($r_ids);$i++){
            $ids.=$r_ids[$i]['r_id'].',';
        }
//        $new_str=substr($ids,0,-1);
        //删除字符串最后一个字符
        $str=chop($ids,',');
        $sel= "select r_id,r_img from restaurant WHERE r_id IN($str)";
        $imgs = Db::query($sel);
//        print_r($imgs);die;
            $this->assign('list',$list);
            $this->assign('imgs',$imgs);
            $this->assign('sum',$sum);
            $this->assign('text',$text);
            $this->assign('type',$type);
//        }
        return $this->fetch('index');
    }

}
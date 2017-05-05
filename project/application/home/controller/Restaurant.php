<?php
namespace app\home\controller;
// header("content-type:text/html;charset=utf-8");
use think\Controller;
use think\Db;
use \think\Session;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class Restaurant extends Common
{

//饭店详情;
	public function rest_detail(){
		$id=input('get.id');
        //查询该饭店的所有留言
        $sql = 'select c_id,c_content,account,c_time from `comment` c INNER JOIN user u on u.u_id=c.u_id where c.r_id='.$id;
        $comment = Db::query($sql);



        $sql = "select id,t_id,r.r_id,account,state,add_time,r_intrduction,r_img from type_rest tr INNER JOIN restaurant r on tr.r_id=r.r_id where r.r_id = $id";
        $rest = Db::query($sql);
        if($rest){
            $list = $rest[0];
            $sql = "select dish,t_id,price from `type` where t_id in (".$list['t_id'].')';
            $list['dish'] = Db::query($sql);
        }else{
            $sql = 'select account,r_intrduction,r_id,r_img from restaurant where r_id =  '.$id;
            $re  = Db::query($sql);
            if($re){
                $list = $re[0];
                $list['dish'] = 1;
            }else{
                $this->error('无该饭店，请重新尝试');
            }

        }


		$this->assign('list',$list);
        $this->assign('comment',$comment);
		return $this->fetch('detail');
	}



//修改资料;
    public function save(){
       //获取饭店Id
        $dish=Session::get('dish');
        $r_id=$dish['r_id'];
        $info=\think\Db::table('restaurant')->where('r_id',"$r_id")->select();
                                        //  print_r($info);
        $this->assign('info', $info);
        return $this->fetch('save0');
    }

//修改处理;
    public function savedo(){
      //获取该用户信息记录的自增Id
        $id=$_GET['id'];
        $account=isset($_POST['account'])?$_POST['account']:'';
        $tel=isset($_POST['tel'])?$_POST['tel']:'';
        $email=isset($_POST['email'])?$_POST['email']:'';
        $intro=isset($_POST['intro'])?$_POST['intro']:'';
        $address=isset($_POST['address'])?$_POST['address']:'';
$img = request()->file('img');

require_once __DIR__ . '/php-sdk-master/autoload.php';
set_time_limit(0);


// 需要填写你的 Access Key 和 Secret Key
$accessKey = '9TKV8eTKs9cpm6ZllO_8eGpsgNVonHoIXzNSgSmT';
$secretKey = 'JLqqfXqztdSGW-yqcuHttO8VAlxizQa5VFS6CgBy';
// 构建鉴权对象
$auth = new Auth($accessKey, $secretKey);

// 要上传的空间
$bucket = 'camhan';

// 生成上传 Token
$token = $auth->uploadToken($bucket);
 $filePath = $img->getRealPath();
 $ext = pathinfo($img->getInfo('name'), PATHINFO_EXTENSION);
$key = substr(md5($img->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;

$uploadMgr = new UploadManager();
 list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
 $filename = $ret['key'];
                        // print_r($filename);die; 图片名字
 $r_img='http://omqoablsu.bkt.clouddn.com/'.$filename;
                        // echo $r_img;die;
        if($filename){          
               // $new_save=str_replace('\\','/',$info->getSaveName());
            $res=\think\Db::table('restaurant')->where(" r_id=$id")->update(['account'=>$account,'tel'=>$tel,'email'=>$email,'r_img'=>$r_img,'r_intrduction'=>$intro,'address'=>$address]);
            if($res){
                $this->success('succeed!','Show/show2');
            }else{
                $this->error('failed!');
            }
           }else{
            // 上传失败获取错误信息
              echo $img->getError();
          }
    }



//个人点餐订单;
    public function order()
    {
        $tid=trim(input('post.str'),',');     //选购的菜名
        $rid=input('post.rid');     //饭店的id

        $user = Session::get('user');
        $u_id = isset($user['u_id'])?$user['u_id']:0;
        if(!$u_id){
            $msg = 2;
            return $msg;
        }
        $price=input('post.price');
        $new = trim($price,',');
        $num = explode(',',$new);
        $money = array_sum($num);           //消费金额
        $time=time();

        $user_sn = 'Food'.rand(1,99999);

        $sql = "insert into user_order (user_sn,u_id,r_id,t_id,money,status,pay_status,uo_time) values('$user_sn',$u_id,$rid,'$tid','$money','0','0','$time')";
        //echo $sql;exit;
        $re = Db::execute($sql);
        if($re){
            $msg = 1;
            return $msg;
        }else{
             $msg = 0;
           return $msg;
        }
    }



//无限极分类
    public function AllCate($cate,$p_id=0)
    {
        $new=array();
        foreach($cate as $k=>$v){
            if($v['p_id']==$p_id) {
                $new[$k]=$v;
                $son = $this->AllCate($cate,$v['t_id']);
                $new[$k]['son']=$son;
            }
        }
        return $new;
    }



//饭店菜单管理
    public function choice(){
        //查询所有菜系
        $kinds= \think\Db::table('type')->select();
        $arr = $this->AllCate($kinds);
        $dish=Session::get('dish');
        $id=$dish['r_id'];
 
        //查询该饭店已有菜系 展示时默认选中
//        $id=input('get.id');//饭店id
        $ids=\think\Db::table('type_rest')->where('r_id',$id)->find();


        // print_r($ids);die;
        $info=explode(',',$ids['t_id']);
//        print_r($info);die;
        $this->assign(['arr'=>$arr,'info'=>$info]);
         return $this->fetch('add1');
    }




 //饭店选菜修改数据表type_rest
    public function adddo(){
        $arr=$_POST['check'];

                         // print_r($arr);die;
        $dish=Session::get('dish');
        $id=$dish['r_id'];
        //饭店id
        
        //变成字符串 入库
        $str=implode(',',$arr);
//                                print_r($str);die;
    //先查询数据库是否已有该饭店数据 有的话修改 没有就增加
        $info=\think\Db::table('type_rest')->where('r_id',$id)->find();
        if($info){
            //有 修改
            $res=\think\Db::table('type_rest')->where('r_id',$id)->update(['t_id'=>$str]);
            if($res){
                $this->success('修改成功！','Show/show2');
            }else{
                $this->error('failed!');
            }
        }else{
            ///没有 增加
            $res=\think\Db::table('type_rest')->insert(['t_id'=>$str,'r_id'=>$id]);
            if($res){
                $this->success('添加成功！','Show/show2');
            }else{
                $this->error('failed!');
            }
        }
    }


    //添加留言
    public function message(){
        $content=input('post.str');
        $r_id=input('post.rid');
        $user=Session::get('user');
        if(!$user){
            $msg = 1000;
            return json_encode($msg);
        }
        $u_id = $user['u_id'];
        $data = array();
        $data['c_content'] = $content;
        $data['r_id'] = $r_id;
        $data['u_id'] = $u_id;
        $data['c_time'] = time();
        $res=db('comment')->insert($data);
        if($res){
            $msg = 1024;
            return json_encode($msg);
        }else{
            $msg = 1001;
            return json_encode($msg);
        }
        
    }


    




}
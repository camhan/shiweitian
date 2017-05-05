<?php
namespace app\admin\controller;
header("content-type:text/html;charset=utf-8");
use think\Controller;
use think\Db;
use \think\Session;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class Restaurant extends Common
{
	
	//饭店菜谱
	public function type()
	{
		$sql = "select id,t_id,r.r_id,account,state,add_time from type_rest tr INNER JOIN restaurant r on r.r_id=tr.r_id";
		$data = Db::query($sql);
		$num = count($data);
		for($i=0;$i<$num;$i++){
			$sql = "select dish from type where t_id in (".$data[$i]['t_id'].')';
			$dish = Db::query($sql);
			$data[$i]['dish'] = $dish;
		}
		
		$mem = new \memcache();
        $mem ->connect("127.0.0.1",'11211');
        $mem->set('type',$data);


		$this->assign('data',$data);
		
		return $this->fetch('type');
	}	

	//饭店菜谱的详情
	public function detail()
	{
        $r_id=input('get.rid');

        $mem = new \memcache();
        $mem ->connect("127.0.0.1",'11211');
        $type = $mem->get('type');

        foreach ($type as $k => $v){
            if($v['r_id'] == $r_id){
                $arr = $v;
            }
        }
        
        return $this->fetch('detail',['arr'=>$arr]);
	}

//首页；
	public function index(){
        $user=Session::get('name');
		return $this->fetch('index',array('user'=>$user));
	}


//饭店列表；
	public function lists(){
        $page_per=3;
		$list= \think\Db::table('restaurant')->paginate($page_per);
		$lists= \think\Db::table('restaurant')->select();
        //一共多少条   多少页
        $nums=count($lists);
        $pages=ceil($nums/$page_per);
        $this->assign('list',$list);
        $this->assign('pages',$pages);
		return $this->fetch('lists');
	}


//饭店添加；
	public function add(){
		if( request()->isPost() ) {
			$data=input('post.');
	//姓名验证;

				$pwd=$data['password'];
				$data['password']=md5($pwd);				
				$data['state']=0;
				$data['add_time']=date('Y-m-d H:i:s');
									

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
 $data['r_img']='http://omqoablsu.bkt.clouddn.com/'.$filename;
 						// print_r($data);die;

			$num=\think\Db::table('restaurant')->insert($data);	
				if($num){				
					return $this->success('添加成功','restaurant/lists');
				}else{
					echo "<script>alert('添加失败');history.go(-1)</script>";
					// return $this->error('添加失败,请重新添加','restaurant/add');
				}
		}else{
					// return view('add');
			return $this->fetch('add');
		}
	}


//饭店修改；
	public function update(){
		if( request()->isPost() ) {
				$data=input('post.');
				$r_id=$data['r_id'];						
									// print_r($data);

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
 $data['r_img']='http://omqoablsu.bkt.clouddn.com/'.$filename;
                        // echo $r_img;die;
			$num=\think\Db::table('restaurant')->where('r_id',$r_id)->update($data);	
				if($num){
					return $this->success('修改成功','restaurant/lists');
				}else{
					return $this->error('修改失败,请重新修改',"restaurant/update?r_id='$r_id'");
					 // echo "<iscrpt>alert('登陆失败');history.go(-1)</script>";
				}
		}else{
			$r_id=input('get.id');
			$data=\think\Db::table('restaurant')->where('r_id',$r_id)->find();
												// print_r($data);	die;
			$this->assign('lt',$data);
			return $this->fetch('update');
		}	
	}


//饭店删除；
	public function del(){
		$r_id=input('get.id');
													// echo $r_id;die;
		$re=\think\Db::table('restaurant')->where('r_id',$r_id)->delete();
													// print_r($re);die;
		if($re){
			return $this->success('删除成功','restaurant/lists');
		}else{
			return $this->error('删除失败','restaurant/lists');
		}
	}


//饭店列表；
	public function list01(){

		$state=input('get.state');
        $page_per=3;
		$list= \think\Db::table('restaurant')->where("state='$state'")->paginate($page_per);
		$lists= \think\Db::table('restaurant')->where("state='$state'")->select();
		// print_r($list);die;
        //一共多少条   多少页
        $nums=count($lists);
        $pages=ceil($nums/$page_per);
		$this->assign('list',$list);
		$this->assign('pages',$pages);
		$this->assign('state',$state);
		return $this->fetch('list01');
	}
//饭店列表已审核；
    public function list02(){

        $state=input('get.state');
        $page_per=3;
        $list= \think\Db::table('restaurant')->where("state=1")->paginate($page_per);
        $lists= \think\Db::table('restaurant')->where("state=1")->select();
        // print_r($list);die;
        //一共多少条   多少页
        $nums=count($lists);
        $pages=ceil($nums/$page_per);
        $this->assign('list',$list);
        $this->assign('pages',$pages);
        return $this->fetch('list01');
    }

//饭店审核1；
	public function check(){

		$r_id=input('get.id');

		$re= \think\Db::table('restaurant')->where('r_id',$r_id)->update(['state'=>1]);
		
		if($re){
			return $this->success('通过审核','restaurant/lists');
		}else{
			return $this->error('审核失败','restaurant/lists');
		}
	}


//饭店审核2；
	public function check2(){

		$r_id=input('get.id');
		$re= \think\Db::table('restaurant')->where('r_id',$r_id)->update(['state'=>1]);

	}


	public function main(){
		return $this->fetch('main');
	}

}
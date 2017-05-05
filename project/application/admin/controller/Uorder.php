<?php
namespace app\admin\controller;
header("content-type:text/html;charset=utf-8");
use think\Controller;
use think\Db;
// use Gregwar\Captcha\CaptchaBuilder;

class Uorder extends Common{


//个人用户订单的展示；
	public function index(){
        $page=isset($_GET['page'])?$_GET['page']:1;
        //每页显示条数
        $per_num=3;
        //偏移量
        $limit=($page-1)*$per_num;
        $sql = "select uo_id,user_sn,r.account as username,u.account,t_id,money,status from (user_order uo INNER JOIN user u on u.u_id=uo.u_id) INNER JOIN restaurant r on r.r_id=uo.r_id limit $limit,$per_num";
        $data = Db::query($sql);
		$sql = 'select uo_id,user_sn,r.account as username,u.account,t_id,money,status from (user_order uo INNER JOIN user u on u.u_id=uo.u_id) INNER JOIN restaurant r on r.r_id=uo.r_id';
		$datas = Db::query($sql);
		$nums = count($datas);
        $page_num=ceil($nums/$per_num);
        $num = count($data);
		for($i=0;$i<$num;$i++){
			$t_id = trim($data[$i]['t_id'],',');
			$sql = "select dish from type where t_id in (".$t_id.")";
			$dish = Db::query($sql);
			$data[$i]['dish'] = $dish;

		}
        $this->assign('pages',$page_num);
		$this->assign('data',$data);
		return $this->fetch('index');

	}














	// 	if(request()->isPost()){
	// 		$data= input('post.');
	// 		$type_id=$data['type_id'];
	// 		$type_id=implode(',',$type_id);
	// 		$data['type_id']=$type_id;

	// 		$year=$data['year'];
	// 		$month=$data['month'];
	// 		$day=$data['day'];

	// 		$date_time=$year.'.'.$month.'.'.$day;
	// 		$data['date_time']=$date_time;
	// 		unset($data['year']);
	// 		unset($data['month']);
	// 		unset($data['day']);
	// 		print_r($data);die;
	// 		$num= \think\Db::table('yk_date')->insert($data);
	// 			if($num){
	// 				echo $num;
	// 			}else{
	// 				echo "错误！";
	// 			}
							
	// 	}else{
	// 			$type= \think\Db::table('yk_type')->select();
	// 			$this->assign('list',$type);
	// 			return $this->fetch('yuyue');
	// 	}
		


	// }



	// public function comment(){

 //    	$p=1;	$lenght=10;
	// 	$limit=($p-1)*$lenght;
	// 	$count=$db->createCommand("select * from r_room")->execute();
	// 	$pages=ceil($count/$lenght);
	// 	$page=$this->page($count,$p,$lenght,$pages);

 //    	$sql="select * from r_room left join r_type on r_room.rt_id=r_type.t_id limit $limit,$lenght";
	// 	$room=$db->createCommand($sql)->queryAll();
	// 	$sql2="select * from r_type";
	// 	$type=$db->createCommand($sql2)->queryAll();



		
	// 	$comment= \think\Db::table('yk_comment')->select();
	// 	$this->assign('list',$comment);
	// 	return $this->fetch('pingjia');
	
	// }






		// $join=[['yk_type b','a.type_id=b.type_id']];
		// $data= \think\Db::table('yk_date')->alias('a')->join($join)->select();

}
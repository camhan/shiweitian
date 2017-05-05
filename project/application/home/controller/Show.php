<?php
namespace app\home\controller;
use \think\Session;
use think\Controller;
use think\Db;

class Show extends Common
{
//首页功能选项;
	public function show1(){
		return $this->fetch('index1');
	}

	public function show2(){
		return $this->fetch('index2');
	}

//饭店订单;
	public function r_order(){
		return $this->fetch('r_order');
	}



//点餐订单;
	public function r_order1(){
$dish=Session::get('dish');
        $r_id=$dish['r_id'];
        $page=isset($_GET['page'])?$_GET['page']:1;
        //每页显示条数
        $per_num=5;
        //偏移量
        $limit=($page-1)*$per_num;
	//支付状态为0; 支付后改为1;

        //分页
        $sql = "select status,pay_status,uo_id,uo_time,user_sn,t_id,r.account,uo_address,u.account as username,money from (user_order uo inner join restaurant r on r.r_id = uo.r_id) INNER JOIN user u on u.u_id=uo.u_id where uo.r_id = $r_id and pay_status=0 order by uo_time desc limit $limit,$per_num";
        $info = Db::query($sql);
		$sql = "select status,pay_status,uo_id,uo_time,user_sn,t_id,r.account,uo_address,u.account as username,money from (user_order uo inner join restaurant r on r.r_id = uo.r_id) INNER JOIN user u on u.u_id=uo.u_id where uo.r_id = $r_id and pay_status=0 order by uo_time desc";
		$infos = Db::query($sql);
		$num = count($info);
		$nums = count($infos);
        $page_num=ceil($nums/$per_num);
		for($i=0;$i<$num;$i++){
			$t_id = trim($info[$i]['t_id'],',');
		
			$sql = "select dish from type where t_id in (".$t_id.')';
			$dish = Db::query($sql);
			$info[$i]['dish'] = $dish;
		}

		// print_r($info);die;
        $this->assign('pages',$page_num);
        $this->assign('info', $info);
		return $this->fetch('order1');
	}



//续约订单;
	public function r_order2(){
		$dish=Session::get('dish');
        $page=isset($_GET['page'])?$_GET['page']:1;
        //每页显示条数
        $per_num=5;
        //偏移量
        $limit=($page-1)*$per_num;
        $r_id=$dish['r_id'];
        //分页
        $sql = "select  w_id,rest_sn,start_time,end_time,money,email,month,r.r_id from rest_web_order r INNER JOIN restaurant re on re.r_id=r.r_id where r.r_id=$r_id order by start_time desc limit $limit,$per_num";
        $info = Db::query($sql);
        //总数
        $sql = "select  w_id,rest_sn,start_time,end_time,money,email,month,r.r_id from rest_web_order r INNER JOIN restaurant re on re.r_id=r.r_id where r.r_id=$r_id order by start_time desc";
        $infos = Db::query($sql);

        							 // print_r($info);die;
        $nums = count($infos);
        $page_num=ceil($nums/$per_num);
        $this->assign('info', $info);
        $this->assign('pages',$page_num);
		return $this->fetch('order2');
	}	


//个人订单;
	public function u_order(){
 			$user=Session::get('user');
       	 	$u_id=$user['u_id'];
       	 	$page=isset($_GET['page'])?$_GET['page']:1;
       	 	//每页显示条数
            $per_num=5;
            //偏移量
       	 	$limit=($page-1)*$per_num;
       	 							// echo $u_id;die;

		$sqls = "select uo_id,user_sn,uo_time,r.account as username,u.account,t_id,money,status from (user_order uo INNER JOIN user u on u.u_id=uo.u_id) INNER JOIN restaurant r on r.r_id=uo.r_id where uo.u_id=$u_id order by uo_time desc limit $limit,$per_num";
		$data = Db::query($sqls);
				//一共多少条				// print_r($data);die;
        $sql = "select uo_id,user_sn,uo_time,r.account as username,u.account,t_id,money,status from (user_order uo INNER JOIN user u on u.u_id=uo.u_id) INNER JOIN restaurant r on r.r_id=uo.r_id where uo.u_id=$u_id order by uo_time desc";
        $datas = Db::query($sql);
		//一共多少页
        $nums=count($datas);
        $page_num=ceil($nums/$per_num);

        $num = count($data);
									// print_r($data);die;
		for($i=0;$i<$num;$i++){
			$t_id = trim($data[$i]['t_id'],',');
			$sql = "select dish from type where t_id in (".$t_id.")";
			$dish = Db::query($sql);
			$data[$i]['dish'] = $dish;

		}
//		print_r($datas);
//		print_r($data);die;
		$this->assign('data',$data);
		$this->assign('pages',$page_num);
		return $this->fetch('u_order');
	}



//状态值修改; 
	public function uorder_up(){
		$data=$_POST;
						// print_r($data);die;
		$status=$data['status'];
		$id=$data['id'];

		$num=\think\Db::table('user_order')->where('uo_id',$id)->update(array('status'=>$status));
		if($num){
			echo 1;
		}else{
			echo 2;
		}

	}




//支付页面;
	public function pay(){	
		$id=$_GET['ids'];
							// echo $id;die;
		return $this->fetch('pay',array('ids'=>$id));

	}



//   用户订餐状态值 status （user_order）可能存在以下几种情况：

// 0 --- 用户刚点餐后值为 0;					  自动
// 1 --- 用户支付给web后值为 1;	 			  	  自动
// 2 --- 饭店接到订单后去做饭 2;    			  饭店
// 3 --- 饭店做完饭后-->>派出并且未送达 3; 	  	  饭店
// 4 --- 饭已经送出去-->>客户收到未确定 4; 	  	  饭店
// 5 --- 客户确认收货|已送到并且7天倒计时结束 5;  客户|系统自动

//   状态值一旦变为5之后web把钱转给饭店




}
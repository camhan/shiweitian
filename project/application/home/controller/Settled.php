<?php
namespace app\home\controller;

use think\Controller;
use think\Db;
use \think\Session;

class Settled extends Common{
    //饭店入驻页面
    public function index()
    {
        //获取该饭店id
        $dinfo = Session::get('dish');
        $data = DB('rest_web_order')->where('r_id', $dinfo['r_id'])->limit(2)->order('w_id', 'desc')->select();
//        print_r($data);
        $time='';
      if($data){
          //如果已经入驻过那么就提示
          $time_f = $data[0]['start_time'];
          $time_l = $data[0]['end_time'];
          $now = time();
          //对比一下现在的月数是否已经过期了
          if($now-$time_l>0){
              //过期了
              $time.='到期啦!续费吧';
          }else{
              //时间还未到期   在比较天数
              $cha=$data[0]['end_time']-strtotime('now');
//              if($data[0]['start_time']<$now){
//                  //如果最新两次中 不在最新那条 那么剩余天数需要两条联合算出
//
//              }
              $days=floor($cha/30/24/60/60);
              if($days>0){
                  $time.="您目前正在入驻本站！";
              }else{
                  $time.='即将到期啦!续费吧';
              }
          }
      }else{
          //第一次入驻 表示欢迎
          $time='欢迎入驻！';
      }

//        echo $time;die;
        $this->assign('end', $time);
        return $this->fetch('index');
    }
    //饭店入驻添加
    public function add(){
        $data=input('post.');
        if($data['month']==6){
            $money=600;
        }
        if($data['month']==12){
            $money=1000;
        }
        if($data['month']==24){
            $money=2000;
        }
        if($data['month']==36){
            $money=3000;
        }
        $dinfo=Session::get('dish');
        $data['rest_sn']="ESC".rand(1000000000,9999999999).$dinfo['r_id'];

        $now=time();
//        $datas = DB('rest_web_order')->where(['r_id'=>$dinfo['r_id']],['end_time','>',"$now"])->limit(2)->order('w_id', 'desc')->select();
        $sqls="select * from rest_web_order WHERE r_id={$dinfo['r_id']} AND end_time>$now ORDER BY w_id DESC limit 2";
        $datas = DB::query($sqls);
//        print_r($datas);die;
        if($datas){
            //如果已经入驻过那么就提示
            $time_f = $datas[0]['start_time'];
            $time_l = $datas[0]['end_time'];
            //对比一下现在的月数是否已经过期了
            if($now-$time_l>0){
                //过期了
                $times=date(strtotime("{$data['month']}"."months"));
                $res=DB('rest_web_order')->insert(['rest_sn'=>"{$data['rest_sn']}",'r_id'=>"{$dinfo['r_id']}",'month'=>"{$data['month']}",'money'=>"$money",'start_time'=>"$time_l",'end_time'=>"$times"]);
                if($res){
                    $this->success('恭喜入驻！','show/show2');
                }
            }else{
                if(count($datas)>1){
                    //如果多次续费那么就提示 在当前日期基础上最多再加一次
                    $time1 = $datas[0]['start_time'];
                    if($time1>$now){
                        $this->error('续费次数不宜过多哦');
                    }else{
                        $month=$datas[0]['month']+$datas[1]['month'];
                        $times=date(strtotime("$month"."months"));
                        $res=DB('rest_web_order')->insert(['rest_sn'=>"{$data['rest_sn']}",'r_id'=>"{$dinfo['r_id']}",'month'=>"{$data['month']}",'money'=>"$money",'start_time'=>"{$datas[1]['end_time']}",'end_time'=>"$times"]);
//        $data['start_time']=strtotime($data['start_time']);
//        $data['end_time']=date(strtotime("+$data[time] months"));
                        if($res){
                            $this->success('恭喜入驻！','show/show2');
                        }
                    }

                }else{
                    $month=$datas[0]['month']+$data['month'];
                    $times=date(strtotime("$month"."months"));
                    $res=DB('rest_web_order')->insert(['rest_sn'=>"{$data['rest_sn']}",'r_id'=>"{$dinfo['r_id']}",'month'=>"{$data['month']}",'money'=>"$money",'start_time'=>"{$datas[0]['end_time']}",'end_time'=>"$times"]);
                    if($res){
                        $this->success('恭喜入驻！','show/show2');
                    }
                }
            }
        }else{
            //第一次入驻 表示欢迎
            $times=date(strtotime("{$data['month']}"."months"));
            $res=DB('rest_web_order')->insert(['rest_sn'=>"{$data['rest_sn']}",'r_id'=>"{$dinfo['r_id']}",'month'=>"{$data['month']}",'money'=>"$money",'start_time'=>"$now",'end_time'=>"$times"]);
            if($res){
                $this->success('恭喜入驻！','show/show2');
            }
        }

    }
}

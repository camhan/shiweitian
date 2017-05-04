<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:99:"B:\Cam1\wamp\phpStudy\WWW\php10\shiweitian\project\public/../application/home\view\index\index.html";i:1493781951;}*/ ?>
﻿<!DOCTYPE html >
<head>
<title>首页</title>
<base href="__PUBLIC__/home/shop/">
<meta name="keywords" content="">
<meta name="description" content="">
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0 , maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" type="text/css" href="css/apply.css">
<link rel="stylesheet" type="text/css" href="css/share.css">
<link rel="stylesheet" type="text/css" href="css/public.css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="js/swiper-2.1.min.js"></script>
<script type="text/javascript" src="js/lazyload.js"></script>

<script type="text/javascript" charset="utf-8">   
        $(function() {   
            $("img").lazyload({
        effect      : "fadeIn"
      });
        });   
    </script>
</head>


<!--轮播图样式-->
<style>
    .warps{
        position: relative;
        width: 500px;
        margin: 0 auto;
        top: 10px;
    }
    *{
        margin: 0;
        padding: 0;
    }
    #boxs{
        width: 500px;
        height: 300px;
        position: relative;
        margin: 0 auto;
        border: 2px solid red;
        overflow: hidden;
    }
    #boxs div{
        position: absolute;
        left: 0;
        height: 300px;
        width: 2500px;
    }
    #boxs div img{
        float: left;
        width: 500px;
        height: 300px;
    }

    .warps ul{
        position: absolute;
        bottom: 0;
        right: -70px;


    }
    .warps ul li{
        width: 60px;
        height: 60px;
        background: silver;
        margin-right: 5px;
        list-style: none;
    }
    .warps ul li img{
        margin: 5px;
        width: 50px;
        height: 50px;
    }
    .warps ul .bg{
        background: red;
    }
</style>
<body>

<div id="wrap" class="clearfix overflow mg-auto"> 
   <div class="apply-nav overflow mg-auto area">
       <div class="overflow clearfix apply-top border-bom">
           <a href="<?php echo url('show/show1'); ?>" class="apply-return">返回</a>
           <h2>饭店列表</h2>
           <a class="apply-sou" id="apply-sou">搜索</a>
       </div>
       <div class="i-icon mg-auto overflow area clearfix" style="background-color: #00E8D7;display: none;" id="apply-display">
           <center>
               <table>
                   <tr>
                       <td><input type="text" class="text" placeholder="请输入你要查询的饭店"></td>
                       <td><button id="search"><b>点击搜索</b></button></td>
                   </tr>
               </table>
           </center>

        </div>

       <!--轮播图-->
       <div class="warps" style="margin-right:1000px">
           <div id="boxs"  style=" width:800px; height:295px" >
   <div>
 <?php if(is_array($imgs) || $imgs instanceof \think\Collection || $imgs instanceof \think\Paginator): if( count($imgs)==0 ) : echo "" ;else: foreach($imgs as $key=>$v): ?>

 <a href="<?php echo url('restaurant/rest_detail'); ?>?id=<?php echo $v['r_id']; ?>"  ><img src="<?php echo $v['r_img']; ?>"></a>
 <?php endforeach; endif; else: echo "" ;endif; ?>
 <a href="<?php echo url('restaurant/rest_detail'); ?>?id=<?php echo $imgs[0]['r_id']; ?>" ><img src="<?php echo $imgs[0]['r_img']; ?>"></a>
   </div>
           </div>
           <ul>
               <?php if(is_array($imgs) || $imgs instanceof \think\Collection || $imgs instanceof \think\Paginator): if( count($imgs)==0 ) : echo "" ;else: foreach($imgs as $key=>$v): ?>
               <li class="bg" style="margin-right:510px;" ><img src="<?php echo $v['r_img']; ?>"></li>
               <?php endforeach; endif; else: echo "" ;endif; ?>
           </ul>
       </div>
   </div>

<div id="wrap" class="clearfix overflow mg-auto">


  <!--最热开始-->
    <div class="i-tab mg-auto overflow area clearfix">
      <div class="i-tab-title">
          <h2></h2>
          <div class="tab-btn" id="tab1">
            <p class="mg-auto clearfix">
             <a href="javascript:;" class="tab-cur">饭店</a>
             <a href="javascript:;">分类</a>
            </p>
          </div>
      </div>
      <div class="" id="con1">
         <div class="" style="display:block;" id="box">
             <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $key=>$v): ?>
             <ul class="clearfix"  style="margin-left:50px">
       <li>
           <h3><?php echo $v['account']; ?></h3>
           <a href="<?php echo url('restaurant/rest_detail'); ?>?id=<?php echo $v['r_id']; ?>" class="tab-con-href">
               <div ><img src="<?php echo $v['r_img']; ?>?imageView2/1/w/200/h/150/q/75|imageslim" >
              <i class="hoticon"></i>
                  </div>
           </a>
           <a style="margin-left:30px;" href="<?php echo url('restaurant/rest_detail'); ?>?id=<?php echo $v['r_id']; ?>" class="tab-title-href"><?php echo $v['address']; ?></a>
       </li>
       </ul><br>
             <?php endforeach; endif; else: echo "" ;endif; ?>
         </div>
         
             <div class="clearfix tab-con2 tab-con-info">
              <ul class="clearfix">
                  <?php if(is_array($type) || $type instanceof \think\Collection || $type instanceof \think\Paginator): if( count($type)==0 ) : echo "" ;else: foreach($type as $key=>$one): ?>
                  <li>
                      <i href="soft/list_956_1.html" class="tab-con-href2 clearfix">
                          <div class="img2"><img src="img/loading.png" original="images/qbx_logo.jpg" alt="" title=""></div>
                          <h4 class="one" t_id="<?php echo $one['t_id']; ?>" ><?php echo $one['dish']; ?></h4>
                      </i>
                  </li>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>

          </div>
          <center>
              <b style="padding-left: 300px;">
                  <?php
					for($i=1;$i<=$sum;$i++){
						echo "<a href='__URL__/search?page=$i&text=".$text."' style='padding-left: 20px;float: left;'>$i</a>";
                  }
                  ?>
              </b>
          </center>
      </div>
        <span ><b id="more">更多</b></span>
    </div>
  <!--最热结束-->

    <!--底部-->
  <div style="margin-top:15px;display: block;border-bottom: solid 1px #FFF;border-top: solid 1px #cacaca;text-indent: -9999px;height: 0px;">line</div>
  <div style="width: 100%; height: 3.8em; line-height: 3.8em; text-align: center; color:#666"></div> 
  
</div>
<input type="hidden" value="<?php echo url('Index/index'); ?>">
</body> 
<script type="text/javascript" src="js/com.js"></script>
<script type="text/javascript">
    window.onload = function(){
      var mySwiper = new Swiper('.swiper-container',{
        pagination: '.pagination',
        loop:true,
        autoplay:3000,
        paginationClickable: true,
        onSlideChangeStart: function(){
           //会调函数
        }
      });
      cTab("#tab1","#con1");
      cTab("#tab2","#con2");
    };
    $(function(){
        var k = 0;
        $('.one').click(function(){
            var id = $(this).attr('t_id');
            var dish = $(this).text();
            location.href='http://www.food.com/public/home/index/type?id='+id+'&dish='+dish;
        });
        //更多
        $("#more").click(function(){
            k++;
            var url = $("#url").val();
            $.ajax({
                type:"POST",
                url: url,
                data: {k:k},
                dataType:'json',
                success:function(msg){
                  //var msg = eval(msg);
var html = "";
for(var j in msg){
    html += "<ul class='clearfix' style='margin-left:50px'><li><h3 style='margin-left:50px'>"+msg[j]['account']+"</h3><a href='<?php echo url('restaurant/rest_detail'); ?>?id="+msg[j]['r_id']+"' class='tab-con-href'><div ><img   src="+msg[j]['r_img']+"?imageView2/1/w/200/h/150/q/75|imageslim"+"><i class='hoticon'></i> </div> </a><a href='<?php echo url('restaurant/rest_detail'); ?>?id="+msg[j]['r_id']+"' class='tab-title-href'>"+msg[j]['address']+"</a> </li> </ul><br>";
}
$("#box").append(html);
                }
            })
        })
    });
    //搜索
    $('#apply-sou').click(function () {
        $('#apply-display').show();
    });
    $('#search').click(function () {
        var text=$('.text').val();
        $.ajax({
            type:"GET",
            url: "<?php echo url('index/search'); ?>",
            data: {text:text},
            dataType:'json',
            success:function(msg){
                //var msg = eval(msg);
//                var html = "";
//                for(var j in msg){
//                    html += "<ul class='clearfix' style='margin-left:50px'><li><h3 style='margin-left:50px'>"+msg[j]['account']+"</h3><a href='<?php echo url('restaurant/rest_detail'); ?>?id="+msg[j]['r_id']+"' class='tab-con-href'><div ><img  width='450px;' src='__ROOT__/shop/"+msg[j]['r_img']+"'><i class='hoticon'></i> </div> </a><a href='<?php echo url('restaurant/rest_detail'); ?>?id="+msg[j]['r_id']+"' class='tab-title-href'>"+msg[j]['address']+"</a> </li> </ul><br>";
//                }
                $("#wrap").html(msg);
            }
        })
    });

//    轮播图
    $(function(){
        var oList=$(".warps ul li");
        var oUl=$("#boxs div");
        var liLength=oList.size();
        var _now=0;
        oList.click(function(){
            $(this).addClass('bg').siblings().removeClass('bg');
            var _index=$(this).index();
//            alert(_index);
            _now=_index;
            var offset=-_index * 500;
//            alert(offset);
            //图片滚动
            oUl.animate({left:offset},'slow');
        });
        //定时器

        var timer=setInterval(move,1000);
        function move(){
            if(_now< 5){
                oList.eq(_now).addClass('bg').siblings().removeClass('bg');
                oUl.animate({left:-_now * 500},'slow');
                _now++;
            }else{
                _now=0;
            }
        }
        $("#boxs").mouseenter(function(){
            clearInterval(timer);
        }).mouseleave(function(){
            timer=setInterval(move,1000);
        })
    })
</script>

</html>
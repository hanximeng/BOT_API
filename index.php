<?php
/*
* @Package：BOT基础框架
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

//屏蔽报错
error_reporting(0);
//设置时区
date_default_timezone_set('Asia/Shanghai');
$content = file_get_contents('php://input');

//空参数或直接访问时的提示
if(empty($content)){
	//方便后期添加WEB页面
	include './themes/index.php';
	//如非机器人调用则停止与此避免影响
	die();
}

header('content-type:application/json');
$Data=json_decode($content,true);

//创建日志文件夹用来存放群组及私聊消息
mkdir('./Data');
mkdir('./Data/Log');
mkdir('./Data/Cron/');
mkdir('./Data/Log/User');
mkdir('./Data/Log/Other');
mkdir('./Data/Log/Group');
mkdir('./Data/Log/User/'.date('Y-m-d',time()));
mkdir('./Data/Log/Group/'.date('Y-m-d',time()));

//判断类型并存入对应日志目录
if(!empty($Data['group_id'])){
	file_put_contents('./Data/Log/Group/'.date('Y-m-d',time()).'/'.$Data['group_id'].'.txt',$content, FILE_APPEND);
}elseif(!empty($Data['user_id'])){
	file_put_contents('./Data/Log/User/'.date('Y-m-d',time()).'/'.$Data['user_id'].'.txt',$content, FILE_APPEND);
}elseif($Data['meta_event_type'] !== 'heartbeat'){
	//排除心跳事件
	file_put_contents('./Data/Log/Other/'.date('Y-m-d',time()).'.txt',$content, FILE_APPEND);
}

//配置文件
include './inc/config.php';
//函数库
include './inc/function.php';
//自动加载Plugins下的所有的插件
$list = glob('./plugins/*.php');
foreach($list as $file){
	$file=explode('/',$file)['2'];
	require './plugins/'.$file;
}

?>
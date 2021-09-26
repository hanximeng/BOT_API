<?php
/*
* @Plugin Name：毒鸡汤插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message']=='毒鸡汤'){
	//此处调用的好友北冥龙鲲的API服务，可自行更换
	$Json=curl('http://api.lkblog.net/ws/api.php');
	$Soul=json_decode($Json,true)['data'];

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])){
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Soul.'"}');
	}else{
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Soul.'"}');
	}
}
?>
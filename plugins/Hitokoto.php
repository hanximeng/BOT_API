<?php
/*
* @Plugin Name：一言插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message']=='一言'){
	//此处调用的Hitokoto.cn的API服务，可自行更换
	$Json=curl('https://v1.hitokoto.cn');
	$Hitokoto=json_decode($Json,true);

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])){
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Hitokoto['hitokoto'].'\n\nFrom：'.$Hitokoto['from'].'"}');
	}else{
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Hitokoto['hitokoto'].'\n\nFrom：'.$Hitokoto['from'].'"}');
	}
}
?>
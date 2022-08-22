<?php
/*
* @Plugin Name：短视频插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message']=='！短视频'){
	//此处调用的xiao-xin的API服务，可自行更换
	$Json=curl('https://api.xiao-xin.top/API/kuaishou.php?type=json');
	$Kuai_Video=json_decode($Json,true)['video_url'];

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])){
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:video,file='.$Kuai_Video.']"}');
	}
}
?>
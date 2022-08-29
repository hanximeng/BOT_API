<?php
/*
* @Plugin Name：短视频插件
* @Author：Hanximeng
* @Version：Beta_0.0.2
*/

if($Data['message']=='！短视频'){
	//此处调用的banzi66的API服务，可自行更换
	$Json=curl('http://hc.baozi66.top:99/xjj.php?type=json');
	$Kuai_Video=json_decode($Json,true)['video_url'];

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])){
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:video,file='.$Kuai_Video.']"}');
	}
}
?>

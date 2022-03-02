<?php
/*
* @Plugin Name：KonaChan插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

//获取指定Tag图片，仅支持英文
if(strpos($Data['message'],"来一张")!==false){
	$Limit=rand(0,9);
	$KeyWord=explode('来一张 ',$Data['message']);
	//net为去除了18+的子站点，主站点为com，+rating:safe为剔除不健全的图片，可根据自身需求修改。
	$Json=curl("https://konachan.net/post.json?page=1&limit=10&tags=".$KeyWord['1']."+rating:safe");
	$Info=json_decode($Json,true);
	if(empty($Info[$Limit]['file_url'])) {
		$Text='没找到相关图片';
	} else {
		$Text='[CQ:image,file='.$Info[$Limit]['file_url'].']';
	}

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n'.$Text.'"}');
	}
	exit;
}

//随机一张
if($Data['message']=="来一张") {
	$Page=rand(1,10000);
	$Limit=rand(0,9);

	//net为去除了18+的子站点，主站点为com，+rating:safe为剔除不健全的图片，可根据自身需求修改。
	$Json=curl("https://konachan.net/post.json?page=".$Page."&limit=10&tags=+rating:safe");
	$Info=json_decode($Json,true);

	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n[CQ:image,file='.$Info[$Limit]['file_url'].']"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n[CQ:image,file='.$Info[$Limit]['file_url'].']"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']\n[CQ:image,file='.$Info[$Limit]['file_url'].']"}');
	}
}
?>
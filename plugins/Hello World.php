<?php
/*
* @Plugin Name：Hello World 测试用插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message']=="你好"){
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])){
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"Hello World!"}');
	}else{
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"Hello World!"}');
	}
}
?>
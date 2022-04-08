<?php
/*
* @Plugin Name：自动同意邀请入群插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['request_type'] == 'group' and $Data['sub_type'] == 'invite'){
	//此处判断是否是来自机器人管理员的邀请
	if($Data['user_id'] == $Admin_QQ){
		//判断是否存在flag，若不存在则不是邀请入群
		if(!empty($Data['flag'])){
			http_post_json('set_group_add_request','{"flag":"'.$Data['flag'].'","sub_type":"'.$Data['sub_type'].'","approve":"true"}');
			http_post_json('send_msg','{"user_id":"'.$Admin_QQ.'","message":"QQ群：'.$Data['group_id'].'\n邀请者：'.$Data['user_id'].'\n处理结果：同意"}');
		}
	}else{
		if(!empty($Data['flag'])){
			http_post_json('set_group_add_request','{"flag":"'.$Data['flag'].'","sub_type":"'.$Data['sub_type'].'","approve":"false","reason":"抱歉，您暂时没有这项权限。"}');
			http_post_json('send_msg','{"user_id":"'.$Admin_QQ.'","message":"QQ群：'.$Data['group_id'].'\n邀请者：'.$Data['user_id'].'\n处理结果：拒绝"}');
		}
	}
}
?>

<?php
/*
* @Plugin Name：小i插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

/*
使用教程：先到小i进行注册登录，网址：http://open.xiaoi.com/
app_uid获取：登录后会自动跳转，http://open.xiaoi.com/user/user_main.jsp?uid=xxx 将uid后的复制粘贴至app_uid中
自定义机器人信息：点开左侧系统设置=>基本设置中可修改机器人名称及其他信息
*/

//仅对#开头的消息进行处理，也可将#修改为“[CQ:at,qq=机器人QQ号] ”则仅对艾特机器人的消息进行处理，请勿粘贴“”及遗漏空格
if (strpos($Data['message'], "#") !== false) {
	$Info = explode('#', $Data['message']);
	if ($Info[1] !== '' && $Info[0] == '') {
		$app_uid='';
		if(!empty($app_uid)){
			$XiaoI_Json = Curl_Post('http://open.xiaoi.com/apiDebug/apiDebug!ask.do', 'question=' . urlencode($Info[1]) . '&userId=qq_' . $Data['user_id'] . '&platform=web&type=1&app_uid='.$app_uid);
			$XiaoI_Arr = json_decode($XiaoI_Json, true);
			$XiaoI_Data = simplexml_load_string($XiaoI_Arr['result']);
			$Content = str_replace(array(PHP_EOL, "\r", "\n", "\n\r", "\r\n"), '\n', $XiaoI_Data->Content);
			//判断消息是否重复
			if ($Content !== '重复回复') {
				$Text = $Content;
			} else {
				//如需如需对重复消息进行自定义则取消下行处注释
				//$Text='重复消息的提示。';
				//die 则不对同一个用户发送的重复消息进行处理，注释后将发送重复消息的提示
				die();
			}
		}else{
			$Text='未配置小i的app_uid，该插件暂时无法使用！';
		}
		//判断是否存在group_id，若不存在则为私聊
		if (!empty($Data['group_id'])) {
			http_post_json('send_msg', '{"group_id":"' . $Data['group_id'] . '","message":"[CQ:reply,id=' . $Data['message_id'] . ']' . $Text . '"}');
		} elseif (!empty($Data['guild_id'])) {
			http_post_json('send_guild_channel_msg', '{"guild_id":"' . $Data['guild_id'] . '","channel_id":"' . $Data['channel_id'] . '","message":"' . $Text . '"}');
		} else {
			http_post_json('send_msg', '{"user_id":"' . $Data['user_id'] . '","message":"[CQ:reply,id=' . $Data['message_id'] . ']' . $Text . '"}');
		}
	}
}

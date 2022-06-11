<?php
/*
* @Plugin Name：查找群成员插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if(strpos($Data['message'],"！Check_QQ ")!==false) {
	$Info=explode('！Check_QQ ',$Data['message']);
	if($Info[1]!=='' && $Info[0]=='') {
		function Check_QQ($Group,$QQ) {
			$List=http_post_json('get_group_member_list','{"group_id":"'.$Group.'"}');
			$Arr=json_decode($List,true);
			foreach ($Arr['data'] as $value) {
				if($value['user_id'] == $QQ) {
					return true;
				}
			}
		}

		if(Check_QQ($Data['group_id'],$Info[1]) == true) {
			$Text = '本群中存在目标用户！';
		} else {
			$Text = '本群中未找到目标用户！';
		}

		http_post_json('send_group_msg','{"group_id":'.$Data['group_id'].',"message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	}
}
<?php
/*
* @Plugin Name：群打卡签到插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message']=='签到'){
	//因为是群签到插件，所以只对群消息有反应
	if(!empty($Data['group_id'])){
		if(!file_exists('./Data/Plugins/GroupSign/'.date("Y-m-d",time()))){
			mkdir('./Data/Plugins/');
			//用于存储签到数据的目录
			mkdir('./Data/Plugins/GroupSign/');
			//将每天的数据单独存放
			mkdir('./Data/Plugins/GroupSign/'.date("Y-m-d",time()));
		}

		//判断是否已存在用户文件避免重复签到
		if(!file_exists('./Data/Plugins/GroupSign/'.date("Y-m-d",time()).'/'.$Data['user_id'].'.txt')){
			file_put_contents('./Data/Plugins/GroupSign/'.date("Y-m-d",time()).'/'.$Data['user_id'].'.txt','已签到');
			$Text='[CQ:reply,id='.$Data['message_id'].']签到成功！';
		}else{
			$Text='[CQ:reply,id='.$Data['message_id'].']今日已签！';
		}

		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"'.$Text.'"}');
	}
}
?>
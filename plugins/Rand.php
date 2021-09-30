<?php
/*
* @Plugin Name：帮我选
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if(strpos($Data['message'],"帮我选 ")!==false){
	$Info=explode('帮我选 ',$Data['message']);
	if($Info[1]!=='' && $Info[0]==''){
		$Rand=explode('|',$Info[1]);
		//简单的判断一下有没有重复或者仅有一个参数
		if(count($Rand) != count(array_unique($Rand)) or count($Rand) == 1){
			$Text='[CQ:reply,id='.$Data['message_id'].']发现错误的传入参数，本次操作已取消！';
		}else{
			$Text='[CQ:reply,id='.$Data['message_id'].']为您随机到了以下选项\n\n【'.$Rand[array_rand($Rand,1)].'】\n\n结果仅供参考，请勿用于其他场合！';
		}
		
		//判断是否存在group_id，若不存在则为私聊
		if(!empty($Data['group_id'])){
			http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"'.$Text.'"}');
		}else{
			http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
		}
	}
}
?>
<?php
/*
* 酷安图片
*/

if(strpos($Data['message'],"！百科 ")!==false) {
	function unicodeDecode($name) {
		$json = '{"str":"'.$name.'"}';
		$arr = json_decode($json,true);
		if(empty($arr)) return '';
		return $arr['str'];
	}
	$Info=explode('！百科 ',$Data['message']);
	$Keyword=$Info[1];
	if($Info[1]!=='' && $Info[0]=='') {
		$Url='https://baike.baidu.com/item/'.$Keyword;
		$Headers=get_headers($Url, TRUE);
		if(is_array($Headers['Location'])) {
			$Count=count($Headers['Location']) - 1;
			$Headers['Location']=$Headers['Location'][$Count];
		}
		if(!empty($Headers['Location'])) {
			$Url='https://baike.baidu.com'.$Headers['Location'];
		}
		if($Url == 'https://baike.baidu.comhttps://baike.baidu.com/error.html?status=404&uri=/item/'.$Info[1]) {
			$Text='未查询到相关结果！';
		} else {
			$html=curl($Url);
			preg_match('#bdText: "(.*?.)",#',$html,$Bd_WiKi);
			$Text=unicodeDecode($Bd_WiKi[1]);
			$Text=str_replace('_百度百科】','_百度百科】\n\n',$Text);
			$Text=str_replace('（分享自@百度百科）','\n\n（分享自@百度百科）\n\n详情：https://baike.baidu.com/item/'.urlencode($Info[1]),$Text);
		}
		//判断是否存在group_id，若不存在则为私聊
		if(!empty($Data['group_id'])) {
			http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
		} elseif(!empty($Data['guild_id'])) {
			http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
		} else {
			http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
		}
	}
}
?>
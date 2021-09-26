<?php
/*
* @Plugin Name：哔哩哔哩音乐点歌
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if(strpos($Data['message'],"哔哩点歌 ")!==false){
	$Info=explode('哔哩点歌 ',$Data['message']);
	if($Info[1]!=='' && $Info[0]==''){
		$Song=curl('https://api.bilibili.com/audio/music-service-c/s?keyword='.urlencode($Info[1]),'www.bilibili.com','Mozilla/5.0 (Linux; Android 11; Redmi k40 Build/QKQ1.191222.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.203 mobile Safari/537.36baiduboxapp/3.2.5.10 SearchCraft/2.8.2 (Baidu; P1 10)');
		$ID=json_decode($Song,true);
		$html=curl('https://m.bilibili.com/audio/au'.$ID['data']['result']['0']['id'],'www.bilibili.com','Mozilla/5.0 (Linux; Android 11; Redmi k40 Build/QKQ1.191222.002) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.203 mobile Safari/537.36baiduboxapp/3.2.5.10 SearchCraft/2.8.2 (Baidu; P1 10)');
		preg_match_all('#window.__INITIAL_STATE__ = (.*?.);#',$html,$url);
		$Bili_Music=json_decode($url['1']['0'],true);
		$Bili_Music['reduxAsyncConnect']['audioInfo']['urls']['0']=str_ireplace(',','&#44;',$Bili_Music['reduxAsyncConnect']['audioInfo']['urls']['0']);
		
		if(empty($Bili_Music['reduxAsyncConnect']['audioInfo']['urls']['0'])){
			$Text='哔哩点歌暂时没有 '.$Info[1].' 这首歌的播放地址，请尝试更换其他引擎或关键词！';
		}else{
			$Text='[CQ:music,type=custom,audio='.$Bili_Music['reduxAsyncConnect']['audioInfo']['urls']['0'].',title='.$Bili_Music['reduxAsyncConnect']['audioInfo']['title'].',content='.$Bili_Music['reduxAsyncConnect']['audioInfo']['author'].',image='.$Bili_Music['reduxAsyncConnect']['audioInfo']['cover_url'].',url=https://bot.hanximeng.com]';
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
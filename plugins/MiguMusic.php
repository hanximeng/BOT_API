<?php
/*
* @Plugin Name：咪咕音乐点歌
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if(strpos($Data['message'],"咪咕点歌 ")!==false){
	$Info=explode('咪咕点歌 ',$Data['message']);
	if($Info[1]!=='' && $Info[0]==''){
		$Json=curl('https://m.music.migu.cn/migu/remoting/scr_search_tag?rows=20&type=2&keyword='.urlencode($Info[1]).'&pgc=1','m.music.migu.cn');
		$Migu=json_decode($Json,true);
		$Migu['musics']['0']['mp3']=str_replace('https://','http://',$Migu['musics']['0']['mp3']);
		
		if(empty($Migu['musics']['0']['mp3'])){
			$Text='咪咕音乐暂时没有 '.$Info[1].' 这首歌的播放地址，请尝试更换其他引擎或关键词！';
		}else{
			$Text='[CQ:music,type=custom,audio='.$Migu['musics']['0']['mp3'].',title='.$Migu['musics']['0']['songName'].',content='.$Migu['musics']['0']['artist'].',image='.$Migu['musics']['0']['cover'].',url=https://bot.hanximeng.com]';
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
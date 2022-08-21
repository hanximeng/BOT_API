<?php
/*
* @Plugin Name：天气排行
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/


if($Data['message']=="！高温排行"){
mkdir('./Data/Plugins/Weather/');
if(date("H",filemtime('./Data/Plugins/Weather/Data.txt')) !== date("H",time())){
	$html=curl('http://d1.weather.com.cn/dataset/todayRanking.html','http://www.weather.com.cn/');
	file_put_contents('./Data/Plugins/Weather/Data.txt',$html);
}else{
    $html=file_get_contents('./Data/Plugins/Weather/Data.txt');
}
	preg_match("#todayRanking\((.*?.)\)#",$html,$Arr);
	$arr=json_decode($Arr[1],true);

    $x=1;
    $text='';
    foreach ($arr['tMax']['info'] as $value) {
        $text.='['.$x++.'] ('.$value['province'].')'.$value['county'].' ['.$value['temp'].'℃]\n';
    }
	$Text=$text.'\n'.$arr['tMax']['tips'];
	
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}


if($Data['message']=="！低温排行"){
	mkdir('./Data/Plugins/Weather/');
	if(date("H",filemtime('./Data/Plugins/Weather/Data.txt')) !== date("H",time())){
	$html=curl('http://d1.weather.com.cn/dataset/todayRanking.html','http://www.weather.com.cn/');
	file_put_contents('./Data/Plugins/Weather/Data.txt',$html);
}else{
    $html=file_get_contents('./Data/Plugins/Weather/Data.txt');
}
	preg_match("#todayRanking\((.*?.)\)#",$html,$Arr);
	$arr=json_decode($Arr[1],true);

    $x=1;
    $text='';
    foreach ($arr['tMin']['info'] as $value) {
        $text.='['.$x++.'] ('.$value['province'].')'.$value['county'].' ['.$value['temp'].'℃]\n';
    }
	$Text=$text.'\n'.$arr['tMin']['tips'];
	
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}


if($Data['message']=="！温差排行"){
	mkdir('./Data/Plugins/Weather/');
	if(date("H",filemtime('./Data/Plugins/Weather/Data.txt')) !== date("H",time())){
	$html=curl('http://d1.weather.com.cn/dataset/todayRanking.html','http://www.weather.com.cn/');
	file_put_contents('./Data/Plugins/Weather/Data.txt',$html);
}else{
    $html=file_get_contents('./Data/Plugins/Weather/Data.txt');
}
	preg_match("#todayRanking\((.*?.)\)#",$html,$Arr);
	$arr=json_decode($Arr[1],true);

    $x=1;
    $text='';
    foreach ($arr['tDif']['info'] as $value) {
        $text.='['.$x++.'] ('.$value['province'].')'.$value['county'].' ['.$value['tDif'].'℃]\n';
    }
	$Text=$text.'\n'.$arr['tMax']['tips'];
	
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}


if($Data['message']=="！降水排行"){
	mkdir('./Data/Plugins/Weather/');
	if(date("H",filemtime('./Data/Plugins/Weather/rain.txt')) !== date("H",time())){
	$html=curl('http://d1.weather.com.cn/dataset/todayRanking.html','http://www.weather.com.cn/');
	file_put_contents('./Data/Plugins/Weather/rain.txt',$html);
}else{
    $html=file_get_contents('./Data/Plugins/Weather/rain.txt');
}
	preg_match("#todayRanking\((.*?.)\)#",$html,$Arr);
	$arr=json_decode($Arr[1],true);

    $x=1;
    $text='';
    foreach ($arr['rain']['info'] as $value) {
        $text.='['.$x++.'] ('.$value['province'].')'.$value['county'].' ['.$value['rain'].'mm]\n';
    }
	$Text=$text.'\n'.$arr['tMax']['tips'];
	
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}

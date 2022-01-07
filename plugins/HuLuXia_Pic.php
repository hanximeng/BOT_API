<?php
/*
* @Plugin Name：葫芦侠美女图片
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['message'] == "！美腿") {
	$Json = curl('http://floor.huluxia.com/post/list/ANDROID/2.1?platform=2&gkey=000000&app_version=4.1.0.7&versioncode=20141457&market_id=floor_web&_key=&device_code=%5Bd%5Dcd4e7ac1-906f-4045-975b-ae5e69ebb14a&phone_brand_type=OP&start=0&count=200&cat_id=56&tag_id=5601&sort_by=0','','okhttp/3.8.1');
	$Arr = json_decode($Json,true);
	$i = 0;
	foreach ($Arr['posts'] as $value) {
		if(empty($value['images'])) {
			preg_match('#image>(.*?.),(.*?.)</image#',$value['detail'],$image);
			$Test[$i] = $image[1];
		}
		foreach ($value['images'] as $image) {
			$images[$i] = $image;
		}
		$i++;
	}
	$images = array_merge($Test,$images);
	$Text = '[CQ:image,file='.$images[array_rand($images)].']\n\n数据来源：葫芦侠三楼';
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}

if($Data['message'] == "！三坑") {
	$Json = curl('http://floor.huluxia.com/post/list/ANDROID/2.1?platform=2&gkey=000000&app_version=4.1.0.7&versioncode=20141457&market_id=floor_web&_key=&device_code=%5Bd%5Dcd4e7ac1-906f-4045-975b-ae5e69ebb14a&phone_brand_type=OP&start=0&count=200&cat_id=56&tag_id=5603&sort_by=0','','okhttp/3.8.1');
	$Arr = json_decode($Json,true);
	$i = 0;
	foreach ($Arr['posts'] as $value) {
		if(empty($value['images'])) {
			preg_match('#image>(.*?.),(.*?.)</image#',$value['detail'],$image);
			$Test[$i] = $image[1];
		}
		foreach ($value['images'] as $image) {
			$images[$i] = $image;
		}
		$i++;
	}
	$images = array_merge($Test,$images);
	$Text = '[CQ:image,file='.$images[array_rand($images)].']\n\n数据来源：葫芦侠三楼';
	//判断是否存在group_id，若不存在则为私聊
	if(!empty($Data['group_id'])) {
		http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"[CQ:reply,id='.$Data['message_id'].']'.$Text.'"}');
	} elseif(!empty($Data['guild_id'])) {
		http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
	} else {
		http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
	}
}
?>
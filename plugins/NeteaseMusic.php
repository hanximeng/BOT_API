<?php
/*
* @Plugin Name：网易云音乐点歌
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if(strpos($Data['message'],"点歌 ")!==false){
    $Info=explode('点歌 ',$Data['message']);
    if($Info[1]!=='' && $Info[0]==''){
        $Json=curl("https://music.163.com/api/search/pc?type=1&s=".urlencode($Info[1])."&limit=1&offset=0","");
        $ID=json_decode($Json,true)['result']['songs']['0']['id'];
        $Text='[CQ:music,type=163,id='.$ID.']';
        
        //判断是否存在group_id，若不存在则为私聊
        if(!empty($Data['group_id'])){
            http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"'.$Text.'"}');
        }else{
            http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
        }
    }
}
?>
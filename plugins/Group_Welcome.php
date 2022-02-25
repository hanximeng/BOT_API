<?php
/*
* @Plugin Name：群欢迎插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

if($Data['notice_type']=='group_increase'){
    //获取群信息的接口
    $Group_Info=json_decode(curl($GLOBALS['BOT_Server'].'/get_group_info?no_cache=true&group_id='.$Data['group_id'].'&access_token='.$GLOBALS['BOT_Token']),true);
    //入群时所发送的信息
    $Text='====== 欢迎入群 ======\n\n欢迎新成员 [CQ:at,qq='.$Data['user_id'].'] ，您是本群第 '.$Group_Info['data']['member_count'].' 位群友，请花几分钟查看一下群内公告哦！';
    //发送到群内
    http_post_json('send_msg','{"group_id":'.$Data['group_id'].',"message":"'.$Text.'"}');
}
<?php
/*
* @Plugin Name：群欢迎插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

/**
 *User:Qicloud
 *File:Group_Welcome.php
 *Time:2022-04-0721:36
 *Project:BOT_LMY
 *QQ:66547997
 *浑浊变成一种常态，清白就成了一种罪
 */

/**
 * 入群欢迎
 */
if($Data['notice_type']=='group_increase'){
    //获取群信息的接口
    $Group_Info=json_decode(curl($GLOBALS['BOT_Server'].'/get_group_info?no_cache=true&group_id='.$Data['group_id'].'&access_token='.$GLOBALS['BOT_Token']),true);
    //入群时所发送的信息
    $Text='====== 欢迎入群 ======\n
    欢迎新成员 [CQ:at,qq='.$Data['user_id'].'] \n
    您是本群第 '.$Group_Info['data']['member_count'].' 位群友\n
    请花几分钟查看一下群内公告哦！';
    //发送到群内
    http_post_json('send_msg','{"group_id":'.$Data['group_id'].',"message":"'.$Text.'"}');
}
/**
 * 禁言通知
 */
if ($Data['notice_type']=='group_ban'){
    if ($Data['sub_type'] == 'ban'){
        $Text= '[CQ:at,qq='.$Data['user_id'].']'.'被管理员[CQ:at,qq='.$Data['operator_id'].']禁言'.$Data['duration'].'秒';
        http_post_json('send_msg','{"group_id":'.$Data['group_id'].',"message":"'.$Text.'"}');
    }elseif ($Data['sub_type'] == 'lift_ban'){
        $Text= '[CQ:at,qq='.$Data['user_id'].']'.'被管理员[CQ:at,qq='.$Data['operator_id'].']解除禁言';
        http_post_json('send_msg','{"group_id":'.$Data['group_id'].',"message":"'.$Text.'"}');
    }
}
/**
 * 运气王监控
 */
if ($Data['notice_type'] == 'notify')
{
    if ($Data['sub_type'] == 'lucky_king')
    {
        $Text= '[CQ:at,qq='.$Data['user_id'].']大佬的红包已抢完\n[CQ:at,qq='.$Data['target_id'].']是幸运王';
        http_post_json('send_msg','{"group_id":'.$Data['group_id'].',"message":"'.$Text.'"}');
    }
}

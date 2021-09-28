<?php
/*
* @Plugin Name：定时任务Demo
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

//每日任务
if(BOT_Time_Day('Day','12:00') == true){
	http_post_json('send_msg','{"group_id":"549522943","message":"这是一个【每日任务】功能测试，将会在 12:00 发送此消息，且一天仅发送一条，当前时间为：'.date("H:i").'"}');
}

//该功能支持用数组传入多个时间参数
$Day_Time=array('12:05','12:06');
if(BOT_Time_Day('Day',$Day_Time) == true){
	http_post_json('send_msg','{"group_id":"549522943","message":"这是一个【每日任务】功能测试，将会在 12:05 与 12:06 分别发送一次此消息，且一天每个时间仅发送一条，当前时间为：'.date("H:i").'"}');
}

//整点任务
if(BOT_Time_Hour('Hour','18') == true){
	http_post_json('send_msg','{"group_id":"549522943","message":"这是一个【整点】功能测试，将会整点十八分钟发送一次此消息，当前时间为：'.date("H:i").'"}');
}


//计时任务，需传入任务名和间隔时间且任务名不可重名
if(BOT_Time_Inr('InrTest','5') == true){
	http_post_json('send_msg','{"group_id":"549522943","message":"这是一个【定时任务】功能测试，将会每隔 5 分钟发送一次此消息，当前时间为：'.date("H:i").'，预计下次运行时间'.date("H:i",strtotime("+5 minute")).'"}');
}


//单次任务
if(BOT_Time_One('One','2021-09-28 11:25') == true){
	http_post_json('send_msg','{"group_id":"549522943","message":"这是一个【单次任务】功能测试，将会在 2021年9月28日上午11:25 发送此消息，仅运行一次，当前时间为：'.date("Y-m-d H:i").'"}');
}
?>
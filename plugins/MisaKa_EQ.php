<?php
/*
* @Plugin Name：地震速报插件
* @Author：Hanximeng
* @Version：Beta_0.0.1
*/

//判断是否存在插件数据目录
if(!file_exists('./Data/Plugins/MisaKa_EQ/')){
	mkdir('./Data/Plugins/MisaKa_EQ/');
}
//开启插件
if($Data['message']=='开启地震通报'){
	//仅允许群主或管理员进行操作
	if($Data['sender']['role']=='owner' or $Data['sender']['role']=='admin'){
		$Json=file_get_contents('./Data/Plugins/MisaKa_EQ/Group.json');
		if(empty($Json)){
			$Group=array();
		}else{
			$Group=json_decode($Json,true);
		}
		if(in_array($Data['group_id'],$Group)){
			http_post_json('send_msg','{"group_id": '.$Data['group_id'].',"message":"[CQ:reply,id='.$Data['message_id'].']请勿重复开启！"}');
		}else{
			$Group[]=''.$Data['group_id'].'';
			$Json=json_encode($Group);
			file_put_contents('./Data/Plugins/MisaKa_EQ/Group.json',$Json);
			http_post_json('send_msg','{"group_id": '.$Data['group_id'].',"message":"[CQ:reply,id='.$Data['message_id'].']已开启！"}');
		}
	}
}

//关闭插件
if($Data['message']=='关闭地震通报'){
	//仅允许群主或管理员进行操作
	if($Data['sender']['role']=='owner' or $Data['sender']['role']=='admin'){
		$Json=file_get_contents('./Data/Plugins/MisaKa_EQ/Group.json');
		if(empty($Json)){
			$Group=array();
		}else{
			$Group=json_decode($Json,true);
		}
		if(in_array($Data['group_id'],$Group)){
			$key=array_search($Data['group_id'],$Group);
			array_splice($Group,$key,1);
			$Json=json_encode($Group);
			file_put_contents('./Data/Plugins/MisaKa_EQ/Group.json',$Json);
			http_post_json('send_msg','{"group_id": '.$Data['group_id'].',"message":"[CQ:reply,id='.$Data['message_id'].']已关闭！"}');
		}else{
			http_post_json('send_msg','{"group_id": '.$Data['group_id'].',"message":"[CQ:reply,id='.$Data['message_id'].']未开启！"}');
		}
	}
}

//计时任务 每分钟执行一次
if(BOT_Time_Inr('地震通报','1') == true){

//获取数据
	$Json=curl("http://www.ceic.ac.cn/ajax/speedsearch?num=1");
	$Json=str_replace(array('(',')'),'',$Json);
	$Data=json_decode($Json,true);
	
//获取已存旧数据时间
	$Log=file_get_contents('./Data/Plugins/MisaKa_EQ/Log.log');
	
//替换掉YYYY-MM-DD
	$O_TIME=str_replace(date("Y-m-d").' ','',$Data['shuju'][0]['O_TIME']);

//判断是否已更新
	if(!empty($Data['shuju'][0]['LOCATION_C']) and $Log !== $Data['shuju'][0]['O_TIME']){
		$Text='====== 地震速报 ======\n\n地点：'.$Data['shuju'][0]['LOCATION_C'].'\n震级：'.$Data['shuju'][0]['M'].'\n经度：'.$Data['shuju'][0]['EPI_LON'].'\n纬度：'.$Data['shuju'][0]['EPI_LAT'].'\n深度：'.$Data['shuju'][0]['EPI_DEPTH'].'KM\n时间：'.$O_TIME.'\n\n数据来源：中国地震台网';
		//保存Log
		file_put_contents('./Data/Plugins/MisaKa_EQ/Log.log',$Data['shuju'][0]['O_TIME']);
		//推送至QQ群列表
		$Group=file_get_contents('./Data/Plugins/MisaKa_EQ/Group.json');
		$Group=json_decode($Group,true);
		foreach ($Group as $群号){
			if(!empty($群号)){
				http_post_json('send_msg','{"group_id": '.$群号.',"message":"'.$Text.'"}');
			}
		}
	}
}
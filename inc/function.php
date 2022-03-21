<?php
/*
* 机器人函数库
* 已有函数：Curl、随机IP、机器人API接口
* 寒曦朦 2021/02/09 Update
*/

//Curl函数
function curl($url,$guise='',$UA='Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0') {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($curl, CURLOPT_USERAGENT, $UA);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.Rand_IP(), 'CLIENT-IP:'.Rand_IP()));
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($curl, CURLOPT_AUTOREFERER, 0);
	curl_setopt($curl, CURLOPT_HTTPGET, 0);
	if(!empty($guise)){
		curl_setopt($curl, CURLOPT_REFERER, $guise);
	}
	curl_setopt($curl, CURLOPT_TIMEOUT, 0);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 0);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$tmpInfo = curl_exec($curl);
	return $tmpInfo;
}

//随机IP
function Rand_IP(){
	$ip2id = round(rand(600000, 2550000) / 10000);
	$ip3id = round(rand(600000, 2550000) / 10000);
	$ip4id = round(rand(600000, 2550000) / 10000);
	$arr_1 = array('218','218','66','66','218','218','60','60','202','204','66','66','66','59','61','60','222','221','66','59','60','60','66','218','218','62','63','64','66','66','122','211');
	$randarr= mt_rand(0,count($arr_1)-1);
	$ip1id = $arr_1[$randarr];
	return $ip1id.'.'.$ip2id.'.'.$ip3id.'.'.$ip4id;
}

//调用API
function http_post_json($url,$jsonStr){
	//用于统计消息发送的总数
	file_put_contents('./Data/num.txt',file_get_contents('./Data/num.txt')+1);
	//判断延时策略是否开启
	if($GLOBALS['BOT_Sleep'] == 'true'){
		$code=file_get_contents('code.txt');
		$code=$code+3;
		file_put_contents('code.txt',$code);
		if($code<0 or $code>60 or empty($code)){$code=3;}
		sleep($code);//延时避免风控
	}
	//提交至go-cqhttp
	$url = $GLOBALS['BOT_Server'].'/'.$url.'?access_token='.$GLOBALS['BOT_Token'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charset=utf-8'
		)
	);
	$response = curl_exec($ch);
	curl_close($ch);
	//判断延时策略是否开启
	if($GLOBALS['BOT_Sleep'] == 'true'){
		$code=file_get_contents('code.txt');
		$code=$code-3;
		file_put_contents('code.txt',$code);
	}
	return $response;
}

//POST提交
function Curl_Post($remote_server, $post_string) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $remote_server);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'HxmBot beta');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

//用于间隔多久执行一次的函数
function BOT_Time_Inr($Name,$Num){
	//获取时间
	$Time=date('Y-m-d H:i',filemtime('./Data/Cron/Cron_'.$Name.'.log'));
	if(date('Y-m-d H:i') !== $Time){
	$Time=(strtotime(date('Y-m-d H:i'))-strtotime($Time))/60;
		if($Time >= $Num){
			//存入日志
			$Log='任务名：'.$Name.'；执行时间：'.date('Y-m-d H:i')."\n";
			file_put_contents('./Data/Cron/Cron_'.$Name.'.log',$Log);
		//输出结果
			return true;
		}else{
			return false;
		}
	}

}

//用于每天几点执行一次的函数
function BOT_Time_Day($Name,$Num){
	//获取时间
	$Time=date('Y-m-d H:i',filemtime('./Data/Cron/Cron_'.$Name.'.log'));
	if(date('Y-m-d H:i') !== $Time){
		if(is_array($Num)){
			foreach ($Num as $value) {
				if(date('H:i') == $value){
					//存入日志
					$Log='任务名：'.$Name.'；执行时间：'.date('Y-m-d H:i')."\n";
					file_put_contents('./Data/Cron/Cron_'.$Name.'.log',$Log);
					return true;
				}
			}
		}else{
			if(date('H:i') == $Num){
				//存入日志
				$Log='任务名：'.$Name.'；执行时间：'.date('Y-m-d H:i')."\n";
				file_put_contents('./Data/Cron/Cron_'.$Name.'.log',$Log);
			//输出结果
				return true;
			}else{
				return false;
			}
		}
	}
}

//用于某一天的几点执行的函数
function BOT_Time_One($Name,$Num){
	//获取时间
	$Time=date('Y-m-d H:i',filemtime('./Data/Cron/Cron_'.$Name.'.log'));
	if(date('Y-m-d H:i') !== $Time){
		if(date('Y-m-d H:i') == $Num){
			//存入日志
			$Log='任务名：'.$Name.'；执行时间：'.date('Y-m-d H:i')."\n";
			file_put_contents('./Data/Cron/Cron_'.$Name.'.log',$Log);
		//输出结果
			return true;
		}else{
			return false;
		}
	}
}

//用于整点执行的函数
function BOT_Time_Hour($Name,$Num){
	//获取时间
	$Time=date('Y-m-d H:i',filemtime('./Data/Cron/Cron_'.$Name.'.log'));
	if(date('Y-m-d H:i') !== $Time){
		if(date('i') == $Num){
			//存入日志
			$Log='任务名：'.$Name.'；执行时间：'.date('Y-m-d H:i')."\n";
			file_put_contents('./Data/Cron/Cron_'.$Name.'.log',$Log);
		//输出结果
			return true;
		}else{
			return false;
		}
	}
}
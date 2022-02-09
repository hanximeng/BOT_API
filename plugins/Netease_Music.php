<?php
/*
* @Plugin Name：网易云音乐点歌
* @Author：Hanximeng
* @Version：Beta_0.0.2
*/

if(strpos($Data['message'],"点歌 ")!==false) {
	$Info=explode('点歌 ',$Data['message']);
	if($Info[1]!=='' && $Info[0]=='') {
		//以下代码来自 https://github.com/jcdomt/163MusicApi 略有删改
		// 生成随机字符串
		function randomstr($length) {
			$pattern = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ";
			$output = "";
			for ($i=0;$i<$length;$i++) {
				$output .= $pattern[mt_rand(0,35)];
				//生成php随机数
			}
			return $output;
		}
		function NetEaseMusicAES($text,$key) {
			$iv = '0102030405060708';
			return openssl_encrypt($text, 'AES-128-CBC', $key, false, $iv);
		}
		// 下面的代码全部来自于meting
		function bchexdec($hex) {
			$dec = 0;
			$len = strlen($hex);
			for ($i = 1; $i <= $len; $i++) {
				$dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
			}
			return $dec;
		}
		function str2hex($string) {
			$hex = '';
			for ($i = 0; $i < strlen($string); $i++) {
				$ord = ord($string[$i]);
				$hexCode = dechex($ord);
				$hex .= substr('0'.$hexCode, -2);
			}
			return $hex;
		}
		function bcdechex($dec) {
			$hex = '';
			do {
				$last = bcmod($dec, 16);
				$hex = dechex($last).$hex;
				$dec = bcdiv(bcsub($dec, $last), 16);
			}
			while ($dec > 0);
			return $hex;
		}
		function param($n,$default='',$must = false) {
			$p='';
			if(!empty($keyword)) {
				$p = urldecode($keyword);
			}
			if($p=='' && $must) {
				echo '缺少必要参数：'.$n.'<br>';
				exit();
			}
			if($p=='' && !$must) {
				return $default;
			}
			return $p;
		}
		function request($url,$d,$get_header=false) {
			$text = (json_encode($d));
			if($text != '') {
				$modulus = '157794750267131502212476817800345498121872783333389747424011531025366277535262539913701806290766479189477533597854989606803194253978660329941980786072432806427833685472618792592200595694346872951301770580765135349259590167490536138082469680638514416594216629258349130257685001248172188325316586707301643237607';
				$pubkey = '65537';
				// 开始生成encSecKey
				$key2 = randomstr(16);
				$skey = strrev(utf8_encode($key2));
				$skey = bchexdec(str2hex($skey));
				$skey = bcpowmod($skey, $pubkey, $modulus);
				$skey = bcdechex($skey);
				$skey = str_pad($skey, 256, '0', STR_PAD_LEFT);
				$key = '0CoJUm6Qyw8W8jud';
				$enStr = NetEaseMusicAES($text,$key);
				$params = NetEaseMusicAES($enStr,$key2);
			}
			$data['params'] = $params;
			$data['encSecKey'] = $skey;
			//print_r($data);
			return http_post($url,$data,$get_header);
		}
		$limit = param('limit',30);
		$type = param('type',1) ;
		$offset = param('offset',0);
		$arr = array(
			's' => urldecode($Info[1]),
			'type'  => $type,
			'limit' => $limit,
			'offset'  => $offset,
		);
		/*
		* 请求头设置
		*/
		function set_header() {
			return array(
							'Host: music.163.com',
							'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
							'Origin: https://music.163.com',
							'Referer: https://music.163.com',
							'Content-Type: application/x-www-form-urlencoded',
						);
		}
		function http_post($url, $post_data,$get_header=false) {
			$postdata = http_build_query($post_data);
			$options = array(
							'http' => array(
								'method'		=> 'POST',
								'header'		=> set_header(),
								'content'	   => $postdata,
							)
						);
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			return $result;
		}
		$Json=request('https://interface.music.163.com/weapi/search/get',$arr);
		$SongID=json_decode($Json,true)['result']['songs']['0']['id'];
		$Text='[CQ:music,type=163,id='.$SongID.']';

		//判断是否存在group_id，若不存在则为私聊
		if(!empty($Data['group_id'])) {
			http_post_json('send_msg','{"group_id":"'.$Data['group_id'].'","message":"'.$Text.'"}');
		} elseif(!empty($Data['guild_id'])) {
			http_post_json('send_guild_channel_msg','{"guild_id":"'.$Data['guild_id'].'","channel_id":"'.$Data['channel_id'].'","message":"'.$Text.'"}');
		} else {
			http_post_json('send_msg','{"user_id":"'.$Data['user_id'].'","message":"'.$Text.'"}');
		}
	}
}
?>
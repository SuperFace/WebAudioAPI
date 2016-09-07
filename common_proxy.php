<?php
/*
 * $url=$_GET['url'].'?'.'&ltlat='.$_GET['ltlat'].'&ltlng='.$_GET['ltlng'].'&rblat='.$_GET['rblat'].'&rblng='.$_GET['rblng'];
 * echo $info;exit;
 *
 */
	if (! function_exists ( 'callHttpGET' )){
		function callHttpGET($url, $params = null){
			$get_url = '';
			foreach ( $params as $key => $value ) {
				if (isset ( $value )) {
					$get_url .= $key . '=' . $value . '&';
				}
			}
			$get_url = rtrim ( $get_url, '&' );
			$url = $url . '?' . $get_url;
			return $url;
		}
	}

	$url = base64_decode ( $_GET ['url'] );
	$data ['authkey'] = isset ( $_GET ['authkey'] ) ? $_GET ['authkey'] : null;
	$data ['bid'] = isset ( $_GET ['bid'] ) ? $_GET ['bid'] : null;
	$data ['subbid'] = isset ( $_GET ['subbid'] ) ? $_GET ['subbid'] : null;
	$data ['fileid'] = isset ( $_GET ['fileid'] ) ? $_GET ['fileid'] : null;
	$data ['filetype'] = isset ( $_GET ['filetype'] ) ? $_GET ['filetype'] : null;
	$data ['openid'] = isset ( $_GET ['openid'] ) ? $_GET ['openid'] : null;
	$data ['ver'] = isset ( $_GET ['ver'] ) ? $_GET ['ver'] : null;
	
	if (! function_exists ( 'getFile' )){
		function getFile($url,$save_dir='',$filename='',$type=0){
			//创建保存目录
			if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
				return false;
			}
			//获取远程文件所采用的方法
			if($type){
				$ch=curl_init();
				$timeout=5;
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
				$content=curl_exec($ch);
				curl_close($ch);
			}else{
				ob_start();
				readfile($url);
				$content=ob_get_contents();
				ob_end_clean();
			} 
			$size=strlen($content);
			//文件大小
			$fp2=@fopen($save_dir.$filename,'a');
			fwrite($fp2,$content);
			fclose($fp2);
			unset($content,$url);
			return array('file_name'=>$filename,'save_path'=>$save_dir.$filename);
		}
	}
	
	$uri = callHttpGET ( $url, $data );
	
	$save_dir = "./mp3/";
	$filename = substr($data ['fileid'], 0, 20).".mp3";
	
	$_array = getFile($uri,$save_dir,$filename,1);
	echo $_array['save_path'];exit;
?>
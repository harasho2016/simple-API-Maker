<?php
	/* Call 404
	=================== */
	function call404() {
		$data["status"] 	= 'ERROR';
		$data["count"] 		= 0;
		$data["message"] 	= 'Function Name Not Found(404)';
		apiResultShowJson($data);
		exit();
	}

	// Timeout Function
	function timeOutLimiter($sstart,$slimit){
		$send = microtime(true);
		$semet = $send - $sstart;
		if($semet >= $slimit){
			return 'stop';
		}else{
			return 'foword';
		}
	}

	// API RESULT SHOW JSON
	function apiResultShowJson($data){
		if(gettype($data) == 'array'){
			$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		$data = mb_convert_encoding($data, 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
		if (preg_match('/^[\x0x\xef][\x0x\xbb][\x0x\xbf]/', $data)) $data = substr($data, 3);
		header("Content-Type: application/json; charset=utf-8");
		echo $data;

		exit();
	}
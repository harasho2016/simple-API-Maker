<?php
	/* システム変数
	============================================ */
		// 全体システム変数
		$startSessionTime	= microtime(true);
		$server_timeout		= 5;

		$rootUrl 		= "http://".urldecode($_SERVER['HTTP_HOST'])."/06_api_bootstrap/website/";
	    $nowUrl 		= "http://".urldecode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	    $requestURI 	= str_replace($rootUrl, '', $nowUrl);

		// DB情報
		$dbIp 	= '127.0.0.1';
		// $dbName = 'dbName';
		$dbUser = 'user';
		$dbPass = 'pass';

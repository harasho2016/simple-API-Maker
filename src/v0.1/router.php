<?php
	/* URL Interpretation
	============================================ */
	// 分解して変数に格納
	$reqUrl = explode('/', $requestURI);
	for($i=0; $i<count($reqUrl); $i++){
		$reqUrlSection = $reqUrl[$i];
		if(($i+1) == count($reqUrl)){
			if(preg_match('/(.*)\?/', $reqUrlSection, $m)){
				$reqUrlSection = $m[1];
			}
		}else{
			if($reqUrlSection == '') call404();	
		}
		$urlParam[] = $reqUrlSection;
	}


	/* URL Route
	** 	$urlParam[0] // version
	** 	$urlParam[1] // DB Name(if you set dbName on ver.php, then you have not to specify $urlParam[1])
		e.g. http://localhost/06_api_bootstrap/website/v0.1/api/game_master
	** 	$urlParam[2] // Table Name
	============================================ */
	$version 		= $urlParam[0]; // version
	if(!isset($dbName)) $dbName = $urlParam[1]; // DB Name
	$tbName 		= $urlParam[2]; // Table Name


	/* Link DB
	============================================ */
	$dsn 	= "mysql:dbname=$dbName;host=$dbIp";
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET utf8");
	try{
	    $dbc = new PDO($dsn, $dbUser, $dbPass, $options);
	    $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch (PDOException $e){
		if($debugMode == true){
		    // print('Error:'.$e->getMessage());
		    // die();
			$data["status"] 	= 'ERROR';
			$data["count"] 		= 0;
			$data["message"] 	= $e->getMessage();
			apiResultShowJson($data);
			exit();

		}else{
		    call404();
		}
	}


	/* Query Method
	** 	$methodName 	=$_GET['qm_methodName'] // Method Name(SELECT, DELETE, UPDATE, INSERT)
	** 	$numCountBool 	= $_GET['qm_numCountBool'] // Method Name(SELECT Method only)
	** 	$orderBy 		= $_GET['qm_orderBy'] // Response Order(SELECT Nethod only)
	** 	$orderByTarget 	= $_GET['qm_orderByTarget'] // Response Order Target(SELECT Method and (orderBy DESC or orderBy ASC) only)
	** 	$limit 			= $_GET['qm_limit'] // Response Limit(Select Method Only)
	============================================ */
	$methodName 	= $_GET['qm_methodName'];
	$numCountBool 	= $_GET['qm_numCountBool'];
	$orderBy 		= $_GET['qm_orderBy'];
	$orderByTarget 	= $_GET['qm_orderByTarget'];
	$limit 			= $_GET['qm_limit'];

	if(!isset($methodName)) $methodName = 'SELECT';
	if(!isset($numCountBool)) $numCountBool = false;
	if(!isset($limit)) $limit = 50;

	$sql = "SHOW columns FROM $tbName";
    foreach ($dbc->query($sql) as $row) {
    	$tbField = $row["Field"];
    	$validCol[] = $tbField;
    	if(in_array($tbField, array_keys($_GET), false)){
			$colTarget[] = $tbField;
    	}

		// use Update Method
    	if(in_array('set_'.$tbField, array_keys($_GET), false)){
			$setTargetKey[] = $tbField; 
			$setTargetValue[] = 'set_'.$tbField;  
    	}
	}

	/* Call Script for Each Method 
	=============================== */
	include "./src/$version/method/$methodName.php";


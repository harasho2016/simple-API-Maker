<?php
	// 共通処理
	ini_set('default_charset', 'UTF-8');
	mb_http_output('UTF-8');
	mb_internal_encoding('UTF-8');
	error_reporting(E_ALL ^ E_NOTICE);

	// ラボと商用の判定
	$ini_array = parse_ini_file("./sys_preference.ini");

	$debugMode = false;
	if($ini_array["debugMode"] == 'labo') $debugMode = true;
	if($ini_array["debugMode"] == 'staging') $debugMode = false;
	if($ini_array["debugMode"] == 'production') $debugMode = false;


	// 変数記述ファイルとclassファイルを読み込む
	include "./sys/var_".$ini_array["debugMode"].".php";
	include "./sys/class/router.php";


	/* URL解釈を開始する
	** 1. URL分解($urlParam)
	** 3. 変換テーブルを読み込み、固定値マスタを作成(viewMapper)
	** 3. 2で作成した固定値マスタ基に、URLに含まれる固定値と変数の解釈
	** 4. 解釈値を基に変換テーブルを参照。該当のviewPathを読み込む
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
	if(count($urlParam) != 3) call404();

	// 現在のバージョンに対応したスクリプトを実行する
	if(file_exists("./src/$urlParam[0]")){
		include "./src/$urlParam[0]/router.php";
	}else{
		call404();
	}
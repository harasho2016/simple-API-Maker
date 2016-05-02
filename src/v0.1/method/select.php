<?php
	/* Query Method
	** 	$targetSQL 	= ''; 	// Target SQL
	** 	$selWhere 	= ''; 	// Where Option String
	** 	$selOrderBy = '';	// OrderBy Option String
	** 	$selLimit 	= ''; 	// Limit Option Striong
	============================================ */

	// Make "Where" Selecter
	$selWhere = '';
	if(count($colTarget) == 1){
		$selWhere = " WHERE $colTarget[0] = '".$_GET[$colTarget[0]]."' ";
	}else if(count($colTarget) >= 2){
		$selWhere = " WHERE $colTarget[0] = '".$_GET[$colTarget[0]]."' ";
		for($i=1; $i<count($colTarget); $i++){
			$selWhere = $selWhere." AND $colTarget[$i] = '".$_GET[$colTarget[$i]]."' ";
		}
	}

	// Evaluate Query Method and Make "Count" Selecter
	if($numCountBool == true){
		$targetSQL = "SELECT count(*) from $tbName";
	}else if($numCountBool == false){
		$targetSQL = "SELECT * from $tbName";
	}

	// Evaluate Query Method and Make "Order" Selecter
	if(isset($orderBy)){
		if($orderBy == 'DESC' || $orderBy == 'ASC'){
			if(isset($orderByTarget) && in_array($orderByTarget, array_keys($_GET), false)){
				$selOrderBy = " ORDER BY $orderByTarget $orderBy";
			}
		}else if($orderBy == 'RAND'){
			// 200/100000 10万レコードに対して200件取得する場合
			// $selWhere = " AND rand() < 0.0002 ";
			$selOrderBy = " ORDER BY RAND() "; 	// 初期リリース時は負荷がかからないと予測し、これで進める
		}
	}

	// Evaluate Query Method and Make "Limit" Selecter
	if(isset($limit)) $selLimit = " limit $limit ";

	// Evaluate Query Method and Make "SQL" and Execute
	$i = 0;
	$targetSQL = $targetSQL.$selWhere.$selOrderBy.$selLimit;
	try{
	    foreach ($dbc->query($targetSQL) as $row) {
		    for($j=0; $j<count($validCol); $j++){
		    	$data["data"][$i][$validCol[$j]] = $row[$validCol[$j]];
		    }
			$i = $i + 1;
	    }
	}catch (PDOException $e){
		$data["status"] 	= 'ERROR';
		$data["count"] 		= 0;
		$data["message"] 	= $e;
	}

    /* Status Manager
    ================================ */
	$data["status"] = 'SUCCESS';
	$data["count"] = count($data["data"]);


	// outputJson
	apiResultShowJson($data);

# Simple API Makerの概要
MySQL, postgreSQL, SQLiteを5分でAPI化し、API経由でアクセスできるようにするモジュールです。  
API経由で、Select, INSERT, UPDATE, DELETEの操作ができ、Limit, ORDER BYに対応しています。  
API Bootstrap. You can make API easily by using MySQL/postgreSQL/SQLite. You can set up within 5 minutes.  


## A). 目的  
#### DBアクセスの負荷軽減/高速化  
- 想定①　アプリケーションレベルでゴリゴリ実装  
- 想定②　Squid、リバースプロキシと併用  

#### 開発速度の向上  
- SPA開発における高速開発化(javascriptからまるでDBを直接操作できるかのように)  

#### 車輪の再開発(再発明)を防ぐ  
- データのCRUDを行うだけの単純なAPIなら、これ一つで十分としたい

## B). 設定方法  
#### 0. 前提条件: 
	a. MySQL/PostgreSQL/SQLiteが入っていること
	b. DB,TABLEが存在していること

#### 1. ドキュメントルートにFTPで置く
	e.g /var/www/html
  
#### 2.　設定ファイルにMySQL接続先を記述
	設定ファイル置き場所: ./sys/var_{pf_type}.php
	※ pf_typeは./sys_preference.iniに記述
	設定箇所: IP, UserId, Pass
	
#### 3.　下記記載の「使い方」を見て呼び出す



## C). 使い方  
#### 呼び出し方  
	/{VersionInfo}/{DBName}/{TableName}?{columnName}=val&set_{columnName}=val&{FunctionName}=val  

#### Version Info  
	- 定義されていればどんな値でも可能  
	- e.g. v0.1~v999.99  

#### DBName  
	- 設定ファイル(var.php)に記述している値を優先  
		- その場合、URLにはどんな値を埋め込んでも良い  
	- 記述がない場合は、URLに埋め込まれている値を参照する  

#### TableName  
	- DBに存在するテーブル名  

#### QueryMethod(All)  
	- MethodName = Select, Insert, Update, Delete  
		-　メソッド名  
	- {columnName}  
		if SELECT, UPDATE, DELETE  
			- "Where" Selecter  
		else if insert  
			- "Insert" Target  

#### QueryMethod(only UPDATE Method)  
	- set_{columnName}  
		- "UPDATE(SET)" Target  

#### QueryMethod(only SELECT Method)  
	- numCountBool=true, false(default)  
		- trueの場合Select count(*) from {tableName}  
		- defaultはfalse  
	- orderBy=RAND, DESC, ASC  
		- ソート順  
		- DESCとASCを指定した場合はorderByTarget={columnName}を指定する必要アリ  
		- defaultは無指定  
	- limit=1  
		- 返却数  
		- 無指定の場合は50が  


## D). 値例  
#### SELECT  
	http://127.0.0.1/{VersionInfo}/{DBName}/{TableName}  
	=> SELECT * FROM DBName.TableName LIMIT 50  
	http://127.0.0.1/{VersionInfo}/{DBName}/{TableName}?{colName1}=val1&{colName2}=val2&OrderBy=DESC&OrderByTarget={colName1}&MethodName=SELECT&qm_limit=15  
	=> SELECT * FROM DBName.TableName WHERE {colName1}=val1 AND {colName2}=val2 Order by {colName1} DESC LIMIT 15  

#### INSERT  
	http://127.0.0.1/{VersionInfo}/{DBName}/{TableName}?qm_methodName=INSERT&{colName1}=val1&{colName2}=val2  
	=> INSERT DBName.TableName (colName1, colName2) VALUE('val1', 'val2');  

#### UPDATE  
	http://127.0.0.1/{VersionInfo}/{DBName}/{TableName}?{colName1}=val1&{colName2}=val2&methodName=UPDATE&set_{colName1}=val3  
	=> UPDATE DBName.TableName SET  {colName1}=val3  WHERE {colName1}=val1 AND {colName2}=val2 LIMIT 10  

#### DELETE  
	http://127.0.0.1/{VersionInfo}/{DBName}/{TableName}?{colName1}=val1&MethodName=DELETE  
	=> DELETE FROM DBName.TableName WHERE {colName1}=val1;  

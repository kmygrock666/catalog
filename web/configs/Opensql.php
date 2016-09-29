<?php
class Opensql{
    public static $connect = NULL;
    function __construct(){
        $dbhost = "mysql:host=localhost;dbname=game";
        $dbuser = 'root';
    	$dbpass = '';
    	//資料庫連線
    	self::$connect = new PDO($dbhost,$dbuser,$dbpass);
    	self::$connect->exec("set names utf8");
		
    }
    //取得
    function getConnection(){
        return self::$connect;
    }
    //關閉
    function closConnection(){
        return self::$connect = NULL;
    }
}


?>
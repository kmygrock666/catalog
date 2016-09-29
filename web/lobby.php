<?php
session_start();
include_once "configs/Opensql.php";
require_once "configs/config.php";
date_default_timezone_set("Asia/Shanghai");
header("content-type:text/html; charset=utf-8");
	$pdo = new Opensql();
	$db = $pdo->getConnection();
	//判別是否跳過登入從網址進入遊戲頁
	if (empty($_SESSION['user']) && empty($_COOKIE['user']) ) {
		echo  "<script>alert('請先登入')\nwindow.location.href='login.php'</script>";
	}
	$smarty->display('Game.html');

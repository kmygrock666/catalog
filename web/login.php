<?php
session_start();
header("content-type:text/html; charset=utf-8");
include_once "configs/Opensql.php";
require_once "configs/config.php";
	if (isset($_SESSION['user'])){
		header("location:game.php");
	}
	$smarty->display('Gamelogin.html');
	$pdo = new Opensql();
	$db = $pdo->getConnection();
	if (isset($_POST['user'])) {
		$user = $_POST['user'];
		$psw = $_POST['pass'];
		try{
			//檢查是否已登出
			$checklogout =$db->prepare("SELECT * FROM  member  WHERE  username  =  :user AND status = 'logout'");
			$checklogout->bindParam("user",$user);
			$checklogout ->execute();
			if (!$row = $checklogout->fetch() && $_SESSION['user'] = "") {
				$dologout = $db->prepare("UPDATE member SET status = 'logout' WHERE username = :user");
				$dologout->bindParam("user",$user);
				$dologout->execute();
			}
		
			//檢查帳號
			$sth =$db->prepare("SELECT * FROM  member  WHERE  username  =  :user ");
			$sth->bindParam("user",$user);
			$sth ->execute();
			if (!$row = $sth->fetch()) {
				throw new Exception("查無此帳號");
			}
			//檢查是否已登入
			$check = $db->prepare("SELECT * FROM member WHERE status = 'login' AND username = :user AND password = :psw");
			$check->bindParam("user",$user);
			$check->bindParam("psw",$psw);
			$check ->execute();
			$row = $check->fetch();
			if ($row) {
				throw new Exception("帳號已登入");
			}
			
			//更新已登入狀態
			$update = $db->prepare("UPDATE member SET status = 'login' WHERE username = :user");
			$update->bindParam("user",$user);
			$update ->execute();
			
			$_SESSION['user'] = $user;
			setcookie("user",$user);
			echo  "<script>alert('登入成功')\nwindow.location.href='game.php'</script>";
		} catch(Exception $e) {
				echo "<script>alert('".$e->getMessage()."')\nwindow.location.href='login.php'</script>";
			}
	}	
	//登出
	if (isset($_GET['logout'] )) {
		$outValue = $_GET['logout'];
		$user = $_COOKIE['user'];
		$logout = $db->prepare("UPDATE member SET status = 'logout' WHERE username = :user");
		$logout->bindParam("user",$user);
		$logout->execute();
		unset($_SESSION['user']);
		echo "<script>alert('登出成功')\nwindow.location.href='login.php'</script>";
	}
	
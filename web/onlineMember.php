<?php
session_start();
include_once "configs/Opensql.php";
$pdo = new Opensql();
$db = $pdo->getConnection();
$user = $_SESSION['user'];
	if (isset($_POST['rq'])){
			getOnlineMember($db, $user);
	}
	
	if (isset($_POST['battle'])){
			checkBattle($db, $user);
	}
	
	function getOnlineMember($db, $user) {
		try
		{
				$search = $db->prepare("SELECT id,username FROM member WHERE status = 'login' AND username != :user");
				$search->bindParam("user",$user);
				$search->execute();
				$result = $search->fetchAll();
				
				if (!$result) 
				{
					throw new Exception("搜尋失敗");
				}
			echo json_encode($result);
		} catch(Exception $e) {
			echo "<script>alert('".$e->getMessage()."')</script>";
		}
	}
	function checkBattle($db, $user) {
		try{
			$checkBattle = $db->prepare("SELECT * FROM battle WHERE recipient = :user AND respond = 'wait'");
			$checkBattle->bindParam("user",$user);
			$checkBattle->execute();
			$result = $checkBattle->fetch();
			$_SESSION['cRoom'] = $result['roomId'];
			setcookie("cRoom",$result['roomId']);
			echo json_encode($result);
		} catch(Exception $e) {
			echo "failure";
		}
	}
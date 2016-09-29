<?php
session_start();
include_once "configs/Opensql.php";
date_default_timezone_set("Asia/Shanghai");
header("content-type:text/html; charset=utf-8");
$pdo = new Opensql();
$db = $pdo->getConnection();
$user = $_SESSION['user'];
$roomId = $_SESSION['cRoom'] ;
	
$_POST['check'] = 'ADFSF';
	//Client 1 發送對戰
	if (isset($_POST['online'])) {
	
		$_SESSION['TEST'] = "AAA";
		
		
		echo "online";
		//$player = $_POST['online'];
		//$_SESSION['opponent'] = $player;
		//battleplayform($player, $db, $user);
		//$roomId = $_SESSION['cRoom'] ;
		//echo $roomId;

	} 
	//檢查對手是否已回復邀請對戰
	if (isset($_POST['check'])){
		//check($db, $user, $roomId, $respond = 2) ;
		//echo ">>>>".$roomId."<<<<";
		echo "check";
	}
	
	echo "TEST:".$_SESSION['TEST'] ;
	exit();
	
	
	//Client 2 確認請求
	if (isset($_GET['confirm'])) {
		$respond = $_GET['confirm'];
		refresh($db, $user, $respond );
		check($db, $user, $roomId, $respond) ;
		
	}
	//新增對戰請求紀錄
	function battleplayform($player, $db, $user) {
		try{
			$playform = $db->prepare("INSERT INTO battle (sponsor, recipient, respond) VALUE (:user, :player, 'wait')");
			$playform->bindParam('user', $user);
			$playform->bindParam('player', $player);
			$result = $playform->execute();
			$lastId = $db->lastInsertId();
			
			if (!$result) {
				throw new Exception("請求對戰失敗");
			}
			
			$_SESSION['cRoom'] = $lastId;
			$_SESSION['sponsor'] = $user;
			setcookie("cRoom",$lastId);
		} catch(Exception $e) {
			echo "<script>alert('".$e->getMessage()."')\nwindow.location.href='lobby.php'</script>"; 
		}
		
	}
	//更新對戰請求紀錄
	function refresh($db, $user, $respond) {
		$date = date("Y-m-d H:i:s" );
		if ($respond == 1) {
			$update = $db->prepare("UPDATE battle SET respond = 'accept', updateTime = :date WHERE recipient = :user");
			$update->bindParam("user",$user);
			$update->bindParam("date",$date);
			$update->execute();		
		} else {
			$update = $db->prepare("UPDATE battle SET respond = 'refuse', updateTime = :date WHERE recipient = :user");
			$update->bindParam("user",$user);
			$update->bindParam("date",$date);
			$update->execute();
		}
		
	}
	//檢查對手是否已回復邀請對戰
	function check($db, $user, $roomId, $respond) {
		$check = $db->prepare("SELECT * FROM battle WHERE roomId = :roomId ");
		$check->bindParam("roomId", $roomId);
		$check->execute();
		$result = $check->fetch();
		$respondTime = strtotime($result['updateTime'])-strtotime($result['insertTime']);

		if ($respond == 1) {
			if ($respondTime < 10) {
				header("location:playgame.php");
			}
			echo "<script>alert('回應遇時')\nwindow.location.href='lobby.php'</script>";
		} else if ($respond == 2) {
			if ($respondTime < 10  && $result['respond'] == 'accept') {
				echo "<script>alert('邀請成功')\nwindow.location.href='playgame.php?'</script>";
			} elseif ($result['respond'] == 'refuse') {
				echo "<script>alert('邀請被拒絕')\nwindow.location.href='lobby.php'</script>";
			}else {
				echo "<script>alert('無回應')\nwindow.location.href='lobby.php'</script>";
			}
		} else {
			header("location:lobby.php");
		}
	}
	?>
	
<?php
session_start();
include_once "configs/Opensql.php";
$pdo = new Opensql();
$db = $pdo->getConnection();
$user = $_SESSION['user'];
$roomId = $_COOKIE['cRoom'] ;
$opponent = $_SESSION['opponent'];
$sponsor = $_SESSION['sponsor'];
// echo $sponsor;
// echo $opponent;
// echo $roomId;
if (isset($_POST['rq'])) {
	checkRecord($db, $roomId, $user);
}
//選擇符號
if(isset($_GET['sign'])) {
	$sign = $_GET['sign'];
	selectedSign($db, $sign,$roomId, $user, $sponsor, $opponent);
}


if (isset($_POST['position'])) {
	$position = $_POST['position'];
	$sign = $_POST['sign'];
	action($db, $roomId, $position, $sign, $user);
}
//選擇符號
function selectedSign($db, $sign, $roomId, $user, $sponsor, $opponent) {
	$check = $db->prepare("SELECT * FROM battle WHERE roomId = '$roomId' AND symbol != ''");
	$check->execute();
	$checkResult = $check->fetch();
	
	if ($checkResult) {
		if ($checkResult['sponsor'] == $user) {
			echo $checkResult['symbol'];
			return;
		} else {
			echo $checkResult['symbolTwo'];
			return;
		}
	}

		if ($sponsor == $user) {
			if ($sign == 'x') {
				$updatesign = $db->query("UPDATE battle SET symbol = '$sign', symbolTwo = 'o' WHERE roomId = $roomId ");
			} else {
				$updatesign = $db->query("UPDATE battle SET symbol = '$sign', symbolTwo = 'x' WHERE roomId = $roomId ");
			}
			echo "successx";
		} else if ($opponent == $user){
			if($sign == 'x') {
				$updatesign = $db->query("UPDATE battle SET symbol = 'o', symbolTwo = '$sign' WHERE roomId = $roomId ");
			} else {
				$updatesign = $db->query("UPDATE battle SET symbol = 'x', symbolTwo = '$sign' WHERE roomId = $roomId ");
			}
			
			echo "successo";
		}
}

function checkRecord($db, $roomId, $user) {
	$check = $db->prepare("SELECT record,status from battle WHERE roomId = $roomId ");
	$check->execute();
	$result = $check->fetch();
	$temp = explode(",",$result['record']);
	$temp[] = array('status'=>$result['status'],'user'=>$user);
	echo json_encode($temp);
}

function action($db, $roomId, $position, $sign, $user) {
	$search = $db->prepare("SELECT record,status FROM battle WHERE roomId = $roomId");
	$search->execute();
	$searchResult = $search->fetch();
	$temp = explode(",",$searchResult['record']);
	$temp[$position-1] = $sign;
	$tempResult = "";
	foreach($temp as $a) {
		$tempResult .= $a . "," ; 
	}
	
	$tempResult = substr($tempResult,0,strlen($tempResult)-1);
	
	$update = $db->prepare("UPDATE battle SET record = '$tempResult', status = '$user' WHERE roomId = $roomId");
	$result = $update->execute();
	echo json_encode($result);
	
}
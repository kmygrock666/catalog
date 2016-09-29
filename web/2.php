<?php
	session_start();
	include_once "configs/Opensql.php";
	require_once "configs/config.php";

	$arr = array();
	$arr['ErrorCode'] = 0;
	$arr['ErrorMsg'] = '';
	$arr['Data'] = array();
	
	$_POST['CMD'] = 'CreateGame';
	$_POST['USER'] = '123';
	$_POST['player'] = 'aa123ddd';
	
	$cmd = $_POST['CMD'];
	switch($cmd){
			case 'CreateGame':
				$user = $_POST['USER'];
				$player = $_POST['player'];
				try{
				
					$pdo = new Opensql();
					$db = $pdo->getConnection();
					//$sql = "SELECT ID FROM member where username in (  '".$user  ."' ,'".$user  ."'  )";

					$playform = $db->prepare("INSERT INTO battle (sponsor, recipient, respond) VALUE (:user, :player, 'wait')");
					$playform->bindParam('user', $user);
					$playform->bindParam('player', $player);
					$result = $playform->execute();
					$lastId = $db->lastInsertId();
					
					if (!$result) {
						//throw new Exception("請求對戰失敗");
						$arr['ErrorCode'] = 1;
						$arr['ErrorMsg'] = '請求對戰失敗';
					}
					$_SESSION['cRoom'] = $lastId;
					$_SESSION['sponsor'] = $user;
					setcookie("cRoom",$lastId);
					$output = array();
					$output[] = 'ABC';
					$output['roomid'] = $lastId;
					$arr['Data'] = $output;
					echo json_encode($arr);
				} catch(Exception $e) {
						$arr['ErrorCode'] = 2;
						$arr['ErrorMsg'] = $e;
				}
				break;
	
	}

?>
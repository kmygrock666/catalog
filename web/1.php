<?php
	session_start();
	if (isset($_POST['online'])) {
		$_SESSION['ms'] = $_POST['online'];
		//header("Location:2.php" );
		
		sleep(10);
		echo  "<script>alert('½Ð¥ýµn¤J') \n window.location.href='2.php'</script>";
	}
	
	echo $_SESSION['ms'] ++."<br/>";

?>


<form method="post" action="1.php">
	<div style="width:110px;height:100px; margin:0 auto; text-align:center">
		<select style="width:100px" name="online" size="5" id = "select">
			<option value="1" selected> 1</option>
		</select>
	</div>
	<button id="ok">VS</button>
</form>
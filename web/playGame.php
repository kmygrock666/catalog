<?php
session_start();
include_once "configs/Opensql.php";
require_once "configs/config.php";

echo ">".$_SESSION['cRoom'] .'<br/>';
$smarty->display('gameStart.html');

?>

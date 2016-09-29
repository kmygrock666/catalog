<?php
/* 定義伺服器的絕對路徑 */
define('BASE_PATH',$_SERVER['DOCUMENT_ROOT']);
/* 定義Smarty目錄的絕地你路徑 */
define('SMARTY_PATH','\Smarty\\');
/* 載入Smarty類庫檔 */
require BASE_PATH.SMARTY_PATH.'Smarty.class.php';
/* 產生實體一個Smarty物件 */
$smarty = new Smarty();
/* 定義各個目錄的路徑 */
$smarty->template_dir = BASE_PATH . '/templates/';
$smarty->compile_dir = BASE_PATH . '/templates_c/';
$smarty->config_dir = BASE_PATH . '/configs/';
$smarty->cache_dir = BASE_PATH . '/cache/';
/* 調試主控台 */
//$smarty->debugging = true;
/* Smarty緩存 */
//$Smarty->caching = true;
/* 定義定界符 */
$smarty->left_delimiter = '<{';
$smarty->right_delimiter = '}>';
?>

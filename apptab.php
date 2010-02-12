<?php
// Includes
require_once('includes/config.php');

$smarty = new Smarty();
$smarty->assign("host_url",$host_url);
$smarty->assign("app_name",$app_name);

$smarty->display("apptab.tpl");
?>
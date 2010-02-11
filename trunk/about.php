<?php
    require_once('includes/config.php');

    // Create Smarty object
    $smarty = new Smarty();
    $smarty->assign('version', $version);
    $smarty->assign('host_url',$host_url);
    $smarty->display('about.tpl');
?>


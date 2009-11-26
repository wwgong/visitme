<?php
    require_once('includes/config.php');

    // Create Smarty object
    $smarty = new Smarty();
    $smarty->assign('version', $version);
    $smarty->display('about.tpl');
?>


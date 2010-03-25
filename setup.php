<?php
    require_once('includes/config.php');
    require_once('includes/dbsetup.php');

    $prompt_input = true;
    // Create Smarty object
    $smarty = new Smarty();
    $smarty->assign('host_url', $host_url);
    
    $user_name = $_POST['user'];
    $password = $_POST['pwd'];

    if(($user_name == $db_user) && ($password == $db_pass))
    {
        $prompt_input = false;
        echo "<br /><br />Populating ".$db_db." database... ";
        $dbsetup_obj = new DB_SETUP($db_db, $db_user, $db_pass);
        echo "<strong>Success!</strong><br />";
    }

    $smarty->assign('prompt_input', $prompt_input);
    $smarty->display('setup.tpl');
?>

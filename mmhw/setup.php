<?php
/*
Copyright 2010 GoPandas
This file is part of Meet Me Half Way ( an extension of VisitME ).

VisitME is free software: you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or (at your
option) any later version.

VisitME is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
for more details.

You should have received a copy of the GNU General Public License
along with VisitME. If not, see http://www.gnu.org/licenses/.
*/
    require_once('includes/config.php');
    require_once('includes/dbsetup.php');

    $verify_credential = true;
    // Create Smarty object
    $smarty = new Smarty();
    $smarty->assign('host_url', $host_url);
    
    $user_name = $_POST['user'];
    $password = $_POST['pwd'];

    if(($user_name == $db_user) && ($password == $db_pass))
    {
        $verify_credential = false;
        echo "<br /><br /><strong>Populating ".$db_db." database... </strong><br /><br />";
        $dbsetup_obj = new DB_SETUP();
        echo "<strong>Success!</strong><br />";
    }

    $smarty->assign('verify_credential', $verify_credential);
    $smarty->display('setup.tpl');
?>

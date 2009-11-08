<?php

//Database
define("DATABASE", "mysql"); // or "odbc"

$db_host = "site.www.umb.edu";
$db_db = "cnhsdb";
$db_user = "cnhsweb";
$db_pass = "MekCej.3";
/* 
db_host = "site.www.umb.edu";
$db_db = "batec";
$db_user = "batec";
$db_pass = "batec0104"; 
*/

//To also use the LDAP database set "$LDAP = true" before including sqlfunctions.inc
//$db_host2 = "www1.www.umb.edu";
//$db_db2 = "ldap";
//$db_user2 = "ttt";
//$db_pass2 = "t7t7s8";
//$db_con2 ="";

//Sessions
$app_session = "sdb_user";

?>
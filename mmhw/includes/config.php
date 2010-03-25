<?php

require_once('magpierss/rss_fetch.inc');
require_once('smarty/libs/Smarty.class.php');

// Database stuff
define("DATABASE", "mysql"); // or "odbc"

$db_host	= '';
$db_db		= '';
$db_user	= '';
$db_pass	= '';

$host_url	= '';
$version	= '1.0';

// Settings
$debug	= false;
$radius	= 1.0;

?>
<?php

require_once('magpierss/rss_fetch.inc');
require_once('facebook/facebook.php');
require_once('smarty/libs/Smarty.class.php');

$api_key = 'your_api_key';
$secret = 'your_secret';

// Database stuff
define("DATABASE", "mysql"); // or "odbc"

$db_host	= '';
$db_db		= '';
$db_user	= '';
$db_pass	= '';

$host_url	= '';
$app_name	= 'visitme';
$version	= '0.2';

// Settings
$debug	= false;
$radius	= 1.0;

?>
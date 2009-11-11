<?php

require_once('magpierss/rss_fetch.inc');
require_once('facebook/facebook.php');
require_once('smarty/libs/Smarty.class.php');

$api_key = '1a0033d6420f2c33ae75ad020fbe3f97';
$secret = 'f63c658e5d3dc097fc7a87e622652004';

// Database stuff
define("DATABASE", "mysql"); // or "odbc"

$db_host	= 'ebolker.fatcowmysql.com';
$db_db		= 'visitme';
$db_user	= 'gopandas';
$db_pass	= 'UMBpandas';

// Settings
$debug = false;

?>
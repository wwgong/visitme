<?php
/*
Copyright 2010 GoPandas
This file is part of VisitME.

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
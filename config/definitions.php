<?php
// Define database credentials as constants
$db_host = $db_name = $db_charset = $db_user = $db_pass = null;
$parse_ini_array = parse_ini_file("db_credentials.ini");
extract($parse_ini_array);
$db_pass = ($db_pass == 'empty') ? '' : $db_pass;
define('DB_HOST' , $db_host);
define('DB_NAME' , $db_name);
define('DB_USER' , $db_user);
define('DB_PASS' , $db_pass);
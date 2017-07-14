<?php
ini_set("display_errors", 0); 
error_reporting(E_ALL ^ E_DEPRECATED);
$host="localhost";
        $uname = 'pp_j9f349imc9';
        $pass = '~;lWBT35GD;{';
        $database     = 'pp_0cd249u34';
$connection=mysql_connect($host,$uname,$pass) or die("Database Connection Failed");
$selectdb=mysql_select_db($database) or die("Database could not be selected");	
$result=mysql_select_db($database)
or die("database cannot be selected <br>");

session_cache_expire(0);
@session_start();
set_time_limit(0);
?>

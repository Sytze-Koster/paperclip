<?php

// Database gegevens
$server = 'localhost'; 
$user = 'SytzeKostersql7';
$pass = 'xMDc4M';
$db = 'SytzeKostersql7';

// Connect naar Database
$connection = mysql_connect($server, $user, $pass) 
or die ("Could not connect to server 1 ... \n" . mysql_error ());
mysql_select_db($db) 
or die ("Could not connect to database 2... \n" . mysql_error ());

?>
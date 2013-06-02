<?php
$dbHost = "localhost";
$dbUser = "dias2013";
$dbPwd = "aez2b5kq";
$dbName = "db_2013";

$SQL = new PDO('mysql:dbname=' . $dbName . ';host=' . $dbHost, $dbUser, $dbPwd);
?>
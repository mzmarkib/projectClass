<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "projectClass";

$dbConn = new mysqli($host, $username, $password, $database);

if(!$dbConn)
    die ("Database Connection Failed:" . $dbConn -> connect_error);

<?php

$url = getenv('JAWSDB_URL');
$dbparts = parse_url($url);

$server = $dbparts['host'];
$username = $dbparts['user'];
$password = $dbparts['pass'];
$database = ltrim($dbparts['path'],'/');

?>
<?php
    // DB connection
    include_once 'database.php';
    
    $importid   = $_GET['date'];
    //$client   = $_POST['client'];

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows = array();

    $resultTotal = mysql_query("SELECT count(*) AS clients 
            FROM edrclients WHERE importid = '" . $importid . "'");

    while($q = mysql_fetch_assoc($resultTotal)) {
        $rows["total"][] = $q;
    }

    $result = mysql_query("SELECT id, client
            FROM edrclients WHERE importid = '" . $importid . "'");

    while($p = mysql_fetch_assoc($result)) {
        $rows["edr"][] = $p;
    }

    echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');

    mysql_close($conn);

 ?>
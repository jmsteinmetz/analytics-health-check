<?php
    // DB connection
    include_once 'database.php';
    
     $country       = $_GET['country'];
     if ($country == 'UNITED STATES') {
        $country = 'us';
     }

    $total = 0;
    $ct     = 0;

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows = array();

    $result = mysql_query("SELECT * FROM ir500 WHERE locale='" . $country . "'");
         

        while($p = mysql_fetch_assoc($result)) {
            $rows["targets"][] = $p;
            $total = $total + $p["monthlyvisitors"];
            $ct     = $ct + 1;
        };

        $rows["totals"]["total"] = $total;
        $rows["totals"]["ct"] = $ct;
        $rows["totals"]["average"] = $total/$ct;


    echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');



    mysql_close($conn);

 ?>
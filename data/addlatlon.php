<?php
    // DB connection
    set_time_limit(0)
    include_once 'database.php';
    
    //$client   = $_GET['client'];
    //$client   = $_POST['client'];

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows['locations'] = array();

    $result = mysql_query("SELECT * FROM testorders WHERE client = 'targetcom' WHERE id>5900");

        while ($p = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $ip = $p["partialip"].".255";
            $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

            mysql_query("UPDATE testorders SET latlon = '$details->loc' WHERE id = " . $p['id']);
            
        }


        //while($p = mysql_fetch_assoc($result)) {
        
        //$ip = $p["partialip"].".255";
        //$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
        //echo $details->city; // -> "Mountain View"
        // $rows["locations"]["id"] = $p["id"];
        // $rows["locations"]["browser"] = $p["browser"];
        // $rows["locations"]["mobile"] = $p["mobile"];
        // $rows["locations"]["client"] = $p["client"];
        // $rows["locations"]["ordertotal"] = $p["ordertotal"];
        // $rows["locations"]["totalitems"] = $p["totalitems"];
        // $rows["locations"]["partialip"] = $p["partialip"];
        // $rows["locations"]["productid"] = $p["productid"];
        // $rows["locations"]["productname"] = $p["productname"];
        //$rows["locations"]["loc"] = $details->loc;
        // id: "2",
        // browser: "Safari",
        // mobile: "FALSE",
        // client: "targetcom",
        // ordertotal: "9.99",
        // totalitems: "1",
        // partialip: "50.254.114",
        // productid: "50866350",
        // productname: "Day Designer Weekly/Monthly Planner, 2016-2017, 144pgs, 8.5&amp;quot; x 11&amp;quot; - Pink/White"
        
        //};




    //echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');

    mysql_close($conn);

 ?>
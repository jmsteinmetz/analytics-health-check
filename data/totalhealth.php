<?php
    // DB connection
    include_once 'database.php';
    
    $importid   = $_GET['importid'];

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows = array();

    $totalclients = 0;
    $currency = 0;
    $currency_m = 0;
    $quantity = 0;
    $quantity_m = 0;
    $price = 0;
    $price_m = 0;
    $ordertotal = 0;
    $ordertotal_m = 0;
    $productname = 0;
    $productname_m = 0;
    $productid = 0;
    $productid_m = 0;
    $productidmatch = 0;
    $productidmatch_m = 0;

    $result = mysql_query("SELECT * FROM pixelhealth WHERE importid = '" . $importid . "'");

        if (mysql_num_rows($result)==0) { 
            

        } else {

            while($p = mysql_fetch_assoc($result)) 
            {
                $totalclients = $totalclients + 1;
                $currency = $currency + $p['currency'];
                $quantity = $quantity + $p['quantity'];
                $productname = $productname + $p['productname']; 
                $productid = $productid + $p['productid'];
                $price = $price + $p['price'];
                $ordertotal = $ordertotal + $p['ordertotal'];
                $productidmatch = $productidmatch + $p['productidmatch'];              
            };

            // desktop items
                $rows["data"]["clients"]["totalclients"] = $totalclients;
                $rows["data"]["pixelhealth"]["currency"] = $currency;
                $rows["data"]["pixelhealth"]["quantity"] = $quantity;
                $rows["data"]["pixelhealth"]["productname"] = $productname;
                $rows["data"]["pixelhealth"]["productid"] = $productid;
                $rows["data"]["pixelhealth"]["price"] = $price;
                $rows["data"]["pixelhealth"]["ordertotal"] = $ordertotal;
                $rows["data"]["pixelhealth"]["productidmatch"] = $productidmatch;

                // Mobile items
                $rows["data"]["pixelhealth"]["currency_m"] = $currency_m;
                $rows["data"]["pixelhealth"]["quantity_m"] = $quantity_m;
                $rows["data"]["pixelhealth"]["productname_m"] = $productname_m;
                $rows["data"]["pixelhealth"]["productid_m"] = $productid_m;
                $rows["data"]["pixelhealth"]["price_m"] = $price_m;
                $rows["data"]["pixelhealth"]["ordertotal_m"] = $ordertotal_m;
                $rows["data"]["pixelhealth"]["productidmatch_m"] = $productidmatch_m;

        };
             


    echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');



    mysql_close($conn);

 ?>
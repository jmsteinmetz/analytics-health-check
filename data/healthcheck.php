<?php
    // DB connection
    include_once 'database.php';
    
    $bvid       = $_POST['bvid'];
    $clientid   = $_POST['clientid'];
    $importid   = $_POST['importid'];

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows = array();

    $analytics = 0;
    $norm = 0;
    $ext = 0;

    $result2 = mysql_query("SELECT * FROM ir500 WHERE id='" . $clientid . "'");
         while($q = mysql_fetch_assoc($result2)) {
           $monthly = intval($q["monthlyvisitors"]);
           $clientname = $q["company"];
        };

    $result3 = mysql_query("SELECT AVG(monthlyvisitors) AS visitoraverage FROM ir500");
         while($r = mysql_fetch_assoc($result3)) {
           $average = intval($r["visitoraverage"]);
        };

        $result = mysql_query("SELECT * FROM clientmatch WHERE bvid='" . $bvid . "' OR irID='" . $clientid . "'");

        if (mysql_num_rows($result)==0) { 
            
                $analytics = 0;

        } else {

            $analytics = 1;

        };

        $result = mysql_query("SELECT * FROM pixelhealth WHERE clientid='" . $bvid . "' AND importid = '" . $importid . "'");

        if (mysql_num_rows($result)==0) { 
            

        } else {

            while($p = mysql_fetch_assoc($result)) 
            {
                // Calculate the desktop values
                $norm   = $p["currency"] 
                + $p["quantity"] 
                + $p["productname"] 
                + $p["productidmatch"] 
                + $p["price"] 
                + $p["productid"] 
                + $p["ordertotal"];
                // Give extra to fields needed for SA
                if (($p["price"] + $p["productid"] + $p["ordertotal"] + $p["productidmatch"] ) == 4) {
                    $ext    = 3;
                } else {
                    $ext = 0;
                }

                // Calculate the mobile values
                $norm_m   = $p["currency_m"] 
                + $p["quantity_m"] 
                + $p["productname_m"] 
                + $p["productidmatch_m"] 
                + $p["price_m"] 
                + $p["productid_m"] 
                + $p["ordertotal_m"];
                // Give extra to fields needed for SA
                if (($p["price_m"] + $p["productid_m"] + $p["ordertotal_m"] + $p["productidmatch_m"] ) == 4) {
                    $ext_m    = 3;
                } else {
                    $ext_m = 0;
                }
                
                // desktop items
                $rows["data"]["pixelhealth"]["currency"] = $p["currency"];
                $rows["data"]["pixelhealth"]["quantity"] = $p["quantity"];
                $rows["data"]["pixelhealth"]["productname"] = $p["productname"];
                $rows["data"]["pixelhealth"]["productid"] = $p["productid"];
                $rows["data"]["pixelhealth"]["price"] = $p["price"];
                $rows["data"]["pixelhealth"]["ordertotal"] = $p["ordertotal"];
                $rows["data"]["pixelhealth"]["productidmatch"] = $p["productidmatch"];

                // Mobile items
                $rows["data"]["pixelhealth"]["currency_m"] = $p["currency_m"];
                $rows["data"]["pixelhealth"]["quantity_m"] = $p["quantity_m"];
                $rows["data"]["pixelhealth"]["productname_m"] = $p["productname_m"];
                $rows["data"]["pixelhealth"]["productid_m"] = $p["productid_m"];
                $rows["data"]["pixelhealth"]["price_m"] = $p["price_m"];
                $rows["data"]["pixelhealth"]["ordertotal_m"] = $p["ordertotal_m"];
                $rows["data"]["pixelhealth"]["productidmatch_m"] = $p["productidmatch_m"];
                
            };

        };
        
            // Calculate the basic health score 
            // Basic Analytics * 50% + SA Bonus + Analytics
            $desktop = $norm+$ext;
            $mobile = $norm_m+$ext_m;

            $composite = ($desktop + $mobile + $analytics)/2;

            $network = (($monthly/$average)*$composite/10)*10;
            $potential = (($monthly/$average)*10/10)*10;

             $rows["data"]["clientid"] = $clientid;
             $rows["data"]["clientname"] = $clientname;
             $rows["data"]["desktop"] = $desktop;
             $rows["data"]["mobile"] = $mobile;
             $rows["data"]["network"] = round($network,2);
             $rows["data"]["potential"] = round($potential,2); 
             $rows["data"]["monthly"] = $monthly;
             $rows["data"]["average"] = $average;
             $rows["data"]["norm"] = $norm;
             $rows["data"]["ext"] = $ext;
             


    echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');



    mysql_close($conn);

 ?>
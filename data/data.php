<?php
    // DB connection
    include_once 'database.php';
    
    $list       = $_GET['list'];
    $export     = $_GET['export'];
    $importid     = $_GET['date'];

    if ($list == 'us') {

        $country = "united states";
        $locale = "locale = 'us'";

    }

    if ($list == 'all') {

        $country = "All";

    }

    if ($list == 'eu') {

        $country = "EMEA";
        $locale = "locale = 'germany' OR locale = 'uk' OR locale = 'france'";
        
    }

    if ($list == 'germany') {

        $country = "Germany";
        $locale = "locale = 'germany'";
        
    }

    if ($list == 'france') {

        $country = "France";
        $locale = "locale = 'france'";
        
    }

    if ($list == 'uk') {

        $country = "UK";
        $locale = "locale = 'uk'";
        
    }



    if ($export == 'csv') {
        $list       = $_POST['list'];

        // Header info settings
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=output.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
        header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

        $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
        mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

        $rows = array();

        $result = mysql_query("SELECT *
                FROM ir500 
                WHERE $locale 
                ORDER BY monthlyvisitors DESC");
     
    // Define separator (defines columns in excel &amp; tabs in word)
    $sep = "\t"; // tabbed character
     
    // Start of printing column names as names of MySQL fields
    for ($i = 0; $i<mysql_num_fields($result); $i++) {
     echo mysql_field_name($result, $i) . "\t";
    }
    print("\n");
    // End of printing column names
     
     
     
     
        // Start while loop to get data
        while($row = mysql_fetch_row($result))
        {
          $schema_insert = "";
          for($j=0; $j<mysql_num_fields($result); $j++)
          {
        if(!isset($row[$j])) {
          $schema_insert .= "NULL".$sep;
        }
        elseif ($row[$j] != "") {
         $schema_insert .= "$row[$j]".$sep;
        }
        else {
         $schema_insert .= "".$sep;
        }
         }
         $schema_insert = str_replace($sep."$", "", $schema_insert);
         $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
         $schema_insert .= "\t";
         print(trim($schema_insert));
         print "\n";
        }


        
    } else {

        $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
        header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

        $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
        mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

        

        if ($country == 'All') {

            $rows['list'] = array();

            $result = mysql_query("SELECT *
                FROM pixelhealth WHERE importid='$importid' AND clientid <> ''
                GROUP BY clientid");

                while($p = mysql_fetch_assoc($result)) {
                    //$rows["list"][] = $p;

                    $row_array['id'] = '';
                    $row_array['company'] = $p['clientid'];
                    $row_array['sales'] = '';
                    $row_array['monthlyvisitors'] = '';
                    $row_array['locale'] = '';
                    $row_array['importdate'] = '';

                    array_push($rows['list'],$row_array);
                }

        } else {

            $rows = array();

            $result = mysql_query("SELECT *
                FROM ir500 
                WHERE $locale 
                ORDER BY monthlyvisitors DESC");

                while($p = mysql_fetch_assoc($result)) {
                    $rows["list"][] = $p;
                }

        }

        

        $rows["country"] = $country;

        echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');

    }

    mysql_close($conn);

 ?>
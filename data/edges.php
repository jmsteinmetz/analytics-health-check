<?php
    // DB connection
    include_once 'database.php';

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    // $nodes   = $_GET['nodes'];
    // $nodes   = $_GET['links'];

    $callback = isset($_GET['callback']) ? preg_replace('/[^a-z0-9$_]/si', '', $_GET['callback']) : false;
    header('Content-Type: ' . ($callback ? 'application/javascript' : 'application/json') . ';charset=UTF-8');

    $conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
    mysql_select_db($database, $conn) or die ("Couldn't open $test: " . mysql_error());

    $rows = array();
    $rows['nodes'] = array();
    $rows['links'] = array();
    $nodelist[] = array();

    for ($x = 1; $x <= 25; $x++) {
            $testValue = generateRandomString();
            $row_array['id'] = $testValue;
            $row_array['group'] = $x;

            array_push($rows['nodes'],$row_array);

            array_push($nodelist, $testValue);
    };


    for ($x = 1; $x <= 100; $x++) {
            $input = $nodelist;
            $rand_keys = array_rand($input, 2);

            $row_array2['source'] = $input[$rand_keys[0]];
            $row_array2['target'] = $input[$rand_keys[1]];
            $row_array2['value'] = rand(1, 20);;

            array_push($rows['links'],$row_array2);
    };


    

            


    echo ($callback ? $callback . '(' : '') . json_encode($rows) . ($callback ? ')' : '');

    mysql_close($conn);

 ?>
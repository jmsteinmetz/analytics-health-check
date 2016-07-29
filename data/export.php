<?php
	
	include_once 'database.php';

	// Create connection to db
	$conn =  mysql_connect($server, $username, $password) or die("Couldn't connect to MySQL" . mysql_error());
	mysql_select_db($database, $conn) or die ("Couldn't open $database: " . mysql_error());

	$country = $_POST['country'];


$result = mysql_query("SELECT *
            FROM ir500 
            ORDER BY locale, monthlyvisitors DESC");
 
// Header info settings
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=output.xls");
header("Pragma: no-cache");
header("Expires: 0");
 
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


	// -------------------------------------------------------------------------------------

	mysql_close($conn);
	?>
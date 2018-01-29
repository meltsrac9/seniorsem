<?
/*
Note: 
The Web-based database GUI is located at 
https://csci.lakeforest.edu/cscidbgui/
The below user name and password will log you into that.
*/

define('DB_SERVER','localhost');
define('DB_USERNAME','csci488_fall17');
define('DB_PASSWORD','SeNSeMdb1');
define('DB_DATABASE','csci488_fall17');

mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die("Could not connect");
mysql_select_db(DB_DATABASE) or die("Could not select database " . DB_DATABASE);

$animal_names = array('aardvark','wildebeest','bandicoot','muskox');
$random_animal = $animal_names[mt_rand(0,3)];

// Uncomment this to send  the animal through a query string
//$random_animal = $_GET['my_animal'];

$query = "INSERT INTO knuckles_test VALUES ('','$random_animal')";
$result = mysql_query($query);

//var_dump($result);

$query = "SELECT * FROM knuckles_test ";
$result = mysql_query($query);

//var_dump($result);

$num_rows = mysql_num_rows($result);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Basic Database Code</title>
	</head>	
	<body>
	
		<br /><br />
		Database table listing.
		<br /><br />
		<?=$num_rows?> rows in database.
		<br /><br />
		
		
		<!-- 
		  The loop below "fetches" one database $row at a time out of
		  the mysql $result set that resulted from the SQL SELECT statement.
		  Each fetched $row is an associative array whose keys are the column
		  names from the database table. 
		-->
		
		<table width="" cellspacing="0" cellpadding="5">
			<? if ($num_rows>0) { ?>
				<? while ( $row = mysql_fetch_assoc($result) ) { ?>
					<tr  valign="top">
						<td>
							<?=$row['test_id']?>
						</td>
						<td>
							<?=$row['test_data']?>
						</td>
					</tr>
				<? } // while ?>
			<? } // if ?>
		</table>
	
	</body>
</html>
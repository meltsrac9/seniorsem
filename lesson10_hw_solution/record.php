<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
// Always do this at the top of your script if using sessions -- BEFORE any script output
////////////////////////////////////////////////////////////////////////////////////////////////////////
session_start();


////////////////////////////////////////////////////////////////////////////////////////////////////////
// The Erase Part - not part of original assignment
////////////////////////////////////////////////////////////////////////////////////////////////////////
if (  $_GET['reset'] == 'yes' ) {
  setcookie('visit_count', 0 , time() + (60*60*24 * 365) ); // seconds per day * 365 days
  $_SESSION['visits_history'] = array();
  
  // Auto transfer to the page that shows the visits
  header ("Location: show.php");
  exit;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////
// The Cookie Part
////////////////////////////////////////////////////////////////////////////////////////////////////////

if ( isset($_COOKIE['visit_count']) ) {
  $cookie_value = $_COOKIE['visit_count'] + 1;
}
else {
  $cookie_value = 1; // first visit
}

setcookie('visit_count', $cookie_value, time() + (60*60*24 * 365) ); // seconds per day * 365 days


////////////////////////////////////////////////////////////////////////////////////////////////////////
// The Session Part
////////////////////////////////////////////////////////////////////////////////////////////////////////
if ( !isset($_SESSION['visits_history']) ) {
  $_SESSION['visits_history'] = array();
}
$this_visit['ip']           = $_SERVER['REMOTE_ADDR'];
$this_visit['browser_port'] = $_SERVER['REMOTE_PORT'];
$this_visit['browser_data'] = $_SERVER['HTTP_USER_AGENT'];
$this_visit['timestamp']    = time();  // could use $_SERVER['REQUEST_TIME']

array_push ($_SESSION['visits_history'] , $this_visit);

/* 
  $_SESSION['visits_history'][] = $this_visit;  
  Same as array_push statement above.
*/


?>

<!DOCTYPE html>
<html>
<head>
  <title>Cookie/Session Collect Data</title>
</head>

<body>
This example is broken into 2 pages to show that cookie and session data is not just available across different 
transactions, but also available across different pages (scripts) within a Web application. 
<br><br>
This page visit has recorded data from <b>visit #<?= $cookie_value ?></b> from your browser:
<ul>
	<li>A count of this visit using a self-defined cookie</li>
	<li>Data about this visit using built-in PHP sessions</li>
</ul>

<a href="show.php">Click here to see more details about the recorded information.</a>
<br><br>
<a href="record.php?reset=yes">Click here to erase all visit history.</a>

</body>
</html>

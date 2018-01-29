<?  
session_start();   // Always do at top of script if using sessions.
                   // Necessary for storing AND retrieving session data.

/*
It's EXTREMELY useful to see what is in your variables during development
var_dump ($_COOKIE);
var_dump ($_SESSION['visits_history']);

Do the last one after you have accumulated some visit history.  
It's hard to read in the Web page, but look at it in Chrome's "Source Code View"!!
*/


?>

<!DOCTYPE html>
<html>
<head>
  <title>Cookie/Session Show Data</title>
</head>

<body>

<h4>Your browser has visited the other page <?= $_COOKIE['visit_count'] ?> times.</h4>

<form>
  <select>
    <option>Page Visit History</option>   
    <? foreach ( $_SESSION['visits_history'] as $visit ) { ?>
        <option><?= $visit['ip'] . ' -- ' . date("Y-m-d H:i:s", $visit['timestamp']) ?></option> 
    <? } ?>
  </select>
</form>

<br><br>
    
<a href="record.php">Back to the Page that Records the visit history.</a>

<br><br>



</body>
</html>

<?
require 'init.php'; // database connection, etc

$sql = "SELECT * FROM " . PEOPLE_TABLE . " WHERE 1 ORDER BY ppl_lastname ASC ";
$result = lib::db_query($sql);

$num_rows = $result->num_rows;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Non-Fancy Listing of People Table</title>
		<script type="text/javascript">
      
      function confirm_delete(ppl_id, ppl_firstname, ppl_lastname) {
        var choice = confirm("Are you sure you want to delete " + ppl_firstname + " " + ppl_lastname + "?");
        
        if ( choice == true ) {
          window.location.href = "person_form.php?task=delete&ppl_id="+ppl_id;
        }
      }
    </script>
		
	</head>
	<body>
	   <a href="person_form.php">Back To People Edit Form</a>
	   <br><br>
	   
	   <? if ( isset($_REQUEST['deleted_message']) ) { ?>
          <b>The DB record was sucessfuly deleted.</b> 
          <br><br>
     <? } ?>
	
	   <? if ($num_rows == 0) { ?>
	      <b>No records were found in the database.</b>
      <? } else { ?>
        
        <b>Listing of Database Records:</b>
        
        <table width="" border="1" cellspacing="0" cellpadding="5">
	      <tr  valign="top">
            <td>First Name</td>
            <td>Last Name</td>
            <td>Email</td>
            <td>Age</td>
            <td>&nbsp;</td>
         </tr>
         <? while ( $row = $result->fetch_assoc() ) { ?>
            <tr  valign="top">
               <td><?=$row['ppl_firstname']?></td>
               <td><?=$row['ppl_lastname']?></td>
               <td><?=$row['ppl_email']?></td>
               <td><?=$row['ppl_age']?></td>
               <td>
                  <a href="person_form.php?task=edit&ppl_id=<?=$row['ppl_id']?>">Edit</a>
                  &nbsp;&nbsp;|&nbsp;&nbsp;
                  <a href="#null" onclick="confirm_delete(<?=$row['ppl_id']?> , '<?=$row['ppl_firstname']?>' , '<?=$row['ppl_lastname']?>')">Delete</a>
               </td>
            </tr>
         <? } // end while ?>
         </table>
         
         <br><br>
         <a href="person_listing_with_pagination.php">Go to the fancy listing with Pagination</a>
      
      <? } // end else ?>

	</body>
</html>
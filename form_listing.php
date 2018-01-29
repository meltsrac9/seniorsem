<?
require 'init.php'; // database connection, etc

$sql = "SELECT * FROM meltser_form WHERE 1 ORDER BY name ASC ";
$result = lib::db_query($sql);

$num_rows = $result->num_rows;
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Form Submissions</title>
		<script type="text/javascript">
      
      function confirm_delete(id, name) {
        var choice = confirm("Are you sure you want to delete " + name + "?");
        
        if ( choice == true ) {
          window.location.href = "html_form_server.php?task=delete&id="+id;
        }
      }
    </script>
	</head>
	<body>   		
		<a href="html_form_server.php">Back To Edit Form</a>
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
					<td>Name</td>
					<td>Email</td>
					<td>Toppings</td>
					<td># of Pizzas</td>
					<td>Crust Type</td>
					<td>Extra Notes</td>
					<td>Sauce Type</td>
					<td>Size</td>
					<td>Extra Toppings</td>
					<td>&nbsp;</td>
         </tr>
         <? while ( $row = $result->fetch_assoc() ) { ?>
					<tr  valign="top">
						 <td><?=$row['name']?></td>
						 <td><?=$row['email']?></td>
						 <td><?=$row['toppings']?></td>
						 <td><?=$row['number']?></td>
						 <td><?=$row['crust']?></td>
						 <td><?=$row['paragraph']?></td>
						 <td><?=$row['sauce']?></td>
						 <td><?=$row['size']?></td>
						 <td><?=$row['extra_toppings']?></td>
						<td>
								<a href="html_form_server.php?task=edit&id=<?=$row['id']?>">Edit</a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href="#null" onclick="confirm_delete(<?=$row['id']?> , '<?=$row['name']?>')">Delete</a>
						 </td>
            </tr>
         <? } // end while ?>
      </table>
      
      <? } // end else ?>

	</body>
</html>
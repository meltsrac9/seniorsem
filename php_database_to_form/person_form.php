<?
require 'init.php'; // database connection, etc

$task = $_REQUEST['task'];
switch ($task)  {

    case 'save':
        // Create a database record From Form Submission
        
         if ( isset($_REQUEST['ppl_id']) && $_REQUEST['ppl_id'] > 0 ) {
            $ppl_id = $_REQUEST['ppl_id'];  // need to update existing person DB record
         }
         else {
            $ppl_id = 0;                    // need to create new person DB record  
         }
         
         $ppl_firstname = addslashes(trim($_REQUEST['ppl_firstname']));
         $ppl_lastname  = addslashes(trim($_REQUEST['ppl_lastname']));
         $ppl_email     = addslashes(trim($_REQUEST['ppl_email']));
         $ppl_age       = addslashes(trim($_REQUEST['ppl_age']));
         
         if ( !$ppl_firstname || !$ppl_lastname || !$ppl_email || !$ppl_age ) {
            // reload this page with error message
            // Ideally, client-side form validation would have caught this
            
            if ( $ppl_id > 0 ) {
              $transfer_url = "person_form.php?task=edit?ppl_id=$ppl_id&incomplete=yes";
              
            }
            else {
              $transfer_url = "person_form.php?incomplete=yes";
            }
            header ("Location: $transfer_url"); 
            exit;
         }
         
         if ( $ppl_id > 0 ) {
            // Build the UPDATE statement
            $sql = "UPDATE " . PEOPLE_TABLE . " SET ppl_firstname ='$ppl_firstname' , 
                                                    ppl_lastname  ='$ppl_lastname' ,
                                                    ppl_email     ='$ppl_email' ,
                                                    ppl_age       = $ppl_age 
                                                WHERE ppl_id=$ppl_id ";
         }
         else {
            // Build the INSERT statement
            $sql = "INSERT INTO " . PEOPLE_TABLE . " VALUES ('','$ppl_firstname','$ppl_lastname','$ppl_email','$ppl_age') ";
         }
        

        // Execute the SQL query
        lib::db_query($sql);
              
        // Transfer to the listing page -- not good to let the browser sitting on the post or get data transaction
        // The PHP header function adds the Location directive to the HTTP response header, which then causes the browser to do the transfer.
        header ("Location: person_listing.php");
        exit;
        break;
        /////////////////////////////
        // End Save Case
        /////////////////////////////
        
    case 'delete':
      // Just delete that puppy
      
       if ( isset($_REQUEST['ppl_id']) && $_REQUEST['ppl_id'] > 0 ) {
          $ppl_id = $_REQUEST['ppl_id'];                    
       }
       
       // Build the INSERT statement
       $sql = "DELETE FROM " . PEOPLE_TABLE . " WHERE ppl_id=$ppl_id ";
       lib::db_query($sql);
       
       header ("Location: person_listing.php?deleted_message=yes");
       exit;
       break; 
      
      /////////////////////////////
      // End delete Case
      /////////////////////////////
    
    case 'edit':      
      if ( ! isset($_REQUEST['id']) || $_REQUEST['id'] <= 0 ) {
        // if no incoming ppl_id, just give blank form
        break; 
      }
      
      $ppl_id = $_REQUEST['id'];
      $sql = "SELECT * FROM " . PEOPLE_TABLE . " WHERE  id=$id ";
      $result = lib::db_query($sql);
      $row = $result->fetch_assoc();  // will only be one row
      
      foreach ($row as $key => $value) {
        $row[$key] = htmlspecialchars($value);
				lib::menu_from_assoc_array($key);
        // certain characters like " and < and > are reserved in HTML.
        // This converts them all into HTML character entities like &quot;
      }
      break; 
      
      /////////////////////////////
      // End edit Case
      /////////////////////////////

    default: 
    // default case just drops into the page with the form
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Sample Form to Database Transaction</title>
	</head>
	<body>
	   <? if( $_REQUEST['incomplete'] ) { ?>
	      Your Form Submission was Missing Data
	      <br><br>
	   <? } ?>
	   
	   <a href="person_listing.php">Go To People Listing Page</a> or create a new person below. 
	   
	   <br><br>
	
	   <!-- This Form Submits to this same file!  -->
	   <form action="person_form.php" method="POST" >
	       <input type="hidden" name="task" value="save">
	       <input type="hidden" name="ppl_id" value="<?=$ppl_id?>">
	       
	       First Name: <input type="text" name="ppl_firstname" value="<?=$row['ppl_firstname']?>">
	       <br>
	       Last Name: <input type="text" name="ppl_lastname" value="<?=$row['ppl_lastname']?>">
         <br>
         Email: <input type="text" name="ppl_email" value="<?=$row['ppl_email']?>">
         <br>
         Age: <input type="text" name="ppl_age" value="<?=$row['ppl_age']?>">
	       <br><br>
	       <button type="submit" id="submit_button"> Submit </button>
	   </form>
	
	</body>
</html>
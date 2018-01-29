<?
require 'init.php'; // database connection, etc

$task = $_REQUEST['task'];
switch ($task)  {

    case 'save':
        // Create a database record From Form Submission
         
         $ppl_firstname = trim($_REQUEST['ppl_firstname']);
         $ppl_lastname  = trim($_REQUEST['ppl_lastname']);
         $ppl_email     = trim($_REQUEST['ppl_email']);
         $ppl_age       = trim($_REQUEST['ppl_age']);
         
         if ( !$ppl_firstname || !$ppl_lastname || !$ppl_email || !$ppl_age ) {
            // reload this page with error message
            // Ideally, client-side form validation would have caught this
            header ("Location: person_form.php?incomplete=yes"); 
            exit;
         }

        // Build the INSERT statement
        $sql = "INSERT INTO " . PEOPLE_TABLE . " VALUES ('','$ppl_firstname','$ppl_lastname','$ppl_email','$ppl_age') ";
        
        // Execute the INSERT statement 
        lib::db_query($sql);
        
        // Used db_query() wrapper for $mysqli->query($query) from class_lib.php

              
        // Transfer to the listing page -- not good to let the browser sitting on the post or get data transaction
        // The PHP header function adds the Location directive to the HTTP response header, which then causes the browser to do the transfer.
        header ("Location: person_listing.php");
        exit;
        break;
        /////////////////////////////
        // End Save Case
        /////////////////////////////
        
    case 'delete':
      // not implemented yet -- just falls through to default case
      
      // break; 
      
      /////////////////////////////
      // End delete Case
      /////////////////////////////
    
    case 'edit':
      // not implemented yet -- just falls through to default case
      
      // break; 
      
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
	
	   <!-- This Form Submits to this same file!  -->
	   <form action="person_form.php" method="POST" >
	       <input type="hidden" name="task" value="save">
	       
	       First Name: <input type="text" name="ppl_firstname" value="">
	       <br>
	       Last Name: <input type="text" name="ppl_lastname" value="">
         <br>
         Email: <input type="text" name="ppl_email" value="">
         <br>
         Age: <input type="text" name="ppl_age" value="">
	       <br><br>
	       <button type="submit" id="submit_button"> Submit </button>
	   </form>
		
	
	</body>
</html>
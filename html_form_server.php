<?
require 'init.php'; // database connection, etc

$task = $_REQUEST['task'];
switch ($task)  {

    case 'save':
        // Create a database record From Form Submission
      if(isset($_REQUEST['id']) && $_REQUEST['id'] > 0){
				$id = $_REQUEST['id'];
			}   
			else{
				$id = 0;
			}		
		
         $name = trim($_REQUEST['name']);
         $email  = trim($_REQUEST['email']);
         $toppings = json_encode($_REQUEST['toppings']);
				 $extra_toppings = json_encode($_REQUEST['extra_toppings']);
         $number = trim($_REQUEST['number']);
         $crust = trim($_REQUEST['crust']);
         $paragraph = trim($_REQUEST['paragraph']);
         $sauce = trim($_REQUEST['sauce']);
         $size = trim($_REQUEST['size']);
		
        
         if ( !$name || !$email) {
		
		 				if ( $id > 0 ) {
              $transfer_url = "html_form_server.php?task=edit?id=$id&incomplete=yes";
              
            }
            else {
              $transfer_url = "html_form_server.php?incomplete=yes";
            }
            header ("Location: $transfer_url"); 
            exit;
         }
         
         if ( $id > 0 ) {
            // Build the UPDATE statement
            $sql = "UPDATE meltser_form SET email='$email', name = '$name', toppings = '$toppings', number = '$number', crust = '$crust', paragraph = '$paragraph', sauce = '$sauce', size = '$size', extra_toppings = '$extra_toppings' WHERE id = '$_REQUEST[id]'" ;
         }
         else {
            // Build the INSERT statement
           $sql = "INSERT INTO meltser_form VALUES ('', '$email', '$name', '$toppings', '$number', '$crust', '$paragraph', '$sauce', '$size', '$extra_toppings')";
				 }

		
		
			lib::db_query($sql);
			header ("Location: form_listing.php");
      exit;
      break;
/*		
		
         
		//IT IS ALSWAYS GOING TO EDIT CASE AND RETURNING SO MANY NULLS
				if(!isset($_REQUEST['id'])){
					$sql = "INSERT INTO meltser_form VALUES ('', '$email', '$name', '$toppings', '$number', '$crust', '$paragraph', '$sauce', '$size', '$extra_toppings')";
					
				}
				else{
        // Build the upidate statement
					$sql = "UPDATE meltser_form SET email='$email', name = '$name', toppings = '$toppings', number = '$number', crust = '$crust', paragraph = '$paragraph', sauce = '$sauce', size = '$size', extra_toppings = '$extra_toppings' WHERE id = '$_REQUEST[id]'" ;
				}*/
        
        // Execute the INSERT statement 
       
        
        // Used db_query() wrapper for $mysqli->query($query) from class_lib.php

              
        // Transfer to the listing page -- not good to let the browser sitting on the post or get data transaction
        // The PHP header function adds the Location directive to the HTTP response header, which then causes the browser to do the transfer.
        
        /////////////////////////////
        // End Save Case
        /////////////////////////////
        
    case 'delete':
      // not implemented yet -- just falls through to default case
      if ( isset($_REQUEST['id']) && $_REQUEST['id'] > 0 ) {
          $id = $_REQUEST['id'];                    
       }
       
       // Build the INSERT statement
       $sql = "DELETE FROM " . meltser_form . " WHERE id=$id ";
       lib::db_query($sql);
       
       header ("Location: form_listing.php?deleted_message=yes");
       exit;
       break; 
      
      /////////////////////////////
      // End delete Case
      /////////////////////////////
    
    case 'edit':
      // not implemented yet -- just falls through to default case
       if ( ! isset($_REQUEST['id']) || $_REQUEST['id'] <= 0 ) {
        // if no incoming id, just give blank form
        break; 
      }
      
      $id = $_REQUEST['id'];
      $sql = "SELECT * FROM " . meltser_form . " WHERE  id=$id ";
      $result = lib::db_query($sql);
      $row = $result->fetch_assoc();  // will only be one row
      	
		  $extra_toppings = json_decode($row['extra_toppings']);
	   	$toppings = json_decode($row['toppings']);
         

      foreach ($row as $key => $value) {
				$row[$key] = htmlspecialchars($value);					
      }
			
			break;
      /////////////////////////////
      // End edit Case
      /////////////////////////////
		
		default: 
    // default case just drops into the page with the formb
		break;
	}		
			
   

?>
<!DOCTYPE html>
<html>
<head>
  <title>HTML Form</title>
</head>
<body>
<!--
+Two type="text" input fields (single line text entry). One of them needs to ask for an email address.
+A type="number" input field.
+A type="range" input field.
+Textarea (a multi-line text field)
+At least 3 checkboxes.
+Two different unique selection groups of at least 2 radio buttons each. By a unique selection group, I mean a group of radio buttons where only one of them can be selected.
+A select dropdown menu where only one item can be selected.
+A select dropdown menu that allows multiple selections.
+A hidden form element.
Both a reset and a submit button.-->
<form name="meltser_form[]" action="html_form_server.php" method="POST">
	<input type = "hidden" name = "task" value = "save">
	<input type="hidden" name="id" value="<?=$id?>">
	Enter Email address: 
	<br>
	<input type="text" name="email" value= "<?=$row['email']?>">    
	<br><br>

	Enter Name:
	<br>
	<input type = "text" name = "name" value = "<?=$row['name']?>">
	<br><br>
	
	Select a Pizza Topping<br>
	<input type="checkbox" name="toppings[]" value="pepperoni"  <? if($toppings != null && in_array("pepperoni",$toppings)){echo 'checked="yes"';} ?>>Pepperoni
	<br>
	<input type = "checkbox" name="toppings[]" value = "sausage" <? if($toppings != null && in_array("sausage", $toppings)){echo 'checked="yes"';} ?>>Sausage
	<br>
	<input type = "checkbox" name = "toppings[]" value = "extra_cheese" <? if($toppings != null && in_array("extra_cheese", $toppings)){echo 'checked="yes"';} ?>>Extra Cheese

	<br><br>
	How Many Pizzas will it be?<input type = "number" name = "number" value = "0">
	<br><br>
	
	How thick should the crust be?<input type = "range" min = "1" max = "10" name = "crust" value = "<?=$row['crust']?>">
	<br><br>
	<textarea rows = "4" cols = "50" name = "paragraph" value = "<?=$row['paragraph']?>"> Extra delivery instructions</textarea>
	<br><br>	
	
	Pick a Sauce<br>
	<input type="radio" name="sauce" value="marinara" <?$row['sauce'] == 'marinara'? print "checked" : "";?>> Marinara<br><!--ES6 goin' on over here-->
  <input type="radio" name="sauce" value="pesto" <?$row['sauce'] == 'pesto'? print "checked" : "";?>> Pesto<br>
  <input type="radio" name="sauce" value="bbq" <?$row['sauce'] == 'bbq'? print "checked" : "";?>> BBQ
	<br><br>
	
	Pick a Size<br>
	<select name = "size" >
  <option value="small" <? $row['size'] == 'small' ? print "selected" : "";?>>Small</option>
  <option value="medium" <? $row['size'] == 'medium' ? print "selected" : "";?>>Medium</option>
  <option value="large" <? $row['size'] == 'large' ? print "selected" : "";?>>Large</option>
  <option value="extra_large" <? $row['size'] == 'extra_large' ? print "selected" : "";?>>Extra Large</option>
</select>
	
	<br><br>
	Select multiple extra toppings using ctr/cmd<br>
	<select multiple name = "extra_toppings[]"><!--getting nulls instead of array-->
  <option value="mushrooms" <? if($extra_toppings != null && in_array("mushrooms", $extra_toppings)){echo 'selected="yes"';}  ?>>mushrooms</option>
  <option value="pineapple" <? if($extra_toppings != null && in_array("pineapple", $extra_toppings)){echo 'selected="yes"';} ?>>pineapple</option>
  <option value="spinach" <? if($extra_toppings != null && in_array("spinach", $extra_toppings)){echo 'selected="yes"';} ?>>spinach</option>
  <option value="olives" <? if($extra_toppings != null && in_array("olives", $extra_toppings)){echo 'selected="yes"';} ?>>olives</option>
</select>
	
	<br><br>   
    <br><br>
    
    <input type="submit" value=" Submit ">
	 <button type="reset" value="Reset">Reset</button>
</form>

</body>
</html>

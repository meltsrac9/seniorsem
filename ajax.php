<?require 'init.php';?>
<?
	$ip= $_SERVER['REMOTE_ADDR'];
	$browser_port = $_SERVER['REMOTE_PORT'];
	$browser_data = $_SERVER['HTTP_USER_AGENT'];
	$timestamp= time();  // could use 
	
	$query = "INSERT INTO meltser_test VALUES ('','$ip', '$timestamp', '$browser_port', 'browser_data')";	
	//var_dump($query);		
	$result = $mysqli->query($query);
	//var_dump($result);
/*	$query = "SELECT * FROM melser_test ";
	$result = $mysqli->query($query);
	var_dump($result);
	$num_rows =  $result->num_rows;*/

?>
	
<!DOCTYPE html>
<html>
<head>
  <title>Lesson 11 Solution</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<script type="text/javascript">

  function load_visit_data_from_server() {
    
		
		//select * from meltser_test
		
    // JQuery Objects
    $form_obj = $('#visit_form');       
    $menu_obj = $('#previous_visits');       
    
    //console.log($menu_obj.val());
    //console.log($form_obj.serialize());
    //return;
		

	
    if ( $menu_obj.val() == -1 ) {
        $('#visit_details').html(''); // clear out the div
    }
    else {

      $.ajax({
        type: 'GET',
        url: 'server.php',
        dataType: 'json',
        
        data:  $form_obj.serialize(),
        error: error_callback,
        success: success_callback

      });

    }
  }
  
  
  //////////////////////////////////////////////////////////////////////////////
  // Error Callback
  //////////////////////////////////////////////////////////////////////////////
  function error_callback(req, status, err) {
    console.log("OOPS: " , req, status, err )
  }
  
  //////////////////////////////////////////////////////////////////////////////
  // Success Callback
  //////////////////////////////////////////////////////////////////////////////
  function success_callback(response) {
    console.log("OK: " , response );
    
    var display = "IP: " + response.ip + '<br>' +
                  "Browser Port: " + response.browser_port + '<br>' +
                  "Browser Data: " + response.browser_data + '<br>' +
                  "Visit Time: " + response.timestamp + '<br>';
                  
                  
    if ( response.geo ) {
      display += "Area Code: " + response.geo.area_code + '<br>' +
                 "City: " + response.geo.city + '<br>' +
                 "State: " + response.geo.region + '<br>' +
                 "ISP: " + response.geo.isp + '<br>' +
                 "Latitude: " + response.geo.latitude + '<br>' +
                 "Longitude: " + response.geo.longitude + '<br>';   
      
      display += '<br><a href="googlemap.php?lat='+ response.geo.latitude +'&long='+ response.geo.longitude +'" target="_blank">See on Map</a>';
    }
    
    
     $('#visit_details').html(display);
		
		//insert into meltser_test 'visit_details'
     
  } // end success callback
  
  
</script>


<body>

<b>Previous Visits to This page:</b>
<br><br>
<? 
	$query = "SELECT * FROM meltser_test";
	$result = $mysqli->query($query);
	$num_rows = $result->num_rows;
	var_dump($result);
	var_dump($num_rows);
	?><br><br>
<form id="visit_form">


	
	  <select name="previous_visits" id="previous_visits" onchange="load_visit_data_from_server()">
    <option value="-1">Select Visit to Show Additional Visit Info</option> 
    
    
				<? while ( $row = $result->fetch_assoc() ) { ?>
					<option value = "<?= $row["ID"] ?>">
							<?=$row["IP"] . ' -- ' . $row["Timestamp"] ?>
				<? } // while ?></option>
	
			
  </select>
  
  <br><br>
  Also return visit geolocation data: <input type="checkbox" name="geo" value="yes"> 
  
</form>

<br><br>

<b>Visit Details:</b>
<div id="visit_details"></div>

<br><br>
   
</body>
</html>
<!DOCTYPE html>
<html>
<head>
  <title>AJAX Example</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
  <script type="text/javascript">

     ///////////////////////////////////////////////////////////////////////////////////////
     // Event handler for 1st button.
     // Manually constructs data sent to server. 
     ///////////////////////////////////////////////////////////////////////////////////////
     function ajax_call1() {
     
        var animal_id = document.myform.animal_id.value;
        
        if ( animal_id != -1 ) {
          
            var server_script = 'server.php?animal_id='+animal_id;
            
            console.log(server_script);
            
            $.ajax({
              type: 'GET',
              url: server_script,
              dataType: 'json',
              error: error_callback,
              success: success_callback
      
            });
        }
     }
     
     
     ///////////////////////////////////////////////////////////////////////////////////////
     // Same Success and Error Callbacks for both AJAX calls. 
     ///////////////////////////////////////////////////////////////////////////////////////
     function success_callback(response) {
        console.log(response);
     
        var breeds = '';
        for ( var i = 0; i < response.length ; i++ ) {
        	breeds = breeds + response[i].breed_name + '<br>';
        }
        document.getElementById('mydiv').innerHTML = breeds;
     }
     
     function error_callback(req, status, err) {
        console.log("OOPS: " , req, status, err )
     }
      
      
     ///////////////////////////////////////////////////////////////////////////////////////
     // Event handler for 2nd button.
     // Serializes whole form to send to server. 
     ///////////////////////////////////////////////////////////////////////////////////////
      function ajax_call2() {
        
        if ( document.myform.animal_id.value != -1 ) {
          
            // serialize() is a JQuery function
            console.log( $('#form1').serialize() );
            
            $.ajax({
              type: 'GET',
              url: 'server.php',
              data: $('#form1').serialize(),
              dataType: 'json',
              error: error_callback,
              success: success_callback
      
            });
        }
     }
    
  </script>
</head>
<body>

<h4>AJAX Example</h4>

<form name="myform" id="form1">
  Text: <input type="text" name="x" value="">
  <br>
  Choice: <input type="checkbox" name="y" value="y">
  <br>
  <select name="animal_id">
    <option value="-1">Dummy Option</option>  
    <option value="1">dog</option> 
    <option value="2">cat</option>
    <option value="3">bat</option>
    <option value="4">rat</option>
  </select>
  <br><br>
  <button type="button" onclick="ajax_call1()">Ajax Call 1</button>
  <br><br>
  <button type="button" onclick="ajax_call2()">Ajax Call 2</button>
</form>

<br><br>

<div id="mydiv"></div>
</body>
</html>

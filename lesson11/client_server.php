<?
/*
This is just a very simple AJAX example that shows how you can use
the same server-side script to both generate a web page
and handle an AJAX call from that page for additional data.
*/
if ( $_GET['incoming'] ) {
  echo '{"payload" : "Hello!"}';
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>AJAX Example</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  
  <script type="text/javascript">
     
     function ajax_call() {
  
        $.ajax({
          type: 'GET',
          url: 'client_server.php?incoming=data',
          dataType: 'json',
          error: error_callback,
          success: success_callback
  
        });
     }
     
     
     function success_callback(response) {
        document.getElementById('mydiv').innerHTML = response.payload;
     }
     
     
     function error_callback(req, status, err) {
        console.log("OOPS: " , req, status, err )
     }
    
  </script>
</head>
<body>

<h4>Single File AJAX Example</h4>

<button type="button" onclick="ajax_call()">Ajax Call</button>

<br><br>

<div id="mydiv"></div>
</body>
</html>

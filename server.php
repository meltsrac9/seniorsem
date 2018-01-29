<?  
session_start();   // Always do at top of script if using sessions.
                   // Necessary for storing AND retrieving session data.


// Add this to browser address field to simulate AJAX call to this script
// server.php?previous_visits=0&geo=yes


//now instead of getting it from session, do a SELECT * from DB to generate menu
if ( isset($_GET['previous_visits']) && $_GET['previous_visits'] >= 0 ) {
    // The if statement shouldn't be necessary if the AJAX call ensures
    // that a valid previous_visits array index is submitted 
    var_dump($row);
    $query = "SELECT * FROM meltser_test WHERE ID = $row['ID']";
		$result = $mysqli->query($query);
      exit;
    
    if ( $_GET['geo'] == 'yes' ) {
      $result = file_get_contents("https://tools.keycdn.com/geo.json?host=".$visit_data['ip']); 
      $result = json_decode($result);
      
      // var_dump($result->data->geo); exit;
      
      $visit_data['geo'] = $result->data->geo;
    }
    
    echo json_encode($result);
    exit;
}
?>
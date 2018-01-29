<? require 'init.php';


	switch (key($_GET))  {

		case 'id':
			$id = $_GET['id'];
			$sql = "SELECT * FROM meltser_form WHERE id = " . $id;
			$result = lib::db_query($sql);
			$row = $result -> fetch_assoc();

			if($result != null){
				$data['status_code'] = 1;
				$data['data'] = [$row];
			}
			
			else{
				$data['status_code'] = 0;				
				$data['data'] = "";
			}
			
			echo json_encode($data);
			
			exit;
			break;
			
    case 'q':
      $q= $_GET['q'];
      if($q == null || $q == ""){
        $sql = "SELECT * FROM meltser_form";
        $result= lib::db_query($sql);
        
        while( $row = $result-> fetch_assoc()){
          $data['status_code']=1;
          $data['data']=[$row];
          echo json_encode($data);
        }
        }
      else{
        $sql="SELECT * FROM meltser_form WHERE name like '%$q%' OR email like '%$q%' OR paragraph like '%$q%'";
        $result= lib::db_query($sql);
        
        while( $row = $result-> fetch_assoc()){
          $data['status_code']=1;
          $data['data']=[$row];
          echo json_encode($data);
        }
      }
    
      exit;
      break;
    
    default:
    //goes into HTML
      break;
}
    
?>


<!DOCTYPE html>
<html>
<head>
  <title>HTML Form</title>
</head>
	<body>
		This is an API for a form. To use it use either id= followed by a potential id number to find an element with a matching primary key, or type a query string q= to find a match. 
		
		id value of 4, 10, 30, 34, 36, and 37 will return positive status codes.
	</body>
</html>

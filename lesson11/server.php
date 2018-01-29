<?  

$json_string = '[{"animal_id":1,"animal_type":"dog","animal_breeds":[{"breed_id":1,"breed_name":"Beagle"},{"breed_id":2,"breed_name":"Poodle"},{"breed_id":3,"breed_name":"Hound"},{"breed_id":4,"breed_name":"Collie"}]},{"animal_id":2,"animal_type":"cat","animal_breeds":[{"breed_id":1,"breed_name":"Tabby"},{"breed_id":2,"breed_name":"Calico"},{"breed_id":3,"breed_name":"Persian"}]},{"animal_id":3,"animal_type":"bat","animal_breeds":[{"breed_id":1,"breed_name":"Hoary"},{"breed_id":2,"breed_name":"Egyptian Fruit"},{"breed_id":3,"breed_name":"Spotted"},{"breed_id":4,"breed_name":"Leaf-nosed"}]},{"animal_id":4,"animal_type":"rat","animal_breeds":[{"breed_id":1,"breed_name":"Sewer"},{"breed_id":2,"breed_name":"Bilge"},{"breed_id":3,"breed_name":"Dirty"}]}]';

$json_data = json_decode($json_string);

/*
Look at these var_dumps in Browser Source Code View to better see the structure.


var_dump($json_data);                   // Array of animal objects
var_dump($json_data[0]);                // Animal object
var_dump($json_data[0]->animal_type);   // Animal object property
                                        // Since . is used for concatenation PHP uses -> for objects
                                        // $object->property
                                        // $object->method()
                                  
To simulate the AJAX response, call this script from a browser manually
and simulate the query string server.php?animal_id=1
*/

$submitted_animal_id = $_GET['animal_id'];

If ( $submitted_animal_id >= 1) {
  foreach ( $json_data as $animal_object ) {
    if ( $animal_object->animal_id == $submitted_animal_id) {
      echo json_encode($animal_object->animal_breeds);
      exit;
    }
  }
}

// This script does not return a web page.  Just a string of JSON data.
?>
<? 
/* 
This example uses a PHP function to dynamically generate menus.
It also shows how to dynamically deal with checkboxes.  
Radio buttons are similar, although there should be only one checked, not an array of checked things. 
*/

$animals = array( 'w'=>'wildebeest',
                  'a'=>'aardvark',
                  'b'=>'bandicoot',
                  'm'=>'muskox'
); 

$selected1 = '';
$selected2 = 'b';
$selected3 = array();
$selected4 = array('a','m');

$checked = array('dog','rat');
?>
<!DOCTYPE html>
<html>
<head>
  <title>PHP Menu-Generating Function</title>
</head>
<body>

  The menus below are all generated using the same PHP menu-generating function, but by calling the function in different ways.
  <br>
  See the source code.
  
  <br><br>
  <?= menu_from_assoc_array("menu_single1", $animals); ?>
  <br><br>
  <?= menu_from_assoc_array("menu_single2", $animals, '-Choose Animal-', $selected1); ?>
  <br><br>
  <?= menu_from_assoc_array("menu_single3", $animals, '-Choose Animal-', $selected2); ?>
  <br><br>
  <?= menu_from_assoc_array("menu_multiple1[]", $animals,'', $selected3, 'multiple'); ?>
  <br><br>
  <?= menu_from_assoc_array("menu_multiple2[]", $animals,'', $selected4, 'multiple' , 'onchange="do_something"'); ?>
  
  <br><br>
  
  Dog <input type="checkbox" name="animal[]" value="dog" <? if(in_array("dog",$checked)){echo 'checked="yes"';} ?> > 
  Cat <input type="checkbox" name="animal[]" value="cat" <? if(in_array("cat",$checked)){echo 'checked="yes"';} ?> >
  Rat <input type="checkbox" name="animal[]" value="rat" <? if(in_array("rat",$checked)){echo 'checked="yes"';} ?> > 
</body>
</html>


<? 
/* 
This function is also included in the class_lib.php file that's part of the larger examples in this lesson. 
This stand-alone example is provided to make it easier to see how it works.

Notice how the optional function parameters work in PHP!
*/

function menu_from_assoc_array($name, &$array, $dummy_option='', $selected='', $multiple='', $event_handler='') {
  
  // The $array keys become the menu's hidden values.  The $array values become the visible text on the menu.
  
  // The id doesn't need the [] but the menu name needs them for multiple selection menus.
  $id = str_replace(array('[',']'),'',$name); 
  
  $menu = "<select name=\"$name\" id=\"$id\" $multiple $event_handler >\n";
  
  if ($dummy_option) {
      // Option such as "Choose Item" for first item of single selection menu
      $menu .= "<option value=\"\" ";
      if ($default == '' ) {
          $menu .= "selected='yes'";
      }
      $menu .= ">$dummy_option</option>\n";
  }
  
   foreach ($array as $key=>$value) {
        $menu .= "<option value=\"$key\" ";
        if ( (!is_array($selected) && $key==$selected) || (is_array($selected) && in_array($key,$selected)) ) {
            $menu .= "selected='yes'";
        }
        $menu .= ">$value</option>\n";
   }
   $menu .= "</select>\n";
   return $menu;
} 

?>
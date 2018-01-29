<?
// Data Source for Dynamic HTML generation below.
// This is an associative array.
$animal_data = array(  'wildebeest'=>1,
                       'dog'=>0,
                       'aardvark'=>1,
                       'cat'=>0,
                       'bandicoot'=>1,
                       'bat'=>0,
                       'muskox'=>1, 
                       'rat'=>0
); 

/* 
PHP code above where the HTML page generation starts generally looks like other types 
of programming code you are used to dealing with.

But server-side scripting within the HTML page itself is somewhat of a different animal:)
The examples below generate an HTML UL and TABLE using two different coding styles.
You will see professionals mostly stick to the first style, regardless of whether PHP, JSP, C#, or whatever
is being used for scripting on the server.
*/
?>

<!DOCTYPE html>
<html>
<head>
  <title>Examples of PHP Coding Style</title>
  <style type="text/css">
  	 table, td, th {
         border-collapse: collapse;
         border: 1px solid black;
      }
  </style>
  
</head>
<body>

Below are two HTML structures built as raw HTML, with "embedded" PHP scripting.
<hr />
<br />

<ul>
  <? foreach ($animal_data as $name => $is_cool) { ?>
    <li><?=$name?>
      <? if( $is_cool ) { ?>
        <ul>
        	<li type="square" style="color:red;font-size:.75em;">Qualifies as Cool Animal Name</li>
        </ul>
      <? } ?>
    </li>
  <? } ?>
</ul>

<table width="" cellspacing="0" cellpadding="3">
    <? foreach ($animal_data as $name => $has_pic) { ?>
        <tr>
          <td width="100"><?=$name?></td>
          <td>
            <? if( $has_pic ) { ?>
              <img src="animal_pics/<?=$name?>.png" height="50"/>
            <? } else { ?>
               Sorry, Not Picture Worthy
            <? } ?>
          </td>
        </tr>
    <? } ?>
</table>

<br />
Below are the exact same HTML structures, but built basically as output to PHP scripts.
<hr />
<br />

<? 
echo "<ul>";
foreach ($animal_data as $name => $is_cool) { 
  echo "<li>$name";
  if( $is_cool ) { 
    echo "<ul>
           <li type=\"square\" style=\"color:red;font-size:.75em;\">Qualifies as Cool Animal Name</li>
         </ul>";
  } 
  echo "</li>";
} 
echo "</ul>";
?>


<?
echo "<table cellspacing=\"0\" cellpadding=\"3\">";
foreach ($animal_data as $name => $has_pic) { 
  echo "<tr>
          <td width=\"100\">$name</td>
          <td>";
  if( $has_pic ) { 
    echo "<img src=\"animal_pics/$name.png\" height=\"50\"/>";
  } 
  else { 
     echo "Sorry, Not Picture Worthy";
  }
  echo "  </td>
        </tr>";
}
echo "</table>";
?>






</body>
</html>

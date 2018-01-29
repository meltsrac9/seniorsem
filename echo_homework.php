<? $data = array_merge($_GET, $_POST); ?>

<!DOCTYPE html>
<html>
	<head>
		<title>PHP Homework 1</title>	
	</head>
	<body>
		Here's what you've submitted:<br><br>
		
    <? foreach ($data as $key=>$value) { ?>
        <tr>
          <td><?=$key?>:</td>
          <td>&nbsp;</td>
          <td><?=$value?></td>
					<br>
        </tr>
    <? } ?>
		<!--Hi Professor Knuckles! I initially tried initializing $data 
				as an array, and using value as itself a key value pair, in order
				to list multiple value entries, but that did not work :( I'm not sure
				how to otherwise get those multiple values to shwo up-->
		
	</body>
</html>
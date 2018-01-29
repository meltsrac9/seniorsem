<?
// $_GET and $_POST are built-in PHP SuperGlobals that handle those kind of form submissions, respectively.
//The line below merges both into one array so that this script will handle both GET and POST form submissions.

$submitted_form_data = array_merge($_GET,$_POST);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Echo of HTML Form Submission</title>
</head>
<body>
This script echos back the data in all <tt>name=value</tt> pairs submitted by any HTML form.
<hr />
<br />

<table width="" cellspacing="0" cellpadding="3">
    <tr>
      <th>Submitted Name</th>
      <td width="15">&nbsp;</td>
      <th>Submitted Value</th>
    </tr>
    
    <? foreach ($submitted_form_data as $key=>$value) { ?>
        <tr>
          <td><?=$key?></td>
          <td>&nbsp;</td>
          <td><?=$value?></td>
        </tr>
    <? } ?>
    
</table>

</body>
</html>

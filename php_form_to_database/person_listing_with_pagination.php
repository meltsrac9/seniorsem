<?
require 'init.php'; // database connection, etc


$query = "SELECT * FROM ". PEOPLE_TABLE ." WHERE 1 ";

// First create a new $listing object
// See the documentation at the top of the class for what all the parameters do
$listing = new pg_list($query, 'ppl_id', 'ppl_lastname', 'ASC', '', '', 1, 4, true, 3,'even_row_css','odd_row_css','highlight_css');

// Now add columns for the display

// The last parameter here makes this column into a link with the ppl_id embedded in the link.
$listing->add_column('ppl_lastname', 'Last Name','','','person_form.php?task=edit&ppl_id=%%%ppl_id%%%');

$listing->add_column('ppl_firstname', 'First Name');
$listing->add_column('ppl_email', 'Email');

// The following two columns apply custom formatting to the columns
// See the documentation at the top of the class for the difference
// Search the class for young_or_old and people_action_links to see how it works.
$listing->add_column('ppl_age', 'Status' , 'young_or_old');
$listing->add_column('', 'Actions','','','','',false,'','people_action_links');

// This gets it ready for calling get_html() for the list below
$listing->init_list();
 
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Listing of People Table With Pagination</title>
		
		<style type="text/css">
			/* styles for list object row highlighting */
			.even_row_css {
				 background-color:#DDDDDD;
				 font-size:10pt;
			}
			.odd_row_css {
				 background-color:#EEEEEE;
				 font-size:10pt;
			}
			.highlight_css {
				 background-color:#DDDDFF;
				 font-size:10pt;
			}
		</style>
		
	</head>
	<body>
		<?=$listing->get_html()?>
	</body>
</html>
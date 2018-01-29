This example is mostly identical to the php_form_to_database example.

This one adds the "edit" and "delete" functionality. 

In addition to the application logic and SQL statements for that, 
there are also two subtle additions that deal with special characters in the data.

First is addslashes() when preparing the data for the DB save/update.
SInce the SQL statements use ' characters, a ' in the data breaks the SQL.
Submit the original php_form_to_database example with a ' in the data and see what happens.

Second is htmlspecialchars() when preparing the data to re-populate the HTML form.
SInce HTML uses characters like " and < and > , those characters in the data can cause problems when the data goes back into the HTML.
Submit the original php_form_to_database example with a " and a < in the data.
Then click the edit option for that record and see what happens.



<?
session_start();   // Always do at top of script if using sessions.
                   // Necessary for storing AND retrieving session data.

define('DB_SERVER','localhost');
define('DB_USERNAME','csci488_fall17');
define('DB_PASSWORD','SeNSeMdb1');
define('DB_DATABASE','csci488_fall17');

define('meltser_form', 'meltser_form');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; 
    exit;
}

require_once 'class_lib.php'; 

session_start();
?>
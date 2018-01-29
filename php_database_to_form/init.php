<?
// Sample init file loading resources needed by multiple PHP pages in a Web Application.


/******************************************************************************************
Database Connection
******************************************************************************************/
define('DB_SERVER','localhost');
define('DB_USERNAME','csci488_fall17');
define('DB_PASSWORD','SeNSeMdb1');
define('DB_DATABASE','csci488_fall17');

$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}

/******************************************************************************************
Database Tables
******************************************************************************************/
define('PEOPLE_TABLE','knuckles_people');


/******************************************************************************************
Classes
******************************************************************************************/
require_once 'class_lib.php';  
// Wrapper for some utility functions that are useful globally.

require_once 'class_pageable_list.php';
// A class to facilitate fancy listings with pagination.

/******************************************************************************************
Native Sessions
******************************************************************************************/
session_start();


// No whitespace after the closing php tag because that generates script output.
?>
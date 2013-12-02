<?php
/* Welcome to GrandQA
 * This file constains all the credentials
 * for you system. Make sure they are correct! 
 * 
 * Before using the system, the Db credentials must be
 * correct, and you need also to define a username and password.
 * 
 */

// define the database srever name
$db = 'localhost';

// define the database user name

$dbuser = '';

// define the database password

$dbpass = '';

// define the database name. You can leave the default

$dbname = 'grandqa';

// define the user name  

$username = '';

// define the password

$password = '';


// answer to the curl of the db connection
if (isset($_POST['name']) && $_POST['name'] == 'qa-sys') {
    echo $db.','. $dbuser .','. $dbpass .','. $dbname;
    die;
}

// answer to the user call
if (isset($_POST['getuser']) && $_POST['getuser'] == '1') {
    echo $username .','.$password;
}

?>

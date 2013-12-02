<?php

// Inialize session
session_start();

        include 'Config.php';
               
        $user = $username;
        $pass = $password;
       
if($_POST['username'] == $user && $_POST['password']== $pass)
{
	$_SESSION['username'] = $_POST['username'];
    //If it's ok, move to secure page
	header('Location: index.php');
}
else 
{
    echo $user . $pass;
	header('Location: login_1.php');
}

?>
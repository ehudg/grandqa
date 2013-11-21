<?php
if (isset($_POST['name']) && $_POST['name'] == 'qa-sys') {
    echo 'localhost,root,ehudpassword,grandqa';
    die;
}

// user name and password
// modifey as you need
$username = 'user';
$password = 'wajamu11';

if (isset($_POST['getuser']) && $_POST['getuser'] == '1') {
    echo $username .','.$password;
}

?>

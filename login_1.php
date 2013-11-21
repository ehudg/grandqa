<?php
session_start();

// Check, if user is already login, then jump to secured page
if (isset($_SESSION['username'])) {
header('Location: user.php');
}

?>
<html>
<head></head>
<body>


<div align="center"><h1>Welcome to the QA zone</h1>
<form method="POST" action="login_2.php">
<table border="0">
<tr><td>Username</td><td>:</td><td><input type="text" name="username" size="20"></td></tr>
<tr><td>Password</td><td>:</td><td><input type="password" name="password" size="20"></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" value="Login"></td></tr>
</table>
</form>
</div>
</body>
</html>

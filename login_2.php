<?php

// Inialize session
session_start();

 function curPageURL() {
        $pageURL = 'http';
        
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
        $pageURL .= $_SERVER["SERVER_NAME"];
        }
        
        return $pageURL;
        }

        function getcreddentials() {
            $urlbase = curPageURL();
            $url=  $urlbase . '/ehudqa/Config.php'; 
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "getuser=1");
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_status != 200)
            {
                $error = curl_error($curl);
                echo $error . $info;
                die;
            }
           
            return $result;
        }
        
        $res = getcreddentials();
        $arr=explode(',',$res);
        $user = $arr[0];
        $pass = $arr[1];
       
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
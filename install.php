<?php
        include 'ChromePhp.php';
        
        //ChromePhp::log('Hello console!');
        
        
        // get the base url and retur  it to getdbconst() to connect to db
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

        function getdbconst() {
            $urlbase = curPageURL();
            $url=  $urlbase . '/ehudqa/Config.php'; 
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, "name=qa-sys");
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_status != 200)
            {
                $error = curl_error($curl);
                echo $error . $info;
                die;
            }
            
            $arr=explode(',',$result);                     
            $db_host=$arr[0];
            $db_user=$arr[1];
            $db_pass=$arr[2];
            $db_name=$arr[3];
            curl_close($curl);
            $connection_ =new mysqli($db_host, $db_user, $db_pass, $db_name);    
            return $connection_;
        }
        // global var
        $connection = getdbconst();        
        
        // Check if there's a connection to the DB
        function checkConn() {
            GLOBAL $connection;
            if ($connection->connect_errno) {   
                ChromePhp::log('connection to db failed');
                return false;                
            }        
            else {
                ChromePhp::log('connection to db worked!');
                return true;
            }
        }
        
     
        // get a table name, if the table exist retunrn true, else return false
        // called by checkTableexist()
        function TableExists($table) {
            GLOBAL $connection;
                        
            $res = $connection->query("SHOW TABLES LIKE '".$table."'");
            if(isset($res->num_rows)) {
                return $res->num_rows > 0 ? true : false;
            } else return false;
        }
        
       
        
        
        // check if all the tables exist
        function checkTablesexist() {
             
             $tablesArr = array(
                 0 => 'instructions',
                 1 => 'products',
                 2 => 'routine_test',
                 3 => 'tests',
                 4 => 'isdeleted'
             );
          
           for ($i = 0; $i<count($tablesArr); $i++) {
             if (!TableExists($tablesArr[$i])) {
                    ChromePhp::log('a table is missing');
                    return false;
             }
             else {
                 ChromePhp::log('all tables exist, continue!');
                 return true;
             }
           }
           
        }
        
        
        // if the tables do not exist, create them
        function createTables() {
            GLOBAL $connection;
            if (!checkConn()) {
                echo '<h1 style="text-align:center;font-size:3em;font-color:red;">There is no connection to the database.</h1><br /> <h3 style="text-align:center;"> Please  update Config.php file.</h3>';
                die;
            }
            else {
                $query_array = array(5);
                
                $query_array[0] = "CREATE TABLE IF NOT EXISTS instructions (
                    `name` varchar(20)  NOT NULL ,
                    `text` varchar(3000)  NOT NULL                    
                ) DEFAULT CHARSET=utf8";
                $query_array[1] = "CREATE TABLE IF NOT EXISTS products (
                    `PRODUCT_NAME` varchar(20)  NOT NULL ,                                       
                ) DEFAULT CHARSET=utf8";
                $query_array[2] = "CREATE TABLE IF NOT EXISTS routine_test (
                    `TASK` varchar(100)  NOT NULL ,
                    `TIME_SPAN` varchar(50)  NOT NULL,
                    `LAST_TEST` date
                ) DEFAULT CHARSET=utf8";
                $query_array[3] = "CREATE TABLE IF NOT EXISTS tests (
                    `index` int(255) unsigned NOT NULL auto_increment,
                    `PRODUCT_NAME` varchar(50)  NOT NULL ,  
                    `TITLE` varchar(2000)  NOT NULL ,
                    `RESULTS` varchar(3000)  NOT NULL,   
                    `NOTES` varchar(3000)  NOT NULL,   
                    `DATE` date
                ) DEFAULT CHARSET=utf8";
                $query_array[4] = "CREATE TABLE IF NOT EXISTS isdeleted (
                    `index` int(255) unsigned NOT NULL auto_increment,
                    `PRODUCT_NAME` varchar(50)  NOT NULL ,  
                    `TITLE` varchar(2000)  NOT NULL ,
                    `RESULTS` varchar(3000)  NOT NULL,   
                    `NOTES` varchar(3000)  NOT NULL,   
                    `DATE` date
                ) DEFAULT CHARSET=utf8";
                
                
                for ($i = 0; $i<count.$query_array; $i++) {
                    if(!$result = $connection->query($query_array[$i])){
                        die('There was an error running the query [' . $connection->error . ']');
                    }   
                }
                
                echo '<h1 style="text-align:center;font-size:3em;font-color:green;">all tables were created successfully</h1>';
                echo '<h2 style="text-align:center;">Go to <a href="index.php"> home page </a> </h2>';
            }
            
        }
        
        // called from index.php
        // check if the db is connect, if the tables exist.
        // if the tables dosnt exist, asume this is first entrance
        // create the tables and notify the user
        function install() {
            if (!checkConn()) {
                echo '<h1 style="text-align:center;font-size:3em;font-color:green;">Can\'t connect to Mysql database, please check your config </h1>';
                die;
            }
            
            if (!checkTablesexist()) {
                createTables();
            }
            
            // all is ok, continue as noraml!
            else {
                // do nothing
            }
        }
        
        
       
?>

<?php
        include 'ChromePhp.php';
        
        //ChromePhp::log('Hello console!');
        
        include 'Config.php';
        
        // global var
        $connection = new mysqli($db, $dbuser, $dbpass, $dbname);         
        
        
        //create the database
        function createdb($_db) {
            GLOBAL $connection;                       
                       
            if (mysqli_query($connection,"CREATE DATABASE '".$_db."'"))            {
                return true;
            }
            
            else            {
                return false;
            }
        }
        
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
                    `PRODUCT_NAME` varchar(20)  NOT NULL                                        
                ) DEFAULT CHARSET=utf8";
                $query_array[2] = "CREATE TABLE IF NOT EXISTS routine_tests (
                    `TASK` varchar(100)  NOT NULL ,
                    `TIME_SPAN` varchar(50)  NOT NULL,
                    `LAST_TEST` date
                ) DEFAULT CHARSET=utf8";
                $query_array[3] = "CREATE TABLE IF NOT EXISTS tests (
                    `index` int(255)  NOT NULL auto_increment,
                    `PRODUCT_NAME` varchar(50)  NOT NULL ,  
                    `TITLE` varchar(2000)  NOT NULL ,
                    `RESULTS` varchar(3000)  NOT NULL,   
                    `NOTES` varchar(3000)  NOT NULL,   
                    `DATE` date,
                    PRIMARY KEY (`index`)
                ) DEFAULT CHARSET=utf8";
                $query_array[4] = "CREATE TABLE IF NOT EXISTS isdeleted (
                    `index` int(255)  NOT NULL auto_increment,
                    `PRODUCT_NAME` varchar(50)  NOT NULL ,  
                    `TITLE` varchar(2000)  NOT NULL ,
                    `RESULTS` varchar(3000)  NOT NULL,   
                    `NOTES` varchar(3000)  NOT NULL,   
                    `DATE` date,
                     PRIMARY KEY (`index`)
                ) DEFAULT CHARSET=utf8";
                
                
                for ($i = 0; $i<count($query_array); $i++) {
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
            GLOBAL $dbname;
            // create the database
            createdb($dbname);
            
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

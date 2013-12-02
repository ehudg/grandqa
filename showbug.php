<?php
        // this const is handels by function creteHotfile

        $PORTION_CONST1 = <<<'EOT'
           // $("#addcbButton").click(function(){	
               function addCb(buttonid) {
                
                var parent = $('#' + buttonid).parent().attr('id');                     
                
                console.log('parent ' + parent);

                // find number of checkboxes in the parent span
                var totalCheckboxes = $('#'+parent).find('input:checkbox').length  
                
                console.log('total cb number is ' + totalCheckboxes);
                    
                switch(true)
                {
                case (totalCheckboxes >= 5):
                alert ('Only 5 checkboxes can be created.');
                break;                
                case (totalCheckboxes > 0):
                  // get last cb id and add 1 to new cb
                  //var id = $('#'+parent).children().length + 1;
                  var id = totalCheckboxes + 1;                  
                  $('#'+parent).append('<input type="checkbox" id="'+parent+'_cb_'+id+'" /> &nbsp; <input type="text" id="'+parent+'_text_'+id+'" />');
                  break;
                case (totalCheckboxes == 0):
                  // There are no checkboxes  
                  console.log('no cb, adding first');
                  $('#'+parent).append('<input type="checkbox" id="'+parent+'_cb_1" /> &nbsp; <input type="text" id="'+parent+'_text_1" />');                  
                  break;                
                }
               }
               
               function deletePortion(buttonid) {
                $('#' + buttonid).remove();
                console.log(buttonid + ' was deleted');
               }
           // });
EOT;
        $PORTION_CONST2 = <<<'EOT'
            
              var $container = $("#PNAME_portion_XXX_hot_XXX");                     
              var $parent = $container.parent();
              var autosaveNotification;
              $container.handsontable({
                startRows: 6,
                startCols: 6,
                colWidths: [200, 200, 200, 2000, 200, 200],
                manualColumnResize: true,                            
                rowHeaders: true,
                colHeaders: true,
                minSpareCols: 0,
                minSpareRows: 4,
                contextMenu: true,
                afterChange: function (change, source) {
                  if (source === "loadData") {
                    return; //dont save this change
                  }				
                }
              });
              var handsontabletestInstruction = $container.data("handsontable");  
EOT;
        
        //----------------------------------------HELPERS FUNCTIONS-------------------------------//
         // connect to db
        
        // global var
        include 'Config.php';
        
        // global var
        $connection = new mysqli($db, $dbuser, $dbpass, $dbname);     
        
        // generic function to creatd the 3 products ddl's
        function showproduct($ddlname) { 
           global $connection;

           //$mysqli = new mysqli("localhost", "php", 'Antigua', "ehudqa");            

           $query1 = "SELECT * FROM `products`";                
           if(!$result = $connection->query($query1)){
               die('There was an error running the query [' . $connection->error . ']');
           }                
           $Products = array(); // Will store all the products names                
           while($row = $result->fetch_assoc())
           {
               $Products[] = $row['PRODUCT_NAME'];
           } 

           //$mysqli->close();               

           $html = array();
           for ($i=1;$i<4;$i++) {
               if ($i==1) { // first one should have a number
                   $html[$i] = '<select  name="product">';
               }
               else {
                   $html[$i] = '<select  name="'.$ddlname.$i.'">';  
               }         

                foreach ($Products as $option => $name) {
                    $html[$i] .= '<option value='.$name.'>'.$name.'</option>';
                }
                $html[$i] .= '</select>';

                $html[$i] .= '-s-';

           }
           $finalhtml = implode('',$html);
           echo  $finalhtml;
        }
        
        //----------------------------------------newTests-----------------------------------------//
        
      
        
        
        //----------------------------------------manageProducts----------------------------------//
        function addProduct($prd) {
                global $connection;
                //$mysqli = new mysqli("localhost", "php", 'Antigua', "ehudqa");   
                $query1 = "INSERT INTO `products`(`PRODUCT_NAME`) VALUES ('".$prd ."')";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }   
            }
        
        //----------------------------------------testTypes---------------------------------------//
        // Show tests instructions for specefiec product
             function testInstroction($pname) {
                 global $connection;
                //$mysqli = new mysqli("localhost", "php", 'Antigua', "ehudqa");            
                            
                $query1 = "SELECT `text` FROM `instructions` WHERE `name`='".$pname."';";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }                
                $inst ='';// Will store all the products names                
                while($row = $result->fetch_assoc())
                {
                    $inst = $row['text'];
                } 
                //$mysqli->close();    
                if ($inst == '') { // if there are no test instructions
                    echo json_encode('null');
                }
                else {                
                    $arr =  Array();
                    
                    $rowarray = explode('-r-', $inst);

                    $notesTable = Array();

                    for ($i=0;$i<count($rowarray);$i++) {
                        $arr[$i] = explode('-c-',$rowarray[$i]);  
                            for ($j=0;$j<count($arr[$i]);$j++) {
                                $notesTable[$i][$j] = $arr[$i][$j] ;                               
                            }
                    }   
                    echo json_encode($notesTable);
                }
            }
            
             // first delete the row, then insert it
            function updateInstructions($jsondata) {
                global $connection;
                
                $res = json_decode($jsondata);
               
               $prd = $res->{'name'}; 
               $text = $res->{'text'};     
              
              // handle the text               
                 $newVal = '';                                
                 for ($r = 0; $r < count($text); $r++) { // read each row                   
                    for ($c = 0; $c < count($text[$r]); $c++) {  // read each cell in $r row    
                      $newVal .= $text[$r][$c].'-c-';  // split each cell with -c-  
                    }
                    $newVal .='-r-'; // split each row with -r-
                 }
               $newVal2 = str_replace("'", "\'", $newVal);           
               
               $textinstruvtions = $newVal2;
               
               $query1 = "DELETE FROM `instructions` WHERE `name`='".$prd."';";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                } 
                
                $query2 = "INSERT INTO `instructions`(`name`, `text`) VALUES ('".$prd."','".$textinstruvtions."')";                
                if(!$result = $connection->query($query2)){
                    die('There was an error running the query [' . $connection->error . ']');
                }  
              // $mysqli->close();               
               $out = array(
                    'result' => 'ok'
                  );
                  echo json_encode($out);              
            }
            
            // create a hot.js file that with the proper id's and return ok when done
            function creteHotfile($sender, $id, $pname) {
                // take the const $PORTION_CONST
                // change the id numbers. PNAME = $pname XXX = $id
                // re create hot.js file
                
                // clear the file
                file_put_contents('hot.js', '');
                
                GLOBAL $PORTION_CONST1;
                GLOBAL $PORTION_CONST2;
                $tempconst = str_replace('PNAME', $pname, $PORTION_CONST2);
                $hotScript = str_replace('XXX', $id, $tempconst);        
               
                    if (file_put_contents('hot.js', $PORTION_CONST1 . $hotScript)) {                    
                        echo 'ok';
                    }                        
                    else  {                    
                        echo 'error writing to file';
                    }     
                
            }
        
        //----------------------------------------lastTests---------------------------------------//
        
        // generate a report per test by it's index, and return it to the div
            function generatReoprt($id) {               
                global $connection;                   
                              
                $query1 = "SELECT * FROM `tests` WHERE `index`='".$id."'";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }                
                $testResult = array(); // Will store all the products names                
                while($row = $result->fetch_assoc())
                {
                    $testResult[] = $row;                    
                } 
                
                // create a table from the NOTES, and retun it as json ---> json_encode($arr);
                $arr =  Array();
                // create a table from the NOTES, and retun it as json ---> json_encode($arr);
                $rowarray = explode('-r-', $testResult[0]['NOTES']);
                
                $notesTable = '<table cellpadding="10px"  style="text-align:center;border-width:1px;border-style:solid;"><tr>';
                
                for ($i=0;$i<count($rowarray);$i++) {
                    $arr[$i] = explode('-c-',$rowarray[$i]);  
                        for ($j=0;$j<count($arr[$i]);$j++) {
                            if ($arr[$i][$j] != '') {
                                $notesTable .= '<td >' . $arr[$i][$j] . '</td>';
                            }
                        }
                    $notesTable .= '</tr>' ;                    
                }     
                
                $notesTable .= '</table>';
                
                 echo '                
                <div  style="background-color:#006995;text-align:right;"> 
                    <img src="images/x.jpg" onClick="closeReport()" />;
                </div>
                <h3 align="center">Show report</h3>
                <h4 align="center">Test summery: '.$testResult[0]['TITLE'].' </h4>
                <table class="myOtherTable" align="center">
                    <tr><th>Product</th><th>Notes</th><th>Results</th><th>Componenets</th><th>Date</th></tr>
                ';
                 
                echo '<tr>';                
                echo '<td>'. $testResult[0]['PRODUCT_NAME'] . '</td>';                
                //echo '<td>'. $testResult[0]['NOTES'] . '</td>';
                echo '<td>'.$notesTable.'</span></td>';
                echo '<td>'. $testResult[0]['RESULTS'] . '</td>';
                echo '<td>'. $testResult[0]['COMPONENTS'] . '</td>';
                echo '<td>'. $testResult[0]['DATE'] . '</td>';                
                echo '</tr>';
                
                echo '</table>';                
               
                
            }
            
            // returns html according to user choice, ddl or date pickers
            function showproductfilter($prd_or_date) { 
               global $connection;
               
               if ($prd_or_date == 'prd') {
               //$mysqli = new mysqli("localhost", "php", 'Antigua', "ehudqa");            
                             
               $query1 = "SELECT * FROM `products`";                
               if(!$result = $connection->query($query1)){
                   die('There was an error running the query [' . $connection->error . ']');
               }                
               $Products = array(); // Will store all the products names                
               while($row = $result->fetch_assoc())
               {
                   $Products[] = $row['PRODUCT_NAME'];
               } 

               //$mysqli->close();               
               
               $html = '';
               
                       $html .= '<select id="filter_product" name="filter_product">';  
                            

                    foreach ($Products as $option => $name) {
                        $html .= '<option value='.$name.'>'.$name.'</option>';
                    }
                    $html .= '</select>';
                    $html .= '&nbsp; ';
               return  $html;
               }
               
               elseif ($prd_or_date == 'date') { // show date pickers
                 
                   $html = 'Please type dates YYYY-MM-DD: From <input type="textbox id="startdate" /> To <input type="textbox" id="enddate" />';
                   return $html;
               }
            }
        
        //----------------------------------------routine-----------------------------------------//
        
        // run when check box in routine tasks is checked
            function taskDone ($name) {
                global $connection;                           
                
                $today = date('Y-m-j');
                $query = "update `routine_tests` set `LAST_TEST`='".$today."' WHERE `TASK`= '". $name."'"; 

                $result = mysqli_query($connection, $query);
                if ( false===$result ) {
                  printf("error: %s\n", mysqli_error($connection));
                }       
            }
            
             // run in every page load, create a full table
            function checkRoutinetasks () {
                global $connection;
                // select last test date for every task
                //$mysqli = new mysqli("localhost", "php", 'Antigua', "ehudqa");            
                             
                $query1 = "SELECT * FROM `routine_tests`";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }                
                $dates = array(); // Will store all the last tests dates             
                while($row = $result->fetch_assoc())
                {
                    $dates[] = $row;
                } 

                //$mysqli->close();
                // current date
                $today1 = "'" .  date('Y-m-d') ."'";
                echo '<table id="routinetable" style=" border-collapse:collapse;">
                        <tr>
                            <th>
                                New task name
                            </th>
                            <th>
                               Select timing 
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                        <tr style=" border-bottom: dashed ;">
                            <td>
                                <input type="text" name="addnewtask" />
                            </td>
                            <td>
                               <select name="selectroutinetime">
                                   <option value="day"> Day </option>
                                   <option value="week"> Week </option>
                                   <option value="month"> Month </option>
                               </select>
                            </td>
                            <td colspan="2">
                                <input type="button" onclick="addRoutineTask(' .$today1. ')" value="Add task" /> 
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Task
                            </th>
                            <th>
                               timing 
                            </th>
                            <th>
                                Redo?
                            </th>
                        </tr> ';
                // check every task, if it's out of date. If true, change the <tr> of the table
                for ($i=0;$i<count($dates);$i++) {
                    // last test date
                    $date1 = $dates[$i]['LAST_TEST'];                    
                    // this test time span
                    $span = $dates[$i]['TIME_SPAN'];

                    // convert to miliseconds
                    $date = strtotime($date1); 
                    $lastday = strtotime('-1 day');
                    $lastweek = strtotime('-1 week');
                    $lastmonth = strtotime('-1 month');
                    // month: 2628000000
                    // week :  604800000
                    // day:        86400                    
                    
                    switch ($span) {
                        case 'day':
                            if ($date < $lastday) { // less then a day passed
                                echo '<tr class="rowselected" rowid="'.$dates[$i]['TASK'].'" >';                                        
                            } 
                            else {
                                echo '<tr rowid="'.$dates[$i]['TASK'].'">';     
                            }
                            break;
                        case 'week':
                            if ($date < $lastweek) { // less then a week passed
                                echo '<tr class="rowselected" rowid="'.$dates[$i]['TASK'].'" >';                                        
                            } 
                            else {
                                echo '<tr rowid="'.$dates[$i]['TASK'].'">';     
                            }
                            break;
                        case 'month':
                            if ($date < $lastmonth) { // less then a month passed
                                echo '<tr class="rowselected" rowid="'.$dates[$i]['TASK'].'" >';                                        
                            } 
                            else {
                                echo '<tr rowid="'.$dates[$i]['TASK'].'">';     
                            }
                            break;                                
                    }           
                    echo '    
                        
                            
                            <td>'.$dates[$i]['TASK'].' </td>
                            <td>'.$span.' </td>
                            <td><input type="checkbox" id="ch_sanity" onClick="routineTaskdone(this)" /> </td>
                            <td> <input type="button" onClick="delete_routinetask(this.name)" value="delete" name="' . $dates[$i]['TASK'] . '" /></td>    
                        </tr>                            
                        ';                             
                }
                echo '</table>-s-';
            }
            
            
            // delete a routine task
            function deleteroutinetask($taskname) {
                global $connection;           
                if (mysqli_connect_errno()) {
                    echo 'Connect failed: %s\n' . mysqli_connect_error();
                    exit();
                }                
                $query1 = "DELETE FROM ehudqa.routine_tests WHERE `TASK` = '" . $taskname ."'";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }                  
            }
            
            // Add new routine task
            function addnewtask($taskname,$taskspan, $lasttime) {
                global $connection;                 
                $query = "INSERT INTO `ehudqa`.`routine_tests` (`TASK`, `TIME_SPAN`, `LAST_TEST`) VALUES ('$taskname', '$taskspan', '$lasttime')"; 
               
               $result = mysqli_query($connection, $query);
               if ( false===$result ) {
                 printf("error: %s\n", mysqli_error($connection));
               }
               else {
                 echo 'New task added.';                   
               }
                
            }
             
            
           
            
             // DO ACTIONS ACCORDING TO THE DATA SENT-------------------------------------------
            
            
            // add a product
            if (isset($_GET['addproduct'])) {
                addProduct($_GET['addproduct']);                
            }
            
            // Add new routine task
            if (isset($_GET['addnewtask'])) {
                addnewtask($_GET['taskname'], $_GET['timespan'], $_GET['lasttime']);
            }
            
            // return a product ddl
            if (isset($_GET['getproductsddl'])) {
                showproduct($_GET['getproductsddl']);
            }
            
            // generate the routine tasks table in every page load
            if (isset($_GET['routine'])) {
                checkRoutinetasks();
            }
            
            if (isset($_GET['deleteRoutine'])) {
                deleteroutinetask($_GET['deleteRoutine']);
            }
            
            // update a routine test was done
            if (isset($_GET['taskdone'])) {
                taskDone($_GET['taskdone']);
            }
            
            // change tests instructions
            if (isset($_POST['testInstructions'])) {
                updateInstructions($_POST['testInstructions']);
            }
            
            // show test instruction
            if(isset($_GET['showInstraction'])) {
                testInstroction($_GET['showInstraction']);
            }
            
            
            // return html of the report back to index.php
            if (isset($_GET['generate'])) {
                $res = generatReoprt($_GET['generate']);
                echo $res;
                // prevent showing the rest of this code
                die;
            }
            
            
            // move test to 'isdeleted' table        
            if (isset($_GET['delete'])) { 
                global $connection;
                
                $query_insert = "INSERT INTO `isdeleted` SELECT * FROM `tests` WHERE `index`='".$_GET['delete']."' ";
                if(!$result = $connection->query($query_insert)){
                    die('There was an error running the query [' . $connection->error . ']');
                }   
                
                
                $query_delete = "DELETE FROM ehudqa.tests WHERE `index` = '" . $_GET['delete'] ."'";                
                if(!$result1 = $connection->query($query_delete)){
                    die('There was an error running the query [' . $connection->error . ']');
                } 
                 
            }
            
            
            // delete a product
            if (isset($_GET['deleteproduct'])) {                
                global $connection;           
                if (mysqli_connect_errno()) {
                    echo 'Connect failed: %s\n' . mysqli_connect_error();
                    exit();
                }                
                $query1 = "DELETE FROM ehudqa.products WHERE `PRODUCT_NAME` = '" . $_GET['deleteproduct'] ."'";                
                if(!$result = $connection->query($query1)){
                    die('There was an error running the query [' . $connection->error . ']');
                }   
            }
            
            // returns the tests data
            function getData($query) {
                global $connection;                
                             
                //$query1 = $query;                
                if(!$result = $connection->query($query)){ //"SELECT * FROM `tests`"
                    die('There was an error running the query [' . $connection->error . ']');
                }                
                $Products = array(); // Will store all the products names                
                while($row = $result->fetch_assoc())
                {
                    $Products[] = $row;                    
                } 
                
                return $Products;
            }  
            
            
            // Return html code of filters according to user choice
            if (isset($_GET['filters_type'])) {
                echo showproductfilter($_GET['filters_type']);
            }
            
            // show the last tests TODO show only 10 tests at a time
            if (isset($_GET['lastTest'])) {
                $data[] = array();
                
                switch (($_GET['lastTest'])) {
                    case 'no_filter':
                        $data = getData('SELECT * 
                                        FROM  `tests` 
                                        ORDER BY 1 DESC 
                                        LIMIT 10');   
                        break;
                    case 'filter_by_prd':
                        $data = getData("SELECT * FROM `tests` WHERE `PRODUCT_NAME` = '".$_GET['prdname']."'");
                        break;
                    case 'filter_by_date':
                        $data = getData("SELECT * FROM `tests` 
                                WHERE DATE(  `DATE` ) >=  '".$_GET['s_date']."'
                                AND DATE(  `DATE` ) <= '".$_GET['d_date']."'");                        
                        break;
                    default:
                        // act like no_filter
                  $data = getData('SELECT * 
                                        FROM  `tests` 
                                        ORDER BY 1 DESC 
                                        LIMIT 10');   
                        
                }
                
                
                
                echo '
                    <h3>Show Last Tests</h3>
                
                <div id="filters_wrap"  style="border-bottom: dotted; line-height:200%">
                    Filter by <select onchange="showfilters()" id="filters_select"> 
                    <option value="none" selected="selected">Last 10</option> 
                    <option value="prd">Product</option> 
                    <option value="date">Date</option> </select> 
                    
                    <span id="filters" > <!--wil host the ajax call for filters --> </span>
                    <input type="button" onclick="showFilterdresults()" value="Show Results" />
                </div>
                
                <table id="resTable">
                    <tr><th>ID</th><th>Product</th><th>Test summery</th><th>Date</th><th>Delete row</th></tr>

                ';
                for ($i=0;$i<count($data);$i++) {
                    echo '<tr>';
                    echo '<td>'. $data[$i]['index'] . '</td>';
                    echo '<td>'. $data[$i]['PRODUCT_NAME'] . '</td>';
                    echo '<td>'. $data[$i]['TITLE'] . '</td>';
                    echo '<td>'. $data[$i]['DATE'] . '</td>';
                    echo '<td> <input type="button" onClick="delete_row('. $data[$i]['index'] .')" value="delete" name="' . $data[$i]['index'] . '" /></td>
                          <td> <input type="button" onClick="generatereport('.$data[$i]['index'].')" value="Generatereport" /> <td/>
                    ';
                    echo '</tr>';
                }
                echo '</table>';
                
               // echo '-s-'; // the delimiter
                    
            }
            
            // return data to the excel plugin in reports. Keep this structue! should alwayas return more then 1 array
            /*if (isset($_GET['excel'])) {
               $out = array(
                'cars' => array (
                    array ('manufacturer' => 'aaa','year'=> 'bbb','price' =>  'ccc'),
                    array ('manufacturer' => 'QQQ','year'=> 'WWW','price' =>  'EEE')
                    )
              );
              echo json_encode($out);
            }*/
            
            // get and save the data from the excel plugin ABORT THIS
            if (isset($_POST['exceldata'])) {               
                 $newVal = '';                                
                 for ($r = 0; $r < count($_POST['exceldata']); $r++) { // read each row                   
                    for ($c = 0; $c < count($_POST['exceldata'][$r]); $c++) {  // read each cell in $r row    
                      $newVal .= $_POST['exceldata'][$r][$c].',';   
                    }
                    $newVal .='-s-';
                 }
                 file_put_contents('1.txt',$newVal);
                 $out = array(
                    'result' => 'ok'
                  );
                  echo json_encode($out);
            }
            
            // catch test results form submition
            if (isset($_POST['testData'])) {// Insert new test details
               global $connection;
               $res = json_decode($_POST['testData']);
               
               $prd = $res->{'PRODUCT_NAME'}; 
               $temp_title = $res->{'TITLE'}; 
               $results = $res->{'RESULTS'};               
               $d = $res->{'DATE'}; 
               
               // handle the , ' chars in the title
               $title = str_replace("'", "\'", $temp_title);  
                 
              // handle the NOTES
               $excel = $res->{'NOTES'};
                 $newVal = '';                                
                 for ($r = 0; $r < count($excel); $r++) { // read each row                   
                    for ($c = 0; $c < count($excel[$r]); $c++) {  // read each cell in $r row    
                      $newVal .= $excel[$r][$c].'-c-';  // split each cell with -c-  
                    }
                    $newVal .='-r-'; // split each row with -r-
                 }
               $newVal2 = str_replace("'", "\'", $newVal);           
               
               $notes = $newVal2;
               // NOTES IS THE EXCEL PLUGIN TODO***
               $query = "INSERT INTO `tests` (`PRODUCT_NAME`,`TITLE`, `RESULTS`, `NOTES`, `DATE`) VALUES ('$prd', '$title', '$results', '$notes', '$d')"; 
               
               $result = mysqli_query($connection, $query);
               if ( false===$result ) {
                 printf("error: %s\n", mysqli_error($connection));
               }
               else {
                 //echo 'New test inserted. <br /><br />';                   
               }

               //$mysqli->close();
               
               $out = array(
                    'result' => 'ok'
                  );
                  echo json_encode($out);
              
                
            }
            
            // get a call for convertion check
            if (isset($_GET['field']) && isset($_GET['param'])) {
               echo wasConverted($_GET['param'], $_GET['field']);
            }
            
            // get the call for creating new hot.js file
            if (isset($_GET['createhotfile'])) {
                creteHotfile($_GET['createhotfile'], $_GET['id'], $_GET['pname']);
            }
            
            $connection->close();
              
?>
          
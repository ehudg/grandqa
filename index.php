<?php
// Inialize session
session_start();
// Check, if username session is NOT set then this page will jump to login page
if (!isset($_SESSION['username']))
{
    header('Location: login_1.php');
}
error_reporting(E_ERROR | E_PARSE);
include 'install.php';

// check if all connecions are ok
install();
?>

<html>
    <head>       
        <title> The coolest QA system ever </title>
        
        <link rel="icon"   type="image/png"  href="images/qa-logo-W.ico">
        
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="chosen/chosen.jquery.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/calendarDateInput.js"></script>
        
        <link type="text/css" href="css/jquery.datepick.css" rel="stylesheet">
        <script type="text/javascript" src="js/js.js"></script>
        
        <!-- Codes by HTML.am -->
        <!-- Start Styles. Move the 'style' tags and everything between them to between the 'head' tags -->
        <link rel="stylesheet" type="text/css" href="css/css.css">
        <link rel="stylesheet" href="chosen/chosen.css" />
        <!-- drag n drop scripts-->
        <script src="js/jquery.ui.core.js"></script>
	<script src="js/jquery.ui.widget.js"></script>
	<script src="js/jquery.ui.mouse.js"></script>
	<script src="js/jquery.ui.draggable.js"></script>
        
        <!-- excel like plugin scripts-->        	
        <script data-jsfiddle="common" src="js/jquery.handsontable.full.js"></script>
        <link data-jsfiddle="common" rel="stylesheet" media="screen" href="css/jquery.handsontable.full.css">
        
    </head>
   
    <body>
        <div style="float:none;" >    
            <!--  the last tests appears first-->
            <span id="draggable3" class="ui-widget-content">
               <div onClick="allowdrag('draggable3')" class="drgtop" >Drag me!</div>
                <span id="lastTests" >

                </span>
           </span>
        </div>
        
        <div style="float:left;"> <!-- left part of doc -->
         <span id="draggable1" class="ui-widget-content">
            <div onClick="allowdrag('draggable')" class="drgtop" >Drag me!</div>        
        
            <span id="newTests">
                <h3>Insert Test Data</h3>
                <form  id="testForm" action="index.php" method="post">
                        <input  type="hidden" name="current_date" value='<?php echo date("Y-m-d") ?>' <br />
                        Select product:                         
                        <span id="wrap_product"> </span>              
                        <!--
                        <div class="side-by-side clearfix">
                            Select components:
                            <select data-placeholder="Choose a Country..." style="width:350px;" multiple tabindex="3" id="componenets-select">
                                <option>DefaultTab</option>
                                <option>Delta</option>
                                <option>WebCake</option>
                                <option>Yontoo</option>
                        </select>
                        <input type="hidden" class="selectvalue" name="selectvalue" value="no erros" />
                        </div>
                        -->
                        <br />
                        <table id="nweteststable" >
                            <tr>
                                <td >
                                    Test summery: <br />
                                    <textarea name="title" cols=40 rows=5 name="area"></textarea>
                                    <br />
                                </td>
                            </tr>                          
                            <tr>
                                <td>
                                    Test details:
                                    <div id="example1"></div>                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <br /> <br />
                                    <input style="width:100%;" name="save" type="button" value="Done" onClick="submitTest()"  />    <br />       
                                    <input name="clearForm" type="button" value="Clear form" onClick="clearTestForm()"  />
                                    
                                    <script>
                                      // handsontable excel like script
                                      var $container = $("#example1");
                                      var $console = $("#exampleConsole");
                                      var $parent = $container.parent();
                                      var autosaveNotification;
                                      $container.handsontable({
                                        startRows: 4,
                                        startCols: 5,
                                        colWidths: [150, 150, 150,150,150],  
                                        manualColumnResize: true,
                                        rowHeaders: true,
                                        colHeaders: true,
                                         minSpareCols: 4,
                                         minSpareRows: 4,
                                        contextMenu: true,
                                        afterChange: function (change, source) {
                                          if (source === 'loadData') {
                                            return; //don't save this change
                                          }				
                                        }
                                      });
                                      var handsontable = $container.data('handsontable');
                                    </script>
                                </td>
                            </tr>
                        </table>     
                  </form>                    
            </span>
         </span>
        
        <span id="draggable2" class="ui-widget-content">
            <div onClick="allowdrag('draggable2')" class="drgtop" >Drag me!</div>
            <span id="manageProducts">
                <h3>Manage Products</h3>
                Add product: <input type="textbox" id="addProduct" /> &nbsp; <input type="button" onclick="addprd()" value="Add" /> <br />
                Delete product : 
                        <span id="wrap_product2"> </span>      
                        <input type="button" onclick="delprd()" value="Delete" /> <br /> 
            </span>
        </span>
            
      
    </div> <!-- end of left part -->  
    
    <div style="float:right;"> <!-- right part of doc-->
       
        <span id="draggable4" class="ui-widget-content">
            <div onClick="allowdrag('draggable4')" class="drgtop" >Drag me!</div>
            <!-- all the routine tasks -->
            <span id="routine">
                
            </span>   
        </span>
        
        <br />        
      
  
        
    </div> <!-- end of right part-->
    
    <div style="clear:both;"></div>
    
     <span id="draggable5" class="ui-widget-content">      
      <div onClick="allowdrag('draggable5')" class="drgtop" >Drag me!</div>      
        <table id="testTypes">            
            <tr>
                <td width="1200px">
                    <h4>Tests Instructions</h4>
                     <!-- ddl with all the products-->  
                     Choose product: &nbsp;
                    <span id="wrap_product3"> </span> 
                    <!--
                    <input type="button" onclick="showTestInstructionNEW()" value="Show instructions" /> 
                    
                    <!-- enable adding test portions -->
                    <!--<input type="button" id="addportion" disabled onclick="AddPortion()" value="Add test portion" /> 
                    <br />    <br />                   
                    
                    <span id="testsinst" > <!-- shows the tests instructions-->
                       
                    <!--</span>
                    
                     <br />
                     <input style="margin-left:250px;width:200px" onClick="stopeditNEW()" type="button"  id="stopbutton" value="Save changes" />
                     
                     -->
                     
                     <!-- old -->
                     <input type="button" onclick="showTestInstruction()" value="Show instructions">
                     	<span id="testsinst" contenteditable="true"> <!-- shows the tests instructions-->
                        <div id="hot3" class="handsontable">
						
						</div>
                    </span>
					 
					 <script>
                          // handsontable excel like script
                          var $container = $("#hot3");
                          var $console = $("#exampleConsole");
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
                              if (source === 'loadData') {
                                return; //don't save this change
                              }				
                            }
                          });
                          var handsontabletestInstruction = $container.data('handsontable');  
                     </script>
			<br />		 
					 <input style="margin-left:250px;width:200px" onclick="stopedit()" type="button" id="stopbutton" value="Save changes">
                </td>                
            </tr>
        </table>
  </span>   
    </body>    
</html>
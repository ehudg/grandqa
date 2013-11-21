       // template of a test instructions portion
       // is called from the function showTestInstruction()
       // XXX is the pordion id number
       // AAA is the excel id number 
       var portion_const =          
       ' <div  id="PNAME_portion_XXX">  <hr />      '+
       ' <input id="PNAME_portion_XXX_title" type="text" placeholder="Choose portion title" /> <img  style="float:right;cursor:pointer;" src="images/deleteportion.png" onclick="deletePortion(this.parentNode.id)" alt="Remove portion" />'+
       ' <br />'+
       ' <span id="PNAME_portion_XXX_cbspan" >'+
       '     <input type="button" value="Add checkbox" id="addcbButtonXXX" onClick="addCb(this.id)" />'+ //onClick="addCb(this.id, PNAME)"
       ' </span>'+
       ' <br />'+
       ' <div id="PNAME_portion_XXX_hot_AAA" >'+
       
       ' </div> <br />'+
       '</div>';
       
 
        //----------------------------------------HELPERS FUNCTIONS-------------------------------//
        
        // refresh only the products ddl one at a time
        function refreshProductsDdl() {
                        xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {
                                // take the xmlhttp.responseText and spilt it to the relevant div
                                var tempres = xmlhttp.responseText;
                                var res = tempres.split("-s-");
                              
                                document.getElementById('wrap_product').innerHTML=res[0];
                               
                                document.getElementById('wrap_product2').innerHTML=res[1];

                                document.getElementById('wrap_product3').innerHTML=res[2];  
                                  
                              }
                        }
                      xmlhttp.open("GET","showbug.php?getproductsddl=product",true);                    
                      xmlhttp.send();
        }
        
        function sendAjax(qstring, callback) {// callback contain the name of the function to run after sucess
            xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {
                                // document.getElementById("lastTests").innerHTML=xmlhttp.responseText;
                                if (callback){
                                       window[callback]();
                                }
                              }
                        }
                      xmlhttp.open("GET","showbug.php?" + qstring,true);                    
                      xmlhttp.send();
            
        }
        
          // send 2 params to php, 1 for routine tasks the other for tests results
        function showTables () {
            xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {                                  
                              // take the xmlhttp.responseText and spilt it to the relevant div
                              var tempres = xmlhttp.responseText;
                              var res = tempres.split("-s-");
                               
                               document.getElementById('wrap_product').innerHTML=res[0];
                               
                               document.getElementById('wrap_product2').innerHTML=res[1];
                               
                               document.getElementById('wrap_product3').innerHTML=res[2];  
                               
                               document.getElementById("routine").innerHTML=res[3];
                              
                               document.getElementById("lastTests").innerHTML=res[4];
                              }
                        }
                      xmlhttp.open("GET","showbug.php?routine=1&lastTest=no_filter&getproductsddl=product",true);   //getproductsddl wrap_product                 
                      xmlhttp.send();
        }
        
        //----------------------------------------newTests-----------------------------------------//
        
         // find the checked check boxes, return a string
        function cbVal(){            
            var chd= '';
            $('input.chckbox').each(function(){                
                if($(this).is(':checked')){
                    chd += $(this).attr('value') +".";                    
                }                                
            });
            return chd;
        }
        
        // if a checkbox isn't check, return false
        function wasallcheckd() {
            var res = 'true'
            $('input.chckbox').each(function(){                
                if($(this).is(':not(:checked)')){
                   res = 'false';                  
                }                
            });
            if (res == 'false') {
                return false;
            }
        }
        
        // submit the test form        
            function submitTest() {
                
                // first make sure all the checkboxes were checked
                if (wasallcheckd() == false) {
                    alert("One of the test checkboxes wasn't checkd, please re-test.")
                }
                
                else {                
                    //Change the hidden field selectvalue to contain the select of components values
                    //$(".selectvalue").val($("#componenets-select").val());
                    //var comp = $("#componenets-select").val();

                    //  get the product name
                    var prdname = $('select[name="product"]').val();

                    // get title
                    var testTitle = $('textarea[name="title"]').val();                

                    // get date current_date
                    var date = $('input[name="current_date"]').val();    

                    // get the checked checkboxes                
                    var checked = cbVal();                

                    var exceldata = handsontable.getData(); 
                    // NOTES will contaian the excel
                    // create the data as one json                

                    var datajson = new Object();
                    datajson.PRODUCT_NAME = prdname;
                    datajson.TITLE = testTitle;
                    datajson.RESULTS = checked;
                    //datajson.COMPONENTS = comp;
                    datajson.NOTES = exceldata;
                    datajson.DATE = date;

                    var sendjson = JSON.stringify(datajson);
                    //alert (dataString);return false;
                    $.ajax({
                      type: "POST",
                      url: "showbug.php",
                      dataType: 'json',                  
                      data: {"testData": sendjson},
                     success: function (res) {				
                      if (res.result === 'ok') {				 
                        alert('ok');
                        showLastresults();
                      }
                      else {
                        alert('error in saving');
                      }
                    }
                    });   
                }
            }
            
            // Clear tests form
            function clearTestForm() {            
                $('#testForm').find('input:text, input:password, input:file, select, textarea').val('');
                $('#testForm').find('input:radio, input:checkbox')
                     .removeAttr('checked').removeAttr('selected');
                     $("#example1").handsontable('clear');
            }
        
        
        //----------------------------------------manageProducts----------------------------------//
        
        // add a product
        function addprd() {
            var prdadd = "addproduct=" +$('#addProduct').val();
            sendAjax(prdadd);
            
            // reload the ddl's'
            refreshProductsDdl();
            
        }
        
        // Delete a product
        function delprd() {
           var prdname = "deleteproduct=" + $('select[name="product2"]').val();
           sendAjax(prdname);
           
           // reload the ddl's'
           refreshProductsDdl();
        }        
        
        
        //----------------------------------------testTypes---------------------------------------//
        
        function showTestInstruction() {   
                    $.ajax({
                    url: "showbug.php?showInstraction=" + $('select[name="product3"]').val(),
                    dataType: 'json',
                    type: 'GET',
                    success: function (res) {
                      if (res == 'null') { 
                           // there are no instructions,                           
                          alert('No instructions found.');
                          // clear the excel plugin
                           $("#hot3").handsontable('clear');
                      }  
                    else {                        
                        var data = [], row;
                        for (var i = 0; i < res.length; i++) {
                          row = [];
                          row[i] = res[i];                          
                          data[i] = row;
                        }
                        handsontabletestInstruction.loadData(res);
                        $console.text('Data loaded');
                      }
                    }
                  });  
        }
        
        // temp development function
        function showTestInstructionNEW() {   
            
                    var pname = $('select[name="product3"]').val();
                    
                    $.ajax({    
                    url: "showbug.php?showInstraction=" + '64bit_sanity',
                    dataType: 'json',
                    type: 'GET',
                    success: function (res) {                        
                      if (res == 'null') {
                          
                           
                          // there are no instructions, 
                          // check if portion 1 was appended
                          if ($('#'+pname+'_portion_1').length > 0) {
                              alert ('Please enter test instructions.');
                          }
                          // if not, create empty portion where portion id  = 1 and hot id = 1
                          else {                              
                            alert('No instructions found.');     
                            // first append the div. portion_const is a var in the top of this file
                           // var empty_portion = portion_const.replace('PNAME_portion_XXX',pname+'_portion_1').replace('PNAME_portion_XXX_cbspan',pname+'_portion_1_cbspan').replace('PNAME_portion_XXX_hot_AAA',pname+'_portion_1_hot_1').replace('PNAME',pname).replace('addcbButtonXXX','addcbButton1');  
                            var empty_portion = portion_const.replace(/XXX/g, '1').replace(/PNAME/g, pname).replace(/AAA/g, '1');
							$('#testsinst').append(empty_portion);
                            
                            console.log('first append');
                            
                            // then call php to update the hot.js and appeand the hot script from it  
                            return $.get("showbug.php?createhotfile=1&pname="+ pname +"&id=1", function(results){                                           
                                                
                                                var script = document.createElement('script');
                                                script.type = 'text/javascript';
                                                // take the script from file created by PHP
                                                script.src = "hot.js";
                                                $('#'+pname+'_portion_1_hot_1').append(script);
                                                // enable the 'add portion' button
                                                $('#addportion').removeAttr('disabled').removeClass( 'ui-state-disabled' );
                                           
                            });                               
                          }
                      }  
                    else {    
                        // TODO show all the portions from DB in editing mode
                        var data = [], row;
                        for (var i = 0; i < res.length; i++) {
                          row = [];
                          row[i] = res[i];                          
                          data[i] = row;
                        }
                        handsontabletestInstruction.loadData(res);
                        $console.text('Data loaded');
                      }
                    }
                  });  
        }
        
        // return number of portions
        function lastPortion (pname, num) {
            // get last portion id. Assume that portion_1 allready exist
                var isexist = false;
                var portionid = num;
                do {
                     if ($('#'+pname+'_portion_'+portionid).length > 0) {
                              portionid ++;
                          }
                          // create empty portion where portion id  = 1 and hot id = 1
                          else {      
                              isexist = true;
                          }
                }
                while (isexist == false);
                // Now we have the last portion id!  
                console.log('you have ' + portionid + ' portions');
                return portionid;
        }
        
        // return array of all portion's id's
        function portionsIds() {
            
            var parent =  document.getElementById('testsinst');
            var names = new Array();
            var j = 0;
            
            for(var i = 0; i < parent.childNodes.length; i++)
            if(parent.childNodes[i].nodeName == 'DIV'){                
                names[j] = parent.childNodes[i].id;                
                j++;
            }
        
            console.log(names);
            return names;
        }
        
        // Called from button 'addportion'. add a div with all the components
           // get product name
           // get last portion id number
           // get last hot id number        
           function AddPortion() {
                
                var pname = $('select[name="product3"]').val();
                
                var portionid = lastPortion(pname, 2);   
              
                //first append the div. replace all strings
                //var empty_portion = portion_const.replace('PNAME_portion_XXX',pname+'_portion_'+portionid).replace('PNAME_portion_XXX_cbspan',pname+'_portion_'+portionid+'_cbspan').replace('PNAME_portion_XXX_hot_AAA',pname+'_portion_'+portionid+'_hot_'+portionid).replace('PNAME',pname).replace('addcbButtonXXX','addcbButton' + portionid);  
                var empty_portion = portion_const.replace(/XXX/g, portionid).replace(/PNAME/g, pname).replace(/AAA/g, portionid);
				$('#testsinst').append(empty_portion);
                // then appeand the hot script in it                
                return $.get("showbug.php?createhotfile=2&pname="+ pname +"&id="+portionid, function(results){
                                var script = document.createElement('script');
                                            script.type = 'text/javascript';
                                            // take the script from file created by PHP
                                            script.src = "hot.js";
                                            $('#'+pname+'_portion_'+portionid+'_hot_'+portionid).append(script);          
                            });               
           }    
        
        // stop and saves the instructions editing
        function stopeditNEW() {
                // send product name, and a json with potion_id-->html_content, for each portion
                // DB fields: (index) product_name portion_id html_content
                //var testinst = handsontabletestInstruction.getData();
                var prdname = $('select[name="product3"]').val();
                //var totalportions = lastPortion(prdname, 1);
                
                var portions_ids =  portionsIds();
                
                var dataObj = {};
                
                dataObj.name = prdname;
                dataObj.objportions_ids = {};
                
                // obj --> portion key --> portion html
                for (var i = 0;i < portions_ids.length; i++ ) {
                    dataObj.objportions_ids[portions_ids[i]] = $('#' + portions_ids[i]).html();
                }
                
                console.log(dataObj);
                
                /*var sendjson = JSON.stringify(datajson);
                
                $.ajax({
                  type: "POST",
                  url: "showbug.php",
                  dataType: 'json',                  
                  data: {"testInstructions": sendjson},
                 success: function (res) {				
                  if (res.result === 'ok') {				 
                    alert('Test instructions accepted.');                    
                  }
                  else {
                    alert('error in saving');
                  }
                }
                });    */          
        }     
        
         // stop and saves the instructions editing
        function stopedit() {
            
                var testinst = handsontabletestInstruction.getData();
                var prdname = $('select[name="product3"]').val();
             
                var datajson = new Object();
                datajson.name = prdname;
                datajson.text = testinst;                
                var sendjson = JSON.stringify(datajson);
                
                $.ajax({
                  type: "POST",
                  url: "showbug.php",
                  dataType: 'json',                  
                  data: {"testInstructions": sendjson},
                 success: function (res) {				
                  if (res.result === 'ok') {				 
                    alert('Test instructions accepted.');                    
                  }
                  else {
                    alert('error in saving');
                  }
                }
                });              
        }     
        
        //----------------------------------------lastTests---------------------------------------//
        
        //  Show the last tests results
        function showLastresults() {
            xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {
                              document.getElementById("lastTests").innerHTML=xmlhttp.responseText;
                              }
                        }
                      xmlhttp.open("GET","showbug.php?lastTest=1",true);                    
                      xmlhttp.send();
        }
        
        // Show last results by filter. Invoked by buttonShow results
        function showFilterdresults() {
                        var q= ''; // the query
                        
                        var filter_type = $('#filters_select option:selected').attr('value');
                        
                        if (filter_type == 'prd') {
                            var prd =    $('#filter_product option:selected').attr('value');
                            q = 'lastTest=filter_by_prd&prdname=' +prd;
                        }
                        
                        if (filter_type == 'date') {
                            var startDate = $("#startdate").val();
                            var doneDate = $("#enddate").val();
                            q = 'lastTest=filter_by_date&s_date='+startDate+"&d_date="+doneDate; //TODO
                        }
                        if (filter_type == 'none') { //TODO
                            q = 'lastTest=no_filter';
                        }
                        
                      
                        xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {                              
                              document.getElementById("lastTests").innerHTML=xmlhttp.responseText;                              
                              }
                        }
                      xmlhttp.open("GET","showbug.php?"+q,true);                    
                      xmlhttp.send();
        }
        
        // Show filters for last results. invoked by ddl.
        function showfilters() {          
                
                var selectedVal = $('#filters_select option:selected').attr('value');
                
                xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {
                              document.getElementById("filters").innerHTML=xmlhttp.responseText ; 
                              }
                        }
                      xmlhttp.open("GET","showbug.php?filters_type="+selectedVal,true);                    
                      xmlhttp.send();
                
        }
        
        // remove report div
        function closeReport() {            
            $("#report").remove();
        }
        
         // Delete a test result
        function delete_row(id) {
            sendAjax("delete="+id, 'showLastresults');
            
            // refresh the last tests
            //showLastresults();
        }
        
         
        function generatereport(id) {
            // show the div
            var w = window.innerWidth - 200;
            var  h = window.innerHeight - 200;
			
            var htm_l  = '<div id="report" style="background-color:LightSlateGray ;position:absolute;left:100px;top:100px;z-index:99;display: inline-block;width:'+w+'px;height:'+h+'px;" >';
			htm_l += '';
			htm_l += '</div>';
            $(htm_l).appendTo("body");
            
                        xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {
                              document.getElementById("report").innerHTML=xmlhttp.responseText;
                              }
                        }
                      xmlhttp.open("GET","showbug.php?generate=" + id,true);                    
                      xmlhttp.send();
        }        
        
        //----------------------------------------routine-----------------------------------------//
        
         function routineTaskdone(element) {
            // change the b-color to none
            var row = $(element).parent().parent();
            $(row).css("background-color", "transparent");
            var rowId = $(row).attr('rowid');
            // send an ajax call to taskDone ($name)
            sendAjax('taskdone=' + rowId);
        }
        
        function delete_routinetask(taskname) {
            sendAjax('deleteRoutine='+taskname, 'showroutinetable');
        }
        
        // Reload the routine tasks
        function showroutinetable() {
           xmlhttp=new XMLHttpRequest();
                        xmlhttp.onreadystatechange=function() {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                              {                                  
                              // take the xmlhttp.responseText and spilt it to the relevant div
                              var tempres = xmlhttp.responseText;
                              var res = tempres.split("-s-");
                               document.getElementById("routine").innerHTML=res[0];                             
                               
                              }
                        }
                      xmlhttp.open("GET","showbug.php?routine=1",true);              
                      xmlhttp.send();
        }
        
        function addRoutineTask(lasttime) {
            var taskname =   $('input[name="addnewtask"]').val();            
            var timespan = $('select[name="selectroutinetime"]').val(); 
            
            sendAjax('addnewtask=1&taskname='+ taskname + '&timespan='+ timespan +'&lasttime='+ lasttime, 'showroutinetable');
        }
       
        //------------------------------------routine end------------------------------------//
        
        // when clicking the span, the div can be dragged
        function allowdrag(divname) {
            if (divname == 'all') {
                // first initialize
                for (var i=1;i<6;i++) {
                    $( "#draggable"+i).draggable();
                }
            }
            else {
                // the re allow
                $( "#" +divname).draggable('enable');
            }
        }
        
        // load
        $(document).ready(            
                      function () // showbug.php in here
                      {
                        // send 3 params to the php, and split the resposne accordingly.
                        // shows the ddl, toutine tasks and last tests
                        showTables ()                        
                        // do the components selectbox magic
                        $("#componenets-select").chosen();                        
                       // init the drag event
                        allowdrag('all');
                      }
                      
                      
        );
            
            // TODO give colors to exel plugin
            // http://stackoverflow.com/questions/17971932/handsontable-coloring-cells-using-button/18068064?noredirect=1#18068064
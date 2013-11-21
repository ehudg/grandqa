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
                  console.log(parent+'_cbspan_cb_'+id);
                  $('#'+parent).append('<input type="checkbox" id="'+parent+'_cb_'+id+'" /> &nbsp; <input type="text" id="'+parent+'_cbspan_text_'+id+'" />');
                  break;
                case (totalCheckboxes == 0):
                  // There are no checkboxes  
                  console.log('no cb, adding first');
                  $('#'+parent).append('<input type="checkbox" id="'+parent+'_cb_1" /> &nbsp; <input type="text" id="'+parent+'_cbspan_text_1" />');
                  
                  break;                
                }
               }
               
               function deletePortion(buttonid) {
                $('#' + buttonid).remove();
                console.log(buttonid + ' was deleted');
               }
           // });            
              var $container = $("#Coolpic_portion_1_hot_1");                     
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
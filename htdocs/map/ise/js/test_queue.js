$(document).ready(function(){

  ////////////////////////////////////////////////////////////////////////////
  ////////////////////////////Live Test///////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////
  //10/20/14 -- we don't yet handle cases where multiple tests are to be canceled, but only part of them are canceled
  //successfully. If backend server's response is NOT ok. No test will be removed from this page. Of course on
  //manual reload, the page will reflect whatever's in the database.


  //cancel_selected button
  $('div').on('click','.cancel_selected',function(){
    var template_id = $(this).attr('data-ise-template-id');
    var section_body_id = 'section_body_'+template_id;
    var is_disabled = $(this).hasClass('disabled');
    if(!is_disabled) //If this button is not disabled
    {
      var execution_id_list = $('#'+section_body_id).find('.checkbox_test').map(function(){
        if($(this).is(":checked")) //If the check box is checked
        {
          var execution_id = $(this).attr('data-ise-test-execution-id');
          //console.log(test_id);
          return execution_id;
        }//end of if($(this).is(":checked"))
      }).get();//end of map(function())
      //sending data to php
      if(execution_id_list.length >0) //if there's actual tests to be canceled
      {
        $.ajax({
          type: 'post',
          url:  'test_action.php',
          data: 'action=cancel&execution_ids='+execution_id_list,
          success: function(data){
            //console.log(data);
            if(data.trim() == 'ok') //If tests are canceled, then remove those rows
            {
              for(i=0; i<execution_id_list.length;i++)
              { //hiding the parent tr of the ones that are canceled
                var temp = execution_id_list[i];
                var full_selector = "[data-ise-test-execution-id='"+temp+"']";
                $(full_selector).parents('tr').hide();
              } //end of for loops
            }//end of if(ok)
          }//end of success function
        });//end of ajax call
        //console.log(execution_id_list);
        //console.log(section_body_id);
        //console.log('there');

      }//end of if(execution_id_list.length >0)
    } //end of if(!is_disabled)
  });//end of cancel_selected button

  //cancel_all button
  $('div').on('click','.cancel_all',function(){
    var template_id = $(this).attr('data-ise-template-id');
    var section_body_id = 'section_body_'+template_id;
    var is_disabled = $(this).hasClass('disabled');
    if(!is_disabled)
    {
      var execution_id_list = $('#'+section_body_id).find('.checkbox_test').map(function(){
        var execution_id = $(this).attr('data-ise-test-execution-id');
        //console.log(test_id);
        return execution_id;
      }).get();//end of map(function())

      if(execution_id_list.length >0) //if there's actual tests to be canceled
      {
        //sending data to php
        $.ajax({
          type: 'post',
          url:  'test_action.php',
          data: 'action=cancel&execution_ids='+execution_id_list,
          success: function(data){
            //console.log(data);
            if(data.trim() == 'ok') //If tests are canceled, then remove those rows
            {
              for(i=0; i<execution_id_list.length;i++)
              { //hiding the parent tr of the ones that are canceled
                var temp = execution_id_list[i];
                var full_selector = "[data-ise-test-execution-id='"+temp+"']";
                $(full_selector).parents('tr').hide();
              } //end of for loops
            }//end of if(ok)
          }//end of success function
        });//end of ajax call

        //console.log(execution_id_list);
        //console.log(section_body_id);
        //console.log('there');
      }//end of if(execution_id_list.length >0)
    }//end of if(!is_disabled)
  }); //end of cancel_all button

}); // end of document ready function

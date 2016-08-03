$(document).ready(function(){

  ////////////////////////////////////////////////////////////////////////////
  //////////////////////////// Task ///////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////
  //cancel function
  $('div').on('click','.cancel_cross',function(){
    var task_id = $(this).attr('data-ise-task-id');
    var current_selector = $(this);
    $.ajax({
          type: 'post',
          url:  'task_action.php',
          data: 'action=cancel&task_id='+task_id,
          success: function(data){
            //console.log(data);
            if(data.trim() == 'ok') //If tests are canceled, then remove those rows
            {
              //console.log('removing');
              $(current_selector).parents('tr').hide();
            }//end of if(ok)
            else
            {
              console.log(data);
            }
          }//end of success function
      });//end of ajax call
  });

  $('div').on('click', '[id^=task_db_conv_button_]', function () {
    var button_element = $(this);
    var cur_task_id = $(this).parent('td').parent('tr').attr('data-ise-task-id');
    console.log(cur_task_id);
    if(!$(this).hasClass('disabled')){
      $.ajax({
        type: 'post',
        url:  'db_conv_complete.php',
        data: 'task_id='+cur_task_id,
        success: function(data){
          if(data.trim() == 'ok'){
            console.log('ok');
            button_element.addClass('disabled');
            console.log(button_element);
          }
          else{
            console.log('error');
          }
        }
      });//end of ajax call
    }
    else{
      console.log('button is disabled');
    }
  });

}); // end of document ready function

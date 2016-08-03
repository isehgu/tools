$(document).ready(function(){

  //Template suspend and unsuspend
  /*using btn
  $('div').on('click','.suspend_btn',function(){
    $(this).removeClass('suspend_btn alert').addClass('unsuspend_btn').text('Unsuspend');
  });

  $('div').on('click','.unsuspend_btn',function(){
    $(this).removeClass('unsuspend_btn').addClass('suspend_btn alert').text('Suspend');
  });
  */
  //The following replace using btns. Somehow, since button in Zurb is a <a>,
  //once it's pressed, firefox considered it depress/having focus. Thus
  //it's color is in the depressed state. Chrome works fine.
  //So I had to swap out the entire html code to have it work correctly.
  $('div').on('click','.suspend_td',function(){
    if(!$(this).find('a').hasClass('disabled')){
      var current_element = $(this);
      var template_id = $(this).attr('data-ise-template-id');
      $.ajax({
        type: 'get',
        url:  'template_action.php',
        data: 'type=template&action=suspend&template_id='+template_id,
        success: function(data){
          if(data.trim() == 'ok'){
            current_element.removeClass('suspend_td').addClass('unsuspend_td').html("<a href='#' class='button small radius'>Unsuspend</a>");
            //console.log('inside');
          }//end of if
          //console.log(data);
        }//end of success function
      });//end of ajax call
      return false;
    }
    else{
      console.log('DENIED!');
    }
  });

  $('div').on('click','.unsuspend_td',function(){
    if(!$(this).find('a').hasClass('disabled')){
      var current_selector = $(this);
      var template_id = $(this).attr('data-ise-template-id');
      $.ajax({
        type: 'get',
        url:  'template_action.php',
        data: 'type=template&action=unsuspend&template_id='+template_id,
        success: function(data){
          if(data.trim() == 'ok'){
            current_selector.removeClass('unsuspend_td').addClass('suspend_td').html("<a href='#' class='button alert small radius'>Suspend</a>");
            //console.log('inside');
          }//end of if
          //console.log(data);
        }//end of success function
      });//end of ajax call
      return false;
    }
    else{
      console.log('DENIED!');
    }
  });
  //End of template suspend and unsuspend

}); // end of document ready function

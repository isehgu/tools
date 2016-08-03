$(document).ready(function(){

  ////////////////////////////////////////////////////////////////////////////
  ////////////////////////////Test History///////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////
  //test replay function
  $('div').on('click','.test_replay',function(){
    var group_id  = $(this).attr('data-ise-group-id');
    var test_name = $(this).attr('data-ise-test-name');
    $.ajax({
      type: 'get',
      url:  'test_request_resubmit.php',
      data: 'group_id='+group_id,
      success: function(data){
        //console.log(data);
        if(data.trim() == 'ok') //If tests are canceled, then remove those rows
        {
          var success_message = "'"+test_name+"' successfully submitted."+
          ' Go to the \'Live Tests\' page or to the Tasks page to follow progress.';
          show_toast(success_message);
          console.log('Received');
        }//end of if(ok)
        else
        {
          console.log(data);
        }
      }//end of success function
    });//end of ajax ca
  });

  //test history row clicking for rerun history
  $('div').on('click','.test_history_row',function(){
    //console.log('Calling modal');
    var test_or_label = '';
    $('.second_bar_link').each(function(){
      if($(this).parent('dd').hasClass('active')){
        test_or_label = $(this).attr('id');
        if(test_or_label == 'test_user_history_by_label'){
          test_or_label = 'label';
        }
        else if(test_or_label == 'test_user_history_by_test'){
          test_or_label = 'test';
        }
      }
    });
    var group_id = $(this).attr('data-ise-group-id');
    $.ajax({
      type: 'post',
      url:  'modal_content.php',
      data: 'caller=rerun_history&group_id='+group_id+'&test_or_label='+test_or_label,
      success: function(data){
        $('#detail_modal').html(data);
        //console.log(data);
      }
    });//end of ajax call

    $('#detail_modal').foundation('reveal','open');
  });
  //End of Detail Modal Control

}); // end of document ready function

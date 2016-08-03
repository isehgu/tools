$(document).ready(function(){

  //Detail Modal Control
  $('div').on('click','.event_history_template_row',function(){
    //console.log('Calling modal');
    var release_id = $(this).attr('data-ise-release-id');
    var template_id = $(this).attr('data-ise-template-id');
    $.ajax({
      type: 'post',
      url:  'modal_content.php',
      data: 'caller=templaterow&release_id='+release_id+'&template_id='+template_id,
      success: function(data){
        $('#detail_modal').html(data);
        //console.log(data);
        $('#detail_modal').foundation('reveal','open');
      }
    });//end of ajax call


  });

  $('div').on('click','.event_history_release_row',function(){
    //console.log('Calling modal');
    var release_id = $(this).attr('data-ise-release-id');
    var template_id = $(this).attr('data-ise-template-id');
    $.ajax({
      type: 'post',
      url:  'modal_content.php',
      data: 'caller=releaserow&release_id='+release_id+'&template_id='+template_id,
      success: function(data){
        $('#detail_modal').html(data);
        //console.log(data);
        $('#detail_modal').foundation('reveal','open');
      }
    });//end of ajax call


  });

}); // end of document ready function

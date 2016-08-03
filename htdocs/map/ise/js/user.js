$(document).ready(function(){

  /****************************************************************/
  /* User Section in top-bar */
  /****************************************************************/
  $('#login_button').click(function(){
    var username = $('#data-ise-username').val();
    var password = $('#data-ise-password').val();
    $.ajax({
      type: 'post',
      url:  'uc_login.php',
      data: 'username='+username+'&password='+password,

      success: function(data){
        return_data = JSON.parse(data);
        return_value = return_data[0];
        return_contents = return_data[1];
        if(return_value.slice(0,5) == 'ERROR'){
          // $('#id_top_bar_error').html(return_contents);
          if(return_value == 'ERROR_USER'){
            $('#data-ise-username').focus();
            $('#id_top_bar_error').addClass('top_bar_error');
            $('#data-ise-username').removeClass('input-styling');
            $('#data-ise-username').addClass('input-validation-error'+STYLE);
            $('#data-ise-password').removeClass('input-validation-error'+STYLE);
            if(STYLE == 2){
              $('#data-ise-password').addClass('input-styling');
            }
          }
          if(return_value == 'ERROR_INVALID'){
            $('#data-ise-username').focus();
            $('#id_top_bar_error').addClass('top_bar_error');
            $('#data-ise-username').removeClass('input-styling');
            $('#data-ise-password').removeClass('input-styling');
            $('#data-ise-username').addClass('input-validation-error'+STYLE);
            $('#data-ise-password').addClass('input-validation-error'+STYLE);
          }
          if(return_value == 'ERROR_PASSWORD'){
            $('#data-ise-password').focus();
            $('#id_top_bar_error').removeClass('top_bar_error');
            $('#data-ise-password').removeClass('input-styling');
            $('#data-ise-password').addClass('input-validation-error'+STYLE);
            $('#data-ise-username').removeClass('input-validation-error'+STYLE);
            if(STYLE == 2){
              $('#data-ise-username').addClass('input-styling');
            }
          }
        }
        else if(return_value == 'SUCCESS'){
          location.reload();
        }
        console.log(return_data);
      }

    });//end of ajax call
  });

}); // end of document ready function

function submit_user_login(myfield, e)
{
  $('#login_button').trigger('click');
}

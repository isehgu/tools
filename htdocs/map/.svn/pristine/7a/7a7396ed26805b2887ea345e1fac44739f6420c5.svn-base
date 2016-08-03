$(document).ready(function(){

  /****************************************************************/
  /* Submit Test Request Page */
  /****************************************************************/
  $('div').on('click', '[id^=str_checkbox_t_]', function () {

    t_name = $("label[for='" + this.id + "']").text();

    if($('#str_checkbox_all').attr('checked')){
      $('[class^=str_row_]').hide();
      $('#str_checkbox_all').prop('checked', false);
    }

    if($('[id^=str_checkbox_t_'+t_name+']').is(':checked')){
      $('.str_row_'+t_name).show();
      temp_are_all_checked = true;
      $('[id^=str_checkbox_t_]').each(function(){
        if(!$(this).is(':checked')){
          temp_are_all_checked = false;
        }
      });
      if(temp_are_all_checked){
        $('#str_checkbox_all').prop('checked', true);
      }
      $('input:checked').each(function(){
        $( '.str_row_' + $("label[for='" + this.id + "']").text() ).show();
      });
    }
    else{
      $('.str_row_'+t_name).hide();
      $('.str_row_'+t_name).prop('checked', false);
      $('input:checked').each(function(){
        $( '.str_row_' + $("label[for='" + this.id + "']").text() ).show();
      });
    }
    update_test_count();
  });

  // If all is selected then check all of the other checkboxes
  // Hide all or show all depending on all selection
  $('div').on('click', '#str_checkbox_all', function () {
    if($('#str_checkbox_all').is(':checked')){
      $('[class^=str_row_]').show();
      $('[id^=str_checkbox_t_]').prop('checked', true);
    }
    else{
      $('[class^=str_row_]').hide();
      $('[id^=str_checkbox_t_]').prop('checked', false);
    }
    update_test_count();
  });


  var requested_test_ids  = [];
  var requested_suite_ids = [];
  var test_requests_label = '';
  var test_template_id    = ''; // This assumes that all requested tests are for the same template
  var remove_size  = 12;
  var new_col_size = 9;
  var panel_size   = 3;
  $('div').on('click', '#test_link', function () {
    // Get the test id
    cur_test_id      = $(this).attr('data-ise-str-test-id');
    cur_test_name    = $(this).attr('data-ise-str-test-name');
    test_template_id = $(this).attr('data-ise-str-test-template-id');
    cur_test_id = parseInt(cur_test_id);
    // inArray returns index of element. So if -1, then it wasnt found
    found_id = jQuery.inArray(cur_test_id, requested_test_ids)
    // Add the test id to the array and highlight the row
    if(found_id == -1){
      requested_test_ids.push(cur_test_id);
      console.log( requested_test_ids + ': just added ' + cur_test_name);
      $(this).addClass('test_request_highlight');
    }
    // Remove the test id from the array and remove highlight
    else{
      requested_test_ids.splice(found_id, 1);
      console.log( requested_test_ids + ': just added ' + cur_test_name);
      $(this).removeClass('test_request_highlight');
    }

    if(requested_test_ids.length){

      if(requested_test_ids.length == 1){
        // If its already listed then remove it
        if(found_id == -1){
          grow_test_request_content_sizing(remove_size, new_col_size);
          create_selected_tests_panel(panel_size);
          append_li_selected_tests_panel(cur_test_id, cur_test_name);
          close_selected_tests_panel();
          show_selected_tests_panel();
          show_selected_tests_panel_li();
        }
        else{
          remove_li_selected_tests_panel(cur_test_id)
        }
      }
      else{
        if(found_id == -1){
          append_li_selected_tests_panel(cur_test_id, cur_test_name);
          show_selected_tests_panel_li();
        }
        else{
          remove_li_selected_tests_panel(cur_test_id)
        }
      }
      // Activate the submission button if the user is logged in
      enable_test_request_submit_button(requested_test_ids.length);
      jQuery('.test_request_selected_tests_panel_div_class').stickyfloat({ duration: 0, startOffset:100, lockBottom:false });
    }
    else{
      jQuery('.test_request_selected_tests_panel_div_class').stickyfloat('destroy');
      remove_selected_tests_panel(new_col_size, remove_size, false, 0);
    }

  });

  $('div').on('click', '#selected_tests_button', function(){
    $('#test_request_submit_button').trigger('click');
  });

  $('div').on('keydown', '#test_requests_label', function(){
    if (event.keyCode == 13){
      $('#test_request_submit_button').trigger('click');
    }
  });

  $('div').on('click', '#test_request_submit_button', function(){

    var is_disabled = $('#test_request_submit_button').hasClass('disabled');
    if(!is_disabled){
      num_tests_suites = requested_test_ids.length + requested_suite_ids.length;
      console.log('Test IDs: ' + requested_test_ids);
      console.log('Suite IDs: ' + requested_suite_ids);
      json_requested_test_ids  = JSON.stringify(requested_test_ids);
      json_requested_suite_ids = JSON.stringify(requested_suite_ids);
      test_requests_label = $('#test_requests_label').val();
      test_requests_release_id = $('#test_request_selected_tests_version_select').val();
      if(!test_requests_label){
        alert('Please provide a label for the test submission');
        return false;
      }
      $.ajax({
        type: 'get',
        url:  'test_request.php',
        data: 'test_ids='+json_requested_test_ids+'&suite_ids='+json_requested_suite_ids+
          '&label='+test_requests_label+'&release_id='+test_requests_release_id+'&template_id='+test_template_id,
        success: function(data){
          if(data.trim() == 'ok'){
            remove_selected_tests_panel(new_col_size, remove_size, true, num_tests_suites);
          }
          else{
            alert(data);
          }
        }
      });
      // Fastest way to clear an array in Javascript
      while(requested_test_ids.length > 0){
        requested_test_ids.pop();
      }
    }
  });

  // Add the secondary submit tests button into the panel, if it hasn't already been added
  $(document).on('scroll', function(){
    add_selected_tests_button();
  });

  //Filtering all test request table
  $('div').on('keyup', '#test_request_search', function () {
    var rex = new RegExp($(this).val(),'i');
    $('.test_request_searchable tr').hide();
    $('.test_request_searchable tr').filter(function(){
      return rex.test($(this).text());
    }).show();
    update_test_count();
    // Disable the checkboxes if characters in search box; Enable otherwise
    if($(this).val()){
      $('#str_checkbox_all').prop('disabled', true);
      $('[id^=str_checkbox_t_]').each(function(){
        $('#'+$(this)[0].id).prop('disabled', true);
        // $(this).addClass('disabled');
      });
    }
    else{
      $('#str_checkbox_all').prop('disabled', false);
      $('[id^=str_checkbox_t_]').each(function(){
        $('#'+$(this)[0].id).prop('disabled', false);
        // $(this).addClass('disabled');
      });
    }
  });

}); // end of document ready function

function grow_test_request_content_sizing(remove_size, new_col_size)
{
  $('#test_request_table_row').addClass('test_request_table_shift');
  $('#test_request_table_div').removeClass('small-'+remove_size);
  $('#test_request_table_div').addClass('small-'+new_col_size);

  $('#test_request_filter_row').addClass('test_request_table_shift');
  $('#test_request_checkboxes_div').removeClass('small-7');
  $('#test_request_checkboxes_div').addClass('small-8');
  $('#test_request_checkboxes_div').removeClass('test_request_checkboxes_style');
  $('#test_request_checkboxes_div').addClass('test_request_checkboxes_style_exp');

  $('#test_request_filter_wrapper').removeClass('small-'+remove_size);
  $('#test_request_filter_wrapper').addClass('small-'+new_col_size);

  $('#test_request_submit_button_row').addClass('test_request_table_shift');
  $('#test_request_submit_button_wrapper').removeClass('small-'+remove_size);
  $('#test_request_submit_button_wrapper').addClass('small-'+new_col_size);
}

function shrink_test_request_content_sizing(remove_size, new_col_size)
{
  $('#test_request_table_row').removeClass('test_request_table_shift');
  $('#test_request_table_div').addClass('small-'+new_col_size);
  $('#test_request_table_div').removeClass('small-'+remove_size);

  $('#test_request_filter_row').removeClass('test_request_table_shift');
  $('#test_request_checkboxes_div').addClass('small-7');
  $('#test_request_checkboxes_div').removeClass('small-8');
  $('#test_request_checkboxes_div').addClass('test_request_checkboxes_style');
  $('#test_request_checkboxes_div').removeClass('test_request_checkboxes_style_exp');

  $('#test_request_filter_wrapper').addClass('small-'+new_col_size);
  $('#test_request_filter_wrapper').removeClass('small-'+remove_size);

  $('#test_request_submit_button_row').removeClass('test_request_table_shift');
  $('#test_request_submit_button_wrapper').addClass('small-'+new_col_size);
  $('#test_request_submit_button_wrapper').removeClass('small-'+remove_size);
}

function create_selected_tests_panel(col_size, cur_test_name)
{
  $('#test_request_table_row').append("<div class='small-"+col_size+" columns str_panel_animation test_request_selected_tests_panel_div_class' id='test_request_selected_tests_panel_div'>");
  $('#test_request_selected_tests_panel_div').append("<div class='panel' id='test_request_selected_tests_panel'>");
  $('#test_request_selected_tests_panel').append("<h5 id='test_request_selected_tests_panel_heading'>Selected Tests</h5>");
  $('#test_request_selected_tests_panel').append("<ol id='test_request_selected_tests_panel_ul'>");
}

function append_li_selected_tests_panel(cur_test_id, cur_test_name)
{
  $('#test_request_selected_tests_panel_ul').append("<li id='str_panel_"+cur_test_id+"' class='str_panel_li_animation'>"+cur_test_name+"</li>");
}

function remove_li_selected_tests_panel(cur_test_id)
{
  $('#str_panel_'+cur_test_id).fadeOut(100, function(){
    $('#str_panel_'+cur_test_id).remove();
  });
}

function close_selected_tests_panel()
{
  $('#test_request_selected_tests_panel').append("</ol>");
  add_selected_tests_label_textbox();
  add_selected_tests_version_dropdown();
  $('#test_request_selected_tests_panel').append("</div>");
  $('#test_request_table_row').append("</div>");
}

function add_selected_tests_label_textbox(){
  var label_textbox_title = "This label should describe why these tests are being run. "+
    "For example: \"R11.0.0 Regression Rerun\"";
  var selected_tests_label_textbox = "<input id='test_requests_label' type='text' " +
    " placeholder='Provide a label' title='"+label_textbox_title+"'/>";
  $('#test_request_selected_tests_panel').append(selected_tests_label_textbox);
}

function add_selected_tests_version_dropdown(){
  var version_dropdown = "<div id='test_request_selected_tests_version_select_div'></div>";
  $('#test_request_selected_tests_panel').append(version_dropdown);
  $.ajax({
    type: 'get',
    url:  'get_app_version_dropdown.php',
    success: function(data){
      data = data.trim();
      $('#test_request_selected_tests_version_select_div').html(data);
    }
  });
}

function add_selected_tests_button(){
  if(!selected_tests_panel_button_added){
    if(!$('#test_request_submit_button').visible()){
      var btn_disable = $('#test_request_submit_button').hasClass('disabled')?'disabled':'';
      var btn_title = $('#test_request_submit_button').attr('title');
      var selected_tests_button = "<div id='selected_tests_button_div'><a href='#' class='button small radius expand "+btn_disable+"' id='selected_tests_button'"+
        "title='"+btn_title+"'>Submit Selected Test Requests</a></div>";
      $('#test_request_selected_tests_panel').append(selected_tests_button);
      selected_tests_panel_button_added = true;
    }
  }
  else{
    if($('#test_request_submit_button').visible()){
      $('#selected_tests_button').remove();
      selected_tests_panel_button_added = false;
    }
  }
}

function show_selected_tests_panel(){
  $('.str_panel_animation').fadeIn('fast');
}

function show_selected_tests_panel_li(){
  $('.str_panel_li_animation').fadeIn('fast');
}

function remove_selected_tests_panel(remove_size, new_col_size, success, num_tests_suites)
{
  $('#test_request_selected_tests_panel_div').fadeOut(100, function(){
    $('#test_request_selected_tests_panel_div').remove();
    shrink_test_request_content_sizing(remove_size, new_col_size);
    var is_disabled = $('#test_request_submit_button').hasClass('disabled');
    if(!is_disabled){
      $('#test_request_submit_button').addClass('disabled');
    }
    $('#test_request_submit_button').attr('title', 'Select tests from the table to enable this button');

    $('tr').each(function(){
      $(this).removeClass('test_request_highlight');
    });
    if(success){
      var success_message = num_tests_suites+' test(s) successfully submitted.'+
        ' Go to the \'Live Tests\' page or to the Tasks page to follow progress.';
      show_toast(success_message);
    }
  });
}

function update_test_count()
{
  visible_test_count = $('tr:visible').not(':first').length;
  $('#test_request_table_count').html(visible_test_count + ' tests');
}

function enable_test_request_submit_button(num_tests){
  $.ajax({
    type: 'get',
    url:  'check_if_user_logged_in.php',
    success: function(data){
      if(data.trim() == 'ok'){
        $('#test_request_submit_button').removeClass('disabled');
        $('#test_request_submit_button').attr('title', 'Click to submit test'+(num_tests>1?'s':''));
      }
    }
  });
}

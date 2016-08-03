$(document).ready(function(){

  /****************************************************************/
  /* Triggers Page -- Selecting rows */
  /****************************************************************/
  var restrict_to_active   = false;
  var restrict_to_inactive = false;
  $('div').on('click', '.view_triggers_row', function () {
    // var trigger_already_selected = false;
    // $('.view_triggers_row').each(function(){
    //   if($(this).hasClass('view_triggers_table_highlight')){
    //     trigger_already_selected = true;
    //   }
    // });
    // if(trigger_already_selected){
    //   return;
    // }

    // If any other row is being edited then reject this click event
    var already_editing_a_trigger = false;
    $('.view_triggers_row').each(function(){
      if($(this).attr('data-ise-trigger-editing') == 'true'){
        already_editing_a_trigger = true;
        return;
      }
    });
    if(already_editing_a_trigger){
      return;
    }

    if(!$(this).hasClass('view_triggers_table_highlight')){
      if(!restrict_to_inactive && !$(this).hasClass('inactive_trigger')){
        restrict_to_active = true;
        $(this).addClass('view_triggers_table_highlight');
      }
      if($(this).hasClass('inactive_trigger')){
        if(!restrict_to_active){
          restrict_to_inactive = true;
          $(this).removeClass('inactive_trigger');
          $(this).addClass('view_triggers_table_highlight');
        }
      }
    }
    else{
      $(this).removeClass('view_triggers_table_highlight');
      if($(this).attr('data-ise-trigger-active') == 'false'){
        $(this).addClass('inactive_trigger');
      }
    }

    var any_triggers_selected = false;
    var any_inactive_triggers_selected = false;
    $('.view_triggers_row').each(function(){
      if($(this).hasClass('view_triggers_table_highlight') &&
        ($(this).attr('data-ise-trigger-active') == 'true'))
      {
        any_triggers_selected = true;
        enable_trigger_exec_button('Click to bring up modal of releases to deploy');
        enable_trigger_inactivate_button('Click to immediately inactivate trigger');
        enable_trigger_edit_button('Click to enable editing of selected trigger');
      }
      if($(this).hasClass('view_triggers_table_highlight') &&
        ($(this).attr('data-ise-trigger-active') == 'false') &&
        (!restrict_to_active))
      {
        enable_trigger_activate_button('Click to immediately activate trigger');
        any_inactive_triggers_selected = true;
      }
    });
    if(!any_triggers_selected){
      disable_trigger_edit_button();
      disable_trigger_exec_button();
      disable_trigger_inactivate_button();
      restrict_to_active = false;
    }
    if(!any_inactive_triggers_selected){
      disable_trigger_activate_button();
      restrict_to_inactive = false;
    }
  });

  /****************************************************************/
  /* Triggers Page -- Show test modal view only*/
  /****************************************************************/

  $('div').on('click', '#trigger_tests_view_only_table_cell', function () {
    resulting_data       = triggers_closed_fields_data_extraction($(this).parent('tr'));
    var cur_trigger_id   = resulting_data.trigger_id;
    var git_branch       = resulting_data.git_branch;
    var code_track       = resulting_data.code_track;
    var trigger_template = resulting_data.trigger_template;
    var trigger_event    = resulting_data.trigger_event;
    var target_template  = resulting_data.target_template;
    var view_only = 'true';
    $.ajax({
      type: 'post',
      url:  'modal_content_trigger_tests.php',
      data:
        'trigger_id='        + cur_trigger_id           +
        '&git_branch='       + git_branch               +
        '&code_track='       + code_track               +
        '&trigger_template=' + trigger_template         +
        '&trigger_event='    + trigger_event            +
        '&target_template='  + target_template          +
        '&view_only='        + view_only                ,
      success: function(data){
        $('#trigger_tests_modal').html(data);
      }
    });//end of ajax call
    $('#trigger_tests_modal').foundation('reveal','open');
  });

  /****************************************************************/
  /* Triggers Page -- Show test modal */
  /****************************************************************/

  $('div').on('click', '[id^=trigger_data_test_]', function () {
    var view_only = $(this).parent('tr').attr('data-ise-trigger-editing') == 'true' ? 'false':'true';
    resulting_data       = view_only=='true'?
      triggers_closed_fields_data_extraction($(this).parent('tr')) :
      triggers_open_fields_data_extraction($(this).parent('tr'));
    var cur_trigger_id   = resulting_data.trigger_id;
    var git_branch       = resulting_data.git_branch;
    var code_track       = resulting_data.code_track;
    var trigger_template = resulting_data.trigger_template;
    var trigger_event    = resulting_data.trigger_event;
    var target_template  = resulting_data.target_template;
    var add_list = [];
    var rem_list = [];
    if(trigger_test_changes_saved[cur_trigger_id] == true){
      if(cur_trigger_id in trigger_test_ids_add_list){
        var add_list = trigger_test_ids_add_list[cur_trigger_id];
      }
      if(cur_trigger_id in trigger_test_ids_rem_list){
        var rem_list = trigger_test_ids_rem_list[cur_trigger_id];
      }
    }
    else{
      console.log('Clearing add and rem list since changes were not saved');
      trigger_test_ids_add_list[cur_trigger_id] = [];
      trigger_test_ids_rem_list[cur_trigger_id] = [];
    }
    // Show the modal for managing the tests
    $.ajax({
      type: 'post',
      url:  'modal_content_trigger_tests.php',
      data:
        'trigger_id='        + cur_trigger_id           +
        '&git_branch='       + git_branch               +
        '&code_track='       + code_track               +
        '&trigger_template=' + trigger_template         +
        '&trigger_event='    + trigger_event            +
        '&target_template='  + target_template          +
        '&view_only='        + view_only                +
        '&add_list='         + JSON.stringify(add_list) +
        '&rem_list='         + JSON.stringify(rem_list),
      success: function(data){
        $('#trigger_tests_modal').html(data);
        $('#trigger_tests_modal').attr('data-ise-trigger-id', cur_trigger_id);
      }
    });//end of ajax call
    $('#trigger_tests_modal').foundation('reveal','open');
  });

  /****************************************************************/
  /* Triggers Page -- Execute trigger checkboxes and label */
  /****************************************************************/
  var checkbox_selected = false;
  $('div').on('click', '[id^=exec_trigger_checkbox_]', function(){
    if($("[id^=exec_trigger_checkbox_]:checked").length > 0){
      enable_trigger_modal_exec_label_textbox('Provide a label');
      if($('#trigger_exec_label').val().length > 0){
        enable_trigger_modal_exec_button('Click to execute the selected trigger');
      }
      checkbox_selected = true;
    }
    else{
      disable_trigger_modal_exec_label_textbox('Select at least one trigger to execute.');
      disable_trigger_modal_exec_button('Select at least one trigger and provide a label to execute.');
      checkbox_selected = false;
    }
  });

  $('div').on('keyup', '#trigger_exec_label', function() {
    if(($(this).val().length > 0) && checkbox_selected){
      enable_trigger_modal_exec_button('Click to execute the selected trigger');
    }
    else{
      disable_trigger_modal_exec_button('Select at least one trigger and provide a label to execute.');
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: execute trigger in modal */
  /****************************************************************/

  $('div').on('click', '#trigger_modal_exec_button', function(){
    var is_disabled = $('#trigger_modal_exec_button').hasClass('disabled');
    if(!is_disabled){
      $("[id^=exec_trigger_checkbox_]:checked").each(function(){
        var temp_trigger_id = $(this).attr('data-ise-trigger-exec-trigger');
        var temp_value = $(this).attr('data-ise-trigger-exec-value');
        var temp_label = $('#trigger_exec_label').val();
        send_trigger_exec_message(temp_trigger_id, temp_value, temp_label);
        triggers_row_remove_highlights();
        disable_trigger_edit_button();
        disable_trigger_exec_button();
        disable_trigger_inactivate_button();
        $('.close-reveal-modal').trigger('click');
      });
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: execute show modal */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_exec_button', function(){
    var is_disabled = $('#manage_triggers_exec_button').hasClass('disabled');
    if(!is_disabled){
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('view_triggers_table_highlight')){
          var cur_trigger_id = $(this).attr('data-ise-trigger-id');
          // Show the modal for managing the tests
          $.ajax({
            type: 'post',
            url:  'modal_content_execute_trigger.php',
            data: 'trigger_id='+cur_trigger_id,
            success: function(data){
              $('#trigger_exec_modal').html(data);
              $('#trigger_exec_modal').attr('data-ise-trigger-id', cur_trigger_id);
            }
          });//end of ajax call
          $('#trigger_exec_modal').foundation('reveal','open');
        }
      });
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: create */
  /****************************************************************/

  var new_row_created = false;
  $('div').on('click', '#manage_triggers_create_button', function(){
    var is_disabled = $('#manage_triggers_create_button').hasClass('disabled');
    if(!is_disabled && !new_row_created){
      new_row_created = true;
      triggers_row_remove_highlights();
      // Disable some buttons and enable other buttons since we're in create mode
      disable_trigger_edit_button('Complete the new trigger before trying to edit another trigger')
      enable_trigger_cancel_button('Click to cancel this new trigger');
      enable_trigger_submit_button('Click to submit this new trigger');
      disable_trigger_create_button('Complete the creation of this new trigger before creating another new one');
      console.log("$('#view_triggers_table').length: "+$('#view_triggers_table').length);
      if($('.view_triggers_row').length > 1){
        console.log('Adding before first row');
        $('#view_triggers_table > tbody > tr:first').before(
          "<tr class='view_triggers_row' id='view_triggers_row_new' "+
          "data-ise-trigger-editing='true' data-ise-trigger-id='new' data-ise-trigger-active='true'>"+
          "<td id='trigger_new_gb' data-ise-trigger-field='git_branch'></td>"+
          "<td id='trigger_new_ct' data-ise-trigger-field='code_track'></td>"+
          "<td id='trigger_new_tt' data-ise-trigger-field='trigger_template'></td>"+
          "<td id='trigger_new_te' data-ise-trigger-field='trigger_event'></td>"+
          "<td id='trigger_new_ta' data-ise-trigger-field='target_template'></td>"+
          "<td id='trigger_new_de' data-ise-trigger-field='deploy'></td>"+
          "<td id='trigger_data_test_new' data-ise-trigger-field='test'></td>"+
          "</tr>"
        );
      }
      else{
        console.log('Adding as the first row after body');
        $('#view_triggers_table > tbody').after(
          "<tr class='view_triggers_row' id='view_triggers_row_new' "+
          "data-ise-trigger-editing='true' data-ise-trigger-id='new' data-ise-trigger-active='true'>"+
          "<td id='trigger_new_gb' data-ise-trigger-field='git_branch'></td>"+
          "<td id='trigger_new_ct' data-ise-trigger-field='code_track'></td>"+
          "<td id='trigger_new_tt' data-ise-trigger-field='trigger_template'></td>"+
          "<td id='trigger_new_te' data-ise-trigger-field='trigger_event'></td>"+
          "<td id='trigger_new_ta' data-ise-trigger-field='target_template'></td>"+
          "<td id='trigger_new_de' data-ise-trigger-field='deploy'></td>"+
          "<td id='trigger_data_test_new' data-ise-trigger-field='test'></td>"+
          "</tr>");
      }
      var table_data = [
        {field: 'git_branch',       id: 'trigger_new_gb', val: ''},
        {field: 'code_track',       id: 'trigger_new_ct', val: ''},
        {field: 'trigger_template', id: 'trigger_new_tt', val: ''},
        {field: 'trigger_event',    id: 'trigger_new_te', val: ''},
        {field: 'target_template',  id: 'trigger_new_ta', val: ''},
        {field: 'deploy',           id: 'trigger_new_de', val: 'Yes'},
        {field: 'test',             id: 'trigger_data_test_new', val: ''}
      ];
      $('#view_triggers_row_new').addClass('view_triggers_table_highlight');
      triggers_new_fields(table_data);
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: activate */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_activate_button', function(){
    var is_disabled = $('#manage_triggers_activate_button').hasClass('disabled');
    if(!is_disabled){
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('view_triggers_table_highlight') &&
          ($(this).attr('data-ise-trigger-active')=='false'))
        {
          cur_trigger_id = $(this).attr('data-ise-trigger-id');
          trigger_update_state(cur_trigger_id, 'active');
        }
      });
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: inactivate */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_inactivate_button', function(){
    var is_disabled = $('#manage_triggers_inactivate_button').hasClass('disabled');
    if(!is_disabled){
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('view_triggers_table_highlight') &&
          ($(this).attr('data-ise-trigger-active')=='true'))
        {
          console.log('Caught Inactivate click event');
          cur_trigger_id = $(this).attr('data-ise-trigger-id');
          trigger_update_state(cur_trigger_id, 'inactive');
        }
      });
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: show inactive checkbox */
  /****************************************************************/
  $('div').on('click', '#trigger_show_inactive_checkbox', function () {
    if($(this).is(':checked')){
      // First remove any inactive rows that are already there
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('inactive_trigger')){
          $(this).remove();
        }
      });
      $.ajax({
        type: 'post',
        url:  'trigger_get_inactive.php',
        success: function(data){
          console.log(data);
          var row_count = $('#view_triggers_table tr').length;
          if(row_count > 1){
            console.log('Added to top since row_count='+row_count);
            $('#view_triggers_table > tbody > tr:first').before(data);
          }
          else{
            console.log('Appended to body since row_count='+row_count);
            $('#view_triggers_table > tbody').append(data);
          }
        }
      });//end of ajax call
    }
    else{
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('inactive_trigger')){
          $(this).remove();
        }
      });
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: edit */
  /****************************************************************/

  // global_table_data is used for temporarily storing the data that is inside
  // the rows and cells of rows that are being edited. If a cancel or submit is
  // performed, the var will be emptied.
  var global_table_data = [];
  // trigger_test_ids_list is an object that contains a mapping of trigger ids
  // and the associated test ids
  var trigger_test_ids_list = {};
  var trigger_test_ids_add_list = {};
  var trigger_test_ids_rem_list = {};
  var trigger_test_changes_saved = {};
  $('div').on('click', '#manage_triggers_edit_button', function(){
    var is_disabled = $('#manage_triggers_edit_button').hasClass('disabled');
    if(!is_disabled){
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('view_triggers_table_highlight') &&
          ($(this).attr('data-ise-trigger-active') == 'true'))
        {
          var cur_trigger_id = $(this).attr('data-ise-trigger-id');

          // Get the list of test IDs for this trigger if its editable
          $.ajax({
            type: 'post',
            url:  'triggers_get_test_ids.php',
            data: 'trigger_id='+cur_trigger_id,
            success: function(data){
              return_data = JSON.parse(data);
              trigger_test_ids_list[cur_trigger_id] = [];
              return_data.forEach(function(element){
                trigger_test_ids_list[cur_trigger_id].push(element);
                console.log('trigger_test_ids_list:');
                console.log(trigger_test_ids_list);
              });
            }
          });//end of ajax call

          table_data = $('#view_triggers_row_'+cur_trigger_id).children("td").map(function() {
            var temp_field = $(this).attr('data-ise-trigger-field');
            var temp_id = $(this).attr('id');
            var temp_val = $(this).html().trim();
            return { field:temp_field, id:temp_id, val:temp_val };
          }).get();
          global_table_data.push(table_data);
          triggers_open_fields(table_data);
          $(this).attr('data-ise-trigger-editing', 'true');
        }
      });
      $('.view_triggers_row').each(function(){
        if($(this).hasClass('view_triggers_table_highlight') &&
          ($(this).attr('data-ise-trigger-active') == 'false'))
        {
          $(this).removeClass('view_triggers_table_highlight');
          $(this).addClass('inactive_trigger');
        }
      });
      disable_trigger_create_button('Complete the current edit before trying to create a new trigger')
      disable_trigger_edit_button('Submit or cancel the current edit to enable this button again');
      enable_trigger_cancel_button('Click to cancel all edits to triggers');
      enable_trigger_submit_button('Click to submit all edits to triggers');
      disable_trigger_exec_button('Complete the current edit before trying to execute');
      disable_trigger_activate_button('Complete the current edit before trying to change state');
      disable_trigger_inactivate_button('Complete the current edit before trying to change state');
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: cancel */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_cancel_button', function(){
    var is_disabled = $('#manage_triggers_cancel_button').hasClass('disabled');
    if(!is_disabled){
      triggers_close_fields(global_table_data);
      remove_new_trigger_row();
      $('.view_triggers_row').each(function(){
        $(this).removeClass('view_triggers_table_highlight');
        $(this).attr('data-ise-trigger-editing', 'false');
      });
      disable_trigger_edit_button();
      disable_trigger_cancel_button('This button will be enabled if a trigger is being edited');
      disable_trigger_submit_button('This button will be enabled if a trigger is being edited');
      disable_trigger_exec_button();
      disable_trigger_activate_button();
      disable_trigger_inactivate_button();
      enable_trigger_create_button('Click to create a new trigger');
      new_row_created = false;

      while(global_table_data.length > 0) {
        global_table_data.pop();
      }
      trigger_test_ids_list = {};
      console.log('trigger_test_ids_list:');
      console.log(trigger_test_ids_list);
      trigger_test_ids_add_list = {};
      console.log('trigger_test_ids_add_list:');
      console.log(trigger_test_ids_add_list);
      trigger_test_ids_rem_list = {};
      console.log('trigger_test_ids_rem_list:');
      console.log(trigger_test_ids_rem_list);
      trigger_test_changes_saved = {};
      console.log('trigger_test_changes_saved:');
      console.log(trigger_test_changes_saved);
    }
  });

  /****************************************************************/
  /* Triggers Page -- Action buttons: submit */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_submit_button', function(){
    var is_disabled = $('#manage_triggers_submit_button').hasClass('disabled');
    if(!is_disabled){
      $('.view_triggers_row').each(function(){
          if($(this).attr('data-ise-trigger-editing') == 'true'){
              // Add the new test ids to the old list and remove those that have to be removed
              var temp_trigger_id = $(this).attr('data-ise-trigger-id');

              var single_trigger_test_list = trigger_test_ids_list[temp_trigger_id];
              // Only modify the main test list if the user saved the list changes
              if(trigger_test_changes_saved[temp_trigger_id] == true){
                console.log('Modifying main test list since changes were saved');
                var new_add_list = trigger_test_ids_add_list[temp_trigger_id];
                var new_rem_list = trigger_test_ids_rem_list[temp_trigger_id];
                if(single_trigger_test_list){
                  if(new_add_list){
                    single_trigger_test_list = single_trigger_test_list.concat(new_add_list);
                  }
                  if(new_rem_list){
                    single_trigger_test_list = array_subtract(single_trigger_test_list, new_rem_list);
                  }
                }
                else{
                  if(new_add_list){
                    single_trigger_test_list = new_add_list;
                  }
                  else{
                    single_trigger_test_list = [];
                  }
                }
                single_trigger_test_list = array_unique(single_trigger_test_list);
              }
              else{
                if(!single_trigger_test_list){
                  single_trigger_test_list = [];
                }
                console.log('NOT modifying main test list since changes were NOT saved');
              }
              triggers_submit_update($(this), single_trigger_test_list);
              $(this).removeClass('view_triggers_table_highlight');
              $(this).attr('data-ise-trigger-editing', 'false');
          }
      });
      disable_trigger_edit_button();
      disable_trigger_cancel_button('This button will be enabled if a trigger is being edited');
      disable_trigger_submit_button('This button will be enabled if a trigger is being edited');
      disable_trigger_exec_button();
      disable_trigger_activate_button();
      disable_trigger_inactivate_button();
      enable_trigger_create_button('Create new trigger');
      new_row_created = false;

      // Fastest way to clear an array in Javascript
      while(global_table_data.length > 0){
        global_table_data.pop();
      }
      trigger_test_ids_list = {};
      console.log('trigger_test_ids_list:');
      console.log(trigger_test_ids_list);
      trigger_test_ids_add_list = {};
      console.log('trigger_test_ids_add_list:');
      console.log(trigger_test_ids_add_list);
      trigger_test_ids_rem_list = {};
      console.log('trigger_test_ids_rem_list:');
      console.log(trigger_test_ids_rem_list);
      trigger_test_changes_saved = {};
      console.log('trigger_test_changes_saved:');
      console.log(trigger_test_changes_saved);
    }
  });

  /****************************************************************/
  /* Triggers Page -- Test list additions */
  /****************************************************************/

  // For the trigger's test list moditication
  $('div').on('change', '#triggers_add_test_select', function(){
    // Add test ID to list if its not already selected
    var new_test_id = $('option:selected', this).attr('data-ise-triggers-add-test-id');
    new_test_id = parseInt(new_test_id);
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    // Check the current trigger has values in our object. Add it if it doesn't
    if(!(cur_trigger_id in trigger_test_ids_add_list)){
      trigger_test_ids_add_list[cur_trigger_id] = [];
    }
    found_id = jQuery.inArray(new_test_id, trigger_test_ids_add_list[cur_trigger_id]);
    // Add the test id to the array
    if(found_id == -1){
      trigger_test_ids_add_list[cur_trigger_id].push(new_test_id);
      // Add the new item to the list
      $('#triggers_tests_add_intro_text_id').hide();
      var temp_test_name = $('#triggers_add_test_select').val();
      $('#triggers_add_test_ol').append(
        "<li class='triggers_tests_listing_add_li_animation' data-ise-triggers-test-id='"+new_test_id+"'"+
        ">"+temp_test_name+
        "<p class='triggers_actions_buttons triggers_tests_listing_remove_button_class'"+
        "id='triggers_tests_add_list_remove_button_"+new_test_id+"'"+
        "title=\"Click to remove '"+temp_test_name+"' from Add list\">"+
        "x"+
        "</p>"+
        "</li>"
      );
      $('.triggers_tests_listing_add_li_animation').fadeIn('fast');
    }
    console.log('trigger_test_ids_add_list:');
    console.log(trigger_test_ids_add_list[cur_trigger_id]);
  });

  /****************************************************************/
  /* Triggers Page -- Test list additions removals */
  /****************************************************************/

  $('div').on('click', '[id^=triggers_tests_add_list_remove_button]', function(){
    // Add test ID to list if its not already selected
    var new_test_id = $(this).parent('li').attr('data-ise-triggers-test-id');
    new_test_id = parseInt(new_test_id);
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    // Check the current trigger has values in our object. Add it if it doesn't
    if(!(cur_trigger_id in trigger_test_ids_add_list)){
      trigger_test_ids_add_list[cur_trigger_id] = [];
    }
    found_id = jQuery.inArray(new_test_id, trigger_test_ids_add_list[cur_trigger_id]);
    // Remove the test from the add list
    if(found_id != -1){
      trigger_test_ids_add_list[cur_trigger_id].splice(found_id, 1);
      $(this).parent('li').remove();
      if(!trigger_test_ids_add_list[cur_trigger_id].length){
        $('#triggers_tests_add_intro_text_id').fadeIn('fast');
      }
    }
    else{
      console.log('Test not in trigger_test_ids_add_list list');
    }
    console.log('trigger_test_ids_add_list:');
    console.log(trigger_test_ids_add_list[cur_trigger_id]);
  });

  /****************************************************************/
  /* Triggers Page -- Test list removals */
  /****************************************************************/

  $('div').on('click', '[id^=triggers_tests_listing_remove_button]', function(){
    var new_test_id = $(this).parent('td').attr('data-ise-triggers-test-id');
    new_test_id = parseInt(new_test_id);
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    // Check the current trigger has values in our object. Add it if it doesn't
    if(!(cur_trigger_id in trigger_test_ids_rem_list)){
      trigger_test_ids_rem_list[cur_trigger_id] = [];
    }
    found_id = jQuery.inArray(new_test_id, trigger_test_ids_rem_list[cur_trigger_id]);
    // Remove from the array if its in it
    if(found_id == -1){
      trigger_test_ids_rem_list[cur_trigger_id].push(new_test_id);

      // Add highlighting and list item
      $('#triggers_tests_remove_intro_text_id').hide();
      $(this).parent('td').parent('tr').addClass('triggers_tests_remove_highlighting');
      var temp_test_name = $(this).parent('td').attr('data-ise-triggers-test-name');
      $('#triggers_remove_tests_ol').append(
        "<li class='triggers_tests_listing_remove_li_animation' data-ise-triggers-test-id='"+new_test_id+"'"+
        ">"+temp_test_name+
        "<p class='triggers_actions_buttons triggers_tests_listing_remove_button_class'"+
        "id='triggers_tests_remove_list_remove_button_"+new_test_id+"'"+
        "title=\"Click to remove '"+temp_test_name+"' from Remove list\">"+
        "x"+
        "</p>"+
        "</li>"
      );
      $(this).hide();
      $('.triggers_tests_listing_remove_li_animation').fadeIn('fast');
    }
    console.log('trigger_test_ids_rem_list:');
    console.log(trigger_test_ids_rem_list[cur_trigger_id]);
  });

  /****************************************************************/
  /* Triggers Page -- Test Remove list removals */
  /****************************************************************/

  $('div').on('click', '[id^=triggers_tests_remove_list_remove_button]', function(){
    var new_test_id = $(this).parent('li').attr('data-ise-triggers-test-id');
    new_test_id = parseInt(new_test_id);
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    if(!(cur_trigger_id in trigger_test_ids_rem_list)){
      trigger_test_ids_rem_list[cur_trigger_id] = [];
    }
    found_id = jQuery.inArray(new_test_id, trigger_test_ids_rem_list[cur_trigger_id]);
    // Remove from the array if its in it
    if(found_id != -1){
      console.log('Removing test '+new_test_id+' from remove list');
      trigger_test_ids_rem_list[cur_trigger_id].splice(found_id, 1);
      // Remove highlighting and remove formatting from table
      $('#triggers_tests_modal_table_row_'+new_test_id).removeClass('triggers_tests_remove_highlighting');
      $('#triggers_tests_listing_remove_button_'+new_test_id).show();
      $(this).parent('li').remove();
      if(!trigger_test_ids_rem_list[cur_trigger_id].length){
        $('#triggers_tests_remove_intro_text_id').fadeIn('fast');
      }
    }
    else{
      console.log('Test not in trigger_test_ids_rem_list list');
    }
    console.log('trigger_test_ids_rem_list:');
    console.log(trigger_test_ids_rem_list[cur_trigger_id]);
  });

  /****************************************************************/
  /* Triggers Page -- Test Changes Cancel */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_cancel_tests_button', function(){
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    console.log('cur_trigger_id: '+cur_trigger_id);
    if(!(cur_trigger_id in trigger_test_changes_saved)){
      trigger_test_changes_saved[cur_trigger_id] = false;
    }
    trigger_test_changes_saved[cur_trigger_id] = false;
    console.log('trigger_test_changes_saved:');
    console.log(trigger_test_changes_saved[cur_trigger_id]);
    $('#triggers_tests_modal_close_button').trigger('click');
    trigger_test_ids_add_list = {};
    trigger_test_ids_rem_list = {};
  });

  /****************************************************************/
  /* Triggers Page -- Test Changes Save */
  /****************************************************************/

  $('div').on('click', '#manage_triggers_save_tests_button', function(){
    var cur_trigger_id = $('#trigger_tests_modal').attr('data-ise-trigger-id');
    console.log('cur_trigger_id: '+cur_trigger_id);
    if(!(cur_trigger_id in trigger_test_changes_saved)){
      trigger_test_changes_saved[cur_trigger_id] = false;
    }
    trigger_test_changes_saved[cur_trigger_id] = true;
    console.log('trigger_test_changes_saved:');
    console.log(trigger_test_changes_saved[cur_trigger_id]);
    $('#triggers_tests_modal_close_button').trigger('click');
  });

  /****************************************************************/
  /* Triggers Page -- Code Track Auto Move */
  /****************************************************************/

  // Automatically move focus to next textbox in code track cell
  // as soon as user has provided three characters
  $('div').on('keyup', '[id^=code_track_textbox]', function() {
    if($(this).val().length == 3){
      if( !(event.keyCode == 16 || event.keyCode == 9) ){
        var cur_id = $(this)[0].id;
        if(cur_id == 'code_track_textbox1')
          $('#code_track_textbox2').focus();
        else if(cur_id == 'code_track_textbox2')
          $('#code_track_textbox3').focus();
      }
    }
    // Prevent values if Release Branch has data
    if($('#git_branch_textbox').val().length > 0){
      $('#code_track_textbox1').prop('value', '');
      $('#code_track_textbox2').prop('value', '');
      $('#code_track_textbox3').prop('value', '');
      alert('Either \'Release Branch\' or \'Code Track\' may have a value. If you would like '+
        'to provide a value for \'Code Track\', please first empty the textbox for '+
        '\'Release Branch.\'');
      return;
    }
    if($('#code_track_textbox1').val().length > 0 ||
       $('#code_track_textbox2').val().length > 0 ||
       $('#code_track_textbox3').val().length > 0 ){
      $('[id^=git_branch_textbox]').prop('disabled', true);
    }
    else{
      $('[id^=git_branch_textbox]').prop('disabled', false);
    }
  });

  // Prevent both release branch and code track in triggers column from having values.
  // They are mutually exclusive
  $('div').on('keyup', '#git_branch_textbox', function() {

    if($('#code_track_textbox1').val().length > 0 ||
       $('#code_track_textbox2').val().length > 0 ||
       $('#code_track_textbox3').val().length > 0 ){
      $('#git_branch_textbox').prop('value', '');
      alert('Either \'Release Branch\' or \'Code Track\' may have a value. If you would like '+
        'to provide a value for \'Release Branch\', please first empty the textboxes for '+
        '\'Code Track.\'');
      return;
    }

    if($(this).val().length > 0){
      $('[id^=code_track_textbox]').prop('disabled', true);
    }
    else{
      $('[id^=code_track_textbox]').prop('disabled', false);
    }
  });

}); // end of document ready function


function enable_trigger_edit_button(button_title){
  $('#manage_triggers_edit_button').removeClass('disabled');
  $('#manage_triggers_edit_button').attr('title', button_title);
}

function enable_trigger_cancel_button(button_title){
  $('#manage_triggers_cancel_button').removeClass('disabled');
  $('#manage_triggers_cancel_button').attr('title', button_title);
}

function enable_trigger_submit_button(button_title){
  $('#manage_triggers_submit_button').removeClass('disabled');
  $('#manage_triggers_submit_button').attr('title', button_title);
}

function enable_trigger_create_button(button_title){
  $('#manage_triggers_create_button').removeClass('disabled');
  $('#manage_triggers_create_button').attr('title', button_title);
}

function enable_trigger_exec_button(button_title){
  $('#manage_triggers_exec_button').removeClass('disabled');
  $('#manage_triggers_exec_button').attr('title', button_title);
}

function enable_trigger_modal_exec_button(button_title){
  $('#trigger_modal_exec_button').removeClass('disabled');
  $('#trigger_modal_exec_button').attr('title', button_title);
}

function enable_trigger_modal_exec_label_textbox(button_title){
  $('#trigger_exec_label').prop('disabled', false);
  $('#trigger_exec_label').attr('title', button_title);
}

function enable_trigger_activate_button(button_title){
  $('#manage_triggers_activate_button').removeClass('disabled');
  $('#manage_triggers_activate_button').attr('title', button_title);
}

function enable_trigger_inactivate_button(button_title){
  $('#manage_triggers_inactivate_button').removeClass('disabled');
  $('#manage_triggers_inactivate_button').attr('title', button_title);
}

function disable_trigger_edit_button(button_title){
  if(!button_title){
    button_title = 'Select a trigger from the table to enable this edit button';
  }
  $('#manage_triggers_edit_button').addClass('disabled');
  $('#manage_triggers_edit_button').attr('title', button_title);
}

function disable_trigger_cancel_button(button_title){
  $('#manage_triggers_cancel_button').addClass('disabled');
  $('#manage_triggers_cancel_button').attr('title', button_title);
}

function disable_trigger_submit_button(button_title){
  $('#manage_triggers_submit_button').addClass('disabled');
  $('#manage_triggers_submit_button').attr('title', button_title);
}

function disable_trigger_create_button(button_title){
  $('#manage_triggers_create_button').addClass('disabled');
  $('#manage_triggers_create_button').attr('title', button_title);
}

function disable_trigger_exec_button(button_title){
  if(!button_title){
    button_title = 'Select a trigger from the table to enable this execute button';
  }
  $('#manage_triggers_exec_button').addClass('disabled');
  $('#manage_triggers_exec_button').attr('title', button_title);
}

function disable_trigger_modal_exec_button(button_title){
  $('#trigger_modal_exec_button').addClass('disabled');
  $('#trigger_modal_exec_button').attr('title', button_title);
}

function disable_trigger_modal_exec_label_textbox(button_title){
  $('#trigger_exec_label').prop('disabled', true);
  $('#trigger_exec_label').attr('title', button_title);
}

function disable_trigger_activate_button(button_title){
  if(!button_title){
    button_title = 'This button will be enabled if an inactive trigger is selected';
  }
  $('#manage_triggers_activate_button').addClass('disabled');
  $('#manage_triggers_activate_button').attr('title', button_title);
}

function disable_trigger_inactivate_button(button_title){
  if(!button_title){
    button_title = 'This button will be enabled if an active trigger is selected';
  }
  $('#manage_triggers_inactivate_button').addClass('disabled');
  $('#manage_triggers_inactivate_button').attr('title', button_title);
}

function triggers_new_fields(table_data)
{
  console.log(table_data);
  table_data.forEach(function(element) {
    $.ajax({
      type: 'get',
      url:  'trigger_mod_get_values.php',
      data: 'field='+element.field+'&def_value='+element.val.trim(),
      success: function(data){
        $('#'+element.id).html(data);
        $('#'+element.id).addClass('triggers_open_fields_styling');
        $('#'+element.id).children('select').each(function() {
          $(this).addClass('triggers_open_fields_selects_styling');
        });
      }
    });
  });
}

function triggers_open_fields(table_data)
{
  var num_tests = 0;
  table_data.forEach(function(element) {
    if(element.field == 'test'){
      num_tests = element.val.trim();
    }
  });
  table_data.forEach(function(element) {
    $.ajax({
      type: 'get',
      url:  'trigger_mod_get_values.php',
      data: 'field='+element.field+'&def_value='+element.val.trim(),
      success: function(data){
        $('#'+element.id).html(data);
        $('#'+element.id).addClass('triggers_open_fields_styling');
        $('#'+element.id).children('select').each(function() {
          $(this).addClass('triggers_open_fields_selects_styling');
        });
      }
    });
  });
}

function triggers_close_fields(global_table_data)
{
  global_table_data.forEach(function(row){
    row.forEach(function(element){
      $('#'+element.id).html(element.val);
      $('#'+element.id).removeClass('triggers_open_fields_styling');
    });
  });
}

function remove_new_trigger_row()
{
  $('#view_triggers_row_new').remove();
}

function triggers_closed_fields_data_extraction(trigger_row){
  var cur_trigger_id        = trigger_row.attr('data-ise-trigger-id');
  var temp_git_branch       = '';
  var temp_code_track       = '';
  var temp_trigger_template = '';
  var temp_trigger_event    = '';
  var temp_target_template  = '';

  console.log('cur_trigger_id: '+cur_trigger_id);
  trigger_row.children("td").each(function() {
    if($(this).attr('data-ise-trigger-field') == 'git_branch'){
      temp_git_branch = $(this).html().trim();
      console.log('found git branch: '+temp_git_branch);
    }
    else if($(this).attr('data-ise-trigger-field') == 'code_track'){
      temp_code_track = $(this).html().trim();
      console.log('found code track: '+temp_code_track);
    }
    else if($(this).attr('data-ise-trigger-field') == 'trigger_template'){
      temp_trigger_template = $(this).html().trim();
      console.log('found trigger template: '+temp_trigger_template);
    }
    else if($(this).attr('data-ise-trigger-field') == 'trigger_event'){
      temp_trigger_event = $(this).html().trim();
      console.log('found trigger event: '+temp_trigger_event);
    }
    else if($(this).attr('data-ise-trigger-field') == 'target_template'){
      temp_target_template = $(this).html().trim();
      console.log('found target template: '+temp_target_template);
    }
  });
  return {
    trigger_id:       cur_trigger_id,
    git_branch:       temp_git_branch,
    code_track:       temp_code_track,
    trigger_template: temp_trigger_template,
    trigger_event:    temp_trigger_event,
    target_template:  temp_target_template
  }
}

function triggers_open_fields_data_extraction(trigger_row){
  var cur_trigger_id        = trigger_row.attr('data-ise-trigger-id');
  var temp_git_branch       = '';
  var temp_code_track       = '';
  var temp_trigger_template = '';
  var temp_trigger_event    = '';
  var temp_target_template  = '';
  var ct1, ct2, ct3         = '';

  console.log('cur_trigger_id: '+cur_trigger_id);
  trigger_row.children("td").each(function() {
    if($(this).attr('data-ise-trigger-field') == 'git_branch'){
      $(this).find("input").each(function() {
        temp_git_branch = $(this).val();
      });
      console.log('found git branch: '+temp_git_branch);
    }
    else if($(this).attr('data-ise-trigger-field') == 'code_track'){
      var cntr = 1;
      $(this).find("input").each(function() {
        if(cntr == 1) ct1 = $(this).val();
        if(cntr == 2) ct2 = $(this).val();
        if(cntr == 3) ct3 = $(this).val();
        cntr += 1;
      });
      if(ct1 && ct2 && ct2){
        temp_code_track = ct1+'.'+ct2+'.'+ct3;
      }
      console.log('found code track: '+temp_code_track);
    }
    else if($(this).attr('data-ise-trigger-field') == 'trigger_template'){
      temp_trigger_template = $(this).children('select').val();
      console.log('found trigger template: '+temp_trigger_template);
    }
    else if($(this).attr('data-ise-trigger-field') == 'trigger_event'){
      temp_trigger_event = $(this).children('select').val();
      console.log('found trigger event: '+temp_trigger_event);
    }
    else if($(this).attr('data-ise-trigger-field') == 'target_template'){
      temp_target_template = $(this).children('select').val();
      console.log('found target template: '+temp_target_template);
    }
  });
  return {
    trigger_id:       cur_trigger_id,
    git_branch:       temp_git_branch,
    code_track:       temp_code_track,
    trigger_template: temp_trigger_template,
    trigger_event:    temp_trigger_event,
    target_template:  temp_target_template
  }
}

// Take in a row object and look through all of the td's for the info
// needed to update the trigger
function triggers_submit_update(trigger_row, single_trigger_test_list)
{
  resulting_data = triggers_open_fields_data_extraction(trigger_row);
  data_to_send  = 'trigger_id='            + resulting_data.trigger_id;
  data_to_send += '&new_git_branch='       + resulting_data.git_branch;
  data_to_send += '&new_code_track='       + resulting_data.code_track;
  data_to_send += '&new_trigger_template=' + resulting_data.trigger_template;
  data_to_send += '&new_trigger_event='    + resulting_data.trigger_event;
  data_to_send += '&new_target_template='  + resulting_data.target_template;

  data_to_send += '&new_test_list='+JSON.stringify(single_trigger_test_list);
  console.log('Submitting this list:');
  console.log(single_trigger_test_list);
  console.log('data_to_send:');
  console.log(data_to_send);

  $.ajax({
    type: 'get',
    url:  'trigger_update_values.php',
    data: data_to_send,
    success: function(data){
      if(data.trim() == 'ok'){
        console.log('Received OK from Python');
        // Now update the cells with the new values
        $.ajax({
          type: 'get',
          url:  'trigger_get_row.php',
          data: 'trigger_id='+resulting_data.trigger_id,
          success: function(data){
            console.log(data);
            trigger_row.replaceWith(data);
          }
        });
      }
      else{
        console.log('Did not receive OK from Python');
        error_handling(data);
      }
    }
  });
}

function send_trigger_exec_message(trigger_id, value, label){

  $.ajax({
    type: 'get',
    url:  'trigger_execution.php',
    data: 'trigger_id='+trigger_id+'&value='+value+'&label='+label,
    success: function(data){
      if(data.trim() == 'ok'){
        console.log('Received OK from Python');
        show_toast('Successfully submitted trigger');
      }
      else{
        console.log('Did not receive OK from Python');
        error_handling(data);
      }
    }
  });
}

function triggers_row_remove_highlights(){
  $('.view_triggers_row').each(function(){
    if($(this).hasClass('view_triggers_table_highlight')){
      $(this).removeClass('view_triggers_table_highlight');
    }
  });
}

function trigger_update_state(trigger_id, state)
{
  data_to_send = 'trigger_id='+trigger_id+'&state='+state;
  console.log(data_to_send);
  $.ajax({
    type: 'get',
    url:  'trigger_update_state.php',
    data: data_to_send,
    success: function(data){
      if(data.trim() == 'ok'){
        console.log('Received OK from Python');
        triggers_row_remove_highlights();
        disable_trigger_exec_button();
        disable_trigger_activate_button();
        disable_trigger_inactivate_button();
        disable_trigger_edit_button();
        if(state == 'active'){
          $('#view_triggers_row_'+trigger_id).attr('data-ise-trigger-active', 'true');
        }
        else if(state == 'inactive'){
          $('#view_triggers_row_'+trigger_id).attr('data-ise-trigger-active', 'false');
          $('#view_triggers_row_'+trigger_id).addClass('inactive_trigger');
        }
      }
      else{
        console.log('Did not receieve OK from Python');
        error_handling(data);
      }
    }
  });
}

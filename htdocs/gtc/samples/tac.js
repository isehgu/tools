
$(document).ready(function(){
    
    /*
  //First populating the test suite table
  $.ajax({
          type: 'post',
          url:  'table_display.php',
          data: 'type=suite',
          datatype: 'json',
          success:  function(data)
          {
            var result_html = "";
            var return_array = jQuery.parseJSON(data);
            for (var i=0;i<return_array.length;i++) {
              var test_suite = return_array[i];
              //console.log(return_array[i]);
              result_html += "<tr><td><input type='checkbox' name='suites[]' value='"+test_suite.id+"'> ";
              result_html += test_suite.name+"</td>";
              result_html += "<td>"+test_suite.description+"</td></tr>";
            }
            //By now result_html would have all the table struction for test suite
            $('#test_suite_table').html(result_html);
          }
  }); //End of ajax foo test_suite_table
  
  //Populating the test table
  $.ajax({
          type: 'post',
          url:  'table_display.php',
          data: 'type=test',
          datatype: 'json',
          success:  function(data)
          {
            var result_html = "";
            var return_array = jQuery.parseJSON(data);
            for (var i=0;i<return_array.length;i++) {
              var test_case = return_array[i];
              result_html += "<tr><td><input type='checkbox'  name='tests[]' value='"+test_case.id+"'> ";
              result_html += test_case.name+"</td>";
              result_html += "<td>"+test_case.description+"</td></tr>";
            }
            //By now result_html would have all the table struction for test suite
            $('#test_table').html(result_html);
          }
  }); //End of ajax foo test_table
    */
  $('#runbtn').click(function(){
    var suites = [];
    var tests = [];
    
    $("input[name='suites[]']:checked").each(function(){
      suites.push($(this).val());
      //console.log($(this).val());
    });
    
    $("input[name='tests[]']:checked").each(function(){
      tests.push($(this).val());
      //console.log($(this).val());
    });
    
    if(suites.length == 0 && tests.length == 0)
    {
      alert("You did NOT select any test. Please select test to run.");
    }
    else
    {
      //Get lock checkbox, and reason
      var lock_check = $("input[name=env_lock]:checked").val();
      var lock_reason = $("input[name=lock_reason]").val();
      var lock_reason = escapeHtml(lock_reason);
      
      if(lock_check == 1) //if lock requested
      {
        //if lock is enabled, then if more than one test is submited, alert and do not submit data
        if((suites.length > 0 || tests.length > 1))
        {
          alert("ONLY test request with SINGLE test case can have env lock enabled. Please uncheck additional tests.");
        }
        else //if only one test submited with lock, then proceed
        {
          var lock_check_input = "<input type='hidden' name='real_env_lock' value='1'>";
          var lock_reason_input = "<input type='hidden' name='real_lock_reason' value='"+lock_reason+"'>";
          $('#test_request').append(lock_check_input);
          $('#test_request').append(lock_reason_input);
          
          var label = prompt("Please enter a Label for the test run");
          var label_input = "<input type='hidden' name='label' value='"+escapeHtml(label)+"'>";
          $('#test_request').append(label_input);
          $('#test_request').submit();
        }//end of if((suites.length > 0 || tests.length > 1))
      }
      else //If lock is not enabled, just proceed normally
      {
        var label = prompt("Please enter a Label for the test run");
        var label_input = "<input type='hidden' name='label' value='"+escapeHtml(label)+"'>";
        $('#test_request').append(label_input);
        $('#test_request').submit();
      }//end of if(lock_check == 1)
      
    }//end of if(suites.length == 0 && tests.length == 0)
  }); //end of runbtn click
  
  function escapeHtml(text) {
    var characters = {
      '&': '&amp;',
      '"': '&quot;',
      "'": '&#039;',
      '<': '&lt;',
      '>': '&gt;'
    };
    return (text + "").replace(/[<>&"']/g, function(m){
      return characters[m];
    });
  };
  
  //Filtering all 4 tables
  
  $('#test_request_search').on('keyup', function(){
    var rex = new RegExp($(this).val(),'i');
    $('.test_request_searchable tr').hide();
    $('.test_request_searchable tr').filter(function(){
      return rex.test($(this).text());
    }).show();
  });
  
  $('#progress_search').on('keyup', function(){
    var rex = new RegExp($(this).val(),'i');
    $('#progress_searchable tr').hide();
    $('#progress_searchable tr').filter(function(){
      return rex.test($(this).text());
    }).show();
  });
  
  $('#queue_search').on('keyup', function(){
    var rex = new RegExp($(this).val(),'i');
    $('#queue_searchable tr').hide();
    $('#queue_searchable tr').filter(function(){
      return rex.test($(this).text());
    }).show();
  });
  
  $('#history_search').on('keyup', function(){
    var rex = new RegExp($(this).val(),'i');
    $('#history_searchable tr').hide();
    $('#history_searchable tr').filter(function(){
      return rex.test($(this).text());
    }).show();
  });
  
  //Interact with table -- ajax, and hide
  
  //progress table
  $('tbody').on('click','.test_kill_btn',function(){
    var rid = $(this).attr('id');
    var tr_selector = '#p_'+rid;
    var answer = confirm("Really kill this test?");
    var pwd = prompt("Enter Admin Password");
    if (answer) {
      $.ajax({
        type: 'get',
        url:  'test_action.php',
        data: 'action=kill&rid='+rid+'&pwd='+pwd,
        success: function(data){
          if(data == 'ok')
          {
            $(tr_selector).hide();
            //Stat Count update
            $.ajax({ //progress count ajax
              type: 'get',
              url:  'stat_count.php',
              data: 'type=progress',
              success: function(count){
                $('#progress_stat').text(count);
                //console.log(data);
              }
            });//end of progress count ajax
        
            $.ajax({ //history count ajax
              type: 'get',
              url:  'stat_count.php',
              data: 'type=history',
              success: function(count){
                $('#history_stat').text(count);
                //console.log(data);
              }
            });//end of history count ajax
            
            $.ajax({ //update history table
              type: 'get',
              url:  'history_update.php',
              data: 'rid='+rid,
              success: function(row){
                $('#history_searchable').prepend(row);
                //console.log(data);
              }
            });//end of history table update ajax
          }//end of if
          else{
            alert(data);
            console.log(data);
          }
        }
      });//end of ajax
    }//end of if(answer)
    return false;//On the test_kill_btn click 
  });//End of test_kill_btn ajax
  //////////////////////////////////////////////////////////////////////////
  //queue table
  $('tbody').on('click','.test_cancel_btn',function(){
    var rid = $(this).attr('id');
    var tr_selector = '#q_'+rid;
    $.ajax({
      type: 'get',
      url:  'test_action.php',
      data: 'action=cancel&rid='+rid,
      success: function(data){
        if(data == 'ok')
        {
          $(tr_selector).hide();
          //Stat Count update    
          $.ajax({ //history count ajax
            type: 'get',
            url:  'stat_count.php',
            data: 'type=history',
            success: function(count){
              $('#history_stat').text(count);
              //console.log(data);
            }
          });//end of history count ajax
          
          $.ajax({ //queue count ajax
            type: 'get',
            url:  'stat_count.php',
            data: 'type=queue',
            success: function(count){
              $('#queue_stat').text(count);
              //console.log(data);
            }
          });//end of queue count ajax
          
          $.ajax({ //update history table
              type: 'get',
              url:  'history_update.php',
              data: 'rid='+rid,
              success: function(row){
                $('#history_searchable').prepend(row);
                //console.log(data);
              }
          });//end of history table update ajax
        }//end of if
        else{
            alert(data);
            console.log(data);
        }
      }//end of success
    });//end of ajax
    return false;
  });
  ////////////////////////////////////////////////////////////////////////////
  //env table -- lock
  $('tbody').on('click','.env_lock_btn',function(){
    var eid = $(this).attr('id');
    var current_selector = $(this);
    var reason = prompt("Enter a reason for locking env:");
    var user = $.cookie('user');

    $.ajax({
      type: 'get',
      url:  'env_action.php',
      data: 'action=lock&eid='+eid+'&reason='+encodeURIComponent(reason),
      success: function(data){
        if(data == 'ok')
        {
          current_selector.removeClass('env_lock_btn btn-danger').addClass('env_unlock_btn btn-success').text('Unlock it');
           //Stat Count update
          $.ajax({
            type: 'get',
            url:  'stat_count.php',
            data: 'type=env',
            success: function(count){
              $('#env_stat').text(count);
              console.log(count);
            }
          });//end of env count ajax
          $('#lock_reason_display_'+eid).text(reason);
          $('#lock_user_display_'+eid).text(user);
        }//end of if
        else{
            alert(data);
            console.log(data);
        }
      }
    });//end of ajax

    return false;
  });
  
  //env table -- unlock
  $('tbody').on('click','.env_unlock_btn',function(){
    var eid = $(this).attr('id');
    var current_selector = $(this);
    $.ajax({
      type: 'get',
      url:  'env_action.php',
      data: 'action=unlock&eid='+eid,
      success: function(data){
        if(data == 'ok')
        {
          current_selector.removeClass('env_unlock_btn btn-success').addClass('env_lock_btn btn-danger').text('Lock it');
          //Stat Count update
          $.ajax({
            type: 'get',
            url:  'stat_count.php',
            data: 'type=env',
            success: function(count){
              $('#env_stat').text(count);
              console.log(count);
            }
          });//end of env count ajax
          $('.e_'+eid).each(function(){
            $(this).text('');
          });
        }//end of if
        else{
            alert(data);
            console.log(data);
        }
      }
    });//end of ajax

    return false;
  });
  
  //Testing page reload on side navbar links
  
  $('.sidelink').click(function(){
    //event.preventDefault();
    var target = $(this).attr('href');
    console.log(target);
    window.location.assign(target);
    window.location.reload(true);
  });
  
});
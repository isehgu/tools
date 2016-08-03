// Global Settings
var STYLE = 2;
var selected_tests_panel_button_added = false;

$(document).ready(function(){

  if(STYLE == 2){
    $('#data-ise-username').addClass('input-styling');
    $('#data-ise-password').addClass('input-styling');
  }
  // Set the focus on the login box, if it exists
  // $('#data-ise-username').focus();
  //Global variable
  var second_bar_active = "";

  //End of global constant

  //For shrinking
  /*
      function init() {
    window.addEventListener('scroll', function(e){
        var distanceY = window.pageYOffset || document.documentElement.scrollTop,
            shrinkOn = 500,
            section_header = document.querySelector(".section_header");
            header_h1 = document.querySelector(".header_text");
            header_stats = document.querySelector(".header_stats");
        if (distanceY > shrinkOn) {
            classie.add(section_header,"shrink");
            classie.add(header_text,"shrink");
            classie.add(header_stats,"shrink");
        } else {
            if (classie.has(header,"smaller")) {
                classie.remove(header,"smaller");
            }
        }
    });
  }


  $(window).on('scroll','#content',function(){
    console.log("Yay, scrolled");
  });
  $(document).scroll(function(){
    //var bottom = $('#slidebar').offset().top + $('#slidebar').outerHeight(true);
    //var top = $('.section_header').offset().top;
    //console.log(top);
  });
  $("#slidebar").bind('scroll', function(){
    var bottom = $('#slidebar').offset().top + $('#slidebar').outerHeight(true);
    if(bottom > 100)
    {
      console.log(bottom);
    }
  });
*/



  //End of Top bar link's active class handling

  //2nd top bar slide
  var history_slide = "<dd><a class='second_bar_link' id='history_by_release' href='#'>Releases</a></dd> \
                     <dd><a class='second_bar_link' id='history_by_template' href='#'>Templates</a></dd>";

  var task_slide = "<dd><a class='second_bar_link' id='tasks_by_template' href='#'>Templates</a></dd>";

  var test_slide = "<dd><a class='second_bar_link' id='test_live' href='#'>Live Tests</a></dd> \
                    <dd><a class='second_bar_link' id='test_user_history_by_label' href='#'>My History By Label</a></dd> \
                    <dd><a class='second_bar_link' id='test_user_history_by_test' href='#'>My History By Test</a></dd> \
                    <dd><a class='second_bar_link' id='test_complete_history' href='complete_test_history.php' target='_blank'>Complete History</a></dd> \
                    <dd><a class='second_bar_link' id='test_request' href='#'>New Request</a></dd>";

  var trigger_slide = "<dd><a class='second_bar_link' id='manage_my_triggers' href='#'>Manage</a></dd> \
                       <dd><a class='second_bar_link' id='view_all_triggers' href='#'>View All</a></dd>";

  var statistics_slide = "<dd><a class='second_bar_link' id='statistics_summary' href='#'>Summary</a></dd> \
                          <dd><a class='second_bar_link' id='statistics_detailed' href='#'>Detailed</a></dd>";

  var tools_slide = "<dd><a class='second_bar_link' id='etcd' href='#'>ETCD</a></dd>";

  //var debug_slide = "<dd><a class='second_bar_link' id='events' href='#'>Events</a></dd>";

  $('#history_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(history_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast");
    //$(document).foundation();
  });

  $('#task_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(task_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast", function(){
      $('#tasks_by_template').trigger('click');
    });
  });

  //This is special for Templates, no 2nd bar, just change the whole page
  $('#template_btn').click(function(){
    //Need to hide the 2nd bar because template link doesn't have 2nd bar
    $('#slidebar').hide();
    $('#content').html(
      "<div class='row'>" +
        "<div id='loading_div' class='animate-flicker'>" +
          "<p>Loading</p>" +
        "</div>" +
      "</div>"
    );
    //Need to reset the 2nd bar active link because page content changed
    second_bar_active = "";
    var link_id = "templates_display";
    $.ajax({
      type: 'post',
      url:  'content_display.php',
      data: 'link_id='+link_id,
      success: function(data){
        $('#content').hide().html(data).fadeIn('fast');
        // Clear out variable
        requested_test_ids = [];
        $("html, body").animate({scrollTop: 0}, "fast");
      }
    });//end of ajax call
    return false;

  } );

  $('#test_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(test_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast");
    //console.log("here");
  });

  $('#trigger_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(trigger_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast", function(){
    });
  });

  $('#debug_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(debug_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast", function(){
    });
  });

  $('#statistics_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(statistics_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast", function(){
    });
  });

  $('#tools_btn').click(function(){
    $('#slidebar').hide();
    $('#slidebar').html(tools_slide);
    if(second_bar_active) {
      $('#'+second_bar_active).parent('dd').addClass('active');
    }
    $('#slidebar').slideDown("fast", function(){
    });
  });

  //End of 2nd top bar slide





  //2nd top bar link click
  $('div').on('click','.second_bar_link',function(){

    var link_id = $(this).attr('id');

    //Remove other active link
    $(this).parent('dd').siblings('dd').removeClass('active');
    $(this).parent('dd').addClass('active');

    second_bar_active = link_id; //Remember the active 2nd bar link in the global var
    $('#content').html(
      "<div class='row'>" +
        "<div id='loading_div' class='animate-flicker'>" +
          "<p>Loading</p>" +
        "</div>" +
      "</div>"
    );

    //console.log("click detected");
    $.ajax({
      type: 'post',
      url:  'content_display.php',
      data: 'link_id='+link_id,
      success: function(data){

        $('#content').hide().html(data).fadeIn('fast');

        // Clear out variable
        requested_test_ids = [];
        //Important -- zurb's content like tooltip only applies to static content
        //For dynamic content -- ajax, hidden then shown element -- you will
        //need to call $(document).foundation() again to re-apply zurb
        // var t1 = new Date();
        //$(":visible").foundation();
        //$(document).foundation('tooltip');

        // var t2 = new Date();
        // var diff = (t2.getTime() - t1.getTime())/1000;
        console.log(diff);
        $("html, body").animate({scrollTop: 0}, "fast");


        // console.log(t1);
        // console.log(t2);

        //console.log(data);
      }//end of success function inside ajax call
    });//end of ajax call

    return false;
  });

  //End of 2nd top bar link click

  //Toggle section body
  $('div').on('click','.section_header',function(){
    var t1 = new Date();

        var t2 = new Date();
        var diff = (t2.getTime() - t1.getTime())/1000;
    var header_id = $(this).attr('id');
    var release_id = $(this).attr('data-ise-release-id');

    //console.log(release_id);

    var temp = header_id.split("header_");
    var release_version_dash = temp[1];

    //console.log(release_version_dash);
    var section_id = 'section_body_'+temp[1];

    // var appending_text = "<div id='"+section_id+"'>Section Body</div>";

    // //if section_body doesn't exist, then insert it. Otherwise just toggle
    // //hide() is needed to correctly toggle. Without hide(), section_body would show up
    // //after appending, and then slideToggle() would then hide it. User would see that section_body
    // //and then see it sliding out of view.
    // if($("#"+section_id).length == 0)
    // {
    //   $(this).parents('div.section_wrapper').append(appending_text);
    //   $("#"+section_id).hide();
    // }

    $("#"+section_id).slideToggle('slow');
    //console.log($(this).css('border-bottom-color'));
    if($(this).css('border-bottom-color') == 'rgb(166, 166, 166)'){
      $(this).css('border-bottom', '1px solid #FFFFFF');
    }
    else{
      $(this).css('border-bottom', '1px solid #A6A6A6');
    }

    // var t2 = new Date();
    // var diff = (t2.getTime() - t1.getTime())/1000;
    // console.log("pre-foundation took "+diff);

    //Important -- zurb's content like tooltip only applies to static content
    //For dynamic content -- ajax, hidden then shown element -- you will
    //need to call $(document).foundation() again to re-apply zurb
    //$(document).foundation();

    $("#"+section_id).foundation('tooltip');
    // var t3 = new Date();
    // var diff = (t3.getTime() - t2.getTime())/1000;
    // console.log("foundation took "+diff);
    //console.log(section_id);
  });

}); // end of document ready function

function array_unique(array) {
  if(array){
    var a = array.concat();
    for(var i=0; i<a.length; ++i) {
        for(var j=i+1; j<a.length; ++j) {
            if(a[i] === a[j])
                a.splice(j--, 1);
        }
    }
    return a;
  }
  else{
    return array;
  }
}

function array_subtract(array_1, array_2){
  for(var i = 0; i < array_2.length; i++){
    found_id = jQuery.inArray(array_2[i], array_1);
    if(found_id != -1){
      array_1.splice(found_id, 1);
    }
  }
  return array_1;
}

function show_toast(success_message){
  success_message = "<div class='fi-check success_check'/>"+success_message;
  $('#toast_div').html(success_message);
  $('#toast_div').show();
  $('#toast_div').delay(7000).fadeOut(1000, function(){
    $('#toast_div').hide();
    $('#toast_div').html('');
  });
}

function error_handling(data){
  console.log(data);
  var err_message = '';
  if(data.indexOf('Could not connect to MAP Server') > -1)
    err_message = 'Could not connect to MAP Server';
  if(data.indexOf('Could not create socket') > -1)
    err_message = 'Could not create socket';
  if(data.indexOf('Could not send json data to MAP Server') > -1)
    err_message = 'Could not send json data to MAP Server';
  if(data.indexOf('Could not read from MAP Server') > -1)
    err_message = 'Could not read from MAP Server';
  if(data.indexOf('Action unsuccessful') > -1)
    err_message = 'Action unsuccessful';
  alert("Oh no, something went wrong :(\nPlease refresh the page and try again.\n"+
    "If failures persist, please contact the MAP Administrators.\n\n"+
    "Error: "+err_message);
}
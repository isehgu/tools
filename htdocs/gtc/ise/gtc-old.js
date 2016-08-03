
$(document).ready(function(){
  /*
  $('.sidelink').click(function(){
    //event.preventDefault();
    var target = $(this).attr('href');
    console.log(target);
    window.location.assign(target);
    window.location.reload(true);
  });
  */
  //datepicker
  $('#start_date_input').datepicker({
    autoclose:true
  });
  
  /*Not completed yet
  $('#start_date_input').datepicker().on('changeDate', function(e){
       var datestring = $(this).val();
       //console.log(datestring);
   //console.log(window.location.href.toString());
   var fullhref = window.location.href.toString().split("?");
   var basehref = fullhref[0];
       window.location.href = basehref +"?datestring="+datestring;
  });
  */
  
  /*Live filtering of the displayed table rows
   *This would be needed for each table
  $('#test_request_search').on('keyup', function(){
   var rex = new RegExp($(this).val(),'i');
   $('.test_request_searchable tr').hide();
   $('.test_request_searchable tr').filter(function(){
     return rex.test($(this).text());
   }).show();
  });
   */
  //Filtering out checkout sections base on btn clicks
  $('#6pm_btn').click(function(){
    $('.wrapper').each(function(){
      if(!$(this).hasClass('wrapper_hide'))
      {
        $('.wrapper').addClass('wrapper_hide');
      }
    });
    $('#wrapper_6pm').removeClass('wrapper_hide');
    $('.navbar-btn').removeClass('active');
    $('#6pm_btn').addClass('active');
  });
  
  $('#10pm_btn').click(function(){
    $('.wrapper').each(function(){
      if(!$(this).hasClass('wrapper_hide'))
      {
        $('.wrapper').addClass('wrapper_hide');
      }
    });
    $('#wrapper_10pm').removeClass('wrapper_hide');
    $('.navbar-btn').removeClass('active');
    $('#10pm_btn').addClass('active');
  });
  
  $('#2am_btn').click(function(){
    $('.wrapper').each(function(){
      if(!$(this).hasClass('wrapper_hide'))
      {
        $('.wrapper').addClass('wrapper_hide');
      }
    });
    $('#wrapper_2am').removeClass('wrapper_hide');
    $('.navbar-btn').removeClass('active');
    $('#2am_btn').addClass('active');
  });
  
  $('#4am_btn').click(function(){
    $('.wrapper').each(function(){
      if(!$(this).hasClass('wrapper_hide'))
      {
        $('.wrapper').addClass('wrapper_hide');
      }
    });
    $('#wrapper_4am').removeClass('wrapper_hide');
    $('.navbar-btn').removeClass('active');
    $('#4am_btn').addClass('active');
  });
  
  $('#3am_btn').click(function(){
    $('.wrapper').each(function(){
      if(!$(this).hasClass('wrapper_hide'))
      {
        $('.wrapper').addClass('wrapper_hide');
      }
    });
    $('#wrapper_3am').removeClass('wrapper_hide');
    $('.navbar-btn').removeClass('active');
    $('#3am_btn').addClass('active');
  });
  
  //Display only the neccessary btns only and in the right color base on discrepancy found
  var wrapper_list = ['#wrapper_6pm','#wrapper_10pm','#wrapper_2am','#wrapper_3am','#wrapper_4am'];
  var wrapper_btns = ['#6pm_btn','#10pm_btn','#2am_btn','#3am_btn','#4am_btn'];
  for(var i=0;i<wrapper_list.length;i++)
  {
    //console.log(wrapper_list[i]);
    if($(wrapper_list[i]).length > 0) //If the checkout exists
    {
      console.log(wrapper_list[i]);
      //if wrapper exists, then check if the checkout has discrepancy
      //if so then display the button in red by adding class btn-danger
      //else add class btn-success
      if($(wrapper_list[i]).hasClass("wrapper_discrepancy")) $(wrapper_btns[i]).removeClass("btn-success btn-danger").addClass("btn-danger");
      else $(wrapper_btns[i]).removeClass("btn-success btn-danger").addClass("btn-success");
    }
    else //If the wrapper section for the checkout doesn't exist, don't display btn
    {
      $(wrapper_btns[i]).removeClass("navbar_btn_hide").addClass("navbar_btn_hide");
    }
  }//End of for loop for displaying the right btns with the right color
  
  
});
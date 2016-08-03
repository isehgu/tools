$(document).ready(function(){
    $('#start-date').change(function(){
        var day_id = $(this).val(); //selected value
        var new_link = 'index.php?day_id='+day_id;
        window.location.replace(new_link);
    });

    //prevent any disabled buttons being acted upon
    $('div').on('click','a.disabled',function(event){
        event.preventDefault();
    });

    $('.checkout-circle').click(function(event){
        event.preventDefault();
        var day_id = $(this).attr('data-ise-day-id');
        var timeruntype = $(this).attr('data-ise-timeruntype');
        var section_id = '';
        //setting the section id
        if(timeruntype == 1){section_id = '#check6pm';}
        if(timeruntype == 2){section_id = '#check10pm';}
        if(timeruntype == 3){section_id = '#check2am';}
        if(timeruntype == 4){section_id = '#check3am';}
        if(timeruntype == 5){section_id = '#check4am';}

        //if the checkout section is already visible, then just scroll to it
        //if not, then make ajax call, display the content, then scroll to it
        if($(section_id).is(':visible'))
        {
            //console.log('no ajax call');
        }
        else
        {
            $.ajax({
              type: 'get',
              url:  'comparison_summary.php',
              data: 'timeruntype='+timeruntype+'&day_id='+day_id,
              success: function(data){
                $(section_id).html(data);
                $(section_id).show();
                $('html,body').animate({
                    scrollTop: $(section_id).offset().top
                }, 1000);

              }//end of success function
            });//end of ajax

        }//end of if

        //$(section_id).show();
        //console.log($(section_id).offset().top);
        //Just a note here, $('body') will work in Chrome and scroll to the element
        //for some weird reason, it doesn't scroll in FF. Had to change it to $('html,body')
        //$(window) works incorrectly, and only scroll sometimes.
        //The delay is added here to allow page to load the new html content.
        //If seems, if the load is slow, then scroll to happens before the load, and
        //it won't scroll to the actual content
        // $('html,body').delay(300).animate({
        //     scrollTop: $(section_id).offset().top
        // }, 1000);
    });
    ////////////////////////////////////////////////////////////////////
    //searching detail comparison table
    $('#order-detail-search').on('keyup', function(){
    var rex = new RegExp($(this).val(),'i');
    $('#order-detail-table tr').show(); //resetting. Must have this, else hidden rows won't show again
    $('#order-detail-table tr.row-content').filter(function(){
      return !(rex.test($(this).text()));
    }).hide();
  });

});

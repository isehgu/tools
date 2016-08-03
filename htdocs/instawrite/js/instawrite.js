$(document).ready(function(){    
    $('div').on('submit','#searchform',function(){
		//console.log('here');
        if($('#searchinput').val().length === 0) //if input is empty
        {
            $('#control-group').addClass('error');
            $('#searchinput').attr('placeholder','Can\'t have empty value, must specify tag value(s)');
        }
        else //if input is not empty
        {
            var formData = $(this).serialize();
            var current_element = $(this);
            $.get('search_result.php',formData,function(data)
		{
			$('#bottomsection').html(data);
		});
            /*function processData(data){
                $('#bottomsection').html(data);
            } //end of processData
			*/
            $('#searchinput').val('');
            $('html, body').animate({
                scrollTop: ($('#bottomsection').offset().top)
            },500);
            $('#control-group').removeClass('error');
            $('#searchinput').attr('placeholder','Search by tag1,tag2,tag3,tagX');
        }
        return false;
    }); //End of search form submit
    
    $('div').on('submit','#newform',function(){
        var formData = $(this).serialize();
        var current_element = $(this);
        $.post('writeup_add.php',formData);
        $('#writeup_input').val('');
        $('#taginput').val('');
        return false;
    }); //End of search form submit
    
    $('div').on('click','.writeup_wrapper',function(){
        var writeup_id = $(this).attr('id');
        var link = "/instawrite/displaywriteup.php?id="+writeup_id;
        //console.log(link);
        window.open(link);    
    });
    
});
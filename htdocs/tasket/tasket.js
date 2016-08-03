$(document).ready(function(){
      $('table').on('click','.task_wrapper',function(){
        var task_id = $(this).attr('id');
        
        $.ajax({
          type: 'post',
          url:  'task_display.php',
          data: 'id='+task_id,
          success:  function(data)
          {
            $('#tasklabel').text('Task: '+task_id);
            $('.modal-body').html(data);
            $('#completebtn').attr('href','task_action.php?id='+task_id+'&action=complete');
            $('#complete btn').attr('title','Complete');
            $('#changeablebtnicon').removeClass('icon-pencil').addClass('icon-ok');
            $('#deletebtn').attr('href','task_action.php?id='+task_id+'&action=delete');
          }
        }); //End of ajax
        
        $('#taskmodal').modal('show');  
      });

	  $('table').on('click','.completed_task_wrapper',function(){
        var task_id = $(this).attr('id');
        
        $.ajax({
          type: 'post',
          url:  'task_display.php',
          data: 'id='+task_id,
          success:  function(data)
          {
            $('#tasklabel').text('Task: '+task_id);
            $('.modal-body').html(data);
            $('#completebtn').attr('href','task_action.php?id='+task_id+'&action=reopen');
			$('#completebtn').attr('title','Reopen');
			$('#changeablebtnicon').removeClass('icon-ok').addClass('icon-pencil');
            $('#deletebtn').attr('href','task_action.php?id='+task_id+'&action=delete');
          }
        }); //End of ajax
        
        $('#taskmodal').modal('show');  
      });
	  
	  $("#tasktable").tablesorter();
	  $("#completetasktable").tablesorter();
	  
	  $('th div').click(function(){
		if($(this).children('i').hasClass('icon-chevron-up'))
		{
			$(this).children('i').removeClass('icon-chevron-up');
			$(this).children('i').addClass('icon-chevron-down');
		}
		else if($(this).children('i').hasClass('icon-chevron-down'))
		{
			$(this).children('i').removeClass('icon-chevron-down');
			$(this).children('i').addClass('icon-chevron-up');
		}
		else
		{
			$(this).children('i').removeClass('icon-resize-vertical');
			$(this).children('i').addClass('icon-chevron-up');
		}
	  });
	  
	  /*
	  $('tr').on('click','th',function(){
		if($(this).children('i').hasClass('icon-chevron-up')
		{
			$(this).children('i').removeClass('icon-chevron-up');
			$(this).children('i').addClass('icon-chevron-down');
		}
		else if($(this).children('i').hasClass('icon-chevron-down')
		{
			$(this).children('i').removeClass('icon-chevron-down');
			$(this).children('i').addClass('icon-chevron-up');
		}
		else
		{
			var html = "<i class='icon-chevron-up'></i>";
			$(this).prepend(html);
		}
	  });
	  */
}); //End of ready
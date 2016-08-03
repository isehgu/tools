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
            $('#deletebtn').attr('href','task_action.php?id='+task_id+'&action=delete');
          }
        }); //End of ajax
        
        $('#taskmodal').modal('show');  
      });

			
}); //End of ready
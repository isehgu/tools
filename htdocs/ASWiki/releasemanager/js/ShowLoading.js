function show_loading(){
// The form has an id of "theForm".
  // This function defines what happens when it is submitted.
  $('#SireForm').submit(function() {

      // Replace the form w the search icon.
    $("#theForm").html('Searching...<br/>' +
        '<img src="img/loading.gif" />');

      // Make the search request to the PHP page.
      // The 3 arguments are: 
      //     1 - The url. 2 - the data sent 
      //     3 - The function called when data is sent back
    $.post("/ajax_html_echo/", $(this).serialize(), function(result){

          // Here we replace the search image with the data
        $("#page").html(response);
    });

      // Cancel the regular submission of the form.
      // You have to do this, or the page will change and things won't work.
    return false;
  });
}
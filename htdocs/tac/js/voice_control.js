 
$(document).ready(function(){

    if (annyang) {
    // Let's define our first command. First the text we expect, and then the function it should call
    var commands = {

        'selection': function() {
            $("#test_selection_link").trigger("click");
        },
        'in progress': function() {
            $("#progress_link").trigger("click");
        },
        'in queue': function() {
            $("#queue_link").trigger("click");
        },
        'history': function() {
            $("#history_link").trigger("click");
        },
        'complete history': function() {
            $("#history_complete_link").trigger("click");
        },
        'environment settings': function() {
            $("#env_link").trigger("click");
        },
        'upgrade': function() {
            $("#upgrade_link").trigger("click");
        },
        'accolades': function() {
            $("#tac_stats_link").trigger("click");
        },
        'release register': function() {
            $("#isxfiles_link").trigger("click");
        },
        'documentation': function() {
            $("#keyword_link").trigger("click");
        },
        'run test': function() {
            $("#runbtn").trigger("click");
        }

    };
    
    // Add our commands to annyang
    annyang.addCommands(commands);
    
    // Start listening. You can call this here, or attach this call to an event, button, etc.
    annyang.start();
    }

});
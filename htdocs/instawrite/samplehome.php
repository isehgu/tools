<?php
    require_once 'base_function.php';
    f_dbConnect();

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <style type='text/css'>
            body {
                padding-top: 70px;
                background-color: #bee5c6;
                font-weight:200;
                
            }
        </style>
        <script src="js/jquery-1.10.2.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script>
            
            
            $(document).ready(function(){
                $('div').on('submit','#feedback-form',function(){
                    var formData = $(this).serialize();
                    var current_element = $(this);
                    $.post('feedback.php',formData,processData);
                    function processData(data){
                        current_element.replaceWith(data);
                    } //end of processData
                    return false;
                }); //End of feedback-form submit

                $('button.expander').click(function(){

                    $(this).text(function(i,old){

                        return old=='+' ?  '-' : '+';
                    });
                });  //End of click
                
                $('body').tooltip({selector:'.tt'});
                
                $('.remove').click(function(){
                    //$(this).tooltip('hide');
                    var current_button = $(this);
                    var href = $(this).attr('href');
                    var querystring=href.slice(href.indexOf('?')+1);
                    $.get('removeDisplay.php',querystring);//end of get
                    $(this).closest('.accordion-group').remove();
                    return false;
                });//End of remove()
                
                $('tr').on('click','.btn-dish-vote',function(){
                        $(this).tooltip('hide');
                        var current_button = $(this);
                        var href = $(this).attr('href');
                        var querystring=href.slice(href.indexOf('?')+1);
                        $.get('dishaction.php',querystring,function(data){current_button.parent(".onedish").replaceWith(data);});//end of get
                        //$.get('testbe.php',querystring,processResponse);
                        
                       // function processResponse(data){
                         //       current_button.replaceWith(data);
                        //}
                        return false;
                }); //End of click for btn-dish-vote
                
                $('div').on('click','.btn-myspots',function(){
                        $(this).tooltip('hide');
                        var current_button = $(this);
                        var href = $(this).attr('href');
                        var querystring=href.slice(href.indexOf('?')+1);
                        $.get('myspotsaction.php',querystring,function(data){current_button.replaceWith(data);});//end of get
                        return false;
                }); //End of click for btn-myspots
                
                $('div').on('click','.btn-inquire',function(){
                        $(this).tooltip('hide');
                        var current_button = $(this);
                        var href = $(this).attr('href');
                        var querystring=href.slice(href.indexOf('?')+1);
                        $.get('inquire.php',querystring);//end of get
                        return false;
                }); //End of click for btn-inquire
                
                $('div').on('click','.btn-recommend',function(){
                        $(this).tooltip('hide');
                        var current_button = $(this);
                        var href = $(this).attr('href');
                        var querystring=href.slice(href.indexOf('?')+1);
                        $.get('recommend.php',querystring);//end of get
                        return false;
                }); //End of click for btn-recommend
                
            }); //End of ready

        </script>
    </head>
    <body>
 
        <div class="navbar navbar-default navbar-fixed-top">
          <div class="navbar-inner">
            <div class="container">
              <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="brand" href="index.php">SimpoDishIO</a>
              <div class="nav-collapse collapse">
                <ul class="nav">
                  <li class="active"><a href="home.php">Home</a></li>
                  <li><a href="myspots.php">MySpots</a></li>
                </ul>
                <form class="navbar-form pull-right" action='restaurant.php' method='get'>
                    <div class='input-append'>
                    <input class="span4" type="text" placeholder=" By name,address,city,state, zip or showall" name='search_string'>
                        <span class='add-on'><i class="icon-search"></i></span>
                    </div>
                    <a href='logout.php' class='btn tt' data-toggle='tooltip' data-placement='right'  title="" data-original-title='Logout' id='logout'><i class='icon-off'></i></a>
                </form>
                
              </div><!--/.nav-collapse -->
            </div>
          </div>
        </div>
    
        <div class='container' id='display'>
            <div class='accordion top-accordion'>
                <div class='accordion-group top-group' id='top-dishin'><!--DishIn -->
                    <div class='accordion-heading lead'>
                        <button class='btn btn-large expander top-expander' data-toggle='collapse' data-target='#dishin'>+</button>
                         Dish In (Responses to your inquiries)
                    </div>
                    <div class='accordion-body collapse' id='dishin'>
                        <div class='accordion-inner'>
                            
                            <?php displayDishIn($uid) ?>
                            
                        </div>  <!--End of accordion-inner -->
                    </div> <!--End of accordion-body -->
                </div><!--End of DishIn-->
            
                <div class='accordion-group top-group' id='top-dishout'><!--DishOut -->
                    <div class='accordion-heading lead'>
                        <button class='btn btn-large expander top-expander' data-toggle='collapse' data-target='#dishout'>+</button>
                         Dish Out (Others' inquiries that need my responses)
                    </div>
                    <div class='accordion-body collapse' id='dishout'>
                        <div class='accordion-inner'> 
                            
                            <?php displayDishOut($uid) ?>
                            
                        </div>  <!--End of accordion-inner -->
                    </div> <!--End of accordion-body -->
                </div><!--End of DishOut-->
                
                <div class='accordion-group top-group'><!--Restaurant Recommdendation from Friends -->
                    <div class='accordion-heading lead'>
                        <button class='btn btn-large expander top-expander' data-toggle='collapse' data-target='#recommend'>+</button>
                         Restaurant recommended by friends
                    </div>
                    <div class='accordion-body collapse' id='recommend'>
                        <div class='accordion-inner'> 
                            
                            <?php displayRecommend($uid) ?>
                        
                        </div>  <!--End of accordion-inner -->
                    </div> <!--End of accordion-body -->
                </div><!--End of Recommendation from friends-->
                
            </div> <!--End of all accordion -->
            
        </div> <!-- End of container for accordion -->
        
        <div class="navbar navbar-default navbar-fixed-bottom">
          <div class="navbar-inner">
            <div class="container">
              <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".bottom-bar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
             
              <div class="nav-collapse collapse bottom-bar">
                <form class="navbar-form" id='feedback-form' action='feedback.php' method='post'>
                    <div class='input-append'>
                    <input class="span11" type="text" placeholder=" Let us know what you think!" name='feedback'>
                        <span class='add-on'><i class="icon-comment"></i></span>
                    </div>
                </form>
                
              </div><!--/.nav-collapse -->
            </div>
          </div>
        </div>
    

    </body>
</html>
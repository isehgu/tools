<?php
	require_once "base_function.php";
	f_dbConnect();
  
  
?>
<!DOCTYPE html>
  <html lang="en">
    <head>
      <title>TAC Statistics</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="TAC Statistics">
      <meta name="author" content="Autobots">
      <!--bootstrap-->
      <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
      <link href="tac_stats.css" rel="stylesheet">

      <script src="js/jquery-1.11.0.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="tac.js"></script>
    </head>
    
    <body>
      <nav id="nav" class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="index.php">T.A.C.</a>           
          </div>
          
          <ul class="nav navbar-nav text-center">
            <li><a href="#">Overall</a></li>
            <li><a href="#">Weekly</a></li>
            <li><a href="#">Monthly</a></li>
            <li><a href="#">Yearly</a></li>
          </ul>
        </div>
      </nav>
      
      <div id="stat_container" class="container-fluid">
        <div class="row">
          <div class="wrapper" id="wrapper1">
            <h1 id="title_header">T.A.C. launched on 3/6/14</h1>
            <p>Below is a report card of the impact TAC made since then</p>
            <br/>
            <?php
              if(!isset($_COOKIE['user']))
              {
                echo "<form class='form-inline' role='form' method='post' action='authen.php'>
                        <div class='form-group'>
                          <input type='text' class='form-control' name='username' placeholder='User Name'>
                        </div>
                        <button type='submit' class='btn btn-primary'>Sign In</button>
                ";
              }
            ?>
          </div>
        </div>
        
        <div class="wrapper" id="wrapper2">
          <div class="row" id="overall">
            <div class="col-sm-4 col-md-4 text-center">
              <div id="box1" class="box">
                <h3><strong>8686 minutes were rescued</strong></h3>
                <br/>
                <ul class="box_list">
                  <li>58698 emails eliminated</li>
                  <li>5688 messenger converstion avoided</li>
                  <li>6858 phone calls dodged</li>
                  <li>8969 in-person conversation prevented</li>
                </ul>
                <br/>
                <h3>That is 14+ Hours SAVED!</h3>
              </div>
            </div>
            
            <div class="col-sm-4 col-md-4 text-center">
              <div class="box">
                <h3><strong>345790 tests executed since launch</strong></h3>
                <br/>
                <p>More tests are being
                executed than ever because we've removed ourselves as the bottleneck!
                </p>
              </div>
            </div>
            
            <div class="col-sm-4 col-md-4 text-center">
              <div class="box">
                <h3><strong>85% of OAT and PAT covered</strong></h3>
                <br/>
                <p>The more we add, the more we save.</p>
              </div>
            </div>
          </div>
        </div>
          
            
        <div class="wrapper" id="wrapper3">
          <div id="weekly" class="stat_box">
            
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Week Of</th>
                  <th>Decrease in Email</th>
                  <th>Decrease in Lync</th>
                  <th>Decrease in Call</th>
                  <th>Decrease in Conversation</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>3/10/14</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div id="monthly" class="stat_box">
            
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Month Of</th>
                  <th>Decrease in Email</th>
                  <th>Decrease in Lync</th>
                  <th>Decrease in Call</th>
                  <th>Decrease in Conversation</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div id="Year" class="stat_box">
            
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Year Of</th>
                  <th>Decrease in Email</th>
                  <th>Decrease in Lync</th>
                  <th>Decrease in Call</th>
                  <th>Decrease in Conversation</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
                <tr>
                  <td>2014</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                  <td>32423</td>
                </tr>
              </tbody>
            </table>
          </div>
          
        </div>
        
        
      </div>
    </body>
  </html>
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
            <li><a href="#summary">Summary</a></li>
            <li><a href="#detail">Detail</a></li>
						<li><a href="#overview">Overview</a></li>
          </ul>
        </div>
      </nav>
      
      <div id="stat_container" class="container-fluid">
        <div class="row">
          <div class="" id="wrapper1">
						<h1 id="title_header">Test Automation Controller(T.A.C.)</h1>
						<h3>A test collaboration system that removes bottlenecks from testing. <a href="#overview">Learn more.</a></h3>
						
            <!--
						<h1 id="title_header">T.A.C. launched on 3/6/14</h1>
            <p id="sub_title">Below is a report card of the impact TAC made since then</p>
						-->
            <br/>
						<br/>

            <?php
              if(!isset($_COOKIE['user']))
              {
                echo "<form class='form-inline' role='form' method='post' action='authen.php'>
                        <div class='form-group'>
                          <input type='text' class='form-control input-lg' name='username' placeholder='User Name'>
                        </div>
                        <button type='submit' class='btn btn-lg btn-primary'>Sign In</button>
                ";
              }
							else{
								echo "
										<a href='index.php' class='btn btn-primary btn-lg'>Start Testing</a>
								
								";				
								
							} //end of if on user cookie check
            ?>
          </div>
        </div>
        
        <div class="wrapper" id="summary">
          <div class="row">
            <div class="col-sm-4 col-md-4 text-center">
              <div id="box1" class="box">
                <h3><strong>
								<?php
									$total_minute = f_totalMinute();
									echo $total_minute;
								?></strong> Minutes Rescued</h3>
                <br/>
								<p>
									<span class="standout"><?php echo f_totalEmails() ?></span> emails eliminated. 
								</p>
								<p>
									<span class="standout"><?php echo f_totalLync() ?></span> messenger conversations avoided.
								</p>
								<br><br><br>            
                <h3>That is <strong><?php echo round($total_minute/60,2);?></strong> Hours SAVED!</h3>
              </div>
            </div>
            
            <div class="col-sm-4 col-md-4 text-center">
              <div class="box">
                <h3><strong><?php echo f_totalTests()?></strong> Tests Executed</h3>
                <br/>
								<p>
									On <span class="standout">
										<?php
											$busiest = f_getBusiestDay();
											echo $busiest['date'].",";
										?>
									</span> TAC's busiest day,
									<span class="standout">
										<?php echo $busiest['test_count'];?>
									</span> tests requests were processed by TAC.
								</p>
                <p><span class="standout">
									<?php echo f_getAverage();?>
								</span> test requests are processed on an average day.
								</p>
						
								<br>
								<h3>No testers needed!</h3>	
              
              </div>
            </div>
            
            <div class="col-sm-4 col-md-4 text-center">
              <div class="box">
                <h3><strong>
									<?php
										echo f_getTestCoverage("total")."%";
									?>
								</strong> of OAT and PAT regression covered</h3>
                <br/>
								<p>
									<span class="standout">
										<?php echo f_getTestCoverage('PAT')."%";?>
									</span> of PAT regression tests and
									<span class="standout">
										<?php echo f_getTestCoverage('OAT')."%";?>
									</span> of OAT regression tests are available through TAC.
								</p>
								<br><br>
                <h3>The more we add, the more we save!</h3>
              </div>
            </div>
						
          </div>
        </div>
          
            
        <div class="wrapper" id="detail">
          <div id="weekly" class="stat_box">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Week Of</th>
									<th>Test Executed</th>
                  <th>Reduction in Email</th>
                  <th>Reduction in Lync</th>
									<th>Minutes Rescued</th>
                </tr>
              </thead>
              <tbody>
                <?php f_displayWeeklyTable()?>
              </tbody>
            </table>
          </div>
          <!--
          <div id="monthly" class="stat_box">
            
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Month Of</th>
                  <th>Decrease in Email</th>
                  <th>Decrease in Lync</th>
                  <th>Decrease in Call</th>
                  <th>Decrease in Conversation</th>
									<th>Total minute rescued</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
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
									<td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
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
									<td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
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
									<td>32423</td>
                </tr>
                <tr>
                  <td>March 2014</td>
                  <td>32423</td>
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
									<td>32423</td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div id="yearly" class="stat_box">
            
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Year Of</th>
                  <th>Decrease in Email</th>
                  <th>Decrease in Lync</th>
                  <th>Decrease in Call</th>
                  <th>Decrease in Conversation</th>
									<th>Total minute rescued</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>2014</td>
                  <td>32423</td>
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
									<td>32423</td>
                </tr>
                <tr>
                  <td>2014</td>
									<td>32423</td>
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
                  <td>32423</td>
                </tr>
              </tbody>
            </table>
          </div>
          -->
        </div>
        
        
				
				<div class="wrapper" id="overview">
					<h3>TAC is a test collaboration system. It aims to help turn automated testing into a completely bottleneck-free
					process, in the easiest, simplest, and most user-friendly fashion possible.
					</h3>
					<br>
					<div class="overview_list">
						<p>With that focus, TAC is implemented as a web application using html, php, and a python based back-end with a MySQL database.
Within TAC's own realm, it focuses on 4 ANYs -- <strong id="any">ANYONE, ANYTHING, ANYWHERE and ANYTIME.</strong>
						</p>
						
						<ul>
							<li>Test Request -- Make it so easy and simple that test request can be initiated by anyone or anything, from anywhere.</li>
							<li>Requester -- Remove tester from test execution as a bottleneck. No need to wait for someone to run a test. Request test anytime and have it run.</li>
							<li>Test Environment -- No more downtime. Tests are run anywhere as long as there's available environment that meets test requirement.</li>
							<li>Test Result -- Make all test results accessible by anyone, from anywhere.</li>
						</ul>
						
					</div>	
					
					<br><br>
					<h3>In TAC's infancy, it already made significant progress on all of the objectives.</h3>
					<br>
						
					<div class="overview_list">
						<ul>
							<li>Test Request -- Be it your desk, you home pc, or a laptop in a conference room, as long as you can access TAC website, you can put in test request. For external tools or scripts, TAC aims to be RESTful. A simple GET is all that it takes to put in a test request.</li>
							<li>Requester -- No more waiting on someone to kick off tests. Simply checkbox the tests you want, and click Run.</li>
							<li>Test Environment -- Tests for OAT and PAT can run simultaneously. Tests for the same environment would queue if there's ongoing test. All tests are automatically queued if environment is locked (upgrade/downgrade, config change, etc), and released once environment is unlocked.</li>
							<li>Test Result -- Test result summaries are emailed to requester and displayed on TAC website. (canceled, killed, complete-fail, complete-pass) Links to detailed test reports are provided, and accessible on TAC website as well.</li>
						</ul>
						
					</div>
					
				</div>
			</div>
    </body>
  </html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>FAI Install files</title>
    
<!-- Mobile viewport optimized: h5bp.com/viewport -->
	  <meta name="viewport" content="width=device-width">

<!-- Main stylesheet imports bootstrap css and adds custom -->
    <link href="css/style.css" rel="stylesheet">
    
    <style>
    	/* To keep short panes open decently tall */
    	.tab-pane {min-height: 400px;}
    </style>

<!-- Modernizr for browser feature-checking 
			+ HTML5shiv (included in modernizr) see modernizr.com -->
  	<script src="js/modernizr-2.5.3.min.js"></script>

<!-- Fav and touch icons -->
		<!-- alternatively, remove these lines and place icons
				directly in the site parent folder 
				mathiasbynens.be/notes/touch-icons -->
    <link rel="shortcut icon" href="img/favicon.ico">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">
    
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
		  <div class="navbar-inner">
		    <div class="container">
		      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </a>
		      <a class="brand" href="index.html">FAI</a>
		      <div class="nav-collapse">
		        <ul class="nav">
		          <li class="active"><a href="index.html">Welcome</a></li>
		        </ul>
		      </div><!-- /.nav-collapse -->
		    </div><!-- /.container -->
		  </div><!-- /navbar-inner -->
		</div><!-- /navbar -->
		
		<div class="container">
		    <div class="page-header">
			    <h1>FAI Install files for <?php echo $_GET["hostname"]; ?></h1>
		    </div>
		    
		    		<div class="row">
							<div class="tabbable span12">
								<ul class="nav nav-tabs">
                                    <?php
                                    $i = 1;

                                foreach (glob('/var/www/fai/failogview/'.$_GET["hostname"].'/last/*') as $filename)
                                {
				      $file = explode('/',$filename);
                                      echo '<li><a href="#tabs1-pane'.$i.'" data-toggle="tab">'.end($file).'</a></li>'; // Get last array entry, the filename 
                                      $i++;
                                }

                                    ?>
								</ul>
								<div class="tab-content">
                                <?php
                                $i = 1;
                                foreach (glob('/var/www/fai/failogview/'.$_GET["hostname"].'/last/*') as $filename)
                                {
                                        $file = explode('/',$filename);
                                        echo '<div class="tab-pane" id="tabs1-pane'.$i.'">';
					echo '<h4>'.end($file).' content</h4>';
                                        echo "<pre>";
                                        echo htmlspecialchars(file_get_contents($filename));
                                        echo "</pre>";
									    echo "</div>";
                                        $i++;
                                }
                                ?>

								</div><!-- /.tab-content -->
							</div><!-- /.tabbable -->
						</div><!-- /.row -->

      <hr>

      <footer>
        <p>FAI install files web interface, built with Twitter Bootstrap</p>
      </footer>


    </div> <!-- .container -->

<!-- ==============================================
		 JavaScript below! 															-->

<!-- jQuery via Google + local fallback, see h5bp.com -->
	  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
  	<script>window.jQuery || document.write('<script src="js/jquery-1.7.1.min.js"><\/script>')</script>

<!-- Bootstrap JS: compiled and minified -->
    <script src="js/bootstrap.min.js"></script>

<!-- Example plugin: Prettify -->
    <script src="js/prettify/prettify.js"></script>
    
<!-- Initialize Scripts -->
		<script>
			// Activate Google Prettify in this page
			addEventListener('load', prettyPrint, false);
		
			$(document).ready(function(){

				// Add prettyprint class to pre elements
					$('pre').addClass('prettyprint');
								
			}); // end document.ready
		</script>

  </body>
</html>

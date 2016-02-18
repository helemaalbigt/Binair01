<?php 
    include_once './inc/db.inc.php';
	include_once './inc/functions.inc.php';
	
	//initialize session if none exists
	if (session_id() == '' || !isset($_SESSION)) {
		// session isn't started
		session_start();
	}
?>

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/style.css">
		
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav -->
	    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	      <div class="container">
	      	
	        <div class="navbar-header top">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>                        
		      	</button>
	          	<a href="#"><img src="img/LogoSmall.png" class="logo"/></a>
	        </div>
	        
	        <div class="collapse navbar-collapse" id="myNavbar">
			      <ul class="nav navbar-nav navbar-right">
			        <li class="menuoption"><a href="#" class="white"><h4>ABOUT</h4></a></li>
			        <li class="menuoption"><a href="#" class="white"><h4>EVENTS</h4></a></li>
			        <li class="menuoption"><a href="news.php" class="white"><h4>NEWS</h4></a></li>
			      </ul>
			</div>
	        
	      </div>
	    </nav>

		<!-- HomePage CoverImage-->
	    <div class="coverimage">
	    	
	    	<!-- menu -->
		    <nav class="navbar navbar-inverse" role="navigation">
		      	
			        <div class="navbar-header">
		        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar2">
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>                        
				      	</button>
			        </div>
			        
			        <div class="collapse navbar-collapse" id="myNavbar2">
					      <ul class="nav navbar-nav navbar-right">
					        <li class="menuoption"><a href="#" class="white"><h4>ABOUT</h4></a></li>
					        <li class="menuoption"><a href="#" class="white"><h4>EVENTS</h4></a></li>
					        <li class="menuoption"><a href="news.php" class="white"><h4>NEWS</h4></a></li>
					      </ul>
					</div>
		        
		    </nav>
	    	
	    	<!-- content -->
		    <div class="inner">
				<div class="content">
					<img src="./img/Logo.png" class="img-responsive" />
					<h1 class="white">Website Under Construction</h1>
					<iframe class="deezer" width="375" height="100" src="https://www.mixcloud.com/widget/iframe/?embed_type=widget_standard&amp;embed_uuid=e4ddf103-1c24-4903-9fae-0c642c6b3465&amp;feed=https%3A%2F%2Fwww.mixcloud.com%2Fmnsr_z%25C3%25A9r0%2F&amp;hide_cover=1&amp;hide_tracklist=1&amp;replace=0&color=ffffff" frameborder="0"></iframe>
				</div>
			</div>
		  
	    </div>
	    
	    <!-- Homepage Content-->
	    <div class="container">
	    	
	    	<!-- About Short -->
	    	<div class="content-segment">
	    		<div class="row about">
		        	<div class="col-md-3 title">
		        		<h1 class="gray">BINAIR 01</h1>
					</div>
					<div class="col-md-9 body-content">
						<h4>
							Donec id elit non mi porta gravida at <b>eget metus</b>. Fusce dapibus, tellus ac cursus commodo, <b>tortor</b> mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. <b>Donec sed odio dui.</b> </p>
		         		<h4>
					</div>
				</div>
				<a class="btn btn-default link-more" href="#" role="button">learn more &raquo;</a>
			</div>
			
			<!-- Latest News -->
			<div class="content-segment">
	    		<div class="row news">
		        	<div class="col-md-3 col-sm-12 title">
		        		<h1 class="gray">NEWS</h1>
					</div>
					<div class="body-content">
						<?php retrieveBlogpostsPreview(6) ?>
			        </div>
				</div>

				<a class="btn btn-default link-more" href="#" role="button">more news &raquo;</a>
			</div>
	
	
			<!-- Events -->
			<div class="content-segment">
	    		<div class="row news">
		        	<div class="col-md-3 title">
		        		<h1 class="gray">EVENTS</h1>
					</div>
					<div class="body-content">
				        <div class="col-md-3">
				        	<a href="#">
					        	<img src="img/temp/coverimage.jpg" class="img-responsive" />
					          	<h4><b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b></h4>
					          	<div class="subtitle">posted 15/02/2016</div>
				          	</a>
				        </div>
				        <div class="col-md-3">
				        	<a href="#">
					        	<img src="img/temp/coverimage.jpg" class="img-responsive" />
					          	<h4><b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b></h4>
					          	<div class="subtitle">posted 15/02/2016</div>
				          	</a>
				       </div>
				        <div class="col-md-3">
				        	<a href="#">
					        	<img src="img/temp/coverimage.jpg" class="img-responsive" />
					          	<h4><b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b></h4>
					          	<div class="subtitle">posted 15/02/2016</div>
				          	</a>
				        </div>
			        </div>
				</div>
				<a class="btn btn-default link-more" href="#" role="button">more events &raquo;</a>
			</div>
	     
	    
	
	      <hr>
	
	      <footer>
	        <p class="footer">&copy; Binair01 - <?php echo date("Y"); ?></p>
	      </footer>
	    </div> 
	    
    	<!-- /container -->        
    	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>

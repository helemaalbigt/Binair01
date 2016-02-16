<?php
include_once './inc/db.inc.php';
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
	    <nav class="navbar navbar-inverse navbar-fixed-top news" role="navigation">
	      <div class="container">
	      	
	        <div class="navbar-header top">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>                        
		      	</button>
	          	<a href="#"><img src="img/LogoSmall.png" class="logo"/></a><a href="#"><span><h4> / NEWS</h4></span></a>
	        </div>
	        
	        <div class="collapse navbar-collapse" id="myNavbar">
			      <ul class="nav navbar-nav navbar-right">
			        <li class="menuoption"><a href="#" class="white"><h4>ABOUT</h4></a></li>
			        <li class="menuoption"><a href="#" class="white"><h4>EVENTS</h4></a></li>
			        <li class="menuoption"><a href="#" class="white"><h4>NEWS</h4></a></li>
			      </ul>
			</div>
	        
	      </div>
	    </nav>
	    
	    <div class="container">
	    	
	    	<!-- NEWS ITEMS -->
			<div class="content-segment">
				
				<!--preview-->
				<div class="news-preview">
		    		<div class="row">
						<div class="body-content">
					        <div class="col-md-9">
					        	<a href="#">
						        	<img src="img/temp/coverimage.jpg" class="img-responsive" />
					          	</a>
					        </div>
				        </div>
				        <div class="col-md-3 title">
			        		<h1>THIS IS A TITLE</h1>
			        		<h4><b>Lorem ipsum dolor sit amet, consectetur adipiscing elit</b></h4>
				          	<div class="subtitle">posted 15/02/2016</div>
						</div>
					</div>
					<a class="btn btn-default link-more" href="#" role="button">read on&raquo;</a>
				</div>
				
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

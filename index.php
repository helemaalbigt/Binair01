<?php 
    include_once './inc/db.inc.php';
	include_once './inc/functions.inc.php';
	
	//initialize session if none exists
	if (session_id() == '' || !isset($_SESSION)) {
		// session isn't started
		session_start();
	}
?>
<!-- 
	Website by Thomas Van Bouwel - www.tvb-design.com
	-First version published online on 10/04/2016
	-get the source at
	
	Notes to future developpers
	---------------------------
	
	Site main pages:
	-index.php 			main page
	-about.php 			about page
	-news.php 			page with news posts - renders overview of all news or individual news posts
	-events.php 		page with events - renders overview of all events or individual event
	-search.php 		page to display search reseult from clicking a tag on either a news post or event
	-admin.php 			portal for webadmins to manage content
	
	Functional pages:
	-blogpost.inc.php 	news post class - holds parameters for individual news posts and functions to render them on a page
	-event.inc.php 		event class - holds parameters for individual events and functions to render them on a page
	-db.inc.php 		database login info - change the info here to switch between local development and the online database
						NOTE: NEVER PUBLISH THIS IN PUBLIC REPOSITORIES!
	-functions.inc.php 	functionality used throughout the website: creating lists of events/news, cleaning post data, printing headers and footers, ...
	-update.inc.php		handles posting/editing/deleting news and events
-->

<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Binair01</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/style.css">
		
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        
        <script>
        	/**
			 *change iframe dimensions
			 */
			window.onload = function() {
				
			  	var iframe = document.getElementsByTagName("iframe")[0]
			  
				var att = document.createAttribute("width");       // Create a "width" attribute
				att.value = "100%";   								// Set the value of the class attribute
				iframe.setAttributeNode(att);                   	// Add the class attribute to <iframe>
				
				var att = document.createAttribute("height");       // Create a "width" attribute
				att.value = "90";   								// Set the value of the class attribute
				iframe.setAttributeNode(att);                   	// Add the class attribute to <iframe>  	
			};
        </script>
        
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav --> 
        <!--<?php echo printHeader(true, true, "red", "home", "./index.php") ?>--><!-- old navbar that would appear behind the big image on mainhpage-->

		<!-- HomePage CoverImage-->
	    <div class="coverimage">
	    	
	    	<!-- Nav -->
	    	<?php echo printHeader(false, false, "none", "home", "./index.php") ?>
	    	
	    	<!-- content -->
		    <div class="inner">
		    	<!-- logo & playlist -->
				<div class="content">
					<div class="logo_wrapper">
						<img src="./img/Logo.png" class="img-responsive biglogo" />
					</div>
					<!--<h1 class="white">Website Under Construction</h1>-->
					<div class="playlist">
						<?php echo getPlaylistIFrame()?>
						<!-- <iframe scrolling="no" frameborder="0" allowTransparency="true" src="http://www.deezer.com/plugins/player?format=classic&autoplay=false&playlist=false&width=700&height=350&color=007FEB&layout=&size=medium&type=playlist&id=<?php echo getPlaylistIFrame()?>&app_id=1"  width="100%" height="90px"></iframe>-->
						<!-- <iframe scrolling="no" frameborder="0" allowTransparency="true" src="http://www.deezer.com/plugins/player?format=classic&autoplay=false&playlist=false&width=700&height=350&color=007FEB&layout=&size=medium&type=playlist&id=1222291061&app_id=1"  width="100%" height="90px"></iframe>-->
					</div>
				</div>
				
				<!-- slogan -->
				<div class="slogan above_fold">
					<div class="phrase1">
						<b>Binair01</b> cares about <b>music diversity</b>.<br>
					</div>
					<div class="phrase2">
						We offer quality  <b>dance music</b> neglected by <b>mainstream media</b>.
						<br><span><a href="about.php">learn more &raquo;</a></span>
					</div>
				</div>
			</div>
		  
	    </div>
	    
	    
	    
	    <!-- Homepage Content-->
	    <div class="container" id="about_segment">
	    	
	    	<!-- About Short -->
	    	<div class="content-segment slogan under_fold" >
	    		<div class="row about">
		        	<div class="col-md-3 title">
		        		<span class="sidetitle underscore">BINAIR 01</span><br><br>
					</div>
					<div class="col-md-9 body-content">
						<h4>
							Binair01 cares about music <b>diversity</b>. 
							We offer quality dance music <b>neglected by mainstream media</b>, who only focus on Anglo-Saxon pop music.
						<h4>
					</div>
				</div>
				<a class="thin_button" href="about.php" role="button"><span>learn more &raquo;</span></a>
			</div>
			
			<!-- Latest Events -->
			<div class="content-segment eventsoverview">
	    		<div class="row news">
		        	<div class="col-md-3 col-sm-12 title">
		        		<span class="sidetitle underscore">EVENTS</span><br><br>
					</div>
					<div class="body-content">
						<?php retrieveEventsPreview(3) ?>
			        </div>
				</div>

				<a class="thin_button" href="events.php" role="button"><span>more events &raquo;</span></a>
			</div>
	
	      				
			<!-- Latest News -->
			<div class="content-segment">
	    		<div class="news">
	    			<div class="row">
			        	<div class="col-md-3 col-sm-12 title">
			        		<span class="sidetitle underscore">NEWS</span><br><br>
						</div>

						<?php retrieveBlogpostsPreview(6) ?>

				    </div>
				</div>

				<a class="thin_button" href="news.php" role="button"><span>more news &raquo;</span></a>
			</div>
	      
	      
	    </div> 
	    
	    <!--  footer -->
	    <?php printFooter() ?>
	    
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

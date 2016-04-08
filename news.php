<?php 
    include_once './inc/db.inc.php';
	include_once './inc/functions.inc.php';
	include_once './inc/blogpost.inc.php';
	
	//initialize session if none exists
	if (session_id() == '' || !isset($_SESSION)) {
		// session isn't started
		session_start();
	}
	
	//how to render page?
	$showAll = true;	//show all blogposts or just one
	$blogpost = null;	//if we're showing only one blogpost, save it's instance in this variable
	$postsPerPage = 5;	//posts to load per page - ajax will load this n° of posts eachtime you reach the bottom of the page
	
	if (isset($_GET['id'])) 
	{
		$showAll = false;
		
		//formatSinglePost();
		$blogpost = new Blogpost(FALSE);
		$blogpost -> updateParameters($_GET['id']);
	} 
	
	//check logged in
	$loggedin = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) ? TRUE : FALSE;
	$isAdmin = (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin") ? TRUE : FALSE;
	$errorVisibility="none";
?>


<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>
        	<?php
        	if (isset($_GET['id'])) 
			{
				echo $blogpost -> title;
			} 
        	?>
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
       	
       	<?php 
       	
        if (isset($_GET['id'])) 
		{
			$metaTitle = $blogpost -> title;
			$metaDescription = strip_tags($blogpost -> body);
			if($blogpost->youtubeCover != NULL && $blogpost->youtubeCover != ""){
				$metaImg = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/img/LogoFull.jpg";
			} else{
				$metaImg = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/img/medium/".$blogpost -> coverimage;
			}
			
			$metaImg = str_replace("/customers/0/3/1/", "http://www.", $metaImg); 
			$metaImg = str_replace("/httpd.www", "", $metaImg);
			
        	echo <<<META
				<meta property="og:title" value="$metaTitle" />
				<meta property="og:description" value="$metaDescription" />
				<meta property="og:image" value="$metaImg" ?>
META;
		}
		
		 ?>

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" >
        
	        var isLoading = false;
	        var isActive = <?php echo ($showAll) ? "true" : "false";  ?>;
			var postsPP = "<?php echo $postsPerPage ?>"; //posts per page
			var projectsOffset = 0;
	        
	        //AJAX function for loading more projects when reaching the bottom of the page	
	    	$(window).scroll(function() {
			   if($(window).scrollTop() + $(window).height() == $(document).height() && isActive) {
				 if(!isLoading){
				 	
					isLoading = true;
					//update variables
					projectsOffset = projectsOffset+parseInt(postsPP);
					//get data
					$.get("inc/functions.inc.php?loadblogposts=1&load="+postsPP+"&offset="+projectsOffset, function(data) {
						$(data).appendTo("#content");
						isLoading = false;
					});
					
				 }
			   }
			});
			
			
			/**
			 * Adds an event listener to all delete buttons to prompt the user to confirm deletion
			 * Hides visibility fields depending on display mode
			 *
			 * @param
			 * @return
			 */
			$(document).ready(function() {
				var buttons = document.getElementsByClassName("delete");
				
				for (var i = 0; i < buttons.length; i++) {
					buttons[i].addEventListener('click', function() {

						if (confirm('You are about to DELETE a post. \n This CANNOT BE UNDONE! \n \n  Do you want to continue?')) {
							return true;
						} else {
							event.preventDefault();
						};
					}, false);
				}
			});
			
        </script>
    </head>
    <body>	
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav -->
	    <?php echo printHeader(true, true, "green", "news", "./news.php") ?>
        
	    
	    <div class="container pagecontent news">
	    	<!-- NEWS ITEMS -->
			<div class="content-segment" id="content">
					
					<?php 
					//Load the newsitems
					//if we're looking at one post
					if (!$showAll) 
					{
						echo $blogpost -> formatSinglePost($loggedin);
					} 
					//give overview
					else
					{
						retrieveBlogposts(0,$postsPerPage);
					}
					?>
									
			</div>
	    </div> 
	    
	    <?php printFooter() ?>
	    
    	<!-- /container -->        
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

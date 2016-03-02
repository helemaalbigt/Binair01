<?php 
    include_once './inc/db.inc.php';
	include_once './inc/functions.inc.php';
	include_once './inc/blogpost.inc.php';
	
	//initialize session if none exists
	if (session_id() == '' || !isset($_SESSION)) {
		// session isn't started
		session_start();
	}

	//check logged in
	$loggedin = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) ? TRUE : FALSE;
	$isAdmin = (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin") ? TRUE : FALSE;
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
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" >
        
        /**
		 * In a form, replace preview image when a new image is selected
		 *  
		 * @param 
		 * @return
		 */
		function goto(input) {
		    var target = document.getElementById(input);
		    var rect = target.getBoundingClientRect();
		    var dist = rect.top - 70;
			
			$('html, body').animate({
		        scrollTop: dist
		    }, 800);
		}
		
		/**
		 *Add links to tags in "music" section
		 */
		window.onload = function() {
			
		  	var links = document.getElementById('musictext').getElementsByTagName('a'); //[0].innerHTML;
		  
			for(var i =0; i<links.length; i++){
			  	var att = document.createAttribute("href");       // Create a "href" attribute
				att.value = "./search.php?tag="+links[i].innerHTML;   // Set the value of the class attribute
				links[i].setAttributeNode(att);                   // Add the class attribute to <h1>
			  	
			  	var att2 = document.createAttribute("target");      // Create a "href" attribute
				att2.value = "_blank";   							// Set the value of the class attribute
				links[i].setAttributeNode(att2);                    // Add the class attribute to <h1>
			}
		};
		
        
        </script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav -->
	    <?php echo printHeader(true, true, "beige", "about", "./about.php") ?>
        
	    
	    <div class="container pagecontent news">
	    	<!-- ABOUT -->
			<div class="content-segment" id="content">
				<!-- slagzin -->
				<div class="row">
					<div class="col-md-3 title">
		        		<span class="sidetitle underscore">BINAIR 01</span><br><br>
					</div>
					<div class="col-md-8">
						<h2 class="nomargin slagzin">
							<a class="red" href="javascript:goto('bin01');">Binair 01</a> cares about <a class="red" href="javascript:goto('music');">music</a> <a class="red" href="javascript:goto('diversity');">diversity</a>. 
							We <a class="green" href="javascript:goto('offer');">offer</a> <a class="green" href="javascript:goto('quality');">quality</a> <a class="green" href="javascript:goto('dance');">dance music</a> neglected 
							by <a class="beige" href="javascript:goto('mainstream');">mainstream media</a>, who only focus on <a class="beige" href="javascript:goto('anglo');">Anglo-Saxon pop music</a>.
						<h2>
					</div>
				</div>
				
				<br>
				
				<!-- woorden -->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					<div class="col-md-8">
						
						<!-- Binair01 -->
						<h2 id="bin01"><span class="fill red">binair 01</span></h2>
						<br>
						<p>
							Binair01 is a music platform located in Ghent, Belgium, 
							striving for more music diversity and an increased attention 
							for under exposed music origins and influences.  The organization was 
							founded in 2012, aiming to fill a vast gap in the Flemish musical spectrum. 
							It expanded ever since to a collective of music lovers discovering and selecting 
							dance music from all over the world without any borders or format restrictions, 
							as an essential addition to the limited Anglo-Saxon pop music in the mainstream Flemish media.
						</p>
						<br>
		
						<!-- music -->
						<h2 id="music"><span class="fill red">music</span></h2>
						<br>
						<p id="musictext">
							Binair01 promotes music complementary to the selection made by the 
							traditional public media. We pay attention to regions and genres 
							producing amazing dance rhythms, such as <a>balkan</a> and East-European 
							<a>gypsy</a> music, <a>Afro-Latin</a> styles like <a>cumbia</a>, <a>salsa</a>, 
							<a>tango</a>, <a>reggaetón</a> 
							in both traditional and evolving electro directions, <a>mestizo</a> Catalán 
							with a <a>flamenco</a> compás, <a>African</a> <a>clubmusic</a> next 
							to traditional <a>Congolese</a> 
							<a>rumba</a> and <a>morna</a> from <a>Cabo Verde</a>, <a>Arabian</a>
							 <a>raï’n’b</a>, Scandinavian <a>hiphop</a> 
							with <a>swingrhythms</a>, <a>French</a> <a>ska</a> influenced by the Algerian immigrations, 
							Middle-East clashes between Anatolia, Arab raps and Jewish <a>klezmer</a>, 
							and so on. The music we collect and share may come from far away, 
							but is also found in the bars next door in Ghent or melting pot Brussels, 
							unable to broaden its audience in the current media framing. 
						</p>
						<br>
						
						<!-- diversity -->
						<h2 id="diversity"><span class="fill red">diversity</span></h2>
						<br>
						<p>
							Music in Flanders is stuck behind imaginary linguistic and cultural barriers. 
							National public radio broadcast neglects all music styles, origins and 
							influences which do not match the narrowly defined music specifications, 
							thus making the diversity of the first group almost endless and very 
							interesting. The differentiation of music is equally evolving with 
							our multicultural society. The music offered to the broad public 
							domain should be just as diverse, and following demographic trends rather 
							than economical compulsive logics. We try to get this balance in check pledging 
							the conviction that diversity is a vital condition in all evolutions, 
							including musical development.
						</p>
						<br>
						
						<!-- offer -->
						<h2 id="offer"><span class="fill green">offer</span></h2>
						<br>
						<p>
							The music we discover in Belgium, in neighbor countries or at the other side of the planet, 
							is shared on social media and streaming services such as Deezer, Spotify and Soundcloud. 
							Most of all we build amazing parties with this highly inflammable content, 
							from small dj-set editions to big productions with a line-up of several 
							live bands. Generally we organize 5 events a year in the nicest venues Ghent has 
							to offer: De Centrale and Kunstencentrum Vooruit (links!). We invite bands on our 
							stage playing music that doesn’t fit into most national radio formats, but are sometimes 
							famous in other parts of the world and popular in their home countries. Besides 
							these international big fishes, we keep an eye on local, Belgian talent. We give 
							this music and the bands creating it a stage, and an audience of melomaniacs and 
							dance addicts the opportunity to hear and experience what radio leaves uncovered.
						</p>
						<br>
						
						<!-- quality -->
						<h2 id="quality"><span class="fill green">quality</span></h2>
						<br>
						<p>
							If not quality but the lack of airplay is the reason why not being programmed 
							in the regular circuit, we consider this mechanism an increasing and vicious 
							bleakness of the musical landscape. The music outside the range of the conventional 
							media is mostly valuable, both for a social and artistic reason. Artists coloring 
							outside the lines of a radio format often tend to innovative crossovers, and mutual 
							influences lead more than perhaps to new music trends and evolutions with 
							interesting music-technical features and fresh rhythm variations. 
						</p>
						<br>
						
						<!-- dance -->
						<h2 id="dance"><span class="fill green">dance music</span></h2>
						<br>
						<p>
							The music we collect is carefully selected on its dance pedigree 
							and complexity of influences and variety of rhythms. Dancing is the very core of our 
							events. It is not wobbling on endless repeating beats. It’s not waiting till the dj 
							drops a radio hit with a recognizable tune to suddenly go wild. It isn’t shining in 
							front of a photographer’s lens with your whitest teeth either. It’s just dancing, 
							unconditional dancing without any restrictive barriers. Our parties and concerts 
							are never too crowded to even get a chance to start moving. We keep the dance floor 
							enjoyable and give your shoes all the space they need.  So dance, dance, otherwise we are lost.
						</p>
						<br>
						
						<!-- mainstream media -->
						<h2 id="mainstream"><span class="fill beige">mainstream media</span></h2>
						<br>
						<p>
							Most dance music played on the radio is focusing on catchy tunes rather than rich rhythms. 
							Mid-Western Europe lost in bits and pieces the art of dancing together. The responsibility 
							of public media in gathering people as a layered but cohesive society can’t be underestimated. 
							It must represent all its members, in all its cultural, demographic and linguistic differentiations. 
							This need is even enforced by the recent phenomenon of open, global music traffic thanks 
							to virtual and physical migrations. Contrary to this evolution, the music diversity provided 
							by broadcast mainstream media is decreasing and exclusively focusing on a 1% part of economically dominant music.
						</p>
						<br>
						
						<!-- anglo -->
						<h2 id="anglo"><span class="fill beige">Anglo-Saxon pop-music</span></h2>
						<br>
						<p>
							Don’t be misunderstood: we actually like the good part of Anglo-Saxon pop, 
							rock and dance music just like any genres. We just aim for the right balance, 
							and therefore try to bring alive a small part of the 99% neglected music by 
							the conventional radio channels in Flanders.
						</p>
						<br>
						
					</div>
				</div>
				
				<br><br>
				
				<!-- HISTORY -->
				<div class="row">
					<div class="col-md-3 title">
		        		<span class="sidetitle underscore">HISTORY</span><br><br>
					</div>
					
					<div class="col-md-8">
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, 
							ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque 
							nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus 
							et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo 
							ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh 
							condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis 
							sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. 
							Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. 
							Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor 
							a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.
						</p>
					</div>
				</div>
				
				<br><br>
				
				<!-- PARTNERS -->
				<div class="row">
					<div class="col-md-3 title">
		        		<span class="sidetitle underscore">PARTNERS</span><br><br>
					</div>
					
					<div class="col-md-8">
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, 
							ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque 
							nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus 
							et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo 
							ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh 
							condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis 
							sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. 
							Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. 
							Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor 
							a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.
						</p>
					</div>
				</div>
				
								
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

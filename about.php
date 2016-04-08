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
		 * -Old function for old slagzin-
		 * 
		 * Cycle through each slagzin word 
		 */
									/*
		var slagZinLinks;
		var slagZinIndex = 0;
		var auto = true;

		window.setInterval(function(){
		 	
		 	if(auto){
			 	for(var i =0; i<slagZinLinks.length; i++){
			 		slagZinLinks[i].setAttribute("active", "false");
			 		document.getElementById(slagZinLinks[i].getAttribute("targetid")).style.display = 'none';	
			 	}
			 	
			 	slagZinIndex = (slagZinIndex + 1) % slagZinLinks.length;
			 	
			 	slagZinLinks[slagZinIndex].setAttribute("active", "true");
			 	document.getElementById(slagZinLinks[slagZinIndex].getAttribute("targetid")).style.display = 'block';	
		 	}
		 	
		}, 3000);
									*/
				
		/**
		 * Add links to tags in "music" section
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
			
			//prep for auto word switch 
			slagZinLinks = document.getElementById('slagzin').getElementsByTagName('a');	//get all links in slagzin
			//show only first text
			for(var i =0; i<slagZinLinks.length; i++){
		 		slagZinLinks[i].setAttribute("active", "false");
		 		document.getElementById(slagZinLinks[i].getAttribute("targetid")).style.display = 'none';	
			}
		 	slagZinLinks[slagZinIndex].setAttribute("active", "true");
		 	document.getElementById(slagZinLinks[slagZinIndex].getAttribute("targetid")).style.display = 'block';	
		};
		
		
		/**
		 *Opens info on each slagzin word 
		 * 
		 * @param 
		 * @return
		 */
		function openInfo(e){
			
			if(e.getAttribute("active") == "false")
			{
				e.setAttribute("active", "true");
				$(e).parent().next().slideDown(700);
			} 
			else
			{
				e.setAttribute("active", "false");
				$(e).parent().next().slideUp(700);
			}
		}
		
		
		/**
		 * Slagzin mink function: loads respective piece of text
		 *  
		 * @param 
		 * @return
		 */
		function goto(e) {
			auto = false;
			
			for(var i =0; i<slagZinLinks.length; i++){
		 		slagZinLinks[i].setAttribute("active", "false");
		 		document.getElementById(slagZinLinks[i].getAttribute("targetid")).style.display = 'none';	
		 	}
		 	
		 	e.setAttribute("active", "true"); 
		 	document.getElementById(e.getAttribute("targetid")).style.display = 'block';	
		}
        
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
						<div class="nomargin slagzin" id="slagzin">
							<!--Toggle-->
							<h2><a class="red activated" active="false" targetid="bin01" onclick="openInfo(this)">Binair 01&#x25BE</a></h2> 
							
							<p style="display:none;">
							<br>
							Binair01 is a music platform located in Ghent, Belgium, 
							striving for more music diversity and an increased attention 
							for under exposed music origins and influences.  The organization was 
							founded in 2012, aiming to fill a vast gap in the Flemish musical spectrum. 
							It expanded ever since to a collective of music lovers discovering and selecting 
							dance music from all over the world without any borders or format restrictions, 
							as an essential addition to the limited Anglo-Saxon pop music in the mainstream Flemish media.
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2>cares about <a class="red" active="false" targetid="music" onclick="openInfo(this)">music&#x25BE</a></h2> 
							
							<p id="musictext" style="display:none;">
								<br>
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
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2><a class="red" active="false" targetid="diversity" onclick="openInfo(this)">diversity&#x25BE</a>.</h2> 
							
							<p style="display:none;">
								<br>
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
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2>We <a class="green" active="false" targetid="offer" onclick="openInfo(this)">offer&#x25BE</a></h2> 
							
							<p style="display:none;">
								<br>
							The music we discover in Belgium, in neighbor countries or at the other side of the planet, 
							is shared on social media and streaming services such as 
							<a href="http://www.deezer.com/profile/698631591/playlists" target="_blank">Deezer</a>, 
							<a href="https://play.spotify.com/user/binair01" target="_blank">Spotify</a> and 
							<a href="https://soundcloud.com/binair01-1" target="_blank">Soundcloud</a>. 
							Most of all we build amazing parties with this highly inflammable content, 
							from small dj-set editions to big productions with a line-up of several 
							live bands. Generally we organize 5 events a year in the nicest venues Ghent has 
							to offer: De Centrale and Kunstencentrum Vooruit (links!). We invite bands on our 
							stage playing music that doesn’t fit into most national radio formats, but are sometimes 
							famous in other parts of the world and popular in their home countries. Besides 
							these international big fishes, we keep an eye on local, Belgian talent. We give 
							this music and the bands creating it a stage, and an audience of melomaniacs and 
							dance addicts the opportunity to hear and experience what radio leaves uncovered.
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2><a class="green" active="false" targetid="quality" onclick="openInfo(this)">quality&#x25BE</a></h2> 
							
							<p style="display:none;">
								<br>
							If not quality but the lack of airplay is the reason why not being programmed 
							in the regular circuit, we consider this mechanism an increasing and vicious 
							bleakness of the musical landscape. The music outside the range of the conventional 
							media is mostly valuable, both for a social and artistic reason. Artists coloring 
							outside the lines of a radio format often tend to innovative crossovers, and mutual 
							influences lead more than perhaps to new music trends and evolutions with 
							interesting music-technical features and fresh rhythm variations. 
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2><a class="green" targetid="dance" onclick="openInfo(this)">dance music&#x25BE</a></h2> 
							
							<p style="display:none;">
								<br>
							The music we collect is carefully selected on its dance pedigree 
							and complexity of influences and variety of rhythms. Dancing is the very core of our 
							events. It is not wobbling on endless repeating beats. It’s not waiting till the dj 
							drops a radio hit with a recognizable tune to suddenly go wild. It isn’t shining in 
							front of a photographer’s lens with your whitest teeth either. It’s just dancing, 
							unconditional dancing without any restrictive barriers. Our parties and concerts 
							are never too crowded to even get a chance to start moving. We keep the dance floor 
							enjoyable and give your shoes all the space they need.  So dance, dance, otherwise we are lost.
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2>neglected by <a class="beige" active="false" targetid="mainstream" onclick="openInfo(this)">mainstream media&#x25BE</a>,</h2> 
							
							<p style="display:none;">
								<br>
							Most dance music played on the radio is focusing on catchy tunes rather than rich rhythms. 
							Mid-Western Europe lost in bits and pieces the art of dancing together. The responsibility 
							of public media in gathering people as a layered but cohesive society can’t be underestimated. 
							It must represent all its members, in all its cultural, demographic and linguistic differentiations. 
							This need is even enforced by the recent phenomenon of open, global music traffic thanks 
							to virtual and physical migrations. Contrary to this evolution, the music diversity provided 
							by broadcast mainstream media is decreasing and exclusively focusing on a 1% part of economically dominant music.
							<br>
							<br>
							</p>
							
							<!--Toggle-->
							<h2>who only focus on <a class="beige" active="false" targetid="anglo" onclick="openInfo(this)">Anglo-Saxon pop music&#x25BE</a>.</h2>
							
							<p style="display:none;">
								<br>
							Don’t be misunderstood: we actually like the good part of Anglo-Saxon pop, 
							rock and dance music just like any genres. We just aim for the right balance, 
							and therefore try to bring alive a small part of the 99% neglected music by 
							the conventional radio channels in Flanders.
							<br>
							<br>
							</p>
							
						</div>
					</div>
				</div>
				
				
				<br><br><br><br><br>
				
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
				
				
				<!-- PEOPLE -->
				<!-- Collaborators & Volunteers -->
				<div class="row">
					<div class="col-md-3 title">
		        		<span class="sidetitle underscore">PEOPLE</span><br><br>
					</div>
					
					
					<div class="col-md-8 title">
		        		<span class="sidetitle">Collaborators & volunteers</span><br><br>
		        		
		        		<p>
		        			The events organized by binair01 wouldn’t be possible without the help of many volunteers. <br>
		        			For each event 10 to 40 people are working hard on spreading the promo stuff, 
		        			getting the production right, serving drinks and selling tickets.<br><br>
							We are always looking for motivated people to join the organization or to volunteer on one of our events. 
							If you want to jump in, just write us at vrijwilligers@binair01.be.
		        		</p><br><br>
					</div>
				
				</div>
				
				<!-- Board Members -->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<!-- Collaborators & Volunteers -->
					<div class="col-md-8 title">
		        		<span class="sidetitle">Board Members</span><br><br>
					</div>
				</div>
					
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<!--Olivier-->
					<div class="col-xs-4 col-md-2 title">
		        		<img class="img-responsive" src="img/about/oliv.jpg" /> <!--Profile image, change src path to change image-->
					</div>
					
					<div class="col-xs-7 col-md-6">
						<h2 class="no_top_margin"><span class="">Olivier Roegiest</span></h2>
						<b>Founding member, music selection, band scouting, production, resident dj</b>
						<br><br>
						<p>
							Olivier is one of the founding members of binair01. 
							He’s responsible for the overall functioning and production, 
							and selects the playlist music and the live bands being programmed. <br>
							As an architect and an urbanist he’s fascinated by the complexity and diversity of the urban fabric 
							whose identity is subtly influenced by new music tendencies, and in the meantime questioning its 
							imaginary cultural borders. As a devoted music addict he carefully built up an ever expanding 
							valuable collection of non-exposed dance music treasures by years of research in all quarters 
							of our planet. He combines this knowledge with traditional club mixing techniques gained throughout a long career as a party dj.
							<br><br>
							
							The sum of classic beatmatching and unconventional dance tunes makes him a party dj producing 
							a unique sound, developed under the pseudonym mnsr zér0, resident at binair01.
							<br><br>
							
							His sets are surprising blends of greasy balkan with a crazy gipsy wink, super tight electric 
							cumbiabeats on eclectic afrolatinoriffs, hot Spanish nuevo flamenco melting on übercooled 
							Scandinavian swinghip-hop, rumba Catalána versus African clubzouk, contagious ska infecting 
							French jazz manouche, solid reggaetón devouring fragile tango, Arabian raps clashing 
							with uptempo klezmer. His mixes always feature an exciting variety of rhythms and are a 
							real relief for people addicted to dancing and searching for challenges beyond the simple beats of shallow radio hit music.
						</p>
						<p>
							<div class="infotext">
								<span class="glyphicon glyphicon-envelope small"></span> <a href="mailto:olivier.roegiest@binair01.be" target="_blank">olivier.roegiest@binair01.be</a><br>
								<span class="glyphicon glyphicon-user small"></span> <a href="https://www.facebook.com/binair01.mnsrzero" target="_blank">www.facebook.com/binair01.mnsrzero</a><br>
								<span class="glyphicon glyphicon-earphone small"></span> 0478/287323<br>
							</div>
						</p>
						<br>
					</div>		
				</div><br>
				
				<!--Tijl-->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
												
					<div class="col-xs-4 col-md-2 title">
		        		<img class="img-responsive" src="img/about/oliv.jpg" /> <!--Profile image, change src path to change image-->
					</div>
					
					<div class="col-xs-7 col-md-6">
						<h2 class="no_top_margin"><span class="">Tijl</span></h2>
						<b>Founding member, music selection, band scouting, production, resident dj</b>
						<br><br>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, 
							ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque 
							nunc augue sit amet nibh.
						</p>
						<p>
							<div class="infotext">
								<span class="glyphicon glyphicon-envelope small"></span> <a href="mailto:olivier.roegiest@binair01.be" target="_blank">olivier.roegiest@binair01.be</a><br>
								<span class="glyphicon glyphicon-user small"></span> <a href="https://www.facebook.com/binair01.mnsrzero" target="_blank">www.facebook.com/binair01.mnsrzero</a><br>
								<span class="glyphicon glyphicon-earphone small"></span> 0478/287323<br>
							</div>
						</p>
						<br>
					</div>
				</div><br>
			
				<!--Thomas-->	
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<div class="col-xs-4 col-md-2 title">
		        		<img class="img-responsive" src="img/about/thomas.jpg" /> <!--Profile image, change src path to change image-->
					</div>
					
					<div class="col-xs-7 col-md-6">
						<h2 class="no_top_margin"><span class="">Thomas</span></h2>
						<b>Founding member, music selection, band scouting, production, resident dj</b>
						<br><br>
						<p>
							Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, 
							ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque 
							nunc augue sit amet nibh.
						</p>
						<p>
							<div class="infotext">
								<span class="glyphicon glyphicon-envelope small"></span> <a href="mailto:olivier.roegiest@binair01.be" target="_blank">olivier.roegiest@binair01.be</a><br>
								<span class="glyphicon glyphicon-user small"></span> <a href="https://www.facebook.com/binair01.mnsrzero" target="_blank">www.facebook.com/binair01.mnsrzero</a><br>
								<span class="glyphicon glyphicon-earphone small"></span> 0478/287323<br>
							</div>
						</p>
						<br>
					</div>		
				</div><br>
				
				<br><br>
				
				
				<!-- PARTNERS -->
				
				<div class="row">
					<div class="col-md-3 title">
		        		<span class="sidetitle underscore">PARTNERS</span><br><br>
					</div>
					
					
					<div class="col-md-8 title">		        		
		        		<p>
		        			We are constantly looking for local partners who share our philosophy. 
		        			The following brands and companies are actually some of our most loyal 
		        			sponsors or partners. All of them match our mission of creating an urban 
		        			environment which is sustainable and durable, driven by creative concepts 
		        			which increase life quality and embrace the variety and layered complexity 
		        			of our cities.
		        		</p><br><br>
					</div>
				
				</div>
				
				<!--Partner 1-->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<a href="http://www.tastyworld.be/en/home/" target="_blank"> <!--vervang door link naar partner website--> 
						<div class="col-xs-4 col-md-2 title">
		        			<img class="img-responsive" src="img/about/tasty.jpg" /> <!--Profile image, change src path to change image-->
						</div>
					</a>
					
					<div class="col-xs-7 col-md-6">
						<h2 id="anglo" class="no_top_margin"><span class="">Tasty World</span></h2>
							<br>
							<p>
								Tasty World is our royal veggie burger delivery service since years. 
								Thanks to their catering, our volunteers never turn hungry.
								<br><br>
								In Ghent, Tasty World created a new concept in offering a varied choice 
								of vegetarian burgers and smoothies. Their philosophy is to create a solid 
								basis for what’s utterly important in our demanding society: a physical and 
								mental balance thanks to healthy and tasty food. They emphasize the quality 
								of their ingredients and the freshness of the in-house prepared food. Their 
								environmental consciousness is high and they only use bio-degradable waste.
								<br><br>
								Check them out at on their <a href="http://www.tastyworld.be/en/home/" target="_blank">website</a> or in their restaurants at Hoogpoort 1 and Walpoortstraat 38 in Ghent.
							</p>
						<br>
					</div>	
				</div><br>
				
				<!--Partner 2-->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<a href="http://www.lekkergec.be/" target="_blank"> <!--vervang door link naar partner website--> 
						<div class="col-xs-4 col-md-2 title">
		        			<img class="img-responsive" src="img/about/gec.jpg" /> <!--Profile image, change src path to change image-->
						</div>
					</a>
					
					<div class="col-xs-7 col-md-6">
						<h2 id="anglo" class="no_top_margin"><span class="">Lekker Gec</span></h2>
							<br>
							<p>
								Lekker Gec delivers the exquisite meals for our musicians and guests. 
								Thanks to their catering, the performances on our stages are outstanding.
								<br><br>
								Lekker Gec is a bio-vegetarian restaurant. A cooperative company supported 
								by a joyful team. The core of business is not only the preparation of nice 
								food with respect for the environment, but also their social engagement. 
								In their kitchen they train people in need of a re-orientation in order 
								to get a new chance on the labor market.
								<br><br>
								Lucky for us, they recently launched a delivery and catering service, aiming 
								an increased awareness that healthy food can be honest and tasty for a broad public.
								Check them out at on their <a href="http://www.lekkergec.be/" target="_blank">website</a> or in their restaurant at K. 
								Maria Hendrikaplein 6, just in front of the main train station Ghent Sint-Pieters.
							</p>
						<br>
					</div>	
				</div><br>
				
				<!--Partner 3-->
				<div class="row">
					<div class="col-md-3 title">
		        		&nbsp;
					</div>
					
					<a href="http://www.tastyworld.be/en/home/" target="_blank"> <!--vervang door link naar partner website--> 
						<div class="col-xs-4 col-md-2 title">
		        			<img class="img-responsive" src="img/about/bio.gif" /> <!--Profile image, change src path to change image-->
						</div>
					</a>
					
					<div class="col-xs-7 col-md-6">
						<h2 id="anglo" class="no_top_margin"><span class="">Bioplanet</span></h2>
							<br>
							<p>
								Bioplanet is our home supermarket. 
								They offer us all the stuff we need backstage, 
								from crackers and fruit to spoil cast and crew, 
								to soap for washing their plates. Thanks to them, 
								we never run short on cookies with plenty of tea for twenty.
								____________________ nog aan te passen! (mail naar bioplanet)______________
								The Bio-Planet mission states that we want our customers to enjoy life in 
								a healthier and more conscious way, thanks to our tasty, complete and 
								reliable assortment of organic products. Bio-Planet is a unique concept, 
								as a separate supermarket selling only organic food and ecological 
								non-food products. Our new shop in Leuven (Belgium) is designed according 
								to the latest and best possible ecological performance indicators. 
								In this way, it is a trendsetter with an integrated green approach 
								combining product and infrastructure aspects in a business context. 
								Additionally, we are taking a new step towards a 'passive' supermarket. 
								Our first attention went to lowering energy consumption in general. 
								For a shop that is already very energy efficient due to its concept 
								(a closed cold-storage area instead of open refrigerated shelves, 
								no air conditioning, no excessive lighting, no open freezers), 
								the challenge was to cut energy consumption by another half. 
								By monitoring the performance of the different techniques, 
								we will evaluate its applicability for other stores within the Colruyt Group. 	
							</p>
						<br>
					</div>	
				</div><br>
				
								
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

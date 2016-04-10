<?php
//initialize session if none exists
if (session_id() == '' || !isset($_SESSION)) {
	// session isn't started
	session_start();
}

/*
 * AJAX functions
 */
 //load more blogposts
 if(isset($_GET['loadblogposts']) && isset($_GET['load']) && isset($_GET['offset'])){
 	retrieveBlogposts($_GET['offset'], $_GET['load']);
 }
 //load more events
 if(isset($_GET['loadevents']) && isset($_GET['load']) && isset($_GET['offset'])){
 	retrieveEvents($_GET['offset'], $_GET['load']);
 }


/**
 * Clean form data 
 * 
 * Strips tags and removes/replaces certain characters from post data
 * 
 * @param array $p Post data from a form
 * @return array $p
 */
 function cleanData($p){
 	$returnArray = array();
 	foreach($p as $key => $value){
 		//if value is an array recursively apply this function
 		if(is_array($value)){
			$returnArray[$key] = cleanData($value);
		}
		//if value is a string, clean data 
		else{
			//arrays with strings to find and replace
 			$find = array("<?php", "?>");
			$replace  = array("", "");
			//trips possible tags (excluding links, bold, italic, lists, paragraphs) first, then removes certain forbidden strings, then removes backslashes, removes the first pargraph tag, removes the first closing paragraph tag, then converts remaining special characters to htmlentities
 		  	$returnArray[$key] =htmlspecialchars( preg_replace('~<p>(.*?)</p>~is', '$1',stripslashes(str_replace($find, $replace, strip_tags($value, "<a><i><b><strong><em><li><ul><ol><br><p><iframe>"))), 1),ENT_QUOTES); 
		}
 	}
 	//return the cleaned array
 	return $returnArray;
 }
 
/**
 * Generates a unique name for a file
 *
 * Uses the current timestamp and a randomly generated number
 * to create a unique name to be used for an uploaded file.
 * This helps preventing a new file upload from overwriting an
 * existing file with the same name.
 *
 * @param string $ext the file extension for the upload
 * @return string the new filename
 */
function renameFile($ext) {
	/*
	 * returns the current timestamp and a random number
	 * to avoid duplicate filenames
	 */
	return time() . '_' . mt_rand(1000, 9999) . $ext;
}

/**
 * Determines the filetype and extension of an image
 *
 * @param string $type the MIME type of the image
 * @return string the extension to be used with the file
 */
function getImageExtensions($type) {
	
	switch ($type) {
		case 'image/gif' :
			return '.gif';

		case 'image/jpeg' :
		case 'image/pjpeg' :
			return '.jpg';

		case 'image/png' :
			return '.png';

		default :
			throw new Exception('Image File type is not recognized!');

			break;
	}
}

/**
 * Save image in various formats
 * 
 * @param
 * @return
 */
 function saveImage($image){
 	//1.Original Image
	// Separate the uploaded file array
	list($name, $type, $tmp, $err, $size) = array_values($image);
	//get extension
	$ext = getImageExtensions($type);
	//rename
	$filename = renameFile($ext);
	$coverimagePath = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/img/original/".$filename;
	//save original
	if (!move_uploaded_file($tmp, $coverimagePath)) {
	 	throw new Exception("Couldn't save the uploaded image!");
    }
	
	//2.save small, medium and large image
	$destination = '../img/small/'.$filename;
	resizeAndSaveImage($coverimagePath, $destination, 143, 143);
	
	$destination = '../img/medium/'.$filename;
	resizeAndSaveImage($coverimagePath, $destination, 750, 450);
	
	$destination = '../img/large/'.$filename;
	$img = new abeautifulsite\SimpleImage($coverimagePath);
	$img->best_fit(2000, 2000)->save($destination);
	
	//3.remove original
	unlink($coverimagePath);
		
	//4.return filename
	return $filename;
 }
 
/**
 * Save image in various colors for the homepage and the nav bars
 * 
 * @param
 * @return
 */
 function saveWebCoverImage($image){
 	//1.Original Image
	// Separate the uploaded file array
	list($name, $type, $tmp, $err, $size) = array_values($image);
	//get extension
	$ext = getImageExtensions($type);
	//rename
	$filename = "cover_original".$ext;
	$coverimagePath = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/img/original/".$filename;
	//save original
	if (!move_uploaded_file($tmp, $coverimagePath)) {
	 	throw new Exception("Couldn't save the uploaded image!");
    }
	
	//2.save small and medium image
	$destination = '../img/'."cover_red".$ext;
	$img = new abeautifulsite\SimpleImage($coverimagePath);
	$img->desaturate()->contrast(30)->brightness(-40)->colorize('#bf1e2e', .8)->save('../img/'."cover_red".$ext);
	$img->desaturate()->contrast(10)->brightness(-80)->colorize('#648c8c', .8)->save('../img/'."cover_green".$ext);
	$img->desaturate()->contrast(0)->brightness(-100)->colorize('#a1a274', .8)->save('../img/'."cover_beige".$ext);	
 }

/**
 * Resize and save an image
 * 
 * @param
 * @return
 */
 function resizeAndSaveImage($originalPath, $destination, $W, $H){
 	
 	list($width, $height, $type, $attr) = getimagesize($originalPath);
 	$img = new abeautifulsite\SimpleImage($originalPath);
	
	if($width/$height < $W/$H){
		//portrait
		$y1 = (($height * $W / $width) - $H) / 2;
		$y2 = ((($height * $W / $width) - $H) / 2 ) + $H;
		$img->fit_to_width($W)->crop(0, $y1, $W, $y2)->save($destination);
	} else{
		//landscape
		$x1 = (($width * $H / $height) - $W) / 2;
		$x2 = ((($width * $H / $height) - $W) / 2 ) + $W;
		$img->fit_to_height($H)->crop($x1, 0, $x2, $H)->save($destination);
	}
	
	/*
		 if($width/$height < $W/$H){
			$img->fit_to_width($W)->crop(0, ($height - $H) / 2, $W, (($height - $H) / 2) + $H)->save($destination);
		} else{
			$img->fit_to_height($H)->crop(($width - $W) / 2, 0, (($width - $W) / 2) + $W, $H)->save($destination);
		}
	 */	
 }
 
 /**
  * Return current playlist id
  * 
  * @param
  * @return
  */
  function getPlaylistID(){
  	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	$sql = "SELECT playlist FROM parameters WHERE id=1 LIMIT 1";
	$stmt = $db -> prepare($sql);
	$stmt -> execute(array());

	//save the returned playlist
	$e = $stmt -> fetch();
	$stmt -> closeCursor();
	
	return $e['playlist'];
  }
 
 
 /**
  * Return current playlist IFrame
  * 
  * @param
  * @return
  */
  function getPlaylistIFrame(){
  	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	$sql = "SELECT playlist FROM parameters WHERE id=1 LIMIT 1";
	$stmt = $db -> prepare($sql);
	$stmt -> execute(array());

	//save the returned playlist
	$e = $stmt -> fetch();
	$stmt -> closeCursor();
	
	//replace height and width
	//$interm = preg_replace('/(<*[^>]*width=)"[^>]+"([^>]*>)/', '\1"100%"\2', htmlspecialchars_decode($e['playlist']) );
	$result = preg_replace('/(<*[^>]*height=)"[^>]+"([^>]*>)/', '\1"90"\2', htmlspecialchars_decode($e['playlist']));
	$result = preg_replace('/(<*[^>]*width=)"[^>]+"([^>]*>)/', '\1"100%"\2', $result);
	
	return htmlspecialchars_decode($result);
  }
 
 
 /**
  * Print Latest newsItems preview format
  *	
  * @param int $numberOfPosts
  * @return
  */
function retrieveBlogpostsPreview($numberOfPosts) {

	include_once 'blogpost.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM blogposts ORDER BY sortdate DESC, created DESC 
			LIMIT ".$numberOfPosts;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	$counter = 0;
	
	echo "<div class='body-content'>";
	
	while ($row = $stmt -> fetch()) {
		
		//echo in case of a new row of previews (3 per row): adds an empty row as a spacer
		if($counter%3 == 0 && $counter > 0){
			echo <<<ROW
			</div>
				</div>
					<div class='body-content'>
						<div class="row news">
							<div class="col-md-3 col-sm-0">
								&nbsp;
							</div>
ROW;
		}
		
		//echo blogpost
		$blogpost = new Blogpost(FALSE);
		$blogpost -> updateParameters($row['id']);
		echo $blogpost -> formatPreview();
		flush();
		
		$counter++;
	}
	
	echo "</div>";

	$stmt -> closeCursor();
}


/**
  * Print Latest events preview format
  *	
  * @param int $numberOfPosts
  * @return
  */
function retrieveEventsPreview($numberOfPosts) {

	include_once 'event.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM events ORDER BY sortdate DESC, created DESC 
			LIMIT ".$numberOfPosts;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	$counter = 0;
	
	while ($row = $stmt -> fetch()) {
		
		//echo in case of a new row of previews (3 per row)
		if($counter%3 == 0 && $counter > 0){
			echo <<<ROW
			<div class="col-md-3 col-sm-0">
				&nbsp;
			</div>
ROW;
		}
		
		//echo blogpost
		$event = new Event(FALSE);
		$event -> updateParameters($row['id']);
		echo $event -> formatPreview();
		flush();
		
		$counter++;
	}

	$stmt -> closeCursor();
}


/**
  * Print Latest newsItems 
  *	
  * @param int $offset 
  * @param int $numberOfPosts
  * @return
  */
function retrieveBlogposts($offset, $numberOfPosts) {

	include_once 'blogpost.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM blogposts ORDER BY sortdate DESC, created DESC 
			LIMIT ".$numberOfPosts . " OFFSET " . $offset;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	while ($row = $stmt -> fetch()) {
		//echo blogpost
		$blogpost = new Blogpost(FALSE);
		$blogpost -> updateParameters($row['id']);
		echo $blogpost -> formatNewspage();
		flush();
	}

	$stmt -> closeCursor();
}


/**
  * Print Latest newsItems 
  *	
  * @param int $offset 
  * @param int $numberOfPosts
  * @return
  */
function retrieveUpcomingEvents() {

	include_once 'event.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id FROM events 
			WHERE sortdate >= ".date("Y").date("m").date("d")."
			ORDER BY sortdate ASC, created DESC";
			
	
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	while ($row = $stmt -> fetch()) {
		//echo blogpost
		$event = new Event(FALSE);
		$event -> updateParameters($row['id']);
		echo $event -> formatEventpage();
		flush();
	}

	$stmt -> closeCursor();
}


/**
  * Print Latest newsItems 
  *	
  * @param int $offset 
  * @param int $numberOfPosts
  * @return
  */
function retrieveEvents($offset, $numberOfPosts) {

	include_once 'event.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM events 
			WHERE sortdate < ".date("Y").date("m").date("d")."
			ORDER BY sortdate DESC, created DESC 
			LIMIT ".$numberOfPosts . " OFFSET " . $offset;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	while ($row = $stmt -> fetch()) {
		//echo blogpost
		$event = new Event(FALSE);
		$event -> updateParameters($row['id']);
		echo $event -> formatEventpage();
		flush();
	}

	$stmt -> closeCursor();
}


/**
  * Print Latest newsItems 
  *	
  * @param int $offset 
  * @param int $numberOfPosts
  * @return
  */
function retrievePostsWithTag($tag, $offset, $numberOfPosts) {

	include_once 'blogpost.inc.php';
	include_once 'event.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	//initialize array to store retrieved ids
	$results = array();
	
	//search blogposts
	$sql = "SELECT id, sortdate, created
			FROM blogposts 
			WHERE tags LIKE '%".$tag."%'"; 
			//LIMIT ".$numberOfPosts . " OFFSET " . $offset;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	//add results to results array
	while ($row = $stmt -> fetch()) {
		array_push($results, array("id" => $row['id'], "created" => $row['created'], "sortdate" => $row['sortdate'], "posttype" => "blogpost"));
		flush();
	}
	$stmt -> closeCursor();
	
	//search events
	$sql = "SELECT id, sortdate, created
			FROM events 
			WHERE tags LIKE '%".$tag."%'"; 
			//LIMIT ".$numberOfPosts . " OFFSET " . $offset;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	
	//add results to results array
	while ($row = $stmt -> fetch()) {
		array_push($results, array("id" => $row['id'], "created" => $row['created'], "sortdate" => $row['sortdate'], "posttype" => "event"));
		flush();
	}
	$stmt -> closeCursor();
	
	//if we found results, print them
	if(count($results) > 0){
		
		//sort the results array by sortdate and created
		foreach ($results as $key => $row) {			// Obtain a list of columns
		    $sortdate[$key]  = $row['sortdate'];
		    $created[$key] = $row['created'];
		}
		array_multisort($sortdate, SORT_DESC, $created, SORT_DESC, $results);
		
		
		//echo posts
		foreach($results as $result){
			switch($result['posttype']){
				case 'blogpost':
					$blogpost = new Blogpost(FALSE);
					$blogpost -> updateParameters($result['id']);
					echo $blogpost -> formatNewspage();
					flush();
					break;
					
				case 'event':
					$event = new Event(FALSE);
					$event -> updateParameters($result['id']);
					echo $event -> formatEventpage();
					flush();
					break;
			}
		}
		
	} else{
		echo "<h4>Sorry, we found no news articles or events with this tag</h4><br>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><!-- sorry, I was lazy -->";
	}
}


/**
  * Prints standard header
  *	
  * @param bool $logoVisible logo visible?
  * @param bool $does the navbar stick to the top of the page?
  * @param string $background color of the bannerimage (none, red, green, beige)
  * @param string $page name of active page (home, news, events, search, admin)
  * @param string $pageLink link to active page
  * @return
  */
function printHeader($logoVisible, $fixed, $background, $page, $pageLink){
	
	$logo = ($logoVisible) ? "<a href='index.php'><img src='img/LogoSmall.png' class='logo'/></a>" : "";//<h1 class='white'>BINAIR 01</h1>
	$navFixed = ($fixed) ? "navbar-fixed-top" : "";
	$pageBreadcrumb = ($page == "home" || $page == "") ? "": "<a href='".$pageLink."'><span><h4> / ".strtoupper($page)."</h4></span></a>" ;
	//$pageBreadcrumb = ($page == "home" || $page == "") ? "": "<span><h4> / ".strtoupper($page)."</h4></span>" ;
	$rand = rand(0,10000);
	
	$aboutClass = ($page == "about") ? "current" : "";
	$eventsClass = ($page == "events") ? "current" : "";
	$newsClass = ($page == "news") ? "current" : "";
	
	      
	      return <<<HEADER
	      
	       <nav class="navbar navbar-inverse $navFixed $background" role="navigation">
	       <div class="container">
	      	
	        <div class="navbar-header top">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar$rand">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>                        
		      	</button>
	          	$logo  $pageBreadcrumb 
	        </div>
	        
	        <div class="collapse navbar-collapse" id="myNavbar$rand">
			      <ul class="nav navbar-nav navbar-right">
			        <li class="menuoption $aboutClass"><a href="about.php" class="white"><h4>ABOUT</h4></a></li>
			        <li class="menuoption $eventsClass"><a href="events.php" class="white"><h4>EVENTS</h4></a></li>
			        <li class="menuoption $newsClass"><a href="news.php" class="white"><h4>NEWS</h4></a></li>
			      </ul>
			</div>
	        
	      </div>
	    </nav>
			
HEADER;

}


/**
  * Prints standard footer
  *	
  * @param 
  * @return
  */
function printFooter(){
	
	$year = date("Y");
	      
	      echo <<<FOOTER
	      <div class="container footer">
	      	<hr>
			<footer>
				<div class="row">
				
					<div class="col-sm-4">
						<div class="footer darkgray">&copy; Binair01 - $year </div><br>
						<br>
		        		<br>
			        </div>
			        
			        <div class="col-sm-4">
			        	<div class="footer darkgray footertitle">CONTACT</div>
			        	
		        		<div class="footer darkgray">binair01@binair01.be</div>
		        		<div class="footer darkgray">Begijnhoflaan 121, B-9000 Gent</div>
		        		<div class="footer darkgray">t: 09 233 80 23</div>
		        		<br>
		        		<br>
			        </div>
			        
			        <div class="col-sm-4">
			        	<div class="footer darkgray footertitle">FOLLOW BINAIR01</div>
			        	
				        <ul class="social">
				        	<li>
				        		<a data-toggle="tooltip" title="mixcloud" href="https://www.mixcloud.com/mnsr_z%C3%A9r0/" target="_blank" id="mixcloud">mc &nbsp;</a>  
				        		<a data-toggle="tooltip" title="facebook" href="https://www.facebook.com/binair01-182905618426061/?fref=ts" target="_blank" id="facebook">fb &nbsp;</a>
				        		<a data-toggle="tooltip" title="youtube" href="https://www.youtube.com/user/binair01" target="_blank" id="youtube">yt &nbsp;</a> 
				        		<a data-toggle="tooltip" title="twitter" href="https://twitter.com/binair01" target="_blank" id="twitter">tw &nbsp;</a>  
				        		<br>
				        		<a data-toggle="tooltip" title="instagram" href="https://www.instagram.com/binair01/" target="_blank" id="instagram">ig &nbsp;</a> 
				        		<a data-toggle="tooltip" title="soundcloud" href="https://soundcloud.com/binair01-1" target="_blank" id="soundcloud">sc &nbsp;</a>  
				        		<a data-toggle="tooltip" title="spotify" href="https://open.spotify.com/user/binair01" target="_blank" id="spotify">sf &nbsp;</a>   
				        		<a data-toggle="tooltip" title="deezer" href="http://www.deezer.com/profile/698631591/playlists" target="_blank" id="deezer">yt &nbsp;</a>      		
				        	</li>
				        </ul>
				    </div>
				    
			    </div>
	      	</footer>
	      </div>
			
FOOTER;

}

?>
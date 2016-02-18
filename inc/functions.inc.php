<?php
//initialize session if none exists
if (session_id() == '' || !isset($_SESSION)) {
	// session isn't started
	session_start();
}

/*
 * AJAX functions
 */
 if(isset($_GET['load']) && isset($_GET['offset'])){
 	retrieveBlogposts($_GET['offset'], $_GET['load']);
 }

/**
 * Clean form data data
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
 		  $returnArray[$key] =htmlspecialchars( preg_replace('~<p>(.*?)</p>~is', '$1',stripslashes(str_replace($find, $replace, strip_tags($value, "<a><i><b><strong><em><li><ul><ol><br><p>"))), 1),ENT_QUOTES); 
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
	
	//2.save small and medium image
	
	$destination = '../img/small/'.$filename;
	resizeAndSaveImage($coverimagePath, $destination, 265, 155);
	
	$destination = '../img/medium/'.$filename;
	resizeAndSaveImage($coverimagePath, $destination, 750, 450);
		
	//3.return filename
	return $filename;
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
		$img->fit_to_width($W)->crop(0, 0, $W, $H)->save($destination);
	} else{
		$img->fit_to_height($H)->crop(0, 0, $W, $H)->save($destination);
	}
	
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
		$blogpost = new Blogpost(FALSE);
		$blogpost -> updateParameters($row['id']);
		echo $blogpost -> formatPreview();
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

?>
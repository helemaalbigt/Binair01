<?php

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
 * 
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
	
	//2.small image
    $imgSmall = new abeautifulsite\SimpleImage($coverimagePath);
   	$destination = '../img/small/'.$filename;
    $imgSmall->fit_to_height(155)->crop(0, 0, 265, 155)->save($destination);
	
	//3.small image
    $imgMed = new abeautifulsite\SimpleImage($coverimagePath);
   	$destination = '../img/medium/'.$filename;
    $imgSmall->fit_to_width(700)->save($destination);
	
	//4.return filename
	return $filename;
 }
 

?>
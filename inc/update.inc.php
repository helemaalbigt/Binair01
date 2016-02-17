<?php
include_once 'db.inc.php';
include_once 'functions.inc.php';
include_once 'blogpost.inc.php';


//perform verification of input and required values
if($_SERVER['REQUEST_METHOD'] == 'POST' 
&& $_POST['posttype'] == "save blogpost"
&& !empty($_POST['title']) 
&& !empty($_POST['tags']) 
&& !empty($_POST['sortdate']) 
&& !empty($_FILES['coverimage'])) {

//var_dump($_POST);
//var_dump($_FILES);
//echo htmlspecialchars($_POST['body']);
	//instantiate the blogpost class
	$blogpost = new Blogpost();

	//clean post data
	$cleanedPost = cleanData($_POST);
	//update the project
	$id = $blogpost -> updateBlogpost($cleanedPost);
	if (!empty($id)) {
		//go to
		echo "succesfully posted blogpost with id: ".$id;
		//header('Location:../admin.php?id=' . $id . $query);
		exit;
	} else {
		exit('ERROR: problem updating project');	 
	}
}

?>
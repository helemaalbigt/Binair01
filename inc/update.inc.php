<?php
include_once 'db.inc.php';
include_once 'functions.inc.php';
include_once 'blogpost.inc.php';
include_once 'event.inc.php';

//initialize session if none exists
if (session_id() == '' || !isset($_SESSION)) {
	// session isn't started
	session_set_cookie_params(0);
	session_start();
}


//perform verification of input and required values

/**************/
/*Post a Event*/
/**************/
if($_SERVER['REQUEST_METHOD'] == 'POST' 
//&& $_POST['posttype'] == "save blogpost"
&& !empty($_POST['title']) 
&& !empty($_POST['tags']) 
&& !empty($_POST['sortdate']) 
&& !empty($_POST['address'])
&& !empty($_POST['hour'])
&& !empty($_FILES['coverimage'])) {

//var_dump($_POST);exit;
//var_dump($_FILES);
//echo htmlspecialchars($_POST['body']);
	//instantiate the blogpost class
	$event = new Event();

	//clean post data
	$cleanedPost = cleanData($_POST);
	//update the project
	$id = $event -> updateEvent($cleanedPost);
	if (!empty($id)) {
		//go to
		//echo "succesfully posted event with id: ".$id;
		header('Location:../events.php?id=' . $id );
		exit;
	} else {
		exit('ERROR: problem updating project');	 
	}
}

/*****************/
/*Post a blogpost*/
/*****************/
else if($_SERVER['REQUEST_METHOD'] == 'POST' 
//&& $_POST['posttype'] == "save blogpost"
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
		//echo "succesfully posted blogpost with id: ".$id;
		header('Location:../news.php?id=' . $id );
		exit;
	} else {
		exit('ERROR: problem updating project');	 
	}
}

/***************************/
/*Update Website Coverimage*/
/***************************/
else if($_SERVER['REQUEST_METHOD'] == 'POST' 
&& !empty($_POST['updatecoverimage'])
&& !empty($_FILES['coverimage'])){
	
	//clean post data
	$cleanedPost = cleanData($_POST);
	
	//save image in various colors
	saveWebCoverImage($_FILES['coverimage']);
	
	//go back to admin page
	header('Location:../admin.php');
	exit;
}

/*****************/
/*Update Playlist*/
/*****************/
else if($_SERVER['REQUEST_METHOD'] == 'POST' 
&& !empty($_POST['playlist'])){
	
	//clean post data
	$cleanedPost = cleanData($_POST);
	
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	$sql = "UPDATE parameters SET playlist=? WHERE id=1 LIMIT 1";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($cleanedPost['playlist']));
	$response = $stmt->fetch();
	$stmt -> closeCursor();
	
	//go back to admin page
	header('Location:../admin.php');
	exit;
}

/*******/
/*Login*/
/*******/
else if($_SERVER['REQUEST_METHOD'] == 'POST' 
//&& $_POST['posttype'] == "login"
&& !empty($_POST['login_name']) 
&& !empty($_POST['login_password'])) 
{
	
	//clean post data
	$cleanedPost = cleanData($_POST);
		
	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	$sql = "SELECT COUNT(*) AS num_users, username, password, usertype FROM admin WHERE username=?";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($cleanedPost['login_name']));
	$response = $stmt->fetch();
	
	//debug
	//echo md5($_POST['login_password'].$response['salt'])."  -  ".$response['password'];exit;
	
	if($response['num_users'] > 0 && crypt($cleanedPost['login_password'], $response['password']) == $response['password']){
		$_SESSION['loggedin'] = 1;
		$_SESSION['username'] = $response['username'];
		$_SESSION['usertype'] = $response['usertype'];
		header('Location:../admin.php');
		exit;
	} else{
		$_SESSION['loggedin'] = NULL;
		header('Location:../admin.php?loginError=1&usernameAttempt='.$cleanedPost['login_name']);
		exit;
	}
}

/*************/
/*Create user*/
/*************/
else if($_SERVER['REQUEST_METHOD'] == 'POST' 
&& !empty($_POST['create_user_name']) 
&& !empty($_POST['create_user_password']) 
&& !empty($_POST['create_user_usertype'])){
		
	//clean post data
	$cleanedPost = cleanData($_POST);	

	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	//PROCESS PASSWORD source:http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
	// A higher "cost" is more secure but consumes more processing power
	$cost = 10;

	// Create a random salt
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

	// Prefix information about the hash so PHP knows how to verify it later.
	// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
	$salt = sprintf("$2a$%02d$", $cost) . $salt;

	// Hash the password with the salt
	$hash = crypt($cleanedPost['create_user_password'], $salt);
	
	$sql= "INSERT INTO admin (username, password, usertype) VALUES(?, ?, ?)";
	$stmt = $db->prepare($sql);
	$stmt->execute(array($cleanedPost['create_user_name'], $hash, $cleanedPost['create_user_usertype']));
	$stmt -> closeCursor();
	
	header('Location:../admin.php');
	exit;
}

/*****************/
/*delete project */
/*****************/
else if (isset($_GET['action']) && $_GET['action'] == 'project_delete') {
	//check if logged in and logged in as admin or editor before deleting
	if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){	
		//instantiate the Project class
		$blogpost = new Blogpost();
	
		//Delete the post and return to the entry
		if ($blogpost -> deleteBlogpost($_GET['id'])) {
			header('Location:../news.php');
			exit ;
		}
		//if deletion fails, output an error message
		else {
			exit('ERROR: Could not delete the project.');
		}
	
		exit ;
	} else{
		exit('ERROR: You are not authorized to delete projects.');
	};
} 

/*******************************/
/*if logout is pressed, log out*/
/*******************************/
else if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout_submit'])){
	//unset all login session variables
	if(isset($_SESSION['loggedin'])) unset($_SESSION['loggedin']);
	if(isset($_SESSION['username'])) unset($_SESSION['username']);
	if(isset($_SESSION['usertype'])) unset($_SESSION['usertype']);
	
	header('Location:../admin.php');
	exit;
} 

?>
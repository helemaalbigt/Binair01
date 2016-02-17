<?php
    include_once 'db.inc.php';
	include_once 'functions.inc.php';
	include_once 'SimpleImage.inc.php';
	
	class Blogpost{
		
		//the database connection
		public $db;
		
		//define all default values for parameters
		public $id;
		public $sortdate;
		public $tags;
		public $coverimage;
		public $body;
		
		//Upon class instantiation, open a database connection, and generate all default values.
		public function __construct($set_defaults = TRUE) {
			//Open a database connection and store it
			$this -> db = new PDO(DB_INFO, DB_USER, DB_PASS);
			
			//loads default values into blogpostinstance. Used when creatuing a new project or when editing a project
			if ($set_defaults) {
				$this->id = NULL;
				
				$this->sortdate = NULL;
				$this->tags = NULL;
				$this->coverimage = "img/default.jpg";
				$this->body = NULL;
			}
		}
		
		/**
		 * Updates a blogpost or stores a new one
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function updateBlogpost($p){
		 	/*PREP DATA*/
		 	//handle date
		 	list($day, $month, $year) = split('[/.-]', $p['sortdate']);
		 	$date = $year."-".$month."-".$day;
			
		 	//handle coverimage
		 	$filename="";
		 	//if clause prevent execution if project was edited(id exists) and no new image was added (image is not empty)
			if (empty($p['id']) || $_FILES['coverimage']['name'] != '') {
				try {
					$filename = saveImage($_FILES['coverimage']);
				} catch (Exception $e) {
					//if an error occurred, output your custom error message
					die($e -> getMessage());
				}
			}
		 	
		 	
		 	/*UPLOADING DATA*/
		 	//if an id was passed, edit the existing entry
			if (!empty($p['id'])) 
			{
				
			} 
			//save the entry into the database
			else
			{
				$sql = "INSERT INTO blogposts (title, tags, sortdate, coverimage, body) VALUES (?, ?, ?, ?, ?)";
				if ($stmt = $this -> db -> prepare($sql)) {
					$stmt -> execute(array( $p['title'], $p['tags'], $date, $filename, $p['body']));	
					$stmt -> closeCursor();
					
					//get the ID of the entry that was just saved
					$id_obj = $this -> db -> query("SELECT LAST_INSERT_ID()");
					//gets unique ID generated for last entry into database
					$new_id = $id_obj -> fetch();
					//pass data to the $id variable (array with the id in index [0])
					$id_obj -> closeCursor();
					$this -> id = $new_id[0];
				}
			}
			
			return $this -> id;
		 }
		
	}
?>
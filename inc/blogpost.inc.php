<?php
    include_once 'db.inc.php';
	include_once 'functions.inc.php';
	include_once 'SimpleImage.inc.php';
	
	class Blogpost{
		
		//the database connection
		public $db;
		
		//define all default values for parameters
		public $id;
		public $title;
		public $sortdate;
		public $sortdayeArray;
		public $tagsOriginal;
		public $tags;
		public $coverimage;
		public $youtubeCover;
		public $bodyOriginal;
		public $body;
		
		//Upon class instantiation, open a database connection, and generate all default values.
		public function __construct($set_defaults = TRUE) {
			//Open a database connection and store it
			$this -> db = new PDO(DB_INFO, DB_USER, DB_PASS);
			
			//loads default values into blogpostinstance. Used when creatuing a new project or when editing a project
			if ($set_defaults) {
				$this->id = NULL;
				
				$this->title = NULL;
				$this->sortdate = NULL;
				$this->sortdateArray = NULL;
				$this->tags = NULL;
				$this->coverimage = NULL;
				$this->youtubeCover = NULL;
				$this->body = NULL;
			}
		}
		
		/**
		 * Replaces default parameters with blogpost-specific values
		 *
		 * @param string $id
		 */
		public function updateParameters($id) {
			
			$e = $this -> retrieveProjectById($id);
			
			//replace default values for parameters with those of the edited project
			//textfields are already split into arrays containging the three languages
			$this -> id = $id;
			
			$this -> title = $e['title'];
			
			list($Y, $M, $D) = split('[-.-]', $e['sortdate']);
			$this->sortdate = $D."/".$M."/".$Y;
			
			$this->sortdateArray = array(
										    "year" => $Y,
										    "month" => $M,
										    "day"   => $D
										);
			
			$this->tagsOriginal = $e['tags'];
			$tagArray = explode(",", $e['tags']);
			foreach($tagArray as &$value){
				$value = trim($value);
			}
			$this -> tags = $tagArray;
			
			$this -> coverimage = $e['coverimage'];
			$this -> youtubeCover = $e['youtubecover'];
			$this -> body = str_replace("style=\"line-height: 0.7;\"", "style=\"line-height: 1.4;\"", htmlspecialchars_decode($e['body'])); 
		}
		
		
		/**
		 *Retrieves one blogpost from the database based on a passed id
		 *
		 * @param string $id project id to fetch
		 * @return array array with results
		 */
		function retrieveProjectById($id) {
			$sql = "SELECT * FROM blogposts WHERE id=? LIMIT 1";
			$stmt = $this -> db -> prepare($sql);
			$stmt -> execute(array($id));
	
			//save the returned array
			$e = $stmt -> fetch();
			$stmt -> closeCursor();
			
			if(count($e) <= 18){	
				return $e;
			} else{
				exit("ERROR: database query returned more values than allowed (".count($e).")");
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
		 	//if clause prevent execution if no new image was added (image is not empty)
			if ($_FILES['coverimage']['name'] != '') {
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
				$appendSQL ="";
				$appendSTMT = array();
				//check if new image was added, add some stuff to the query if it is
				if ($_FILES['coverimage']['name'] != ''){
					$appendSQL .= ", coverimage=?";
					$appendSTMT = array($filename);
				}
	
				//prepare the sql query and append a part if we're adding images
				$sql = "UPDATE blogposts SET title=?, tags=?, sortdate=?, youtubecover=?, body=?".$appendSQL." WHERE id=? LIMIT 1";
	
				if ($stmt = $this -> db -> prepare($sql)) {
					$A = array_merge(array_merge(array($p['title'], $p['tags'], $date, $p['youtubecover'], $p['body']), $appendSTMT),array($p['id']));
					$stmt -> execute($A);
					$stmt -> closeCursor();
					
					//get the ID of the entry that was just edited
					$this -> id = $p['id'];
				}
			} 
			//save the entry into the database
			else
			{
				$sql = "INSERT INTO blogposts (title, tags, sortdate, coverimage, youtubecover, body) VALUES (?, ?, ?, ?, ?, ?)";
				if ($stmt = $this -> db -> prepare($sql)) {
					$stmt -> execute(array( $p['title'], $p['tags'], $date, $filename, $p['youtubecover'], $p['body']));	
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

		/**
		 * Formats preview version of post
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function formatPreview(){
		 	$formattedProject = "";
			
			$imgPath = "img/medium/".$this->coverimage;
			$title = $this->title;
			$date = $this->sortdate;
			$id = $this->id;
			
			$cover="";
			if($this->youtubeCover != NULL && $this->youtubeCover != "" && ($this->coverimage == "" || $this->coverimage == NULL)){
				$cover = "<div class='embed-responsive embed-responsive-16by9'>".htmlspecialchars_decode($this->youtubeCover)."</div>";
			} else{
				$cover = "<div class='embed-responsive embed-responsive-16by9'><img src='".$imgPath."' class='img-responsive' /></div>";
			}
			
			$formattedProject .= <<<PREVIEW
			<div class="col-md-3 col-sm-4">
	        	<a href="news.php?id=$id" class="previewcover">
		        	$cover 
	          		<h4><b>$title</b></h4>
		        	<div class="subtitle">posted $date</div>
		        </a>
			</div>
PREVIEW;

			return $formattedProject;
		 }
		 
		 
		 /**
		 * Format post for the news overview page
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function formatNewspage(){
		 	$formattedProject = "";
			
			$imgPath = "img/medium/".$this->coverimage;
			$title = $this->title;
			$date = $this->sortdate;
			$id = $this->id;
			$body = $this->body;
			
			$fblink = "http://binair01.be/dev/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink\" href=\"./search.php?tag=".$tag."\">".$tag."</a></span> ";
			}
			
			$cover="";
			if($this->youtubeCover != NULL && $this->youtubeCover != ""){
				$cover = "<div class='embed-responsive embed-responsive-16by9'>".htmlspecialchars_decode($this->youtubeCover)."</div>";
			} else{
				$cover = "<img src='".$imgPath."' class='img-responsive' />";
			}
			
			$formattedProject .= <<<PREVIEW
			<!--preview-->
			<div class="news-preview">
				<div class="body-content news_overview">
				        <a href="news.php?id=$id">
					        $cover
			          		<h1><span>$title</span></h1>
			          		<div class="gray news_subtitle">$date</div>
			          	</a>
		          		
		          		<p class="body">$body</p>
		          		
		          		<div class="italic gray">tags: $taglinks</div>
		        </div>
			</div>
PREVIEW;

			return $formattedProject;
		 }
		 
		 
		 /**
		 * format for blogpost page
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function formatSinglePost($loggedIn = false){
		 	$formattedProject = "";
			
			$imgPath = "img/medium/".$this->coverimage;
			$title = $this->title;
			$date = $this->sortdate;
			$id = $this->id;
			$body = $this->body;
			
			$adminVisibility = ($loggedIn) ? "block" : "none";
			
			$fblink = "http://binair01.be/dev/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink\" href=\"./search.php?tag=".$tag."\">".$tag."</a></span> ";
			}
			
			$cover="";
			if($this->youtubeCover != NULL && $this->youtubeCover != ""){
				$cover = "<div class='embed-responsive embed-responsive-16by9'>".htmlspecialchars_decode($this->youtubeCover)."</div>";
			} else{
				$cover = "<img src='".$imgPath."' class='img-responsive' />";
			}
			
			$formattedProject .= <<<POST
			<!--preview-->
			<div class="news-preview">
	    		<div class="row">
	    			<!--date-->
	    			<div class="col-md-4 title">
		        		<a class="sidetitle_link" href="news.php"><span>« ALL NEWS</span></a>
					</div>
					<!--post-->
					<div class="body-content">
				        <div class="col-md-8">	
						         $cover
				          		<h1>$title</h1>
			          		<div class="italic gray">tags: $taglinks</div>
			          		<p class="body">$body</p>
			          	
			          		<!--<div class="fb-share-button" data-href="$fblink" data-layout="button_count"></div>-->
				        </div>
			        </div>
				</div>
				<div class="row" style="display:$adminVisibility">
					<div class="col-md-4 title">
						&nbsp;
					</div>
					<div class="col-md-8">	
						<a class="btn btn-danger delete"  href="./inc/update.inc.php?action=project_delete&id=$id" >delete</a> 
						<a class="btn btn-primary" href="admin.php?editingPost=1&id=$id">edit</a>
					</div>
				</div>
			</div>
			
POST;

			return $formattedProject;
		 }
		 
		 
		 
		/**
		 * Method for deleting a post
		 *
		 * @param string $id The id of the post to delete
		 */
		public function deleteBlogpost($id) {
			$sql = "DELETE FROM blogposts WHERE id=? LIMIT 1";
			if ($stmt = $this -> db -> prepare($sql)) {
				//Execute the command, free used memory, and return true
				$stmt -> execute(array($id));
				$stmt -> closeCursor();
				return TRUE;
			} else {
				//if something went wrong return false
				return FALSE;
			}
		}
			
	}
?>
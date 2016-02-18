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
				$this->coverimage = "img/default.jpg";
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
			$this -> body = str_replace("style=\"line-height: 0.7;\"", "style=\"line-height: 1.4;\"", htmlspecialchars_decode($e['body'])); 
		}
		
		
		/**
		 *Retrieves one blogpost from the database based on a passed id
		 *
		 * @param string $id project id to fetch
		 * @return array array with results
		 */
		function retrieveProjectById($id) {
			//$sql = "SELECT number, name, coverimage, otherimages, program, startdate, enddate, countrycode, city, clienttype, date, city_pcode, street, street_number, clientname, description, projecttype, competitionwon, newnumber, status, interventiontype, category, scale, area_gross, area_weighted, eelevel, eevalue, eeloldvalue, eeloldunit, budget_estimate, budget_final, budget_type, consultants, teamUP, awards, publications, timebudget_estimate, timebudget_final, internalbudget_estimate, internalbudget_final FROM projects WHERE id=? LIMIT 1";
			$sql = "SELECT * FROM blogposts WHERE id=? LIMIT 1";
			$stmt = $this -> db -> prepare($sql);
			$stmt -> execute(array($id));
	
			//save the returned array
			$e = $stmt -> fetch();
			$stmt -> closeCursor();
			
			if(count($e) <= 16){	
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

		/**
		 * Updates a blogpost or stores a new one
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
			
			$formattedProject .= <<<PREVIEW
			<div class="col-md-3 col-sm-4">
	        	<a href="news.php?id=$id">
		        	<img src="$imgPath" class="img-responsive" />
		          	<h4><b>$title</b></h4>
		          	<div class="subtitle">posted $date</div>
	          	</a>
			</div>
PREVIEW;

			return $formattedProject;
		 }
		 
		 
		 /**
		 * Format for the news overview page
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
			
			$fblink = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink gray\" href=\"#\">".$tag."</a></span> ";
			}
			
			$formattedProject .= <<<PREVIEW
			<!--preview-->
			<div class="news-preview">
	    		<div class="row">
	    			<!--date-->
	    			<div class="col-md-3 title">
		        		<h1 class="gray">$date</h1>
					</div>
					<!--post-->
					<div class="body-content">
				        <div class="col-md-8">	
					        <a href="news.php?id=$id">
						        <img src="$imgPath" class="img-responsive" />
						    </a>
				          		<h1>$title</h1>
			          		<div class="subtitle gray">tags: $taglinks</div>
			          		<p class="body">$body</p>
			          		
			          		<div class="fb-share-button" data-href="$fblink" data-layout="button_count"></div>
				        </div>
			        </div>
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
		 public function formatSinglePost(){
		 	$formattedProject = "";
			
			$imgPath = "img/medium/".$this->coverimage;
			$title = $this->title;
			$date = $this->sortdate;
			$id = $this->id;
			$body = $this->body;
			
			$fblink = $_SERVER['DOCUMENT_ROOT'].APP_FOLDER."/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink gray\" href=\"#\">".$tag."</a></span> ";
			}
			
			$formattedProject .= <<<POST
			<!--preview-->
			<div class="news-preview">
	    		<div class="row">
	    			<!--date-->
	    			<div class="col-md-3 title">
		        		<a class="gray" href="news.php"><h1 class="gray">« BACK</h1></a>
					</div>
					<!--post-->
					<div class="body-content">
				        <div class="col-md-8">	
					        <a href="news.php?id=$id">
						        <img src="$imgPath" class="img-responsive" />
						    </a>
				          		<h1>$title</h1>
			          		<div class="subtitle gray">tags: $taglinks</div>
			          		<p class="body">$body</p>
			          	
			          		<div class="fb-share-button" data-href="$fblink" data-layout="button_count"></div>
				        </div>
			        </div>
				</div>
			</div>
POST;

			return $formattedProject;
		 }
		 
		
	}
?>
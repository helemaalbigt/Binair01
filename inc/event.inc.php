<?php
    include_once 'db.inc.php';
	include_once 'functions.inc.php';
	include_once 'SimpleImage.inc.php';
	
	class Event{
		
		//the database connection
		public $db;
		
		//define all default values for parameters
		public $id;
		public $title;
		public $sortdate;
		public $sortdateArray;
		public $tagsOriginal;
		public $tags;
		public $hour;
		public $venue;
		public $venueurl;
		public $address;
		public $coverimage;
		public $ticketsurl;
		public $ticketsatdoor;
		public $facebookurl;
		public $bodyOriginal;
		public $preview;
		public $body;
		public $galleryimages;
		
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
				$this->hour = NULL;
				$this->venue = NULL;
				$this->venueurl = NULL;
				$this->address = NULL;
				$this->ticketsurl = NULL;
				$this->ticketsatdoor = NULL;
				$this->facebookurl = NULL;
				$this->coverimage = "img/default.jpg";
				$this->preview = NULL;
				$this->body = NULL;
				$this->galleryimages = NULL;
			}
		}
		
		/**
		 * Replaces default parameters with event-specific values
		 *
		 * @param string $id
		 */
		public function updateParameters($id) {
			
			$e = $this -> retrieveEventById($id);
			
			//replace default values for parameters with those of the edited project
			//textfields are already split into arrays containging the three languages
			$this -> id = $id;
			
			$this -> title = $e['title'];
			
			list($Y, $M, $D) = explode("-", $e['sortdate']);
			//list($Y,$M,$D) = array($e['sortdate'],$e['sortdate'],$e['sortdate']);
			$this->sortdate = $D."/".$M."/".$Y;
			$this->sortdateArray = array(
										    "year" => $Y,
										    "month" => $M,
										    "day"   => $D
										);
										
			$this -> hour = $e['hour'];
			
			$this -> venue = $e['venue'];
			
			$this -> venueurl = $e['venueurl'];
			
			$this -> address = $e['address'];
			
			$this -> ticketsurl = $e['ticketsurl'];
			
			$this -> ticketsatdoor = $e['ticketsatdoor'];
			
			$this -> facebookurl = $e['facebookurl'];
			
			$this->tagsOriginal = $e['tags'];
			$tagArray = explode(",", $e['tags']);
			foreach($tagArray as &$value){
				$value = trim($value);
			}
			$this -> tags = $tagArray;
			
			$this -> coverimage = $e['coverimage'];
			
			$this -> preview = str_replace("style=\"line-height: 0.7;\"", "style=\"line-height: 1.4;\"", htmlspecialchars_decode($e['preview'])); 
			
			$this -> body = str_replace("style=\"line-height: 0.7;\"", "style=\"line-height: 1.4;\"", htmlspecialchars_decode($e['body'])); 
			
			$this -> galleryimages = ($e['galleryimages'] != "") ? unserialize($e['galleryimages']) : NULL;
		}
		
		
		/**
		 *Retrieves one event from the database based on a passed id
		 *
		 * @param string $id project id to fetch
		 * @return array array with results
		 */
		function retrieveEventById($id) {
			$sql = "SELECT * FROM events WHERE id=? LIMIT 1";
			$stmt = $this -> db -> prepare($sql);
			$stmt -> execute(array($id));
	
			//save the returned array
			$e = $stmt -> fetch();
			$stmt -> closeCursor();
			
			if(count($e) <= 32){	
				return $e;
			} else{
				exit("ERROR: database query returned more values than allowed (".count($e).")");
			}
		}
		
		
		/**
		 * Updates a event or stores a new one
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function updateEvent($p){
		 	/*PREPARING DATA*/
		 	//handle date
		 	list($day, $month, $year) = split('[/.-]', $p['sortdate']);
		 	$date = $year."-".$month."-".$day;
			
			//handle tickets at door
			$ticketsatdoorValue = (isset($p['ticketsatdoor'])) ? TRUE : FALSE;
			
		 	//handle coverimage
		 	$filenameCoverimage="";
		 	//if clause prevent execution if project was edited(id exists) and no new image was added (image is not empty)
			if (empty($p['id']) || $_FILES['coverimage']['name'] != '') {
				try {
					$filenameCoverimage = saveImage($_FILES['coverimage']);
				} catch (Exception $e) {
					//if an error occurred, output your custom error message
					die($e -> getMessage());
				}
			}
			
			//handle image gallery
			$galleryimages = array();
			//1.Check if there were existing gallery images; add them to the galleryimages array
			if(!empty($p["existingGalleryImages"])){
				foreach($p["existingGalleryImages"] as $key=>$value){
            		array_push($galleryimages, $value); 
				}
			}
			//2.check if there are new gallery images, then loop through them and add them to the array
			if(!empty($_FILES['imagegallery']['name'][0])){
			 	//loop through gallery images
			 	foreach($_FILES["imagegallery"]["name"] as $key=>$tmp_name)
            	{				   
				   	try {
						$filename = saveImage(array($_FILES['imagegallery']['name'][$key], 
													$_FILES['imagegallery']['type'][$key], 
													$_FILES['imagegallery']['tmp_name'][$key], 
													$_FILES['imagegallery']['error'][$key], 
													$_FILES['imagegallery']['size'][$key]));
					} catch (Exception $e) {
						//if an error occurred, output your custom error message
						die($e -> getMessage());
					}
					//add filename to array
					array_push($galleryimages, $filename); 
				}
			}
			//3.serialize arrayvalue into single string
			$galleryimagesSerialized = (count($galleryimages) > 0) ? serialize($galleryimages) : "";
			
		 	/*UPLOADING DATA*/
		 	//if an id was passed, edit the existing entry
			if (!empty($p['id'])) 
			{
				//the coverimage and gallery images are optional when editing events 
				//that's why we append these fields to the sql query only if they have a new value
				$appendSQL ="";
				$appendSTMT = array();
				//check if new image was added, add some stuff to the query if it is
				if ($_FILES['coverimage']['name'] != ''){
					$appendSQL .= ", coverimage=?";
					$appendSTMT = array($filenameCoverimage);
				}
				//append galleryimages to the query (used to be conditional, that's why its seperated - T.
				$appendSQL .= ", galleryimages=?";
				array_push($appendSTMT, $galleryimagesSerialized);
			
				//prepare the sql query and append a part if we're adding images
				$sql = "UPDATE events SET title=?, tags=?, sortdate=?, hour=?, address=?, venue=?, venueurl=?, ticketsurl=?, ticketsatdoor=?, facebookurl=?, preview=?, body=?".$appendSQL." WHERE id=? LIMIT 1";
	
				if ($stmt = $this -> db -> prepare($sql)) {
					$A = array_merge(array_merge(array($p['title'], $p['tags'], $date, $p['hour'], $p['address'], $p['venue'], $p['venueurl'], $p['ticketsurl'], $ticketsatdoorValue, $p['facebookurl'], $p['event_preview'], $p['event_body']), $appendSTMT),array($p['id']));
					$stmt -> execute($A);
					$stmt -> closeCursor();
					
					//get the ID of the entry that was just edited
					$this -> id = $p['id'];
				}
			} 
			//save the entry into the database
			else
			{
				$sql = "INSERT INTO events (title, tags, sortdate, hour, address, venue, venueurl, ticketsurl, ticketsatdoor, facebookurl, coverimage, preview, body, galleryimages) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				if ($stmt = $this -> db -> prepare($sql)) {
					$stmt -> execute(array( $p['title'], $p['tags'], $date, $p['hour'], $p['address'], $p['venue'], $p['venueurl'], $p['ticketsurl'], $ticketsatdoorValue, $p['facebookurl'], $filenameCoverimage, $p['event_preview'], $p['event_body'], $galleryimagesSerialized));	
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
			$venue = $this->venue;
			$id = $this->id;
			
			$formattedProject .= <<<PREVIEW
			<div class="col-md-3 col-sm-4">
	        	<a href="events.php?id=$id">
	        		<div class='embed-responsive embed-responsive-16by9'>
		        		<img src="$imgPath" class="img-responsive" />
		        	</div>
		          	<h4><b>$title</b></h4>
			        <div class="subtitle"> <b>$venue</b> <br> $date</div>
			    </a>
			</div>
PREVIEW;

			return $formattedProject;
		 }
		 
		 
		 /**
		 * Format for the events overview page
		 * 
		 * @param array $p The $_POST superglobal
		 * @return
		 */
		 public function formatEventpage(){
		 	$formattedProject = "";
			
			$imgPath = "img/medium/".$this->coverimage;
			$title = $this->title;
			$date = $this->sortdate." <i>at ".$this->hour."</i>";
			$where = strtoupper($this->venue).", ".$this->address;
			$tickets = $this->ticketsurl;
			$id = $this->id;
			$body = $this->preview;
			
			//only display ticket link if eventdate is after todays date   			date("Y").date("m").date("d")
			$tickets = ( $this->sortdateArray["year"].$this->sortdateArray["month"].$this->sortdateArray["day"] >= date("Y").date("m").date("d")) 
			? "<a class='thin_button' target='_blank' href='". $this->ticketsurl ."' role='button'><span>Tickets &raquo;</span></a>" 
			: "&nbsp;";
			
			$fblink = "http://binair01.be/dev/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink\" href=\"./search.php?tag=".$tag."\">".$tag."</a></span> ";
			}
			
			$formattedProject .= <<<PREVIEW
			<!--preview-->
			<div class="news-preview">
   	
					<!--post-->
					<div class="body-content  news_overview">
							<div class="row">
					        	<a href="events.php?id=$id">
						       
						       		<img src='$imgPath' class='img-responsive' />
						    
						    
							    	<div class="col-md-10">
							          	<h1 class="event-title"><span>$title</span></h1>
							          	<div class="gray news_subtitle"><b>$where</b></div>
						          		<div class="gray news_subtitle">$date</div>
						          	</div>
					          	
					          	</a>
					          	
					          	<div class="col-md-2">
						    		$tickets <!-- btn btn-default link-more -->
						    	</div>
			          		</div>
			          		<p class="body">$body</p>
			          		
			          		<br>
			          		<div class="italic gray">tags: $taglinks</div>
			        </div>
			</div>
PREVIEW;

			return $formattedProject;
		 }
		 
		 
		 /**
		 * format single Event
		 * 
		 * @param 
		 * @return
		 */
		 public function formatSingleEvent($loggedIn = false){
		 	//the heredoc html that will be returned
		 	$formattedProject = "";
			
			//prepare variables for the heredoc
			$imgPath = "img/large/".$this->coverimage;
			$title = $this->title;
			$date =  date('F', strtotime($this->sortdateArray["year"].$this->sortdateArray["month"].$this->sortdateArray["day"]))." ".$this->sortdateArray["day"].", ".$this->sortdateArray["year"];//$this->sortdate;
			//display only if event has passed
			$dateAppendum = ( $this->sortdateArray["year"].$this->sortdateArray["month"].$this->sortdateArray["day"] < date("Y").date("m").date("d")) ? "<span>This event took place on</span>" : "";
			$hour = $this->hour;
			$venueurl = $this->venueurl;
			
			//make a link of the venue if it has one
			$venue = ($venueurl != NULL & $venueurl !="") 
			? "<a class='venueurl' href='".$venueurl."' target='_blank'>".$this->venue."</a>" 
			: $this->venue;
			$address = $this->address;
			
			//tickets
			$tickets ="";
			//only display ticket link if eventdate is after todays date  ( date("Y").date("m").date("d") )
			if( $this->sortdateArray["year"].$this->sortdateArray["month"].$this->sortdateArray["day"] >= date("Y").date("m").date("d")){ //($this->ticketsurl != null && $this->ticketsurl != "")
				//are we selling tickets at the door?
				$tickets = ($this->ticketsatdoor) 
				? "<a class='ticket-link'><h4>Tickets sold at the door</h4></a>"
				: "<a class='ticket-link'><h4>Tickets available soon</h4></a>";  
				
				//if there is a ticket link display that instead
				$tickets = ($this->ticketsurl != null && $this->ticketsurl != "") 
				? "<a class='ticket-link' target='_blank' href='". $this->ticketsurl ."' role='button'><h1>Get Tickets</h1></a><br>" 
				: $tickets; 
			} else{
				//are we selling tickets at the door?
				$tickets = "&nbsp"; 
			}
			
			//show facebook event page url if there is one
			$facebookurl = ($this->facebookurl != NULL && $this->facebookurl != "") 
			? "<a href='".$this->facebookurl ."' target='_blank'>View event on Facebook</a> &nbsp; / &nbsp; " 
			: "";
			
			$id = $this->id;
			$body = $this->body;
			
			$nav = printHeader(true, false, "none", "events", "./events.php?id=".$id);
			
			$adminVisibility = ($loggedIn) ? "block" : "none";
			
			$fblink = "http://binair01.be/dev/news.php?id=".$id;
			
			$taglinks ="";
			foreach($this->tags as $tag){
				$taglinks.= "<span><a class=\"taglink\" href=\"./search.php?tag=".$tag."\">".$tag."</a></span> ";
			}
			
			//format imagegallery only if event has images
			$imagegallery = "";
			if($this->galleryimages != NULL){
				
				$imagegallery .= <<<GALLERYOPEN
				
				<div class="row">
					
					<!--images-->
					<div class="body-content">
				 
GALLERYOPEN;
				
				//add each image
				foreach ($this->galleryimages as $key => $value) {
					$galleryImgPath = "img/small/".$value;
					$galleryImgPathXL = "img/large/".$value;
					
					$imagegallery .=<<<IMAGE
														
								<a class="galleryimage" href="$galleryImgPathXL" data-lightbox="eventalbum" title="image"> 
									<img src='$galleryImgPath' class='img-responsive event' />
								</a>
								
IMAGE;
				}
			
				$imagegallery .=<<<GALARYCLOSE
				
					
			        </div>
					        
				</div>		
GALARYCLOSE;
			}
			
			
			//format the heredoc
			$formattedProject .= <<<POST
			<!--preview-->
			
			<!-- CoverImage-->
		    <div class="coverimage" style="background:url('./$imgPath') no-repeat 50% 50%;">
		    	
		    	<!-- Nav -->
		    	$nav 
		    	
		    	<!-- cover image content -->
			    <div class="inner">
					<div class="content">
						&nbsp;
					</div>
				</div>
			  
		    </div>
			
			<!-- Main Content-->
	    	<div class="container" id="about_segment">
				<div class="news-preview">
				
		    		<div class="row">
		    		
		    			<!--tickets-->
		    			<div class="body-content">
		    				<div class="col-md-3">
			        			$tickets 
							</div>
						</div>
						
						<!--title / fb link / tags-->
						<div class="body-content">
					        <div class="col-md-8 black eventpage-title">	
					          	<h1>$title</h1>
					          	<div class="italic gray">$facebookurl tags: $taglinks</div>	
					        </div>
				        </div>
				        
					</div>
					
					
					
					<div class="row">
						<!--metadata-->
						<div class="col-md-3 event-sidebar">
						<p>
							<br>
							<ul>												
															
								<li>
								<b>
								
								<table>
										<tr>
										
											<td>
												<span class="glyphicon glyphicon-time"></span>
											</td>
												
											<td>
												<div>
													$dateAppendum
													$date 
												</div>
												<div>
													at $hour
												</div>
											</td>
											
										</tr>
									</table>
									
								</b>
								</li>
								
							
								<li>
								<b>
								
									<table>
										<tr>
										
											<td>
												<span class="glyphicon glyphicon-map-marker"></span>
											</td>
												
											<td>
												<div class="uppercase">
													$venue<br>
												</div>
												<div>
													$address
												</div>
											</td>
											
										</tr>
									</table>
									
								</b>
								</li>
								
							</ul>
							
						</div>
						
						<!--body text and tag links-->
						<div class="col-md-8">	
							<br>
						    <p class="body">$body</p>
						</div>
						
					</div>
					
					<br><br><br>
					
					<!--imagegallery-->
					<div>
						<div class="col-md-3">
							&nbsp;
						</div>
						<div class="col-md-8">
							$imagegallery
						</div>
					</div>
					
					<br><br><br><br>
					
					<!-- admin links-->
					<div class="row" style="display:$adminVisibility">
						<div class="col-md-3 title">
							&nbsp;
						</div>
						<div class="col-md-8">	
							<a class="btn btn-danger delete"  href="./inc/update.inc.php?action=event_delete&id=$id" >delete</a> 
							<a class="btn btn-primary" href="admin.php?editingEvent=1&id=$id">edit</a>
						</div>
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
		public function deleteEvent($id) {
			$sql = "DELETE FROM events WHERE id=? LIMIT 1";
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
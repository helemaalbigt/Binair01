<?php
include_once './inc/db.inc.php';
include_once './inc/blogpost.inc.php';
include_once './inc/event.inc.php';

//initialize session if none exists
if (session_id() == '' || !isset($_SESSION)) {
	// session isn't started
	session_start();
}

//check logged in
$loggedin = (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1) ? TRUE : FALSE;
$isAdmin = (isset($_SESSION['usertype']) && $_SESSION['usertype'] == "admin") ? TRUE : FALSE;
$errorVisibility="none";

$editedID="";

//website parameters
$webCoverImage ="./img/original/cover_original.jpg";
$playlist = getPlaylistID();

//blogpost values
$postTitle ="";
$postTags ="";
$postSortdate ="";
$youtubeCover ="";
$postCoverImage ="./img/default.jpg";
$postBody="";
$editingPost = false;

//event values
$eventTitle ="";
$eventTags ="";
$eventSortdate ="";
$eventHour ="";
$eventVenue ="";
$eventVenueUrl = "";
$eventAddress = "";
$eventTicketLink ="";
$eventFacebookUrl = "";
$eventCoverImage ="./img/default.jpg";
$eventPreview ="";
$eventBody="";
$eventGalleryImages=NULL;
$editingEvent = false;

//check whether editing blogpost
if(isset($_GET['editingPost']) && isset($_GET['id'])){
	$editingPost = true;
	$editedID = $_GET['id'];
	
	$blogpost = new Blogpost(FALSE);
	$blogpost -> updateParameters($_GET['id']);
	
	$postTitle = $blogpost->title;
	$postTags = $blogpost->tagsOriginal;
	$postSortdate = $blogpost->sortdateArray["day"]."/".$blogpost->sortdateArray["month"]."/".$blogpost->sortdateArray["year"];
	$youtubeCover = $blogpost->youtubeCover;
	$postCoverImage =  ($blogpost->coverimage != NULL) ? "./img/medium/".$blogpost->coverimage : $postCoverImage;
	$postBody = $blogpost->body;
}

//check whether editing event
if(isset($_GET['editingEvent']) && isset($_GET['id'])){
	$editingEvent = true;
	$editedID = $_GET['id'];
	
	$event = new Event(FALSE);
	$event -> updateParameters($_GET['id']);
	
	$eventTitle = $event->title;
	$eventTags = $event->tagsOriginal;
	$eventSortdate = $event->sortdateArray["day"]."/".$event->sortdateArray["month"]."/".$event->sortdateArray["year"];
	$eventHour = $event->hour;
	$eventVenue = $event->venue;
	$eventVenueUrl = $event->venueurl;
	$eventAddress = $event->address;
	$eventTicketLink = $event->ticketsurl;
	$eventFacebookUrl = $event->facebookurl;
	$eventCoverImage =  "./img/medium/".$event->coverimage;
	$eventPreview = $event->preview;
	$eventBody = $event->body;
	$eventGalleryImages = $event->galleryimages;
}
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
        <style>
            body {
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" type="text/css" media="all" href="css/lightbox.css" />
		
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>
        <script type="text/javascript" src="js/moment.js"></script>
        <script src="js/main.js"></script>
        
        <!-- include summernote css/js-->
		<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script> 
		<link href="dist/summernote.css" rel="stylesheet">
		<script src="dist/summernote.min.js"></script>
		
		<!-- Include Date Range Picker -->
		<script type="text/javascript" src="js/daterangepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
		
		<script>
		$(document).ready(function() {
		//WYSIWYG editor
		  $('#summernote').summernote({
		  	height:200,
		  	toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['font', ['strikethrough']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['insert', ['link']]
			  ]
		  });
		  
		  $('#summernote').summernote('lineHeight', 1.4);
		  
		  
		  $('#summernote2').summernote({
		  	height:100,
		  	toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['font', ['strikethrough']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['insert', ['link']]
			  ]
		  });
		  
		  $('#summernote2').summernote('lineHeight', 1.4);
		  
		  
		  $('#summernote3').summernote({
		  	height:300,
		  	toolbar: [
			    // [groupName, [list of button]]
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['font', ['strikethrough']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['insert', ['link']]
			  ]
		  });
		  
		  $('#summernote3').summernote('lineHeight', 1.4);
		  
		  //scrolls window to top on load
		  window.scrollTo(0,0);
		});
		
		//date picker
		$(function() {
		    $('input[name="sortdate"]').daterangepicker({
		        singleDatePicker: true,
		        showDropdowns: true,
		        locale: {
			      format: 'DD/MM/YYYY'
			    }
		    });
		});
		
		//file select
		$(document).on('change', '.btn-file :file', function() {
		  var input = $(this),
		      numFiles = input.get(0).files ? input.get(0).files.length : 1,
		      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		  input.trigger('fileselect', [numFiles, label]);
		});
		
		$(document).ready( function() {
		    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
		        
		        var input = $(this).parents('.input-group').find(':text'),
		            log = numFiles > 1 ? numFiles + ' files selected' : label;
		        
		        if( input.length ) {
		            input.val(log);
		        } else {
		            if( log ) alert(log);
		        }
		        
		    });
		});
		
		
		/**
		 * Delete Image
		 *  
		 * @param 
		 * @return
		 */
		function deleteImage(e) {		 
			if (confirm('Are you sure you want to DELETE this image? \n This cannot be undone')) {
				e.parentNode.parentNode.removeChild(e.parentNode);
			} 
		}
		</script>
        
        
    	<script type="text/javascript">
    	
		//calls a function to check for errors in a new blogpost, passes arrays with elements to check
		function checkInputForm(edit) {
			
			//array of all inputs to check for 'empty' errors [id,name]
			var checkEmpty = new Array;
			//if editing, omit the check for empty images
			if (edit) {
				checkEmpty = [["title","title"], ["tags","tags"], ["sortdate","sort date"], ["body","body"]];
			} else{
				checkEmpty = [["title","title"], ["tags","tags"], ["sortdate","sort date"], ["body","body"]];
			}

			// array of all inputs to check filesize [id, name]
			var checkFilesize = [['coverimage', "cover image"]];

			//execute form check  //checkEmpty
			return validateForm(true, checkEmpty, checkFilesize);
		}
		
		//calls a function to check for errors in a new event, passes arrays with elements to check
		function checkInputFormEvent(edit) {
			
			//array of all inputs to check for 'empty' errors [id,name]
			var checkEmpty = new Array;
			//if editing, omit the check for empty images
			if (edit) {
				checkEmpty = [["event_title","title"], ["event_tags","tags"], ["event_sortdate","sort date"],  ["event_hour","hour"], ["event_venue","venue"], ["summernote2","preview"], ["summernote3","body"], ["event_address","address"]];
			} else{
				checkEmpty = [["event_title","title"], ["event_tags","tags"], ["event_sortdate","sort date"],  ["event_hour","hour"], ["event_venue","venue"], ["summernote2","preview"], ["summernote3","body"], ["event_address","address"]];
			}

			// array of all inputs to check filesize [id, name]
			var checkFilesize = [['event_coverimage', "cover image"]]; 
			
			//array of all inputs to check filesize, where multiple inputs are allowed
			var checkFilesizeMultiple = [['imagegallery', "one of the gallery images"]];

			//execute form check  //checkEmpty
			return validateForm(true, checkEmpty, checkFilesize, checkFilesizeMultiple);
		}
		
		/**
		 * Check for errors in forms
		 *
		 * Checks empty fields, non numerical input in a numerical field, and bigger than errors.
		 * Changes CSS
		 *
		 * @param boolean gotoError Whether or not to go to the first error after reporting it
		 * @param array(array) checkEmpty 2D array containing elements to check for 'empty' errors (("ID","human readable name"), (...))
		 * @param array(array) checkFileSize 2D array containing elements to check for filesize bigger than 'maxsize' errors (("ID","human readable name"), (...))
		 * @return boolean False if error found
		 */
		function validateForm(gotoError, checkEmpty, checkFilesize, checkFilesizeMultiple) {
			
			var error = false;
			var message = "ERROR";
			var errorColor = "rgb(245,170,165)";
			//max filesize
			var maxsize = 2000000;
			//where the window goes after error report
			var goTo = "#";
		
			/*var checkAll = checkEmpty.concat(checkFilesize);
		
			//reset all backgroundcolors
			for (var i = 0; i < checkAll.length; i++) {
				//var e = document.forms["addProject"][checkAll[i][0]];
				var e = document.getElementById(checkAll[i][0]);
				e.style.backgroundColor = "transparent";
			}*/
		
			/*
			 * EMPTY ERROR
			 */
			for (var i = 0; i < checkEmpty.length; i++) {
						
				//check if element exists
				if (document.getElementById(checkEmpty[i][0]) != null) {
					//get element
					var e = document.getElementById(checkEmpty[i][0]);
					var v = e.value;
					//if element is empty, add a line to the error string, change css, change goTo location
					if (v == null || v == "") {
						var returnString = "\n-" + checkEmpty[i][1] + " is a required field! ";
						message += returnString;
						error = true;
						if (goTo == "#") {
							goTo = checkEmpty[i][0];
						}
						//e.style.backgroundColor = errorColor;
					}
				}
			}
		
			/*
			 * FILESIZE ERROR
			 */
			for (var i = 0; i < checkFilesize.length; i++) {
				// Check for the various File API support.
				if (window.File && window.FileReader && window.FileList && window.Blob) {
					//check if element exists
					if (document.getElementById(checkFilesize[i][0]) != null) {
						//get element file size
						var e = document.getElementById(checkFilesize[i][0]);
						//check if file is selected
						if (!(e.value == null || e.value == "")) {
							//get filesize
							var v = document.getElementById(checkFilesize[i][0]).files[0].size;
							//if element is not empty and not numerical, add a line to the error string, change css
							if (v > maxsize) {
								var returnString = "\n-" + checkFilesize[i][1] + " filesize is bigger than the maximum of 2Mb!";
								message += returnString;
								error = true;
								if (goTo == "#") {
									goTo = checkFilesize[i][0];
								}
								e.style.backgroundColor = errorColor;
							}
						}
					}
				}
			}
			
			/*
			 * FILESIZE (Multiple) ERROR - checks fields that allow multiple files to be selected
			 */
			for (var i = 0; i < checkFilesizeMultiple.length; i++) {
				// Check for the various File API support.
				if (window.File && window.FileReader && window.FileList && window.Blob) {
					//check if element exists
					if (document.getElementById(checkFilesizeMultiple[i][0]) != null) {
						//get element file size
						var e = document.getElementById(checkFilesizeMultiple[i][0]);
						//check if file is selected
						if (!(e.value == null || e.value == "")) {
							for (var j = 0; j < document.getElementById(checkFilesizeMultiple[i][0]).files.length; j++){
								//get filesize
								var v = document.getElementById(checkFilesizeMultiple[i][0]).files[j].size;
								//if element is not empty and not numerical, add a line to the error string, change css
								if (v > maxsize) {
									var returnString = "\n-" + checkFilesizeMultiple[i][1] + " filesize is bigger than the maximum of 2Mb!";
									message += returnString;
									error = true;
									if (goTo == "#") {
										goTo = checkFilesizeMultiple[i][0];
									}
									e.style.backgroundColor = errorColor;
								}
							}
						}
					}
				}
			}
		
			//if any errors occurred return error message
			if (error) {
				alert(message);
				if(gotoError){
					//reset url
					window.location.hash = "#";
					//go to goTo location in window
					window.location.hash = goTo;
				}
				return false;
			}
		}
		
		/**
		 * In a form, replace preview image when a new image is selected
		 *  
		 * @param 
		 * @return
		 */
		function updatePreviewImage(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();            
		        reader.onload = function (event) {
		        	//get the img element above the file input and change the src
		           $(input).parent().parent().parent().parent().siblings('img').filter(':first').attr('src', event.target.result); 
		        }            
		        reader.readAsDataURL(input.files[0]);
		    }
		}


		</script>
        
  </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav -->
	    <?php echo printHeader(true, true, "red", "admin", "./admin.php") ?>
	    
	    <div class="container page-content admin">
	    	<?php 
	    		//if logged in show controls
	    		if($loggedin){
	    	?>
	    	
	    	
	    	<!-- LOGOUT START -->
	    	<?php if(!$editingPost && !$editingEvent){ //don't display if we're editing a post ?>
	    	<div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-4">
                	<h2>Logged in as <?php echo $_SESSION['username'] ?></h2>
                
	                <form id="logout_inputform" action="./inc/update.inc.php" method="post">
					<fieldset>
		                	<input type="hidden" name="logout_submit" value="logout"/>
		                    <button type="submit" class="btn btn-primary">LOG OUT</button>
		            </fieldset>
					</form>
					<br><br><br>
				</div>
				
            </div>
            <!-- LOGOUT END -->
            
            
            <!-- WEBSITE PARAMETERS START -->
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                	<h2>Website parameters</h2>
                </div>
            </div>
            
				 <!-- COVERIMAGE -->
	            <form class="form-horizontal" method="post" action="./inc/update.inc.php" enctype="multipart/form-data" role="form"> <!-- <?php echo $edit ?>-->
	
	                <!--coverimage-->
	                <div class="form-group">
	                    <label class="control-label col-sm-2" for="coverimage">Cover Image:</label>
	                    <img class="col-sm-3 imagepreview img-responsive" src="<?php echo $webCoverImage?>"/>
	                    <div class="col-sm-6">
	                    	<div class="input-group">
				                <span class="input-group-btn">
				                    <span class="btn btn-default btn-file">
				                        Browse&hellip; <input name="coverimage" id="coverimage" type="file" onchange="updatePreviewImage(this)">
				                    </span>
				                </span>
				                <input type="text" class="form-control" readonly>
				            </div>
	                    </div>
	                    <!--submit-->
	                    <div class="col-sm-1">
	                    	<input type="hidden" name="updatecoverimage" value="updatecoverimage" >
	                    	<button type="submit" class="btn btn-primary floatright">Update</button>
	                    </div>
	                </div>
	
	             </form>
	             
	             <!-- PLAYLIST -->
	            <form class="form-horizontal" method="post" action="./inc/update.inc.php" enctype="multipart/form-data" role="form"> <!-- <?php echo $edit ?>-->
	
	                <!--coverimage-->
	                <div class="form-group">
	                    <label class="control-label col-sm-2" for="playlist">Deezer Playlist:</label>
	                    <div class="col-sm-9">
                        	<input type="text" class="form-control" id="playlist" name="playlist" placeholder="eg:1256571301" value="<?php echo $playlist?>">
                    		<div class="infotext">Enter Deezer or Mixcloud IFrames. When choosing the style for the IFrame embed, keep it light of color and small in size (simple iframe will work better than a complex one)</div>
                    	</div>
	                    <!--submit-->
	                     <div class="col-sm-1">
	                    	<input type="hidden" name="updateplaylist" value="updateplaylist" >
	                    	<button type="submit" class="btn btn-primary floatright">Update</button>
	                    </div>
	                </div>
	
	             </form>
	             
			<br><br><br><br>
            <?php } ?>
	    	<!-- WEBSITE PARAMETERS END -->
	    	
	    	
            <!------------------------>
            <!-- ADD BLOGPOST START -->
            <!------------------------>
            <?php if(!$editingEvent){ //don't display if we're editing an event ?>
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                	<?php echo ($editingPost) ? "<h2>Edit blogpost</h2>" :  "<h2>Add new blogpost</h2>"  ?>
               		<div class="infotext">*required fields</div><br>
                </div>
            </div>

            <form class="form-horizontal" method="post" action="./inc/update.inc.php" enctype="multipart/form-data" role="form" onsubmit="return checkInputForm(false)"> <!-- <?php echo $edit ?>-->
            	
                <!--title-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">*Title:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?php echo $postTitle ?>">
                    </div>
                </div>
                
                <!--tags-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">*Tags:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="separate multiple tags by comma eg: party, folk, music" value="<?php echo $postTags ?>">
                    </div>
                </div>
                
                <!--sortdate-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="sortdate">*Sort Date:</label>
                    <div class="col-sm-10">
                    	<input type="text" id="sortdate" name="sortdate" value="<?php echo $postSortdate ?>" />
                    	<div class="infotext">Posts will be ordered chronologically by this date</div>
                    </div>
                </div>
                
                <!--mediacover-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">Media Cover:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="youtubecover" name="youtubecover" placeholder="paste embed code here" value="<?php echo $youtubeCover ?>">
                    	<div class="infotext">Paste the embedcode here (starts and ends with < iframe > tags)</div>
                    </div>
                </div>
                
                 <!--coverimage-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="coverimage">Cover Image:</label>
                    <img class="col-sm-3 imagepreview img-responsive" src="<?php echo $postCoverImage?>"/>
                    <div class="col-sm-7">
                    	<div class="input-group">
			                <span class="input-group-btn">
			                    <span class="btn btn-default btn-file">
			                        Browse&hellip; <input name="coverimage" id="coverimage" type="file" onchange="updatePreviewImage(this)">
			                    </span>
			                </span>
			                <input type="text" class="form-control" readonly>
			            </div>
			            <div class="infotext">
			            	MAX FILESIZE 2Mb!<br> 
			            	You need to either have a mediacover or a cover image. <br> 
			                If both are filled in, the small preview on the front page will display the coverimage instead of the mediacover
			            </div>
                    </div>
                </div>
                
                <!--body-->
                <div class="row">
                    <label class="control-label col-sm-2" for="textbody">*Body:</label>
                    <div class="col-sm-10 nopadding">
						<textarea id="summernote" name="body"><?php echo $postBody ?></textarea>
					</div>
                </div>
                
                
                <!--gallery
                <div class="form-group">
                    <label class="control-label col-sm-2" for="imagegallery">Image Gallery:</label>
                    <div class="col-sm-10">
                    	<div class="input-group">
			                <span class="input-group-btn">
			                    <span class="btn btn-default btn-file">
			                        Browse&hellip; <input name="imagegallery" id="imagegallery" type="file" multiple>
			                    </span>
			                </span>
			                <input type="text" class="form-control" readonly>
			            </div>
                    </div>
                </div>-->

          
                
				<!--submit-->
                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                    	<input type="hidden" name="id" id="id" value="<?php echo $editedID ?>" >
                    	<input type="hidden" name="posttype" value="save blogpost" >
                    	<button type="submit" class="btn btn-primary floatright">Submit</button>
                    </div>
                </div>
             </form>
             <br><br><br>
             <?php } ?>
             <!-- ADD BLOGPOST END -->
             
             
             <!--------------------->
             <!-- ADD EVENT START -->
             <!--------------------->
             <?php if(!$editingPost){ //don't display if we're editing an event ?>
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                	<?php echo ($editingEvent) ? "<h2>Edit event</h2>" :  "<h2>Add new event</h2>"  ?>
               		<div class="infotext">*required fields</div><br>
                </div>
            </div>

            <form class="form-horizontal" method="post" action="./inc/update.inc.php" enctype="multipart/form-data" role="form" onsubmit="return checkInputFormEvent(false)"> <!-- <?php echo $edit ?>-->
            	
                <!--title-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_title">*Title:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_title" name="title" placeholder="Enter title" value="<?php echo $eventTitle ?>">
                    </div>
                </div>
                
                <!--tags-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_title">*Tags:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_tags" name="tags" placeholder="separate multiple tags by comma eg: party, folk, music" value="<?php echo $eventTags ?>">
                    </div>
                </div>
                
                <!--sortdate-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_sortdate">*Event Date:</label>
                    <div class="col-sm-10">
                    	<input type="text" id="event_sortdate" name="sortdate" value="<?php echo $eventSortdate ?>" />
                    	<div class="infotext">The exact date of the event</div>
                    </div>
                </div>
                
                <!--hour-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_hour">*Starting Hour:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_hour" name="hour" placeholder="eg: 21h30" value="<?php echo $eventHour ?>">
                    </div>
                </div>
                
                <!--venue-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_venue">*Venue:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_venue" name="venue" placeholder="eg: Kunstencentrum Vooruit " value="<?php echo $eventVenue ?>">
                    </div>
                </div>
                
                <!--venue link-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_venueurl">Venue Link:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_venueurl" name="venueurl" placeholder="link to the venue's website" value="<?php echo $eventVenueUrl ?>">
                    </div>
                </div>
                
                 <!--address-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_address">*Address:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_address" name="address" placeholder="eg: Kantienberg 5, 9000 Gent " value="<?php echo $eventAddress ?>">
                    </div>
                </div>
                
                 <!--ticketsurl-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_address">Event Ticket Link:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_ticketsurl" name="ticketsurl" placeholder="Add link to where tickets can be bought" value="<?php echo $eventTicketLink ?>">
                    </div>
                </div>
                
                <!--facebook link-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_facebookurl">Facebook Event Page:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="event_facebookurl" name="facebookurl" placeholder="link to the facebook event page" value="<?php echo $eventFacebookUrl ?>">
                    </div>
                </div>
                
                <!--coverimage-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="event_coverimage">*Cover Image:</label>
                    <img class="col-sm-3 imagepreview img-responsive" src="<?php echo $eventCoverImage?>"/>
                    <div class="col-sm-7">
                    	<div class="input-group">
			                <span class="input-group-btn">
			                    <span class="btn btn-default btn-file">
			                        Browse&hellip; <input name="coverimage" id="event_coverimage" type="file" onchange="updatePreviewImage(this)">
			                    </span>
			                </span>
			                <input type="text" class="form-control" readonly>
			            </div>
			            <div class="infotext">
			            	MAX FILESIZE 2Mb!<br> 
			            </div>
                    </div>
                </div>
                
                <!--preview-->
                <div class="row">
                    <label class="control-label col-sm-2" for="event_preview">*Previewtext:</label>
                    <div class="col-sm-10 nopadding">
						<textarea id="summernote2" name="event_preview"><?php echo $eventPreview ?></textarea>
					</div>
                </div>
                
                <!--body-->
                <div class="row">
                    <label class="control-label col-sm-2" for="event_body">*Body:</label>
                    <div class="col-sm-10 nopadding">
						<textarea id="summernote3" name="event_body"><?php echo $eventBody ?></textarea>
					</div>
                </div>
                
                <!--gallery-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="imagegallery">Image Gallery:</label>
                    <div class="col-sm-10">
                    	<div class="input-group">
			                <span class="input-group-btn">
			                    <span class="btn btn-default btn-file">
			                        Browse&hellip; <input name="imagegallery[]" id="imagegallery" type="file" multiple>
			                    </span>
			                </span>
			                <input type="text" class="form-control" readonly>
			            </div>
			            <div class="infotext">MAX FILESIZE PER IMAGE 2Mb!<br> Select one or more images <br> Note: to add images to an existing gallery, the original images need to be re-uploaded as well</div>
			           
			            <br>
			            
			            <div>
			            	<?php
			            		//if event has gallery images show them here
			            		if($eventGalleryImages != NULL){     			
			            			foreach ($eventGalleryImages as $key => $value) {
										$galleryImgPath = "img/small/".$value;
										$galleryImgPathXL = "img/original/".$value;

										echo <<<IMAGE
										<span class="galleryimage" >
											<a class="deleteimage" onclick="deleteImage(this)"><span class="glyphicon glyphicon-remove"></span><a/>
											<a href="$galleryImgPathXL" data-lightbox="eventalbum" title=""> 
												<img src='$galleryImgPath' class='img-responsive event' />
											</a>
											<input type="hidden" name="existingGalleryImages[]" id="existingGalleryImages" value="$value" >			
										</span>				
IMAGE;
			            			}
								}
			            	?>
			            </div>
                    </div>
                </div>
                
                
				<!--submit-->
                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                    	<input type="hidden" name="id" id="id" value="<?php echo $editedID ?>" >
                    	<input type="hidden" name="posttype" value="save event" >
                    	<button type="submit" class="btn btn-primary floatright">Submit</button>
                    </div>
                </div>
             </form>
             <?php } ?>
             <!-- ADD EVENT END -->
             
             
             <!-- ADD USERS START --> 
             <?php if($isAdmin){ ?>
		             <!--create users (admin only)-->
			    	<div class="red">CREATE NEW USER</div>
					<form id="createuser_inputform" action="./inc/update.inc.php" method="post">
						<fieldset>
							<div></br>
								<div class="login_input">
									<span>NAME</span><input class="large" name="create_user_name" type="text"/>
								</div>
								<div class="login_input">
									<span>PASSWORD</span><input class="large" name="create_user_password" type="password"/>
								</div>
								<div class="login_input">
									<span>TYPE</span>
									<select name="create_user_usertype">
										<option value="editor">Editor</option>
										<option value="admin">Admin</option>
									</select>
								</div>
								<input type="hidden" name="action" value="create_user"/>
								<input id="login_submit" type="submit" name="login" value="CREATE"/>
							</div>
						</fieldset>
					</form>
			<?php } ?>
            <!-- ADD USERS END -->  	
             	
             	
            <!-- LOGIN FORM START --> 	
            <?php 
	    		//if not logged in show login
	    		} else{
	    	?>
	    	
	    	
	    	<div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                	<h2>LOGIN</h2>
                	<div class="infotext">*required fields</div><br><br>
                </div>
            </div>
	    	
	    	<div class="none" id="login">
				<div id="login_form_wrapper" style="display: <?php echo (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1)) ? "block": "none"?>">
					<form id="login_inputform"  action="./inc/update.inc.php" method="post">
						<fieldset>

							<!--user-->
			                <div class="form-group">
			                    <label class="control-label col-sm-2" for="title">*User:</label>
			                    <div class="col-sm-10">
			                        <input type="text" class="form-control" id="login_name" name="login_name" placeholder="Enter username" value="<?php echo (isset($_GET['usernameAttempt']))? $_GET['usernameAttempt'] : "" ;  ?>">
			                    </div>
			                </div><br><br>
			                
			                <!--password-->
			                <div class="form-group">
			                    <label class="control-label col-sm-2" for="title">*Password:</label>
			                    <div class="col-sm-10">
			                        <input type="password" class="form-control" id="login_password" name="login_password" >
			                    </div>
			                </div><br><br>
			                
			                <!-- error-->
							<div class="form-group" style="display:<?php echo (isset($_GET['loginError']))? "block": "none"; ?>">
			                    <span class="control-label col-sm-2" for="title">&nbsp;</span>
			                    <div class="col-sm-10">
			                      <b>ERROR: Wrong Username or Password</b> 
			                    </div>
			                    <br>
			                </div>
			                
							<!--submit-->
			                <div class="form-group"> 
			                    <div class="col-sm-offset-2 col-sm-10">
			                    	<input type="hidden" name="posttype" value="login" >
			                    	<button type="submit" class="btn btn-primary">LOG IN</button>
			                    </div>
			                </div>
							
						</fieldset>
					</form>
				</div>
			</div>
	    	<!-- LOGIN FORM END -->
	    	
	    	<?php 
	    		} 
	    	?>
             	
	      <?php printFooter() ?>
	      
	    </div> 
	    
    	<!-- /container -->        


        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
         <!--lightbox script-->
        <script type="text/javascript" src="js/lightbox.js"></script>
    </body>
</html>

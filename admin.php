<?php
include_once './inc/db.inc.php';
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
			    ['font', ['strikethrough', 'superscript', 'subscript']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['insert', ['link', 'video']]
			  ]
		  });
		  
		  $('#summernote').summernote('lineHeight', 0.7);
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
		</script>
        
    	<script type="text/javascript">
		//calls a function to check for errors, passes arrays with elements to check
		function checkInputForm(edit) {
			
			//array of all inputs to check for 'empty' errors [id,name]
			var checkEmpty = new Array;
			//if editing, omit the check for empty images
			if (edit) {
				checkEmpty = [["title","title"], ["tags","tags"], ["sortdate","sort date"], ["body","body"]];
			} else{
				checkEmpty = [["title","title"], ["tags","tags"], ["sortdate","sort date"], ["coverimage","cover image"], ["body","body"]];
			}

			// array of all inputs to check filesize [id, name]
			var checkFilesize = [['coverimage', "cover image"]];

			//execute form check  //checkEmpty
			return validateForm(true, checkEmpty, checkFilesize);
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
		function validateForm(gotoError, checkEmpty, checkFilesize) {
			
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

		</script>
        
  </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        
        <!-- Nav -->
	    <nav class="navbar navbar-inverse navbar-fixed-top news" role="navigation">
	      <div class="container">
	      	
	        <div class="navbar-header top">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>                        
		      	</button>
	          	<a href="index.php"><img src="img/LogoSmall.png" class="logo"/></a><a href="#"><span><h4> / ADMIN</h4></span></a>
	        </div>
	        
	        <div class="collapse navbar-collapse" id="myNavbar">
			      <ul class="nav navbar-nav navbar-right">
			        <li class="menuoption"><a href="#" class="white"><h4>ABOUT</h4></a></li>
			        <li class="menuoption"><a href="#" class="white"><h4>EVENTS</h4></a></li>
			        <li class="menuoption"><a href="#" class="white"><h4>NEWS</h4></a></li>
			      </ul>
			</div>
	        
	      </div>
	    </nav>
	    
	    <div class="container page-content admin">
	    	
            
            <!-- Form -->
            <div class="row">
                <div class="col-sm-2">&nbsp;</div>
                <div class="col-sm-10">
                	<h2>New blogpost</h2>
               		<div class="infotext">*required fields</div><br><br>
                </div>
            </div>

            <form class="form-horizontal" method="post" action="./inc/update.inc.php" enctype="multipart/form-data" role="form" onsubmit="return checkInputForm(false)"> <!-- <?php echo $edit ?>-->
            	
                <!--title-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">*Title:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                    </div>
                </div>
                
                <!--tags-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="title">*Tags:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="separate multiple tags by comma eg: party, folk, music">
                    </div>
                </div>
                
                <!--sortdate-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="sortdate">*Sort Date:</label>
                    <div class="col-sm-10">
                    	<input type="text" id="sortdate" name="sortdate" value="" />
                    	<div class="infotext">Posts will be ordered chronologically by this date</div>
                    </div>
                </div>
                
                <!--coverimage-->
                <div class="form-group">
                    <label class="control-label col-sm-2" for="coverimage">*Cover Image:</label>
                    <div class="col-sm-10">
                    	<div class="input-group">
			                <span class="input-group-btn">
			                    <span class="btn btn-default btn-file">
			                        Browse&hellip; <input name="coverimage" id="coverimage" type="file" >
			                    </span>
			                </span>
			                <input type="text" class="form-control" readonly>
			            </div>
                    </div>
                </div>
                
                <!--body-->
                <div class="row">
                    <label class="control-label col-sm-2" for="textbody">*Body:</label>
                    <div class="col-sm-10 nopadding">
						<textarea id="summernote" name="body"></textarea>
					</div>
                </div>
                
                <br>
                
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

                <br><br>
                
				<!--submit-->
                <div class="form-group"> 
                    <div class="col-sm-offset-2 col-sm-10">
                    	<button type="submit" class="btn btn-primary">Submit</button>
                    	<input type="hidden" name="posttype" value="save blogpost" >
                    </div>
                </div>
             </form>
             	
	      <hr>
	
	      <footer>
	        <p class="footer">&copy; Binair01 - <?php echo date("Y"); ?></p>
	      </footer>
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
    </body>
</html>

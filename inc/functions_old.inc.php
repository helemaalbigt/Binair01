<?php
//initialize session if none exists
if (session_id() == '' || !isset($_SESSION)) {
	// session isn't started
	session_start();
}

//helps interpret french accented characters. They have special needs
header('Content-type: text/html; charset=utf-8');

//check if logged in and logged in as admin or editor. If not, don't do anything
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor")){

	/**
	 * AJAX FUNCTIONS
	 */
	//check if function to load projects is called through URL. Get function and the array of arguments, then execute function
	if (isset($_GET['arguments'])) {
		$arguments = splitData($_GET['arguments'], 0, "joiner");
		//current offset not being used
		if (isset($_SESSION['$projects_current_offset']))
			unset($_SESSION['$projects_current_offset']);
		$_SESSION['$projects_current_offset'] = $_GET['offset'];
		//execute function
		//echo "<script> alert(\"".$arguments[3]."\");</script>";
		retrieveProjectsDataFormat($arguments[0], $arguments[1], $arguments[2], unserialize(urldecode($arguments[4])), $arguments[3]);
	}
	
	//check if function to load field data is caled through URL, then execute function
	if (isset($_GET['field']) && isset($_GET['id']) && isset($_GET['cl'])) {
		$field = retrieveField($_GET['field'], $_GET['id']);
		$id = $_GET['id'];
		$fieldName = $_GET['field'];
		$charlimit = $_GET['cl'];
		
		//print a box with the field in it
		echo <<<FP
		<div class="popup popup-$id-$fieldName" id="$id" field="$fieldName">
			<textarea class="popup_content" rows="5" cols="20" maxlength="$charlimit" >$field</textarea> 
			<div class="save_box tablebutton">save changes</div>
			<div class="close_box tablebutton">cancel</div>
		</div>
FP;
	}

	//check if function to save field data is caled through URL, then execute function
	if (isset($_GET['savefield']) && isset($_GET['fieldvalue'])  && isset($_GET['id'])) {
		//decode fieldvalue and clean it up
		$cleanedField = cleanData(array(rawurldecode($_GET['fieldvalue'])));
		saveField($_GET['savefield'], $cleanedField[0], $_GET['id']);
	}
	
	//check if function to load project data is caled through URL, then execute function
	if (isset($_GET['getProjectData']) && isset($_GET['id'])) {
		retrieveProjectData($_GET['id']);
	}
	
	//check if function to download MySQL dump is called
	if (isset($_GET['dumpMySQL'])){
		dumpMySQL();
	}
	
	//check if function to download Excell is called
	if (isset($_GET['dumpExcel'])){
		dumpProjectsExcel();
	}
}

/**
 * Creates a list of checkboxes based on array data
 *
 * @param array $c array with values for the checkboxes
 * @param string $name name used as checkbox name and as part of the unique id
 * @param string $checked String containing all values that need to be checked by default
 * @return NULL
 */
function createCheckboxes($c, $name, $checked = "%%%%%%%%%%%%%") {

	for ($i = 0; $i < count($c); $i++) {
		//split option value into three language values
		$c_split = splitData($c[$i]);
		//determine visible value of option (if 3 languages, take english [2])
		$value = (count($c_split) >= 3) ? $c_split[2] : $c_split[0];

		//open div
		echo "<div id=\"" . $c[$i] . "_wrapper\" class=\"checkbox\" >";
		//check checkbox if the passed string contains the value of this specific checkbox
		if ((strpos($checked, $c[$i]) !== false)) {
			echo "<input id=\"" . $name . "-" . $c[$i] . "\" type=\"checkbox\" name=\"" . $name . "[]\" value=\"" . $c[$i] . "\" style=\"\" onchange=\"\" checked/>";
		} else {
			echo "<input id=\"" . $name . "-" . $c[$i] . "\" type=\"checkbox\" name=\"" . $name . "[]\" value=\"" . $c[$i] . "\" onchange=\"\"/>";
		}
		echo "<label for=\"" . $name . "-" . $c[$i] . "\">" . $value . "</label></div>";
	}
}

/**
 * Creates a dropdown select list from a 2D array containing the available values (datavalue, visible value)
 *
 * @param array $c array with values for the selectlist. Values are generally strings combining three languages (bad practice - need to update)
 * @param string $name name used as checkbox name and as part of the unique id
 * @param boolean $array Whether or not this values is grouped together in an Array on submit
 * @param string $default Checks for a value that matches this string, if found, set it to default
 * @param boolean $startEmpty Have no option selected at start
 * @param string $onchange The javascript to execute when changed
 * @return NULL
 */
function createSelectList($c, $name, $array = FALSE, $default = NULL, $startEmpty = TRUE, $onchange = "") {

	//make the opening select tag
	if ($array) {
		echo "<select id=\"" . $name . "\" name=\"" . $name . "[]" . "\" onchange=\"". $onchange . "\" >";
	} else {
		echo "<select id=\"" . $name . "\" name=\"" . $name . "\" onchange=\"". $onchange . "\" >";
	}
	
	if($startEmpty)	{
		echo "<option disabled selected> </option>";
	}
	
	//write the options
	for ($i = 0; $i < count($c); $i++) {
		//split option value into three language values
		$c_split = splitData($c[$i]);
		//determine visible value of option (if 3 languages, take english [2])
		$value = (count($c_split) >= 3) ? $c_split[2] : $c_split[0];
		//$selected = ($c[$i] == trim($default)) ? "selected=\"selected\"" : "";
		$selected = (strpos($default, $value) !== false) ? "selected=\"selected\"" : "";
		
		echo "<option value=\"" . $c[$i] . "\" " . $selected . ">" . $value . "</option>";
	}

	//make the closing select tag
	echo "</select>";
}

/**
 * Create Checkbox with hidden field to report non selected values
 *
 * @param string $value
 * @param string $name
 * @param string $labelname
 * @param string $onchange Javascript to ecexute on change
 * @param string $unchecked 
 * @return
 */
function createReverseCheckbox($value, $name, $labelname, $onchange = "", $unchecked = null) {

	$name1 = $name . "[]";
	$name2 = $name . "Hidden[]";
	if (strpos($unchecked, $value) !== false) {//$unchecked != null &&
		$unchecked = "";
	} else {
		$unchecked = "checked";
	}

	echo <<<CHECKBX
 	<input id="$value" class="$name1 reversecheckbox" type="checkbox" name="$name1" value="$value" onchange="$onchange" $unchecked/>
	<input id='$name2' class="$name2 reversecheckboxHidden" type='hidden' value="$value" name="$name2" />
	<label for="$value">$labelname</label>  	
CHECKBX;
}

/**
 * Convert <br> to new lines for use in textareas
 *
 * @param string text
 * @return string
 */
function br2newl($text) {
	$breaks = array("<br />", "<br>", "<br/>", "</br>");
	$text = str_ireplace(array("\n"), "", $text);
	$text = str_ireplace($breaks, "\n", $text);
	return $text;
}

/**
 * Convert new lines to <br> for saving in database
 *
 * @param string text
 * @return string
 */
function newl2br($text) {
	$breaks = array("\n");
	$text = str_ireplace($breaks, "</br>", $text);
	return $text;
}

/**
 * Creates a dropdown select list for all the months in the year
 *
 * @param string $name name used as checkbox name and as part of the unique id
 * @param boolean $array Whether or not this values is grouped together in an Array on submit
 * @param string $default Default selected month
 * @param bool $emptyDefault wether default should be empty or not
 * @return NULL
 */
function createMonthSelectList($name, $array = NULL, $default = NULL, $emptyDefault = false) {

	//get months
	$M = cal_info(0);
	$months = array_slice($M['months'], 0, 12);

	//make the opening select tag
	if ($array) {
		echo "<select id=\"" . $name . "\" name=\"" . $name . "[]" . "\" onchange=\"\">";
	} else {
		echo "<select id=\"" . $name . "\" name=\"" . $name . "\" onchange=\"\" >";
	}
	
	if($emptyDefault){
		echo "<option disabled selected> </option>";
	}
	
	//write the options
	for ($i = 0; $i < count($months); $i++) {
		$value = ($i + 1 < 10) ? "0" . ($i + 1) : $i + 1;
		$selected = ($value == $default) ? "selected=\"selected\"" : "";
		echo "<option value=\"" . $value . "\" " . $selected . ">" . $value . "</option>";
	}

	//make the closing select tag
	echo "</select>";
}

/**
 * Joins all strings in an array into a single string, separated by 's-_--e'. Returns the string
 *
 * @param array $c array to handle
 * @return string combined string
 */
function joinData($c, $key = "s-_--e") {

	//if strings already joined, join on second level
	for ($i = 0; $i < count($c); $i++) {
		$key = (strpos($c[$i], $key) !== false) ? "s-_-_-e" : $key;
	}
	//if strings already joined on second level, join on third level
	for ($i = 0; $i < count($c); $i++) {
		$key = (strpos($c[$i], "s-_-_-e") !== false) ? "s---_-e" : $key;
	}
	//debug
	if (isset($c[0])) {
		$returnString = (trim($c[0]) == "") ? "/" : $c[0];
	} else {
		$returnString = "/";
	}
	for ($i = 1; $i < count($c); $i++) {
		//if value empty then replace by /
		$value = (trim($c[$i]) == "") ? "/" : $c[$i];
		$returnString .= $key . $value;
	}

	return $returnString;
}

/**
 * Pushes Key and Value to an array
 *
 * @param array $a
 * @param string $key
 * @param string $value
 * @return array
 */
function pushKeyValue($a, $key, $value) {
	//push value to array
	array_push($a, $value);
	//add field with proper key
	$a[$key] = end($a);
	//remove field with number key
	unset($a[0]);
	//return the array
	return $a;
}

/**
 * Takes a string of data and seperates it at 's-_--e'. Returns array of values
 *
 * @param string $input input string of joined values
 * @return array
 */
function splitData($input, $level = 0, $key = "s-_--e") {

	//if string is joined on two levels split the first level
	$key = (strpos($input, "s-_-_-e") !== false) ? "s-_-_-e" : $key;
	//if string already joined on second level, split on third level
	$key = (strpos($input, "s---_-e") !== false) ? "s---_-e" : $key;
	//if level is passed, set specific key
	switch ($level) {
		case 1 :
			$key = "s-_--e";
			break;
		case 2 :
			$key = "s-_-_-e";
			break;
		case 3 :
			$key = "s---_-e";
			break;

		default :
			break;
	}
	//debug
	//echo "</br>".$key."</br>";

	$c = explode($key, $input);
	return $c;
}

/**
 * Check if input is empty
 *
 * @param string $value
 * @return boolean
 */
function emptyValue($value) {
	return ($value != "");
}


/**
 *Retrieves field value for specific project
 *
 * @param string $field The field to be retrieved
 * @param string $id  the id of the f-project from which the field is retrieved
 * @return string the field value
 */
function retrieveField($field, $id) {
	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "select ".$field." from projects WHERE id=? LIMIT 1";
	$stmt = $db -> prepare($sql);
	$stmt -> execute(array($id));

	//save the returned field value
	$f = $stmt -> fetch();
	$stmt -> closeCursor();
	
	$fieldValue = $f[$field];

	return $fieldValue;
}

/**
 *Saves field value for specific project
 *
 * @param string $field The field to be retrieved
 * @param string $id  the id of the f-project from which the field is retrieved
 * @return string the field value
 */
function saveField($field, $value, $id) {
	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);
	$sql = "UPDATE projects SET ".$field."=? WHERE id=? LIMIT 1";
	$stmt = $db -> prepare($sql);
	$stmt -> execute(array($value, $id));
	$stmt -> closeCursor();
}

/**
 *Retrieves project Data for specific project
 *
 * @param string $id  the id of the project from which the project data is retrieved
 * @return html The formatted data, ready to b echo-ed on the page
 */
function retrieveProjectData($id) {
	include_once 'project.inc.php';
	
	//check if contributor or admin is logged in, set boolean to make menus visible accordingly
	$menus = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? TRUE : FALSE;
	
	$project = new Project(FALSE);
	$project -> updateParameters($id);
	echo $project -> formatProjectData($menus);
}



/**
 *Retrieves total number of projects in database based on a passed SQL query
 *
 * @param string $where where clause of query
 * @return
 */
function retrieveNumberOfProjects($where = "") {
	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "select count(*) as total from projects " . $where;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	$number_of_projects = $stmt -> fetchColumn();
	$stmt -> closeCursor();

	return $number_of_projects;
}

/**
 *Retrieves all ids of projects in database based on a passed SQL query
 *
 * @param string $where where clause of query
 * @return
 */
function retrieveIds($where = "") {
	include_once 'db.inc.php';
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	$sql = "select id from projects" . $where;
	$stmt = $db -> prepare($sql);
	$stmt -> execute();
	$ids = $stmt -> fetchAll(PDO::FETCH_COLUMN, 0);
	$stmt -> closeCursor();

	return $ids;
}

/**
 * Retrieves list of projects from the database in an easily readable table
 *
 * @param string $language language to use
 * @param int $projects_pp amount of projects to load
 * @param int $projects_offset where to start
 * @param array $project_inclusion array indicating which projects are selected or deselected (1/0 respectively)
 * @param string $where where clause of query
 * @return
 */
function retrieveProjectsListFormat($language, $projects_pp, $projects_offset, $project_inclusion, $where = ""){
	
	include_once 'project.inc.php';
	include_once 'db.inc.php';
	
	//determine our permissions
	$menuPermission = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? TRUE : FALSE;
	
	//1.start drawing table 1 with all the names
	//------------------------------------------
	//if we have admin menus, add two table columns
	$permission = ($menuPermission) ? "<td></td><td></td>" : "";
	echo <<<DRAWTABLEOPEN
	<!--<h1>UNDER CONSTRUCTION DO NOT USE</h1>-->
	<div id="table_wrapper">
		<table class="project_table" id="table1">
			<tr>
				<td></td>
				$permission
DRAWTABLEOPEN;
	
	//2.draw all labels on first row
	//------------------------------
	//Open a database connection and store it
	$db2 = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	$stmt2 = $db2 -> prepare(html_entity_decode("SELECT * FROM `projects` WHERE 1 LIMIT 1"));
	$stmt2 -> execute();
	
	$keys =  $stmt2 -> fetch();
	$keyProject = new Project(FALSE);
	$keyProject -> updateParameters($keys['id']);
	$keyArray = $keyProject -> generateCompletionlistB();
	$stmt2 -> closeCursor();
	
	for($i=0; $i < sizeof($keyArray); $i++){
		$c = array_slice($keyArray, $i);
		$Hkey = key($c); 
		$Hkey = (substr($Hkey, 0, 5 ) === "HIDE_")? "" : $Hkey;
		
		echo <<<DRAWLABELCELL
		<td class="rotate datacell"><div class="vertical-text"><div class="vertical-text__inner">$Hkey</div></div></td>
DRAWLABELCELL;
	}
	echo <<<CLOSELABLEROW
		</tr>
CLOSELABLEROW;
	
	
	//3.Loop through all projects and fill out the table
	//--------------------------------------------------
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id 
			FROM projects " . $where . " ORDER BY date DESC, created DESC";
			//LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	
	while ($row = $stmt -> fetch()) {
		$project = new Project(FALSE);
		$project -> updateParameters($row['id']);
		
		//3.1 draw name and edit button
		$query = (isset($_GET['q'])) ? "&q=".$_GET['q'] : "" ;
		$PID = $row['id'];

		if($menuPermission){
			echo <<<DRAWTABLEROW1
			<tr>
				<td class="row1 static">$project->name</td>
				<td class="buttoncell static"><a class="tablebutton" href="./admin.php?id=$PID$query">edit</a></td>
				<td class="buttoncell static"><a class="tablebutton delete" href="./inc/update.inc.php?action=project_delete&id=$PID$query">Delete</a></td>
DRAWTABLEROW1;
		} else {
			echo <<<DRAWTABLEROW2
			<tr>
				<td class="row1">$project->name</td>
DRAWTABLEROW2;
		}

		//3.2 draw colored cells for completed fields
		$A = $project -> generateCompletionlistB();
		foreach ($A as $key => $value) {
			//$bgColor = ($value) ? "#eb6e64" : "#f0f0f0";
			$bgColor = ($value[0]) ? "#eb6e64" : "#d1d1d1" ;
			$bgColor = (substr($key,0,5) === "HIDE_") ? "#f0f0f0" : $bgColor;
			//marks cells which can be edited
			$editableClass = ($menuPermission) ? ($value[1]) ? ($value[0]) ? "editableCell filled" : "editableCell empty" : "" : "";
			//moudeoverfunction
			$mouseOver = ($value[1] && $menuPermission) ? "onmousedown=getField(this,'".$value[2]."','".$PID."','".$value[3]."') " : "";
			echo <<<DRAWPROJECTCELL
			<td class="datacell $editableClass" id="cell-$PID-$value[2]" bgcolor="$bgColor" $mouseOver opened="false" characterlimit="$value[3]"></td>
DRAWPROJECTCELL;
		}
		

		
	}
	$stmt -> closeCursor();
	
	//4.close tabel
	//-------------
	echo <<<DRAWTABLECLOSE
		</table>
	</div>
DRAWTABLECLOSE;
}


/**
 * Retrieves list of projects from the database in an easily readable table - Alternative draws two tables,a llowing the first to be frozen in place
 * NEEDS TO BE FINISHED
 * This alt version should generate the first three table columns and the first table row as separate fixed tables, that stay in palce when scrolling through the data
 *
 * @param string $language language to use
 * @param int $projects_pp amount of projects to load
 * @param int $projects_offset where to start
 * @param array $project_inclusion array indicating which projects are selected or deselected (1/0 respectively)
 * @param string $where where clause of query
 * @return
 */
function retrieveProjectsListFormatALT($language, $projects_pp, $projects_offset, $project_inclusion, $where = ""){
	
	include_once 'project.inc.php';
	include_once 'db.inc.php';
	
	//determine our permissions
	$menuPermission = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? TRUE : FALSE;
	
	//1.start drawing table 1 with all the names
	//---------------------
	//1.1 spaces
	//if we have admin menus, add two table columns
	$permission = ($menuPermission) ? "<td></td><td></td>" : "";
	echo <<<OPENTABLE1
	<div id="table_wrapper">
		<table class="project_table" id="table1">
			<tr>
				<td></td>
				$permission
			</tr>
OPENTABLE1;

	//1.2 names
	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id 
			FROM projects " . $where . " ORDER BY date DESC, created DESC";
			//LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	
	while ($row = $stmt -> fetch()) {
		$project = new Project(FALSE);
		$project -> updateParameters($row['id']);
		
		//3.1 draw name and edit button
		$query = (isset($_GET['q'])) ? "&q=".$_GET['q'] : "" ;
		$PID = $row['id'];

		if($menuPermission){
			echo <<<DRAWTABLEROW1
			<tr>
				<td class="row1 static">$project->name</td>
				<td class="buttoncell static"><a class="tablebutton" href="./admin.php?id=$PID$query">edit</a></td>
				<td class="buttoncell static"><a class="tablebutton delete" href="./inc/update.inc.php?action=project_delete&id=$PID$query">Delete</a></td>
			<tr>
DRAWTABLEROW1;
		} else {
			echo <<<DRAWTABLEROW2
			<tr>
				<td class="row1">$project->name</td>
			</tr>
DRAWTABLEROW2;
		}
	}
	
	//close table
	echo <<<CLOSETABLE1
		</table>
CLOSETABLE1;
		
	
	//2.draw data
	//------------------------------
	
	//2.1 open second table
	echo <<<OPENTABLE2
	<table class="project_table" id="table2">
		<tr>
OPENTABLE2;
	
	//2.2 draw all all labels
	//Open a database connection and store it
	$db2 = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	$stmt2 = $db2 -> prepare(html_entity_decode("SELECT * FROM `projects` WHERE 1 LIMIT 1"));
	$stmt2 -> execute();
	
	$keys =  $stmt2 -> fetch();
	$keyProject = new Project(FALSE);
	$keyProject -> updateParameters($keys['id']);
	$keyArray = $keyProject -> generateCompletionlist();
	$stmt2 -> closeCursor();
	
	for($i=0; $i < sizeof($keyArray); $i++){
		$c = array_slice($keyArray, $i);
		$Hkey = key($c); 
		$Hkey = (substr($Hkey, 0, 5 ) === "HIDE_")? "" : $Hkey;
		
		echo <<<DRAWLABELCELL
		<td class="rotate datacell"><div class="vertical-text"><div class="vertical-text__inner">$Hkey</div></div></td>
DRAWLABELCELL;
	}
	echo <<<CLOSELABLEROW
	</tr>
CLOSELABLEROW;
	
	
	//2.3.Loop through all projects and fill out the table
	//Open a database connection and store it
	$db3 = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id 
			FROM projects " . $where . " ORDER BY date DESC, created DESC";
			//LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt3 = $db3 -> prepare(html_entity_decode($sql));
	$stmt3 -> execute();
	
	while ($row = $stmt3 -> fetch()) {
		$project = new Project(FALSE);
		$project -> updateParameters($row['id']);
		$A = $project -> generateCompletionlist();
		
		echo <<<OPENROW
			<tr>
OPENROW;
		
		foreach ($A as $key => $value) {
			//$bgColor = ($value) ? "#eb6e64" : "#f0f0f0";
			$bgColor = ($value) ? "#eb6e64" : "#d1d1d1" ;
			$bgColor = (substr($key,0,5) === "HIDE_") ? "#f0f0f0" : $bgColor;
			
			echo <<<DRAWPROJECTCELL
			<td class="datacell" bgcolor="$bgColor"></td>
DRAWPROJECTCELL;
	
		}
		
		echo <<<CLOSEROW
			<tr>
CLOSEROW;
		
	}

	$stmt3 -> closeCursor();
	
	//2.4 close table
	echo <<<DRAWTABLECLOSE
	</table>
</div>
DRAWTABLECLOSE;
}

/**
 * Retrieves list of projects from the database based on a passed SQL query and formats them in dataformat mode
 *
 * @param string $language language to use
 * @param int $projects_pp amount of projects to load
 * @param int $projects_offset where to start
 * @param array $project_inclusion array indicating which projects are selected or deselected (1/0 respectively)
 * @param string $where where clause of query
 * @return
 */
function retrieveProjectsDataFormatOld($language, $projects_pp, $projects_offset, $project_inclusion, $where = "") {

	include_once 'project.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM projects " . $where . " ORDER BY date DESC, created DESC 
			LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	while ($row = $stmt -> fetch()) {
		$project = new Project(FALSE);
		$project -> setLanguage($language);
		$project -> updateParameters($row['id']);
		//check if id is set in the $project_inclusion array, and take the incluision state from there. If not set to true (visible)
		$included = (array_key_exists($project -> id, $project_inclusion)) ? $project_inclusion[$project -> id] : TRUE;
		//check if contributor or admin is logged in, set boolean to make menus visible accordingly
		$menus = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? TRUE : FALSE;
		echo $project -> formatProjectData($included, TRUE, $menus, TRUE);
		flush();
	}
	$stmt -> closeCursor();
}

/**
 *Retrieves list of projects from the database based on a passed SQL query and formats them in dataformat mode
 * Alt version with retractable data
 *
 * @param string $language language to use
 * @param int $projects_pp amount of projects to load
 * @param int $projects_offset where to start
 * @param array $project_inclusion array indicating which projects are selected or deselected (1/0 respectively)
 * @param string $where where clause of query
 * @return
 */
function retrieveProjectsDataFormat($language, $projects_pp, $projects_offset, $project_inclusion, $where = "") {

	include_once 'project.inc.php';
	include_once 'db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT id
			FROM projects " . $where . " ORDER BY date DESC, created DESC 
			LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	while ($row = $stmt -> fetch()) {
		$project = new Project(FALSE);
		$project -> setLanguage($language);
		$project -> updateParameters($row['id']);
		//check if id is set in the $project_inclusion array, and take the incluision state from there. If not set to true (visible)
		$included = (array_key_exists($project -> id, $project_inclusion)) ? $project_inclusion[$project -> id] : TRUE;
		//check if contributor or admin is logged in, set boolean to make menus visible accordingly
		$menus = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? TRUE : FALSE;
		echo $project -> formatProjectCard($included, TRUE, $menus, TRUE);
		flush();
	}
	$stmt -> closeCursor();
}

/**
 *Retrieves list of projects from the database based on a passed SQL query and passes it on to the preview page
 *
 * @param PDO $db databse to query from
 * @param string $language language to use
 * @param string $hiddenFields fields to hide in each project
 * @param array $included_projects projects that should be visible
 * @param string $sql query to execute
 * @return
 */
function retrieveProjectsPreviewFormat($listtitle, $language, $hiddenFields, $project_inclusion, $filter_sql = "") {

	$ids = joinData(array_keys($project_inclusion, 0), ',');
	$where = "";
	if ($ids != "/") {
		$where = "WHERE id NOT IN (" . $ids . ")";
		//if there is also a filter query add it
		if ($filter_sql != ""){
			$where = ($where!="") ? $where." AND ".ltrim($filter_sql,"WHERE") : "";
		}
	} 
	if($where == ""){
		//if there is only a filterquery
		if ($filter_sql != ""){
			$where = $filter_sql;
		}
	}


	$sql = "SELECT id FROM projects " . $where . " ORDER BY date DESC, created DESC";
	
	//save sql in a session variable
	$_SESSION['sql_pp'] = $sql;
	$_SESSION['listtitle'] = $listtitle;
	$_SESSION['language'] = $language;
	$_SESSION['hidden'] = $hiddenFields;
	//save project_invlusion in a variable. This is needed bc when you go back to datamode, the inclusion input will be missing
	//$_SESSION['$project_inclusion'] = $project_inclusion;
	//echo $sql;exit;
	$spaces = array("\n", " ", "  ", "   ");
	$sql = str_ireplace($spaces, "%20", $sql);

	echo <<<PREVIEW
	<iframe id="previewIframe" src="./project_listing_print.php?title=$listtitle&language=$language&hidden=$hiddenFields" frameBorder="0"><p>Your browser does not support iframes.</p></iframe>
PREVIEW;
}


/**
 *Retrieves list of projects from the database based on a passed SQL query and passes it on to the preview page
 *
 * @param PDO $db databse to query from
 * @param string $language language to use
 * @param string $hiddenFields fields to hide in each project
 * @param array $included_projects projects that should be visible
 * @param string $sql query to execute
 * @return
 */
function retrieveProjectsPreviewRecordFormat($listtitle, $language, $hiddenFields, $project_inclusion, $filter_sql = "", $image_height = "105") {

	$ids = joinData(array_keys($project_inclusion, 0), ',');
	$where = "";
	if ($ids != "/") {
		$where = "WHERE id NOT IN (" . $ids . ")";
		//if there is also a filter query add it
		if ($filter_sql != ""){
			$where = ($where!="") ? $where." AND ".ltrim($filter_sql,"WHERE") : "";
		}
	} 
	if($where == ""){
		//if there is only a filterquery
		if ($filter_sql != ""){
			$where = $filter_sql;
		}
	}


	$sql = "SELECT id FROM projects " . $where . " ORDER BY date DESC, created DESC LIMIT 15";
	
	//save sql in a session variable
	$_SESSION['sql_pp'] = $sql;
	$_SESSION['listtitle'] = $listtitle;
	$_SESSION['language'] = $language;
	$_SESSION['hidden'] = $hiddenFields;
	$image_height = ($image_height != "" || $image_height != null) ? $image_height : "105";
	//save project_invlusion in a variable. This is needed bc when you go back to datamode, the inclusion input will be missing
	$_SESSION['$project_inclusion'] = $project_inclusion;
	//echo $sql;exit;
	$spaces = array("\n", " ", "  ", "   ");
	$sql = str_ireplace($spaces, "%20", $sql);

	echo <<<PREVIEW
	<iframe id="previewIframe" src="./project_records_print_tcpdf.php?title=$listtitle&language=$language&hidden=$hiddenFields&image_height=$image_height" frameBorder="0"><p>Your browser does not support iframes.</p></iframe>
PREVIEW;
}


/**
 * Retrieves list of projects from the database and places them on a map
 *
 * @param string $language language to use
 * @param int $projects_pp amount of projects to load
 * @param int $projects_offset where to start
 * @param array $project_inclusion array indicating which projects are selected or deselected (1/0 respectively)
 * @param string $where where clause of query
 * @param string $markerType Type of marker to display - either pins or imageicons
 * @return
 */
function retrieveProjectsMapFormat($language, $projects_pp, $projects_offset, $project_inclusion, $where = "", $markerType){
	
	include_once 'project.inc.php';
	include_once 'db.inc.php';
	include 'interface_values.inc.php';
	
	//define array of addresses
	$projectArray = array();

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT * 
			FROM projects " . $where . " ORDER BY date DESC, created DESC";
			//LIMIT " . $projects_pp . " OFFSET " . $projects_offset;
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	
	//get the query
	$query = (isset($_GET['q'])) ? "&q=".$_GET['q'] : "";
	//check if contributor or admin is logged in, set boolean to make menus visible accordingly
	$menus = ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1 && isset($_SESSION['usertype']) && ($_SESSION['usertype'] == "admin" || $_SESSION['usertype'] == "editor"))) ? "display" : "none";
	//check which markertype needs to be displayed
	$marker = ($markerType == $map_markers[1]) ? "1" : "O";

	
	while ($row = $stmt -> fetch()) {
		//number
		$projectNumber = (strlen($row['number'])<3) ? "0".$row['number'] : $row['number'];
		//define the searchable address
		$data = "['".$row['lat']."','".$row['lng']."','".$row['name']."','".APP_FOLDER.str_replace("/projectListing/", "", $row['coverimage'])."','".$row['programEN']."','".$row['id']."','".$query."','".$menus."','".$projectNumber."','".ltrim($row['iconimage'],".")."','".$marker."']";
		//add address to address array
		array_push($projectArray, $data);
	}
	$stmt -> closeCursor();
	
	$projectString = implode(",",$projectArray);
	
	//var_dump($addressString);
	
	echo <<<DRAWMAP
<script>

	function initialize()
	{
		//the list of addresses to display
		var projects = [$projectString];
		var infowindow = null;
		
		//the locations of each pin in string format "lat-long"
		var locations = [];
		
		// Create an array of styles.
		//var styles = [{"featureType":"water","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"on"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"off"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"hue":"#000000"},{"saturation":0},{"lightness":-100},{"visibility":"on"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"off"}]}];
		var styles = [{"featureType":"water","elementType":"all","stylers":[{"hue":"#000000"},{"saturation":-100},{"lightness":-6},{"visibility":"on"}]},{"featureType":"landscape","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":6},{"visibility":"off"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":6},{"visibility":"off"}]},{"featureType":"poi","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":-100},{"lightness":6},{"visibility":"off"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":6},{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"on"}]},{"featureType":"transit","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":6},{"visibility":"off"}]}];
	
		var mapProp = {
			center:new google.maps.LatLng(50.846427, 4.353235),
		    zoom:13,
		    mapTypeId:google.maps.MapTypeId.ROADMAP
		};
		
		var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
		map.setOptions({styles: styles});
		
		for(var i = 0; i < projects.length; i++){
		
			var lat = projects[i][1];
			var lng = projects[i][0];
			var iconImg = 'http://pl.urbanplatform.com' + projects[i][9];
			
			//check if pin position is already taken by another pin
			if(locations.indexOf(lat+"-"+lng) > -1){
				lng = lng - 0.001;
			}
			locations.push(lat+"-"+lng);
		
			//check whether to draw pins or images
			if (projects[i][10] === "1"){
			
				var marker=new google.maps.Marker({
				  map: map,
				  position:new google.maps.LatLng(lat, lng),
				  zIndex:1,
				  icon: iconImg,
				  //draggable: true
				});
				
			} else{
			
				var marker=new google.maps.Marker({
				  map: map,
				  position:new google.maps.LatLng(lat, lng),
				  zIndex:1,
				});
				
			}
			
			marker.setMap(map);
			
			infowindow = new google.maps.InfoWindow({
			  	content: '<table class="infowindow"><tr> <td rowspan="3"><IMG BORDER="0" ALIGN="Left" SRC="'+projects[i][3]+'" width="200px"></td><td class="title"><h2>'+projects[i][2]+'</h2></td></tr> <tr><td>'+projects[i][4]+'</td></td> <tr><td></td></td> <tr><td></td><td></td></td> </tr></table>'
			});
			
			infoBubble = new InfoBubble({
		      content: 	'<div class="infowindow"><div class="image"><IMG BORDER="0" ALIGN="Left" SRC="'+projects[i][3]+'" width="200px"></div><div class="info"><div class="title">'+projects[i][8]+' '+projects[i][2]+'</div><div class="program">'+projects[i][4]+'</div><div class="edit" style="display:'+projects[i][7]+'"><a href="./admin.php?id='+projects[i][5]+projects[i][6]+'">Edit</a></div></div></div>',
		      shadowStyle: 1,
		      padding: 0,
		      backgroundColor: 'rgb(255,255,255)',
		      borderRadius: 0,
		      borderWidth: 0,
		      disableAutoPan: true,
		      hideCloseButton: false,
		      arrowSize: 10,
		      arrowPosition: 50,
		      backgroundClassName: 'transparent',
		      arrowStyle: 0,
		    });
		    
		    //infoBubble.setCloseSrc("http://pl.urbanplatform.com/images/X.png");
			
			//code from http://stackoverflow.com/questions/11106671/google-maps-api-multiple-markers-with-infowindows
			google.maps.event.addListener(marker,'click', (function(marker,infoBubble){ 
			    return function() {
			        //infowindow.open(map,marker);
			        infoBubble.open(map, marker);
			    };
			})(marker,infoBubble)); 
			
			//on hover, place marker on top
			google.maps.event.addListener(marker, "mouseover", function() {
            	getHighestZIndex();
            	this.setOptions({zIndex:highestZIndex+1});
        	});
		}
	}

google.maps.event.addDomListener(window, 'load', initialize);


</script>
<!--<h1>UNDER CONSTRUCTION</h1>-->
<!--<div id="googleMap" style="width:1200px;height:800px;"></div>-->
<div id="googleMap" ></div>

DRAWMAP;
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
 * Decode htmlspecialchars
 * 
 * @param array $p Post data from a form
 * @return array $p
 */
 
 function convertHTMLSpecialChars($p){
 	$returnArray = array();
 	foreach($p as $key => $value){
 		//if value is an array recursively apply this function
 		if(is_array($value)){
			$returnArray[$key] = cleanData($value);
		}
		//if value is a string, clean data 
		else{
		//trips possible tags (excluding links, bold, italic, lists, paragraphs) first, then removes certain forbidden strings, then removes backslashes, removes the first pargraph tag, removes the first closing paragraph tag, then converts remaining special characters to htmlentities
 		  $returnArray[$key] =html_entity_decode(htmlspecialchars_decode($value)); 
		}
 	}
 	//return the cleaned array
 	return $returnArray;
 }

/**
 * Convert Post data to a compressed string and then returns a url containing the data as a variable "var"
 * 
 * The main page of this application contains a complex form that filters the results of the projectListing.
 * Although passing this formdata through the Post method would be simpler and cleaner,
 * having this form pass its data through url allows specific filterconfigurations to be shared 
 * between users by simply sharing the url
 * 
 * This funnction generates that url
 * 
 * @param array $post
 * @param string $url
 * @return string url with data in it
 */ 
function postToUrl($post,$url){
	//serialize the post data
	$serialized = serialize($post);
	//compress the serialized string and urlencode it
	$comp = urlencode(bzcompress($serialized, 9));
	//if the generated string exceeds the max length supported by browsers dipsplay an error
	if(strlen($comp) >4000){
		//$co = str_replace("\0", "", bzcompress($serialized, 9));
		exit("ERROR: the selection generated a url that exceeded the character limit. Contact your administrator to resolve this problem.");
	} else{
		//make and return the url
		return $url."?var=".$comp;
	}
}

/**
 * Revert the compressed urlencoded data passed through the url to the original post array
 * 
 * The main page of this application contains a complex form that filters the results of the projectListing.
 * Although passing this formdata through the Post method would be simpler and cleaner,
 * having this form pass its data through url allows specific filterconfigurations to be shared 
 * between users by simply sharing the url.
 * 
 * This function decodes the variable from that url
 * 
 * @param string $var
 * @return array 
 */ 
function urlToPost($var){
	$post = (unserialize(bzdecompress($var)));
	return $post;
}

/**
 * Gets Latitude/longitude coordinates from an address
 * by: http://pl.urbanplatform.com/test.php
 * 
 * @param string $string The address for which you need coordinates
 * @return array Array containing 'latitude', 'longitude', 'location_type'
 */
function lookup($string){
 	
   $string = str_replace (" ", "+", urlencode($string));
   $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";
 
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $details_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $response = json_decode(curl_exec($ch), true);
 
   // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
   if ($response['status'] != 'OK') {
    return null;
   }
 
   //print_r($response);
   $geometry = $response['results'][0]['geometry'];
 
    $longitudeResponse = $geometry['location']['lat'];
    $latitudeResponse = $geometry['location']['lng'];
 
    $array = array(
        'latitude' => $geometry['location']['lng'],
        'longitude' => $geometry['location']['lat'],
        'location_type' => $geometry['location_type'],
    );
 
    return $array;
 
}

/**
 * Converts location to coordinates for specific project
 * 
 * @param int the project Id
 * @return
 */
 function convertLocation($id){
 	
 	include_once 'project.inc.php';
	include_once 'db.inc.php';
 	
 	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT * FROM projects WHERE id=? LIMIT 1";
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute(array($id));
	
	//save the returned array
	$row = $stmt -> fetch();
	$stmt -> closeCursor();
		
	$country = ($row['countrycode'] == "ES") ? "Spain" : ($row['countrycode'] == 'MA') ? "Morocco" : $row['countrycode'];
	//define the searchable address
	$projectAddress = $country." ".$row['cityEN']." ".$row['city_pcode']." ".$row['street']." ".$row['street_number'];
	//add address to address array
	$coordinates = lookup($projectAddress);
	//echo $row['name'];
	//echo "lat: ".$coordinates['latitude']."-lng: ".$coordinates['longitude'];
	
	//Open a database connection and store it
	$dab = new PDO(DB_INFO, DB_USER, DB_PASS);
	
	$sql = "UPDATE projects SET lat=?, lng=? WHERE id=? LIMIT 1";
	if ($stamt = $dab -> prepare($sql)) {
		$A = array($coordinates['latitude']."", $coordinates['longitude']."", $row['id']);
		$stamt -> execute($A);
		$stamt -> closeCursor();
	} else{
		echo "ERROR UPDATING FIELD";exit;
	}
 }


/**
 * Print login or user popup
 * 
 * @param boolean $loggedIN
 * @param boolean $ isAdmin
 * @return
 */
 function printLogin($loggedIn = FALSE, $isAdmin = FALSE, $sitewide = FALSE){
 	//opening tags
 	$loginID = ($sitewide && $loggedIn == FALSE) ? "login_form_sitewide":"login_form";
 	$returnHTML = <<<OPENTAGS
 					<div id="$loginID">
OPENTAGS;
	
	//add closing button
	if(!($sitewide && $loggedIn == FALSE)){
	$returnHTML .= <<<CLOSE
					<a id="hide_login" onclick="swapDisplay('login_form_wrapper', 'none')">
						X
					</a>
CLOSE;
	
	}
	
	//login form
	if(!$loggedIn){
		$userName = "";
		$errorVisibility = "none";
		if(isset($_GET['loginError']) && isset($_GET['usernameAttempt'])){
			$errorVisibility = ($_GET['loginError']) ? "block" : "none";
			$userName = $_GET['usernameAttempt'];
		}
		$returnHTML .= <<<LOGIN
						<form id="login_inputform"  action="./inc/update.inc.php" method="post">
							<fieldset>
								<div>
									<div class="login_input">
										<span>NAME</span><input class="large" name="login_name" type="text" value="$userName"/>
									</div>
									<div class="login_input">
										<span>PASSWORD</span><input class="large" name="login_password" type="password"/> 
									</div>
									<span class="red" id="loginError" style="display:$errorVisibility">*wrong username or password</span>
									<input type="hidden" name="action" value="login"/>
									<input id="login_submit" type="submit" name="login" value="LOG IN"/>
								</div>
							</fieldset>
						</form>
LOGIN;
	}
	
	//print user authorisation info depending on their usertype
	if($loggedIn){
		$n = ucfirst ($_SESSION['username']);
		$t = ucfirst ($_SESSION['usertype']);
		switch ($_SESSION['usertype']) {
			
			case 'admin':
				$authorization = <<<AU
				<li>Authorization: 
				<ul>
					<li>create project listings</li>
					<li>add/edit/delete projects</li>
					<li>add new users</li>
				</ul>
			</li>
AU;
		
				break;
				
			case 'editor':
				$authorization = <<<AU2
				<li>Authorization: 
				<ul>
					<li>create project listings</li>
					<li>add/edit/delete projects</li>
				</ul>
			</li>
AU2;
		
				break;
			
			default:
				$authorization = <<<AU3
				<li>Authorization: 
				<ul>
					<li>create project listings</li>
				</ul>
				</li>
AU3;
				
				break;
		}
		$returnHTML .= <<<INFO
		<ul>
			<li>Username: $n </li>
			<li>Account Type: $t</li>
			$authorization
		</ul>
INFO;
 	
	}
	
	//print extra functions if admin
	if($loggedIn && $isAdmin){
		$returnHTML .= <<<ADD
						<hr>
						<div class="red">CREATE NEW USER</div>
						<form id="createuser_inputform" action="./inc/update.inc.php" method="post">
							<fieldset>
								<div></br>
									<div class="login_input">
										<span>NAME</span><input class="large" name="login_name" type="text"/>
									</div>
									<div class="login_input">
										<span>PASSWORD</span><input class="large" name="login_password" type="password"/>
									</div>
									<div class="login_input">
										<span>TYPE</span>
										<select name="usertype">
											<option value="contributor">Contributor &nbsp;<span class="gray">(create lists)</span></option>
											<option value="editor">Editor &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class="gray">(+edit projects)</span></option>
											<option value="admin">Admin &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="gray">(+edit users profiles)</span></option>
										</select>
									</div>
									<input type="hidden" name="action" value="create_user"/>
									<input id="login_submit" type="submit" name="login" value="CREATE"/>
								</div>
							</fieldset>
						</form>
						<hr>
						<div class="red">BACKUP DATA</div>
						<ul id="backup">
							<li>
								<a class="button" href="./inc/functions.inc.php?dumpMySQL=1">
									<span>MySQL dump &darr;</span>
								</a>
								<a class="button"  href="./inc/functions.inc.php?dumpExcel=1">
									<span>Excel dump &darr;</span>
								</a>
							</li>
							<li>
							</li>
							<li>
							</li>
						</ul>
ADD;
	}

	if($loggedIn){
		$returnHTML .= <<<LOGOUT
		<hr>
		<form id="logout_inputform" action="./inc/update.inc.php" method="post">
			<fieldset>
				<input type="hidden" name="action" value="logout"/>
				<input id="logout_submit" type="submit" name="logout" value="LOG OUT"/>
			</fieldset>
		</form>
LOGOUT;
	}
	
	$returnHTML .= <<<CLOSE
						</div>
CLOSE;

	echo $returnHTML;
 }
 
 
 /**
  * Creates a MySQL dump backup and downloads it as a zip
  * 
  * @param
  * @return
  */
  function dumpMySQL(){
  	include_once './db.inc.php';

	$return_var = NULL;
	$output = NULL;
	$date = date("Y")."-".date("m")."-".date("d")."_".date("H")."h".date("i");
	$filename = "mysqldump_".$date.".sql";
	$location = "../files/sql_backup/".$filename;
	$command = "mysqldump -u ".DB_USER." -p".DB_PASS." urbanpla_project_listing > ".$location;
	exec($command, $output, $return_var);
	
	if($return_var) { 
		echo "there was an error code: ".$return_var.", see the ".$output; 
	} else{
		//http://stackoverflow.com/questions/1754352/download-multiple-files-as-zip-in-php
		$files = array($location);
		$zipname = 'mysqldump_'.$date.'.zip';
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach ($files as $file) {
		  $zip->addFile($file, $filename);
		}
		$zip->close();
		
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: '.filesize($zipname));
		readfile($zipname);
		
		unlink($zipname);
	}
  }
  
 /**
  * Creates a dump backup of all proejct data in and excell file
  * 
  * @param
  * @return
  */ 
  function dumpProjectsExcel(){
  	// Original PHP code by Chirp Internet: www.chirp.com.au
	// Please acknowledge use of this code by including this header.
	// http://www.the-art-of-web.com/php/dataexport/

	include_once './db.inc.php';

	//Open a database connection and store it
	$db = new PDO(DB_INFO, DB_USER, DB_PASS);

	//compose sql query
	$sql = "SELECT * FROM projects";
	$stmt = $db -> prepare(html_entity_decode($sql));
	$stmt -> execute();
	
	//define array to store projects
	$data = array();
	
	//loop through each project
	while ($row = $stmt -> fetch()) {
		//define empty array to store project fields
		$projectArray = array();
		//loop thorugh each field and add it to an associative array
		for($i = 0; $i < count($row); $i++) {
			$c = array_slice($row, $i);
			$Hkey = key($c);
			$Hvalue = array_values($c)[0];
			
			$Hvalue = utf8_decode(af(htmlspecialchars_decode(html_entity_decode($Hvalue,ENT_QUOTES,'UTF-8'))));
			
			$projectArray[$Hkey] = $Hvalue;
		}
		//push project to array
		array_push($data, $projectArray);
	}
	$stmt -> closeCursor();
	
	function cleanDataExcel(&$str)
	{
		// escape tab characters
	    $str = preg_replace("/\t/", "\\t", $str);
	
	    // escape new lines
	    $str = preg_replace("/\r?\n/", "\\n", $str);
	
	    // convert 't' and 'f' to boolean values
	    if($str == 't') $str = 'TRUE';
	    if($str == 'f') $str = 'FALSE';
	
	    // force certain number/date formats to be imported as strings
	    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
	      $str = "$str";
	    }
	
	    // escape fields that include double quotes
	    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}
	
	//prep filename
	$date = date("Y")."-".date("m")."-".date("d")."_".date("H")."h".date("i");
	$filename = "PL_ExcelDump_".$date.".xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");
	
	$flag = false;
	foreach($data as $row) {
		if(!$flag) {
			// display field/column names as first row
			echo implode("\t", array_keys($row)) . "\n";
			$flag = true;
		}
		array_walk($row, 'cleanDataExcel');
		echo implode("\t", array_values($row)) . "\n";
	}
  }
 
 /**
  * Apostophefix this string
  * 
  * @param string $word the string to fix
  * @return string the fixed string
  */
  function af($word) {
    $word = str_replace("&#039;","'",$word); 
    $word = str_replace("&amp;","&",$word);
    $word = str_replace("&#128;","€",$word);
    $word = str_replace("Ã©","é",$word);
    $word = str_replace("Ã¨","è",$word);
	return $word;
  }
?>
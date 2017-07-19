<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
//Get name of book to display
$targetBook = $_GET["title"];
//Get name of chapter to display
$targetChap = $_GET["chap"];

//Get MongoDB config info
include_once("config.php");
//Establish connection to MongoDB
$connection = new MongoClient();
//Load Mongo database
$db = $connection->$mongodb;
//Load Mongo collection
$collection = $db->$mongocollection;

//Construct page's header
//Query collection and return document that has the book to display
$targetDoc = $collection->findOne(array('titleID' => $targetBook));
//Store the "title" from the queried document
$bookTitle = $targetDoc["title"];
//Get all the chapter info for the target chapter
//Loop through all chapters until find the matching $targetChap
//Once found, store all the info (title, order, and file name) in appropriate variables
for($i = 0; $i < count($targetDoc["chapters"]); $i++){
	if($targetDoc["chapters"][$i]["file"] === $targetChap){
		//Store target file name
		$targetFile = $targetDoc["chapters"][$i]["file"];
		//Also store target chapter title
		$targetTitle = $targetDoc["chapters"][$i]["chapTitle"];
		//Also store the order of the target chapter
		$targetOrder = $targetDoc["chapters"][$i]["order"];
	}
}
//Put book title and chapter in the header
echo "<title>$bookTitle | $targetTitle</title>";
?>
<!-- BOOTSTRAP -->
<?php
echo "<link href=\"" . $base_url . "bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">";
?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<!-- STYLE MODIFICATIONS -->
<?php
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $base_url . "css/viewbook.css\">";
echo "<link rel=\"stylesheet\" media=\"print\" type=\"text/css\" href=\"" . $base_url . "css/print.css\">";
?>
</head>
<body>
<?php
//Add Google Analytics tracker
include_once($google_analytics);
?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="container-fluid">
<div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#viewbook-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
</div>
<div class="collapse navbar-collapse" id="viewbook-navbar-collapse">
<!-- Leftside of navbar -->
<ul class="nav navbar-nav navbar-left">
<?php
//Include link to return to book's description page (titleID is the book's parent URL)
echo "<li id=\"home_btn\"><a href=\"" . $return_url . $targetDoc["titleID"] . "/\">" . "Return to " . $site_name . "</a></li>";
?>
<!-- Create dropdown menu of book's TOC -->
<li class="dropdown" id="toc_btn">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Table of Contents&nbsp;<span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
<?php
        for($i = 0; $i < count($targetDoc["chapters"]); $i++){
                if($targetDoc["chapters"][$i]["chapTitle"] === $targetTitle){
                        echo "<li class=\"active\"><a href=\"#\">" . $targetDoc["chapters"][$i]["chapTitle"] . "<span class=\"sr-only\">(current)</span></a></li>";
                }
                else {
                        echo "<li><a href=\"" . $base_url . $targetDoc["titleID"] . "/" . $targetDoc["chapters"][$i]["file"] . "
\">" . $targetDoc["chapters"][$i]["chapTitle"] . "</a></li>";
                }
        }
?>
        </ul>
</li>
</ul><!-- ./navbar-left -->
<!-- Rightside of navbar -->
<ul class="nav navbar-nav navbar-right">
<?php
//Test if there is a previous chapter or not
if($targetOrder > 0){
	//Include link to previous chapter
	echo "<li id=\"prev_btn\"><a href=\"" . $base_url . $targetDoc["titleID"] . "/" . $targetDoc["chapters"][$targetOrder-1]["file"] . "\"><span class=\"glyphicon glyphicon-menu-left\"></span>&nbsp;Previous Chapter</a></li>";
}
//If no previous chapter, include an empty button
else {
	echo "<li class=\"blank_nav\" id=\"prev_btn\"></li>";
}
//Test if there is a next chapter or not
if($targetOrder  < (count($targetDoc["chapters"]) - 1)){
	//Include link to next chapter
	echo "<li id=\"next_btn\"><a href=\"" . $base_url . $targetDoc["titleID"] . "/" . $targetDoc["chapters"][$targetOrder+1]["file"] . "\">Next Chapter&nbsp;<span class=\"glyphicon glyphicon-menu-right\"></span></a></li>";
}
//If no next chapter, include an empty button
else {
	echo "<li class=\"blank_nav\" id=\"next_btn\"></li>";
}
?>
</ul><!-- ./navbar-right -->
</div> <!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
<!-- CHAPTER CONTENT -->
<div class="container">
<?php
//Embed chapter's html file referenced by the directory and html file
include($targetDoc["titleID"] . "/" . $targetFile . ".html");
?>
</div>
<!-- BOOTSTRAP JAVASCRIPT -->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
<?php
echo "<script src=\"" . $base_url . "bootstrap/js/bootstrap.min.js\"></script>";
?>
</body>
</html>

<?php

// A1LIB IS A RESOURCE FOR COMMON CODE FOUND ON MORE THAN ONE PAGE

// FUNCTION CONNECTS TO THE DATABASE`
function dbconnect() {
	$lines = file('../../secret/connect.txt');
	$dbserver = trim($lines[0]);
	$uid = trim($lines[1]);
	$pw = trim($lines[2]);
	$dbname = trim($lines[3]);
	
	$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
		or die ('Could NOT connect to the server" ' . mysql_error($link));
	
	return $link;
} // end funciton

function head() {
	echo '
	        <img src="flags1.jpg" height="25%" />
	        <h1>The Flag Emporium</h1>
	        <hr />
	        <a style="padding:20px;" href="add.php">Add</a>          <a href="view.php">View All</a>
	        <hr />
	     ';	
} // end function

function copywrite() {
	echo '
		 <p style="font-size:12px;">Copyright &copy; 20155555 - All Rights Reserved -
        	<a href="http://www.scotchmob.com">Scotchmob.com</a></p>
	     ';
}

?>

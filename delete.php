<?php
/*
THIS PART OF THE APPLICATION DISPLAYS ALL THE RECORDS CURRENTLY IN THE DATABASE.
*/
	require 'a2lib.php';
    require 'myClasses.php';

	startSession();
	$delete = $_GET['deleted'];
	if($delete =="n")
		$delete ="y";
	else if($delete =="y")
		$delete ="n";
	$id = $_GET['id'];
	
// CONNECT TO THE SERVER WITH UN AND PW 
	$db = new DBlink("int322_151b18");
	
// CREATE UPDATE QUERY STORING RESULT IN VAR
	$k = $db->query( 'UPDATE inventory SET deleted="' . $delete . '" WHERE id="' . $id . '" ');

// redirect to view.php
header("Location:view.php");

?>



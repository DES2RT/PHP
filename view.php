<?php 
/*
THIS PART OF THE APPLICATION DISPLAYS ALL THE RECORDS CURRENTLY IN THE DATABASE.
*/
// make require calls
	require("a2lib.php");
	require("myClasses.php");

// SECURITY FIRST
	$timeout = 3 * 60; // 3 minutes
	$fingerprint = md5('SECRET-SALT'.$_SERVER['HTTP_USER_AGENT']);
	session_start();
	
	if(!isset($_SESSION['user'])) 
		header("Location:logout.php");
		
	if( (isset($_SESSION['lastActive'])) ) {
		if(time() > ($_SESSION['lastActive']+$timeout)) {
			header("Location:logout.php");
		}
		if(isset($_SESSION['fingerprint']) && $_SESSION['fingerprint']!=$fingerprint)
			header("Location:logout.php");
	}
	session_regenerate_id(); 
	$_SESSION['lastActive'] = time();
	$_SESSION['fingerprint'] = $fingerprint;

/*******************************************/

// process the search query submitted from the user in the menu. and display results
// start checking form input
	$search="";
	$searchErr="";
	$result ="";
	$order ="";
	if($_POST) {
	  if(isset($_POST['search'])) {
		$search = $_POST['search'];
		if($search === "")// || (!preg_match("/^(\s*)(\w{3,30})(\s*)$/", $search)))
			header("Location:view.php");
        else {
            //echo $search;
		    $db = new DBlink('int322_151b18');
		    $result = $db->query("SELECT * FROM inventory WHERE description LIKE '%($search)%' ");
            //var_dump($result);
		    if(mysqli_num_rows($result) ===0) {
                $searchErr = "No records found that match your search.<br>";
            }
		}
	  }	// end 'isset search'
	}	// end 'if post'
	
	// process the order request by the user. the inventory is ordered for display
    // by the users preference which is stored in a cookie
	elseif($_GET)  {
		if(isset($_GET['order']))
			$order = $_GET['order'];
		setcookie('OrderSort', $_GET['order'], time()+3600*24*30);
	}
		if(isset($_COOKIE['OrderSort']))
			$order = $_COOKIE['OrderSort'];
		
	//	echo "order option is ".$order."<br>";
	//	echo "Sort option is ".$order."<br>";
		$db = new DBlink('int322_151b18');
		if($order === 'id')
			$result = $db->query('SELECT * FROM inventory ORDER BY id ASC');
		if($order === 'iName')
			$result = $db->query('SELECT * FROM inventory ORDER BY itemName ASC');
		elseif($order === 'desc')
			$result = $db->query('SELECT * FROM inventory ORDER BY description ASC');
		elseif($order === 'supplier')
			$result = $db->query('SELECT * FROM inventory ORDER BY supplierCode ASC');
		elseif($order === 'cost')
			$result = $db->query('SELECT * FROM inventory ORDER BY price ASC');
		elseif($order === 'price')
			$result = $db->query('SELECT * FROM inventory ORDER BY price ASC');
		elseif($order === 'onHand')
			$result = $db->query('SELECT * FROM inventory ORDER BY onHand ASC');
		elseif($order === 'reOrder')
			$result = $db->query('SELECT * FROM inventory ORDER BY reOrderPoint ASC');
		elseif($order === 'backOrder')
			$result = $db->query('SELECT * FROM inventory ORDER BY backOrder ASC');

	
	else {
		$db = new DBlink('int322_151b18');
		$result = $db->query('SELECT * FROM inventory');
	}
		// DISPLAY THE RESULTS 
		htmlHeader();
		topPage($_SESSION['user'], $_SESSION['role'],$search);
		printViewTH($searchErr);

		while($row = mysqli_fetch_assoc($result))
 		{
?>
	<tr>
	  <td><a href="add.php?id=<?php print $row['id'];?>"><?php print $row['id'];?></a></td>
	  <td style="padding:3px 10px;"><?php print $row['itemName']; ?></td>
	  <td><?php print $row['description']; ?></td>
	  <td><?php print $row['supplierCode']; ?></td>
	  <td><?php print $row['cost'];?></td>
	  <td><?php print $row['price'];?></td>
	  <td><?php print $row['onHand'];?></td>
	  <td><?php print $row['reorderPoint'];?></td>
	  <td><?php print $row['backOrder'];?></td>
	  <td>
		<a href="delete.php?id=<?php print $row['id']; ?>&deleted=<?php print $row['deleted'];?>"><?php if($row['deleted'] =="n") echo "Delete"; elseif($row['deleted'] =="y") echo "Restore"; ?>
	  </td>
	</tr>
<?php
 		}
		printViewFooter();
	
	
?>


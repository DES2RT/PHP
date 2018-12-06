<html>
<body>

<?php

$itemName;
$nameErr = "";
$description;
$descriptionErr = "";
$supplierCode;
$supplierCodeErr = "";
$cost;
$costErr = "";
$price;
$priceErr = "";
$onHand;
$onHandErr = "";
$reorderPoint;
$reorderPointErr = "";
$backOrder;
$backOrderErr = "";
$dataValid = true;

// If submit with POST
if ($_POST) { 
        // Test for nothing entered in field
	if (isset($_POST['name']))	{
		$itemName = $_POST['name'];
		if( !(preg_match("/^(\s*)[a-zA-Z0-9;,:\s'-].*(\s*)$/", $itemName) ) )	
		{
		  	$nameErr = "Error - Please enter your name";
			$dataValid = false;
		}
		else
			trim($itemName);
	}
	
	if ( isset($_POST['description'] ) )	{
		$description = $_POST['description'];
		if( ( !preg_match("/^(\s*)([A-Za-z][^0-9].*)(\s*)$/", $description) ) )
		{
			$descriptionErr = "Error - Please fill in a valid description";
			$dataValid = false;
		}
		else
			trim($description);
	}
	
	if ( isset($_POST['supplierCode'] ) )	{
		$supplierCode = $_POST['supplierCode'];
		if( ( !preg_match("/^(\s*)([A-Za-z][^0-9].*)(\s*)$/", $supplierCode) ) )
		{
			$supplierCodeErr = "Error - Please fill in a valid supplier code";
			$dataValid = false;
		}
		else	
			trim($supplierCode);
	}
	
	if ( isset( $_POST['cost']) ) {
		$cost = $_POST['cost'];
		if($cost == "")	{
			$costErr = "Error - Please fill in a valid cost";
			$dataValid = false;		
		}
		else
			trim($cost);
	}
	
	if ( isset ($_POST['price']) )	{
		$price = $_POST['price'];
		if ($price == "")	{
			$priceErr = "Error - Please enter a valid price";
			$dataValid = false;
		}
		else
			trim($price);
	}
	
	if ( isset ($_POST['onHand']) )	{
		$onHand = $_POST['onHand'];
		if ( $onHand == "" && ( !preg_match("/^(\s*)[0-9].*(\s*)$/", $onHand)) )
		{
			$onHandErr = "Error - Please enter a valid On Hand quantity";
			$dataValid = false;
		}
		else
			trim($onHand);
 	}
	
	if (isset($_POST['reorderPoint']))	{
		$reorderPoint = $_POST['reorderPoint'];
		if($reorderPoint == ""  && ( !preg_match("/[0-9].*/", $onHand)) )  {
			$reorderPointErr = "Error - Please enter Re-Order Point";
			$dataValid = false;
		}
		else
			trim($reorderPoint);
	}
	
	if ( isset($_POST['backOrder']) )	{
		$backOrder = $_POST['backOrder'];
	}
	else
		$backOrder = "n";
}
// If the submit button was pressed and something was entered in all fields, process data
if ($_POST && $dataValid) { 
?>

<?php
// CONNECT TO THE SERVER WITH UN AND PW FROM FILE
	$lines = file('/home/int322_151b18/apache/secret/connect.php');
	$dbserver = "db-mysql";//trim($lines[0]);
	$uid = "int322_151b18"; //trim($lines[1]);
	$pw = "djBM6546"; //trim($lines[2]);
	$dbname = "int322_151b18";//trim(lines[3]);
 
	$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
		or die ('Could not connect to server: ' . mysql_error($link));
	if($link){ echo "Connection Success <br />";
	}
// SELECT THE DB FROM THE CONNECTION
//	mysqli_select_db($link, "test") 
//		or die ('Could not connect to DB: ' . mysql_error());

// CREATE INSERT QUERY STORING RESULT IN VAR
	$query = 'INSERT INTO inventory 
			(itemName, description, supplierCode, cost, price, onHand, reorderPoint, backOrder) 
		VALUES ("'.$itemName.'","'.$description.'","'.$supplierCode.'","'.$cost.'","'.$price.'", "'.$onHand.'", "'.$reorderPoint.'", "'.$backOrder.'") ';
//	itemName="'.$itemName.'", description="'.$description.'", supplierCode="'.$supplierCode.'", cost="'.$cost.'", price="'.$price.'", onHand="'.$onHand.'", reorderPoint="'.$reorderPoint.'", backOrder="'.$backOrder.'" ';
		
// RUN QUERY STORING RESULT IN VAR
	mysqli_query($link, $query)
		or die ( 'Could Not Insert ' . mysqli_error($link));
		
// CREATE SELECT QUERY STORING RESULT IN VAR
	$query = 'SELECT * FROM inventory';

// RUN QUERY STORING RESULT IN VAR
	$result = mysqli_query($link, $query)
		or die ( 'Could Not Select Data ' . mysql_error());

// PRINT THE RESULT OF QUERY
	
?>

<html>
<body>
<table border="1">
	<tr>
	  <th>Title</th><th>First Name</th><th>Last Name</th><th>Organisation</th><th>Email</th><th>Phone</th><th colspan="2">Attending</th><th>T-Shirt Size</th>
	</tr>
<?php 	
		while($row = mysqli_fetch_assoc($result))
 		{
?>
	<tr>
	  <td><?php print $row['title'];?></td>
	  <td><?php print $row['fName']; ?></td>
	  <td><?php print $row['lName']; ?></td>
	  <td><?php print $row['org']; ?></td>
	  <td><?php print $row['email'];?></td>
	  <td><?php print $row['phone'];?></td>
	  <td><?php print $row['attendMon'];?></td>
	  <td><?php print $row['attendTue'];?></td>
	  <td><?php print $row['size'];?></td>
  	  <td><?php print $row['resNum'];?></td>
	  <td><a href="change.php?resNum=<?php print $row['resNum'];?>&attendMon=<?php print $row['attendMon'];?>&attendTue=<?php print $row['attendTue'];?> ">Cancel</a></td>
		</tr>
<?php
 		}

?>
</table>
  <a href="form.php" name="backtoform">Back To Form</a>
</body>
</html>

<?php
		
// CLOSE DB CONNECTION		
	mysqli_close($link);
	
?>

<?php
// If no submit or data is invalid, print form, repopulating fields and printing err mesgs
} else { 
?>
	<img src="flags1.jpg" height="25%"/>
	<h1>Flags Emporium</h1>
	<hr />
	<a style="padding:20px;" href="add.php">Add</a>          <a href="view.php">View All</a>
	<hr />
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	<table>
	  <tr>
	    <td valign="top">Name:</td>
	    <td><input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
	    </td>
		<td><?php echo $nameErr;?></td>
	  </tr>
	  <tr>
	    <td>Description:</td>
	    <td><input type="textarea" name="description" value="<?php if (isset($_POST['description'])) echo $_POST['description']; ?>">
	    </td>
	    <td><?php echo $descriptionErr;?>
	    </td>
	  </tr>
	  <tr>
	    <td>Supplier Code:</td>
	    <td><input type="text" name="supplierCode" value="<?php if (isset($_POST['supp'	])) echo $_POST['supplierCode']; ?>">
	    </td>
	    <td><?php echo $supplierCodeErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Cost</td>
	    <td><input type="text" name="cost" value="<?php if(isset($_POST['cost'])) echo $_POST['cost']; ?>">
	    </td>
	    <td><?php echo $costErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Price:</td>
	    <td><input type="text" name="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>">
	    </td>
	    <td><?php echo $priceErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>On Hand:</td>
	    <td><input type="text" name="onHand" value="<?php if(isset($_POST['onHand'])) echo $_POST['onHand']; ?>">
			</td>
	    <td><?php echo $onHandErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>ReOrder Point:</td>
	    <td><input type="text" name="reorderPoint" value="<?php if(isset($_POST['reorderPoint'])) echo $_POST['reorderPoint']; ?>">
	    </td>
	    <td>	<?php echo $reorderPointErr; ?></td>
	  </tr>
	  <tr>
	    <td>Back Order:</td>
	    <td><input type="checkbox" name="backOrder" value="y">
	    </td>
	    <td>	<?php echo $backOrderErr; ?></td>
	  </tr>
	  <tr><td><br /></td></tr>
	  <tr>
	    <td><br /></td>
	    <td><input type="submit" name="submit"></td>
	  </tr>
	</form>
	</table>
<?php
}
?>
</body>
</html>


<?php 
/*


THIS PAGE CONTAINS THE LOGIC FOR PROCESSING THE FORM SUBMITTED FOR ADDING ITEMS TO
INVENTORY
*/

// SECURITY FIRST
	require("a2lib.php");
	require("myClasses.php");
	// session info
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
/***************************************************/

// FORM PROCESSING
// declare variables
$name;
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
if(isset($_POST['deleted'])) 
	$delete = $_POST['deleted'];
$dataValid = true;
$search;
$searchErr = "";
/********************************/

// PROCESS THE FORM STORING INPUT INTO VARIABLES
// If form submit with POST
if ($_POST) { 	
	
   // Test for valid data entered in form fields and create error messages if data
   // is invalid
	if (isset($_POST['name']))	{
		$name = trim($_POST['name']);
		if( $name == "" || ( !preg_match("/^(\s*)[A-Za-z0-9;:,'\s-].*(\s*)$/", $name) ) )
		{
		  	$nameErr = "Error - Please enter your name";
			$dataValid = false;
		}
	}
	
	if ( isset($_POST['description'] ) )	{
		$description = trim($_POST['description']);
		if( $description == "" || ( !preg_match("/^(\s*)[A-Za-z0-9\.,'\s-].*(\s*)$/", $description) ) )
		{
			$descriptionErr = "Error - Please fill in a valid description";
			$dataValid = false;
		}
	}
	
	if ( isset($_POST['supplierCode'] ) )	{
		$supplierCode = trim($_POST['supplierCode']);
		if( $supplierCode == "" || ( !preg_match("/^(\s*)[A-Za-z0-9-\s].*(\s*)$/", $supplierCode) ) )
		{
			$supplierCodeErr = "Error - Please fill in a valid supplier code";
			$dataValid = false;
		}
	}
	
	if ( isset( $_POST['cost']) ) {
		$cost = trim($_POST['cost']);
		if($cost == "" || ( !preg_match("/^[0-9].*(\.)[0-9]{2}$/", $cost) ) )	
		{
			$costErr = "Error - Please fill in a valid cost ($#.##)";
			$dataValid = false;		
		}
	}
	
	if ( isset ($_POST['price']) )	{
		$price = trim($_POST['price']);
		if ($price == "" || ( !preg_match("/^[0-9].*(\.)[0-9]{2}$/", $price) ) )	
		{
			$priceErr = "Error - Please enter a valid price ($#.##)";
			$dataValid = false;
		}
	}
	
	if ( isset ($_POST['onHand']) )	{
		$onHand = trim($_POST['onHand']);
		if ( $onHand == "" || ( !preg_match("/^(\s*)[0-9].*(\s*)$/", $onHand)) )
		{
			$onHandErr = "Error - Please enter a valid On Hand quantity (digits only)";
			$dataValid = false;
		}
 	}
	
	if (isset($_POST['reorderPoint']))	{
		$reorderPoint = trim($_POST['reorderPoint']);
		if($reorderPoint == ""  || ( !preg_match("/^(\s*)[0-9].*(\s*)$/", $reorderPoint)) )  {
			$reorderPointErr = "Error - Please enter valid Re-Order Point (digits only)";
			$dataValid = false;
		}
	}
	
	if ( isset($_POST['backOrder']) )	{
		$_POST['backOrder'] = "y";
		$backOrder = $_POST['backOrder'];
	}
	else 	{
		$_POST['backOrder'] = "n";
		$backOrder = $_POST['backOrder'];
	}
/*****************************************************************/
// 

	if(isset($_POST['modsubmit'])) {
		$db = new DBlink('int322_151b18');
		$id = $_POST['id'];
	    $result = $db->query("UPDATE inventory 
				SET itemName='".$name."',description='".$description."',supplierCode='".$supplierCode."',cost='".$cost."',price='".$price."',onHand='".$onHand."',reorderPoint='".$reorderPoint."',backOrder='".$backOrder."' WHERE id = $id");

	header("Location:view.php");

  } // end 'if post modsubmit'
}	// end 'if post'

// If the submit button was pressed and something was entered in all fields, process data
if (isset($_POST['submit']) && $dataValid) { 
	
		$db = new DBlink('int322_151b18');	//connect to db and run query
		$results = $db->query('INSERT INTO inventory (itemName, description, supplierCode, cost,price,onHand,reorderPoint,backOrder,deleted)		
		VALUES("'.$name.'","'.$description.'","'.$supplierCode.'","'.$cost.'","'.$price.'","'.$onHand.'","'.$reorderPoint.'","'.$backOrder.'","n")'); 
		if($results) {
			//echo "Insert Completed Successfully<br>";
		    // redirect to view.php
	        header("Location:view.php");
        }
}	// end 'if post & datavalid'

elseif($_GET) {
	$db = new DBlink('int322_151b18');
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	}
	$result = $db->query("SELECT * FROM inventory WHERE id 	= $id");
	
	htmlHeader();
	topPage($_SESSION['user'], $_SESSION['role'],'');
	while($row = mysqli_fetch_assoc($result))
	{
?>

	<form method="post" action="add.php">
	<h2 class='db'>Modify Selection</h2>
	<table>
	  <tr>
	    <td>ID:</td>
		<td><input type="text" name="id" value="<?php echo $row['id']; ?>" readonly="readonly"></td>
	  </tr>
	  <tr>
	    <td>Name:</td>
	    <td> <input type="text" name="name" value="<?php echo $row['itemName']; ?>">
	    </td>
		<td class="err"><?php echo $nameErr;?></td>
	  </tr>
	  <tr>
	    <td>Description:</td>
	    <td> <input type="textarea" name="description" value="<?php echo $row['description']; ?>">
	    </td>
		<td class="err"><?php echo $descriptionErr;?>
	    </td>
	  </tr>
	  <tr>
	    <td>Supplier Code:</td>
	    <td> <input type="text" name="supplierCode" value="<?php echo $row['supplierCode']; ?>">
	    </td>
		<td class="err"><?php echo $supplierCodeErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Cost</td>
	    <td><input type="text" name="cost" value="<?php echo $row['cost']; ?>">
	    </td>
		<td class="err"><?php echo $costErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Price:</td>
	    <td><input type="text" name="price" value="<?php echo $row['price']; ?>">
	    </td>
		<td class="err"><?php echo $priceErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>On Hand:</td>
	    <td> <input type="text" name="onHand" value="<?php echo $row['onHand']; ?>">
		</td>
		<td class="err"><?php echo $onHandErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td><label for="rO">ReOrder Point:</label></td>
	    <td> <input type="text" name="reorderPoint" id="rO" value="<?php echo $row['reorderPoint']; ?>">
	    </td>
		<td class="err"><?php echo $reorderPointErr; ?></td>
	  </tr>
	  <tr>
	    <td><label for="bO">Back Order:</label></td>
	    <td class="bo"> <input type="checkbox" name="backOrder" id="bO" value="<?php if(isset($_POST['backOrder'])) echo 'checked'; ?>">
	    </td>
	    <td></td>
	  </tr>
<?php 
		}	// end while loop
?>
	  <tr><td><br /></td></tr>
	  <tr>
	    <td><br /></td>
	    <td><input type="submit" name="modsubmit" value="Submit"></td>
	  </tr>
	</form>
	</table>
	</div>	<!-- end of left -->
<div id="bright">
 <p>Copyright &copy; 2014 - All Rights Reserved - 
	<a href="http://www.scotchmob.com">Scotchmob.com</a></p>
</div>		<!-- end of bright -->
</div>  <!-- end page -->    
</body>

</html>
<?php
	}	// end 'if get'

// If no submit or data is invalid, print form, repopulating fields and printing err mesgs
	else { 
	htmlHeader();
	topPage($_SESSION['user'], $_SESSION['role'],'');
?>
	<div id="left" class="add">

	<form method="post" action="add.php">
	<table>
	  <tr>
	    <td>Name:</td>
	    <td> <input type="text" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
	    </td>
		<td class="err"><?php echo $nameErr;?></td>
	  </tr>
	  <tr>
	    <td>Description:</td>
	    <td> <input type="textarea" name="description" value="<?php if (isset($_POST['description'])) echo $_POST['description']; ?>">
	    </td>
		<td class="err"><?php echo $descriptionErr;?>
	    </td>
	  </tr>
	  <tr>
	    <td>Supplier Code:</td>
	    <td> <input type="text" name="supplierCode" value="<?php if (isset($_POST['supplierCode'])) echo $_POST['supplierCode']; ?>">
	    </td>
		<td class="err"><?php echo $supplierCodeErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Cost</td>
	    <td><input type="text" name="cost" value="<?php if(isset($_POST['cost'])) echo $_POST['cost']; ?>">
	    </td>
		<td class="err"><?php echo $costErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>Price:</td>
	    <td><input type="text" name="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>">
	    </td>
		<td class="err"><?php echo $priceErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td>On Hand:</td>
	    <td> <input type="text" name="onHand" value="<?php if(isset($_POST['onHand'])) echo $_POST['onHand']; ?>">
		</td>
		<td class="err"><?php echo $onHandErr; ?>
	    </td>
	  </tr>
	  <tr>
	    <td><label for="rO">ReOrder Point:</label></td>
	    <td> <input type="text" name="reorderPoint" id="rO" value="<?php if(isset($_POST['reorderPoint'])) echo $_POST['reorderPoint']; ?>">
	    </td>
		<td class="err"><?php echo $reorderPointErr; ?></td>
	  </tr>
	  <tr>
	    <td><label for="bO">Back Order:</label></td>
	    <td class="bo"> <input type="checkbox" name="backOrder" id="bO" value="<?php if(isset($_POST['backOrder'])) echo 'checked'; ?>">
	    </td>
	    <td></td>
	  </tr>
	  <tr><td><br /></td></tr>
	  <tr>
	    <td><br /></td>
	    <td><input type="submit" name="submit" value="Submit"></td>
	  </tr>
	</form>
	</table>

 <p>Copyright &copy; 2014 - All Rights Reserved - 
	<a href="http://www.scotchmob.com">Scotchmob.com</a></p>
<?php
}
?>
</body>

</html>


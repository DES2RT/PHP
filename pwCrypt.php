<?php
require 'myClasses.php';
$ok = false;
if($_POST)	{
	if(isset($_POST	['uname']) && isset($_POST['pword']))
	{	
		$uname = $_POST['uname'];
		$pword = $_POST['pword'];
		$hash = password_hash($pword, PASSWORD_DEFAULT);
	//	echo $hash."<br>";

	//CONNECT TO DB
        $db = new DBlink("int322_151b18");

        //INSERT USERNAME AND ENCRYPTED PASSWORD INTO DATABASE
		$okay = $db->query( 'INSERT INTO Login set uName="'. $uname . '", pWord="' . $hash . '", pwHint="i dont know your password" ');
	
/*
        if($okay)
            echo "Successfully inserted<br />";
    //CREATE UPDATE QUERY
        $k = $db->query( 'UPDATE Login set pWord="'.$hash.'" where uName="'.$uname.'"');

        if($k)
            echo "Successul UPDATE<br />";
*/
// THE FOLLOWING BIT OF CODE VERIFIES IF THE PASSWORD ENTERED MATCHES THAT OF THE
// PASSWORD STORED FOR THAT USERNAME

	//CREATE SELECT QUERY
		$result = $db->query('SELECT * FROM Login');
		
		while($row = mysqli_fetch_assoc($result))
		{	
//			echo $row['username'] . ' ' . $row['password'] . ' ' .
//				$row['role'] . ' ' . $row['passwordHint']."<br>";
		    if(!($ok)) {

			    if(password_verify($pword, $row['password'])) {
				    echo "Password (".$row['password'].") Verified<br>";
				    $ok = true;
			    }
			    else {
				    $error = "Invalid username or password. Try Again";
				    header("Location:pwCrypt.php?error=$error");
			    }
            }
		}		
	
	} // end 'if uname and pword'
	
} // end 'if post'

else {
?>

<html>
<head>
  <title>Login System</title>
</head>

<body>
	<table>	
	 <h3>Login System</h3>
	 <form method="post" action="pwCrypt.php">
	 <!--<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>-->
	 <?php echo $error; ?>
	 <tr> 
	   <td><label for="uname">Username:</label></td>
	   <td><input type="email" name="uname" id="uname">
	 </tr>
	 <tr>
	   <td><label for="pword">Password:</label></td>
	   <td><input type="password" name="pword" id="pword" /></td>
	 </tr>
	 <tr>
	   <td></td>
	   <td><input type="submit" /></td>
	 </tr>
	 </form>
	</table>

</body>
</html>
<?php	
} // end else (display form 1st time)
?>

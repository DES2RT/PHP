<?php
session_start();

if($_POST)	{
	if(isset($_POST	['uname']) && isset($_POST['pword']))
	{
        if($_POST['uname'] == "")
            $unameErr = "Please enter a username <br />";
        else
		    $uname = $_POST['uname'];
        
        if($_POST['pword'] == "")
            $pwordErr = "Please enter a password <br />";
        else
		    $pword = $_POST['pword'];
		
	//CONNECT TO DB
		$lines = file('/home/int322_151b18/apache/secret/connect.txt');
        $dbserver = trim($lines[0]);
        $uid = trim($lines[1]);
        $pw = trim($lines[2]);
        $dbname = trim($lines[3]);

        $link = mysqli_connect($dbserver, $uid, $pw, $dbname)
			or die('Could not connect: ' . mysqli_error());

	//CREATE SELECT QUERY
		$query = 'SELECT * FROM users';
		
	//RUN QUERY
		$result = mysqli_query($link, $query)
			or die('Could NOT select data: ' . mysql_error());
	
        $ok = false;
		while($row = mysqli_fetch_assoc($result))
		{
            //echo "un: " . $uname . " run: " . $row['username'];
            //echo "<br />pw: ". $pword . " rpw: " . $row['password'] . "<br />";
			if(!($ok)) {

                if($uname === $row['username'] && $pword === $row['password']){
				    $ok = true;
                //   echo "matches: " . $uname . ' ' . $row['username'];
			    }
			    else {
				    $error = "Invalid username or password. Try Again";
				    header("Location:login.php?error=".$error);
			    }
           // echo $ok;
           }
		}	

	//CLOSE DB CONNECTION
		//mysqli_close($link);
	
		if($_POST && $ok) {
		//	echo "username is " . $uname . "<br />";
			$_SESSION['user'] = $uname;
			$_SESSION['pass'] = $pword;
			 
			header('Location:protectedstuff.php');
		}     
	
    } // end 'if uname and pword'
	
	if(isset($_POST['forgotuname']))  {
			$phint ="";
			$forgotuname = $_POST['forgotuname'];
	//ACCESS DB FOR EMAIL MATCH
		//CONNECT TO DB
		$lines = file('/home/int322_151b18/apache/secret/connect.txt');
        $dbserver = trim($lines[0]);
        $uid = trim($lines[1]);
        $pw = trim($lines[2]);
        $dbname = trim($lines[3]);
			$link = mysqli_connect($dbserver, $uid, $pw, $dbname)
				or die('Could not connect: ' . mysqli_error());
	
		//CREATE SELECT QUERY
			$query = 'SELECT * FROM users';
		
		//RUN QUERY
			$result = mysqli_query($link, $query)
				or die('Could NOT select data: ' . mysql_error());
			
			while($row = mysqli_fetch_assoc($result))
			{
				if($forgotuname === $row['username']){
					// RETURN PASSWORD HINT FOR EMAIL
					$phint = $row['passwordHint'];
				}
				else
					$error = "Invalid username or password. Try Again";
			}		
			// SEND EMAIL WITH USERNAME AND PASSWORD HINT
		
			$to = "int322@localhost";//$forgotuname;
			$subject = "Password Recovery";
			$message = "Username: " . $forgotuname . " <br>Password Hint: "	. $phint;
			$ok = mail($to, $subject, $message);
			if($ok)
				echo "Mail successfully sent<br>";
			else
				echo "Could not send mail<br>";
			
?>
<html>
<head>
  <title>Lab 7 - Sessions</title>
</head>

<body>
	<table>	
	 <h3>Session - Lab 7 Part 2</h3>
	 <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	 <tr> 
	   <td><label for="uname">Username:</label></td>
	   <td><input type="email" name="uname" id="uname" value="<?php if(isset($_POST['forgotuname'])) echo $_POST['forgotuname']; ?>">
	 </tr>
	 <tr>
	   <td><label for="pword">Password:</label></td>
	   <td><input type="password" name="pword" id="pword" /></td>
	   <td>Hint: <?php echo $phint; ?></td>
	 </tr>
	 <tr>
	   <td></td>
	   <td><input type="submit" /></td>
	 </tr>
	 </form>
	</table>
	<a href = "/lab7/login.php?forgot=y" name="forgot">Forgot Your Password?</a>

<?php
	} // end 'if forgotuname'
} // end 'if post'

else if(isset($_GET['forgot']))  {
		//DISPLAY FORM BELOW PROMPTING FOR USERS EMAIL ADDRESS
?>
<html>
<head>
  <title>Lab 7 - Sessions</title>
</head>

<body>
	<table>	
	 <h3>Password Recovery</h3>
	 <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	 <tr> 
	   <td><label for="uname">Email:</label></td>
	   <td><input type="email" name="forgotuname" value="<?php if(isset($_POST['uname'])) echo $_POST['uname']; ?>" id="forgotuname">
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
}	// end 'if get forgot'

else  {
		// DISPLAY FORM FOR THE FIRST TIME
?>
<html>
<head>
  <title>Lab 7 - Sessions</title>
</head>

<body>
	<table>	
	 <h1>Session - Lab 7 Part 2</h1>
	 <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
	 <?php if(isset($_GET['error'])) {
				echo $_GET['error'];
				$error = $_GET['error'];
			}
			//echo $error; 
	?>
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
	<a href = "login.php?forgot=y" name="forgot">Forgot Your Password?</a>

</body>
</html>
<?php	
} // end else (display form 1st time)
?>

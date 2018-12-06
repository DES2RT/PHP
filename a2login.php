<?php

//THE LOGIN FORM PROCESSES USERNAME AND PASSWORD FROM USER. VALIDATES THE INPUT AND
//EITHER LOG THE USER INTO THE APP OR REDISPLAYS THE PAGE WITH AN ERROR
    
    // files needed for program
	require("a2lib.php");
	require("myClasses.php");

	// session info
	$timeout = 3 * 60; // 3 minutes
	$fingerprint = md5('SECRET-SALT'.$_SERVER['HTTP_USER_AGENT']);
	session_start();

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

	if(!isset($_SESSION['user'])) 
		//header("Location:logout.php");
	

	$ok = false;	
	$error = "";
	if($_POST)	{
		if(isset($_POST	['uname']) && isset($_POST['pword'])) {	
			$uname = $_POST['uname'];
			$pword = $_POST['pword'];
            $validUname= filter_var($uname, FILTER_VALIDATE_EMAIL);
		    if($uname === '' || !$validUname || $pword=== '') {
                $error = "Invalid username or password. Try Again<br />";
                header("Location:a2login.php?error=$error");
            }
            /*
			elseif(!preg_match("/^(\s*)([A-z]?[0-9]?(!@#$%^&*()_+)?(\s*)$/", $pword)){
                $error = "Invalid username or password. Try Again<br />";
                header("Location:a2login.php?error=$error");
            }*/
            else {
                //connect to database
			    $db = new DBlink('int322_151b18');
			    $result = $db->query('SELECT * FROM users WHERE username="'.$uname.'"');
                $ok = false;
		        while($row = mysqli_fetch_assoc($result))
		        {   
                    if(!$ok) {
			            $role = $row['role'];
			            if(strcmp($uname, $row['username']==0) && password_verify($pword, $row['password'])) {
				            //echo "Password Verified<br>";
				            $ok = true;
			            }
			            else {
				            $error = "Invalid username or password. Try Again";
				            header("Location:a2login.php?error=$error");
			            }
                    }
		        }
            }
	
		if($_POST && $ok) {
			$fingerprint = md5('SECRET-SALT'.$_SERVER['HTTP_USER_AGENT']);
//			echo "Welcome " . $uname . "<br>";
			$_SESSION['user'] = $uname;
			$_SESSION['pass'] = $pword;
			$_SESSION['role'] = $role;
			$_SESSION['lastActive'] = time();
			$_SESSION['fingerprint'] = $fingerprint;

			cookieSet($uname);
			header('Location:view.php');
		}     
	
	} // end 'if uname and pword
// END OF LOGIN VALIDATION
	
    // if user has forgotten email ..(link on form clicked)
	if(isset($_POST['forgotuname']))  {
			$phint ="";
			$forgotuname = $_POST['forgotuname'];

		// ACCESS DB FOR EMAIL MATCH
		// connect to database
		$db = new DBlink('int322_151b18');
		$result = $db->query('SELECT * FROM users');
			
			while($row = mysqli_fetch_assoc($result))
			{
				if(strcmp($forgotuname, $row['username']) ===0) {
					// return password hint for username
					$phint = $row['passwordHint'];
					$error = "found a match";
					$error = mailingPW($forgotuname, $phint);
					htmlHeader();
					firstForm($error);
				}
				else {
					$error = "Invalid email address. Try Again";
				}
			}				
		//	echo $forgotuname. ' ' .$error;
	
    } // end 'if forgotuname' func

} // end 'if post' func

else if(isset($_GET['forgot']))  {
		// recovForm - a2lib.php
		htmlHeader();
		recovForm();
}	// end 'if get forgot'

else  {
		// DISPLAY FORM FOR THE FIRST TIME
		if(isset($_GET['error']))
		$error =$_GET['error'];
		htmlHeader();
		firstForm($error);
?>

  </body>
</html>

<?php	
} // end else (display form 1st time)
?>

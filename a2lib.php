<?php 
/*
THIS PART OF THE APPLICATION DISPLAYS ALL THE RECORDS CURRENTLY IN THE DATABASE.
*/


function startSession()	{
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
		header("Location:logout.php");

}	// end startSession function
/****************************************************************/

// this function sets the cookies 
function cookieSet($value) {
	
	if(!isset($_COOKIE['uname']))	
		setcookie('uname', $value, time()+3600);

	setcookie('lastVisit', "");
	date_default_timezone_set('Europe/Istanbul');
    $today = date('l, F j, Y, T');
	$timestamp = date('g:i A');
	$lasttime;
	if(isset($_COOKIE['lastVisit'])) {
		if (strcmp($_COOKIE['lastVisit'], "") == 0)   
			$lasttime = "";
		else 
			$lasttime = $_COOKIE['lastVisit'];
    }

	$lastVis = $today . " at " . $timestamp;
	//echo $lastVis;

	//set last_visit cookie with date/time, with expiration for 2 full weeks
	setcookie('lastVisit', $lastVis, time() +3600*24*14);
	//set session variable for last visit
	$_SESSION['lastVisit'] = $_COOKIE['lastVisit'];

	//set visit count cookie
	if ($_COOKIE['count'] == 0) 
		$visitcount = 0;
	else 
		$visitcount = $_COOKIE['count'];
	
	// set visit_number cookie with count, with expiration for 2 full weeks
	setcookie("visCount",1 + $visitcount, time() + 3600*24*14);
 
}	

function htmlHeader() {
	echo '
                <html>
                 <head>
                    <title>DES2rt BU Stuff</title>
                </head>
                <body>
        ';
}
/*************************************************/

function topPage($u, $r, $s)	{
        echo '
				<img src="img/flags1.jpg" name="flagpic" />
				<h1>The Flag Emporium</h1>
				<form method="post" action="view.php">
				<ul style="list-style-type:none;">
				  <li style="padding:0 20px 0 0; display:inline;"><a href="add.php">Add</a></li>      
				  <li style="padding-right:250px; display:inline;"><a href="view.php">View All</a></li>
				  <li style="display:inline;"> Search In Description:
				   <input type="text" name="search" id="search" value="
            ';
					  
						if(isset($s)) echo $s;
			
		echo '
                   ">
				   <input type="submit" value="Search"></li>
				  <li style="padding:5px; display:inline;"> 
            ';
				
                        if(isset($u)) echo $u;
                        echo ' ';
				
                        if(isset($r)) echo " Role: " . $r;
		echo '
                 <a href="logout.php" style="padding-left:5px;">Logout</a>
				</ul>
				</form>
			';

}	// end topPage function
/****************************************************************/

function firstForm($error)  {
		echo '	
				<img src="img/flags1.jpg" name="flagspic" />
				<h1>DES2rt's HOP</h1>
				<h2>Please Login:</h2>
				<table>	
				<form method="post" action="a2login.php">
			';
				if(isset($error))
					echo $error;
		echo '
				 <tr> 
				   <td><label for="uname">Username:</label></td>
				   <td><input type="email" name="uname" id="uname" autofocus="autofocus">
				 </tr>
				 <tr>
				   <td><label for="pword">Password:</label></td>
				   <td><input type="password" name="pword" id="pword" /></td>
				 </tr>
				 <tr>
				   <td></td>
				   <td style="text-align:right;">
					<input type="submit" value="Login" /></td>
				 </tr>
				 <tr>
				   <td  style="padding-top:15px;">
				      <a href="a2login.php?forgot=1 name=forgot">
							Forgot Your Password?</a></td>
				   <td></td>
				 </tr>
			    </form>
			    </table>
            ';

}  // end firstForm  function
/***********************************************************/

// display password recovery form
function recovForm()  {
	echo '
			  <img src="img/flags1.jpg" name="flagspic" />
			  <h1>The Flag Emporium</h1>
		
			  <table>	
			  <h2>Password Recovery</h2>
			  <form method="post" action="a2login.php">
				<tr> 
				  <td><label for="uname">Email:</label></td>
	<td><input type="email" name="forgotuname" value="" id="forgotuname"></td>
				</tr>
				<tr>
				  <td></td>
				  <td style="text-align:right;">
				  <input type="submit" value="Submit" /></td>
				</tr>
			  </form>
			  </table>
			  <p style="padding-top:50px;">Copyright &copy; 2014 - All Rights Reserved - 
			    <a href="http://www.scotchmob.com" target="_blank">Scotchmob.com</a>
              </p>
			  
  </body>
</html>
		';

}  // end recovForm function	

function mailingPW($recip, $hint)	{
			// SEND EMAIL WITH USERNAME AND PASSWORD HINT
// this code has been commented out because the program was developed on 
// windows using wamp server without mail .. to use this feature just
// uncomment the code .. (delete /* and */)
			
			$to = "int322_151b18@localhost";//$recip;
			$subject = "Password Recovery";
			$message = "Username: ".$recip." Password Hint: ".$hint;
			$ok = mail($to, $subject, $message);
			if($ok)
				echo "Mail successfully sent<br>";
			else
				echo "Could not send mail<br>";
			
			$mess = "Mail Sent Successfully to ".$recip."<br>";
			return $mess;
}	// end mailing function

function printViewTH($sE) {
	echo '
            <table style="border:10px;">
		';

	if(isset($_POST['search'])) {
		if(strcmp($sE, "") ==0){
			echo "<h2>Search Results</h2>";
		}
		else  {
			echo "<h2>Search Results  ";
			echo "<span>".$sE."</span></h2>";
		}
	}
	else {
		echo "<h2>Inventory</h2>";
	}
			
	echo '
			<th><a href="view.php?order=id">ID</a></th>
			<th><a href="view.php?order=iName">Item Name</a></th>
			<th><a href="view.php?order=desc">Description</a></th>
			<th><a href="view.php?order=supplier">Supplier</a></th>
			<th><a href="view.php?order=cost">Cost</a></th>
			<th><a href="view.php?order=price">Price</a></th>
			<th><a href="view.php?order=onHand">Number On Hand</a></th>
			<th><a href="view.php?order=reOrder">ReOrder Level</a></th>
			<th><a href="view.php?order=backOrder">On Back Order?</a></th>
			<th>Delete/ Restore</th>
		';
}//end printViewTH function

function printViewFooter() {
	echo '
			</table>
			<p style="padding-top:50px;">Copyright &copy; 2014 - All Rights Reserved - 
			  <a href="http://www.scotchmob.com">Scotchmob.com</a>
            </p>
		    </body>
		    </html>
		';
}

?>

<?php
/*
THIS PART OF THE APPLICATION DISPLAYS ALL THE RECORDS CURRENTLY IN THE DATABASE.
*/
 class DBlink {
  private $links;
  private $lastquery;
  public 
    function __construct ($dbname) {
        /* $lines = file('/home/int322_151b18/apache/secret/connect.txt');
        $dbserver = trim($lines[0]);
        $uid = trim($lines[1]);
        $pw = trim($lines[2]); */
		$dbserver = "localhost";
		$uid = "des2rt_me";
		$pw = "NKstu8872"
        $link = mysqli_connect ($dbserver, $uid, $pw, $dbname)
		    or die('Could NOT connect to server:  ' . mysqli_error());
        $this -> links = $link;
    }//end construct
  
    function query ($query) {
        //echo $query . "<br>";
        $result = mysqli_query($this -> links, $query)
		    or die('Could NOT perform query ' . mysqli_error($this->links));
        $this->lastquery = $result;
        return $result;				
    }
  
    function emptyResult() {
	    if(mysqli_num_rows($this->lastquery) ===0){
	        echo "the last query was true<br>";
  	    return true;
	    }
	    else {
	        echo "the last query was false<br>";
	    return false;
	    }
    }
    function displayQuery($result) {
	    while($row = mysqli_fetch_assoc($result))
	    {
		    foreach($row as $value) {
			    echo $value . ' ';
		    }
		    echo "<br>";
	    }
    }
    function __destruct() {
        mysqli_close ($this -> links);
    }
 }// end of DBlink class

///////////////////////////////////////////////////////
 
 class Menu {
	private $links = array();
	private $i;
	public 
		function __construct($links) {
		
			$args = func_get_args($links);
			$i = count($args);
			$this->i = $i;
			for($x=0; $x<$i; $x++) {
				$this->links[$x] = $args[$x];
			}
			//echo "in construct i is " . $this->i."<br>";

		}
		function add($item) {
			echo "new item is: ".$item."<br>";
			$i = $this->i+=1;
			$this->links[$i-1] = $item;
		}
		function display() {
			$i = $this->i;
			echo "<ul>";
			for($x=0; $x<$i; $x++) {
				echo "link #" . ($x+1) . " is ";
				echo "<a href=". $this->links[$x] . ".html>";
				echo $this->links[$x];
				echo "</a><br>";
			}
			echo "</ul>";
		}

		function __destruct() {
		}
 }
 ?>

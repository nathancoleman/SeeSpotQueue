<?php 
	require_once('config.php');

	echo generateSessionCode();

	function generateSessionCode() { 
	    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
	    srand((double)microtime()*1000000); 
	    $session_code = '' ; 

	    for($i = 0; $i < 6; $i++) { 
	        $num = rand() % 33; 
	        $tmp = substr($chars, $num, 1); 
	        $session_code = $session_code . $tmp; 
	    }

	    if( validateSessionCode($session_code) ) return $session_code;
	    else generateSessionCode();
	}

	function validateSessionCode($session_code)
	{
		global $connection;

		if( mysqli_connect_errno($connection) )
		{
			echo "Problem connecting to the database.";
		}
		else
		{
			$query = "SELECT id FROM Sessions WHERE session_code='" . $session_code . "'";
			$result = mysqli_query($connection,$query);

			if( mysqli_num_rows($result) == 0 )
			{
				return assignSessionCode($session_code);
			}
			else return false;
		}
	}

	function assignSessionCode($session_code)
	{
		global $connection;

		date_default_timezone_set('America/Chicago');
		$created = date("Y-m-d H:i:s");

		$query = "INSERT INTO Sessions (session_code,created) VALUES ('" . strtoupper($session_code) . "','" . $created . "')";
		$result = mysqli_query($connection,$query);

		if( $result )
		{
			$_SESSION['last_hosted'] = $session_code;
			return true;
		}
		else return false;
	}
 ?>
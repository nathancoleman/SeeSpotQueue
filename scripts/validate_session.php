<?php 
	require_once('scripts/config.php');

	function validateSession($session_code)
	{
		global $connection;

		$query = "SELECT id FROM Sessions WHERE session_code='" . $session_code . "'";
		$result = mysqli_query($connection,$query);

		if( mysqli_num_rows($result) > 0 )	return true;
		else return false;
	}
 ?>
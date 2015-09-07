<?php 
	require_once('config.php');

	$track_id = $_POST['track_id'];
	promoteTrack($track_id);


	function promoteTrack($track_id)
	{
		global $connection;

		$query = "UPDATE Tracks SET votes = votes + 1 WHERE id=" . $track_id;
		mysqli_query($connection,$query);

		return true;
	}
 ?>
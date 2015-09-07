<?php 
	require_once('config.php');

	$session_id = $_POST['session_id'];
	$track_id = $_POST['track_id'];
	removeTrack($track_id);

	function removeTrack($track_id)
	{
		global $connection;

		$query = "DELETE FROM Tracks WHERE id='" . $track_id . "'";
		mysqli_query($connection,$query);

		return true;
	}

 ?>
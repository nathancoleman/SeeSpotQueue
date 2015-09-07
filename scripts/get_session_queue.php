<?php 
	require_once('config.php');

	$session_code = $_POST['session_code'];
	$hosting = $_POST['hosting'];

	getQueue($session_code);


	function getQueue($session_code)
	{
		global $connection;

		$query = "SELECT * FROM Tracks WHERE session_code='" . $session_code . "' AND status='queued' ORDER BY votes DESC, id";
		$tracks = mysqli_query($connection,$query);
		$numTracks =  mysqli_num_rows($tracks);
			
		while( $track = mysqli_fetch_array($tracks) )
		{
			echo '<button class="queue-item text-left" onclick="promoteTrack(' . $track['id'] . ')">';
				echo '<span class="title">' . $track['title'] . '</span>';
				echo '<span class="length">' . secondsToMinutes($track['length']) . '</span>';
				echo '<span class="artist">' . $track['artist'] . '</span>';
				echo '<span class=divider">-</span>';
				echo '<span class="album">' . $track['album'] . '</span>';
				echo '<span class="votes">' . $track['votes'] . '</span>';
				//echo '<a class="remove" onclick="removeTrack(' . $track['id'] . ')"><i class="icon-remove-sign"></i></a>';
			echo '</button>';
		}

		return true;
	}

	function secondsToMinutes($seconds)
	{
		$minutes = '';
		$minutes .= (int)($seconds/60);
		$minutes .= ':';
		if( $seconds%60 < 10 ) $minutes .= '0';
		$minutes .= $seconds%60;
		return $minutes;
	}
 ?>
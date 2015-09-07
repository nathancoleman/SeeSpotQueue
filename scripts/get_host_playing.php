<?php 
	require_once('config.php');

	$session_code = $_POST['session_code'];
	$_SESSION['last_hosted'] = $session_code;

	setPlaying($session_code);
	getPlaying($session_code);


	function setPlaying($session_code)
	{
		global $connection;

		$query = "SELECT id FROM Tracks WHERE session_code='" . $session_code . "' AND status='queued' ORDER BY votes DESC";
		$tracks = mysqli_query($connection,$query);
		$track = mysqli_fetch_array($tracks);

		$query = "UPDATE Tracks SET status='played' WHERE status='playing'";
		mysqli_query($connection,$query);

		$query = "UPDATE Tracks SET status='playing' WHERE id=" . $track['id'];
		mysqli_query($connection,$query);
	}

	function getPlaying($session_code)
	{
		global $connection;

		$query = "SELECT * FROM Tracks WHERE session_code='" . $session_code . "' AND status='playing'";
		$tracks = mysqli_query($connection,$query);
		$track = mysqli_fetch_array($tracks);

		echo '<div>';
		echo '<div id="progress"></div>';
		echo '<span id="now-playing" href="' . $track['spotify_uri'] . '">';
			echo '<span class="title">' . $track['title'] . '</span>';
			echo '<span class="length">' . secondsToMinutes($track['length']) . '</span>';
			echo '<span class="secs" style="display:none">' . $track['length'] . '</span>';
			echo '<span class="artist">' . $track['artist'] . '</span>';
			echo '<span class=divider">-</span>';
			echo '<span class="album">' . $track['album'] . '</span>';
			echo '<span class="votes">' . $track['votes'] . '</span>';
		echo '</span>';
		echo '</div>';

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
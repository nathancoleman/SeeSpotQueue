<?php 
	require_once('config.php');
	require_once('../lib/config.php');

	$session_code = $_POST['session_code'];
	$track_uri = $_POST['track_uri'];

	$spotify = MetaTune\MetaTune::getInstance();
	$track = $spotify->lookup($track_uri);

	addToQueue($session_code,$track);


	function addToQueue($session_code,$track)
	{
		global $connection;

		$query = "INSERT INTO Tracks (session_code,spotify_uri,artist,album,title,image,length) VALUES ('" . strtoupper($session_code) . "','" . mysqli_real_escape_string($connection,$track->getURI()) . "','" . mysqli_real_escape_string($connection,$track->getArtistAsString()) . "','" . mysqli_real_escape_string($connection,$track->getAlbum()) . "','" . mysqli_real_escape_string($connection,$track->getTitle()) . "','" . mysqli_real_escape_string($connection,getImage($track->getURI())) . "'," . (int)($track->getLength()) . ")";

		mysqli_query($connection,$query);

		return true;
	}

	function getImage($track_uri)
	{
		$url = "https://embed.spotify.com/oembed/index.php?url=" . $track_uri . "&format=json";
		echo $url;
		$output = get_content($url);
		$output = json_decode($output,true);
		return $output['thumbnail_url'];
	}

	function get_content($Url){
	    if (!function_exists('curl_init')) die('Sorry cURL is not installed!');
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $Url);
	    curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
	    curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    $output = curl_exec($ch);
	    curl_close($ch);
	 
	    return $output;
	}
 ?>
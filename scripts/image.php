<?php 
	
	$url = 'https://embed.spotify.com/oembed/?url=spotify:track:6bc5scNUVa3h76T9nvpGIH';

	$output = readfile($fetch_url);
	
	echo $output;

 ?>
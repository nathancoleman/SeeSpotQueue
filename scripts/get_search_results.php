<?php 
	require_once('../lib/config.php');

	$spotify = MetaTune\MetaTune::getInstance();

	$search_term = $_POST['search_term'];

	$tracks = array_slice( $spotify->searchTrack($search_term), 0, 5 );

	foreach($tracks as $track)
	{
		echo '<button class="search-result text-left" onclick="addToQueue(\'' . $track->getURI() . '\')">';
			echo '<span class="title">';
				echo $track->getTitle();
			echo '</span>';
			echo '<span class="artist">';
				echo $track->getArtistAsString();
			echo '</span>';
			echo '<span class="divider">-</span>';
			echo '<span class="album">';
				echo $track->getAlbum();
			echo '</span>';
			echo '<span class="length">';
				echo $track->getLengthInMinutesAsString();
			echo '</span>';
		echo '</button>';
	}
 ?>
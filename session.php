<?php 
	require_once('scripts/config.php');

	$session_code = $_GET['id'];
	$last_hosted = $_SESSION['last_hosted'];
	$is_hosting = $session_code == $last_hosted;
 ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta property="og:image" content="/assetslogo_dark.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SeeSpotQueue | <?php echo $session_code; ?></title>

	<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="/style.css">
	<link rel="icon" type="image/png" href="/assets/favicon.png">

	<script src="/assets/jquery/jquery.min.js"></script>
	<script src="/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="//davidwalsh.name/demo/ZeroClipboard.js"></script>
</head>
<body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<header>
		<div class="row-fluid">
			<div class="span1 offset2 text-left hidden-phone"><h1><a href="/"><img src="/assets/logo.png" style="vertical-align:bottom" alt=""></a></h1></div>
			<div class="span6 text-center"><h1 id="session-code"><?php echo $session_code; ?></h1></div>
		</div>
	</header>

	<div class="row-fluid">
		<div id="search" class="span4 offset4 text-center">
			<form id="search-form">
				<img src="/assets/loading.gif" alt="" id="spinner">
				<input id="search-box" type="text" placeholder="search..." autocomplete="off">
			</form>
			<div id="search-results"></div>
		</div>
	</div>
	
	<div class="row-fluid">
		<div id="sharingPanel" class="span4 text-right hidden-phone">
			<button id="shareLink" name="shareLink" class="btn btn-mini btn-inverse">SSQ Link</button>
			<br>
			<div id="shareFacebook" class="fb-share-button" data-href="" data-type="button"></div>
			<br>
			<a href="https://twitter.com/share" id="shareTwitter" class="twitter-share-button" data-text="Add a song to my Spotify queue!" data-count="none">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		</div>

		<div id="session" class="span4">
			<div id="playing"></div>
			<div class="row-fluid text-center">
				<h6><i style="color:#999">click on a song to vote it up in the queue</i></h6>
			</div>
			<div id="queue"></div>
		</div>
	</div>
	<div class="row-fluid text-center">
		<button id="howItWorks" data-toggle="collapse" data-target="#tipsCollapse"><h6>tips</h6></button>
		<br>
		<div id="tipsCollapse" class="collapse text-left" style="display:inline-block">
			<ul>
				<li>Make sure Spotify is open before adding songs</li>
				<li>Need to control the Spotify host from a distance?
					<ul>
						<li>Connect your mobile device to the same network</li>
						<li>Install <a href="//www.unifiedremote.com" target="_blank">UnifiedRemote</a> on the hosting computer and your mobile device</li>
					</ul>
				</li>
			</ul>
		</div>	
	</div>

	<script>
		$(document).ready(function(){
			<?php if( !$is_hosting ) echo 'setInterval(getPlaying,' . $refresh_rate . ");\n"; ?>
			getPlaying();
			getSessionQueue();
			setInterval(getSessionQueue,<?php echo $refresh_rate; ?>);
			setupShare();
			setupSearch();
		});

		function setupSearch()
		{
			var searchWait = 500;
			var searchTimer;
			$('#search-box').keyup(function(){
				searchTimer = setTimeout(getSearchResults,searchWait);
			});

			$('#search-box').keydown(function(){
				clearTimeout(searchTimer);
			});

			$('#search-form').submit(function(e){
				e.preventDefault();
				getSearchResults();
				return false;
			});

			$('#spinner').css('visibility','hidden');
		}

		function setupShare()
		{
			ZeroClipboard.setMoviePath('http://davidwalsh.name/demo/ZeroClipboard.swf');
			var clip = new ZeroClipboard.Client();
			clip.addEventListener('mousedown',function() {
				clip.setText(location.href.toString());
			});
			clip.addEventListener('complete',function(client,text) {
				alert("Link Copied to Clipboard!");
			});
			clip.glue('shareLink');
		}

		function getPlaying()
		{
			<?php 
			if( $is_hosting ) echo "getHostPlaying();";
			else echo "getSessionPlaying();\n\t\t\t";
			?>
		}

		var lasturi = 0;
		function getHostPlaying()
		{
			$.ajax({
				type: "POST",
				url: "/scripts/get_host_playing.php",
				data: {session_code: '<?php echo $session_code; ?>'},
				success: function(res){
					var nowPlaying = $(res).find('#now-playing');
					var uri = nowPlaying.attr('href');
					var sec = nowPlaying.find('.secs').html();
					window.open(uri,'_self');
					setTimeout(getHostPlaying, sec * 1000);
					getSessionQueue();
					$('#playing').html(res);
					$('#progress').animate({width: "100%"}, sec * 1000);
				}
			});
		}

		function getSessionPlaying()
		{
			$.ajax({
				type: "POST",
				url: "/scripts/get_session_playing.php",
				data: {session_code: '<?php echo $session_code; ?>'},
				success: function(res){
					var nowPlaying = $(res).find('#now-playing');
					var uri = nowPlaying.attr('href');
					var image = nowPlaying.find('.image').html();
					var elapsed = nowPlaying.find('.elapsed').html();
					var length = nowPlaying.find('.secs').html();
					var remaining = length - elapsed;
					var percentage = (elapsed/length)*100;
					if( uri != lasturi )
					{
						lasturi = uri;
						$('#playing').html(res);
						$('#progress').css('width',percentage + '%');
						$('#progress').animate({width: "100%"}, remaining * 1000);
						setTimeout(getSessionPlaying,(remaining * 1000)+1000);
					}
				}
			});
		}

		function getSessionQueue()
		{
			$.ajax({
				type: "POST",
				url: "/scripts/get_session_queue.php",
				data: {session_code: '<?php echo $session_code; ?>'},
				success: function(res){
					$('#queue').html(res);
				}
			});
		}

		function getSearchResults()
		{
			$('#spinner').css('visibility','visible');
			$.ajax({
				type: "POST",
				url: "/scripts/get_search_results.php",
				data: {search_term: $('#search-box').val() },
				success: function(res){
					$('#search-results').html(res);
					$('#spinner').css('visibility','hidden');
				}
			});
			return false;
		}

		function addToQueue(trackURI)
		{
			$.ajax({
				type: "POST",
				url: "/scripts/add_to_queue.php",
				data: {session_code: '<?php echo $session_code; ?>',
						track_uri: trackURI },
				success: function(res){
					getSessionQueue();
					$('#search-results').html('');
					$('#search-box').val('');
					<?php if( !$is_hosting ) echo 'getSessionPlaying();'; ?>
				}
			});
			return false;
		}

		function promoteTrack(trackID)
		{
			$.ajax({
				type: "POST",
				url: "/scripts/promote_track.php",
				data: {session_code: '<?php echo $session_code; ?>',
						track_id: trackID },
				success: function(res){
					getSessionQueue();
				}
			});
			return false;
		}

		function removeTrack(trackID)
		{
			$.ajax({
				type: "POST",
				url: "/scripts/remove_track.php",
				data: {session_code: '<?php echo $session_code; ?>',
						track_id: trackID },
				success: function(res){
					alert("Track Removed");
				}
			});
			return false;
		}
	</script>
</body>
</html>
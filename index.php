<?php 
	require_once('scripts/config.php');

	$last_hosted = -1;
	if( isset($_SESSION['last_hosted']) ) $last_hosted = $_SESSION['last_hosted'];
	
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta property="og:image" content="assets/logo_dark.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SeeSpotQueue</title>

	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">

	<script src="assets/jquery/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<header class="text-center"><h1 style="text-transform:none">SeeSpot<img src="assets/logo.png" style="vertical-align:bottom" alt="">ueue</h1></header>
	<div class="row-fluid">
		<div id="btnPanel" class="span4 offset4 text-center">
			<button id="btnHostSession" class="btn btn-large btn-success">Host Session</button>
			<form id="joinSession">
				<input id="joinSessionCode" type="text" placeholder="Code">
			</form>
			<button id="btnJoinSession" class="btn btn-large btn-primary">Join Session</button>
			<br>
			<?php if($last_hosted != -1){ ?>
			<button id="btnContSession" class="btn btn-large btn-info"><?php echo $last_hosted; ?></button>
			<?php } ?>
		</div>
	</div>
	<div class="row-fluid text-center">
		<button id="howItWorks" data-toggle="collapse" data-target="#howItWorksCollapse"><h6>how it works</h6></button>
		<br>
		<div id="howItWorksCollapse" class="collapse text-left" style="display:inline-block">
			<ul>
				<li>To <b>join</b>:
					<ul>
						<li>Get the link or access code for a hosted session</li>
						<li>Add the songs you want</li>
					</ul>
				</li>
				<li>To <b>host</b>:
					<ul>
						<li>You must have <a href="https://www.spotify.com/us/download/" target="_blank">Spotify</a> installed</li>
						<li>Open the Spotify application on your computer</li>
						<li>Select the option to host a session above</li>
						<li>Use our share buttons to give the link to your friends</li>
						<li>Add the songs you want</li>
					</ul>
				</li>
			</ul>
		</div>	
	</div>

	<script>
		$('#btnHostSession').click(function(){
			$.ajax({
				type: "POST",
				url: "scripts/create_session.php",
				success: function(res){
					location.href = res + '/';
				}
			});
			return false;
		});

		$('#btnJoinSession').click(function(){
			$('#btnPanel button').css('display','none');
			$('#joinSessionCode').css('display','inline-block');
			$('#joinSessionCode').focus();
		});

		$('#joinSession').submit(function(e){
			e.preventDefault();
			var sessionCode = $('#joinSessionCode').val().toUpperCase();
			location.href = sessionCode + '/';
		});

		$('#btnContSession').click(function(e){
			e.preventDefault();
			location.href = '<?php echo $last_hosted . "/"; ?>';
			return false;
		});
	</script>
</body>
</html>
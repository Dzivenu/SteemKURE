<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>KURE</title>
	<meta charset="UTF-8">
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
  	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport">
	<meta name="description" content="Kindred United to Reward Excellence">
	<meta name="keywords" content="Steemit, Curation, cure, kure">
	<meta name="author" content="Kris Nelson">
	<!--<base href="http://localhost/steemkure/">-->
	<link rel="apple-touch-icon" sizes="57x57" href="assets/img/fav/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="assets/img/fav/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="assets/img/fav/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/fav/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="assets/img/fav/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="assets/img/fav/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="assets/img/fav/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="assets/img/fav/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/fav/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="assets/img/fav/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/fav/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/fav/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/fav/favicon-16x16.png">
	<link rel="manifest" href="assets/img/fav/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="fav/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/steemkure.css">
	<script type="text/javascript" src="semantic/components/tab.min.js"></script>
	<script type="text/javascript" src="semantic/components/transition.min.js"></script>
	<script type="text/javascript" src="https://cdn.steemjs.com/lib/latest/steemconnect.min.js"></script>
	<style type="text/css">
		.ui.button, .ui.message, .ui.segment, .ui.menu, .ui.label, .ui.modal, .ui.modal .form .button {
			border-radius: 0rem;
		}
	</style>
</head>
<body>
	<div class="ui container">
		<div class="ui grid">
			<div class="row"></div> <!-- TOP SPACER -->
			<div class="row">
				<!-- MAIN -->
				<div class="sixteen wide column">
					<div id="nav" class="ui blue menu">
						<a id="home" class="item" href="./">Home</a>
						<a id="kurate" class="item" href="kurate">Kurate</a>
						<a id="manage" class="item" href="manage">Manage</a>
						<a id="about" class="item" href="about">About</a>						
						<div class="right menu">
							<a id="loginLink" class="ui item">Login</a>
						</div>
					</div>
					<div class="ui active" id="homePage">
						Error, this page doesn't exist.
					</div>
				</div><!-- end MAIN -->
			</div>
		</div>
	</div>
</body>
</html>
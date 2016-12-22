<?php header('Content-type: text/html; charset=utf-8'); 
if (!defined('PROPER_ACCESS')) {
	exit;
}
?>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.js"></script>
	<script src="assets/js/steemjs-lib.js"></script>
	<script src="https://cdn.steemjs.com/lib/latest/steem.min.js"></script>
	<script src="https://cdn.steemjs.com/lib/latest/steemconnect.min.js"></script>
	<script src="kure.js"></script>
	<style type="text/css">
		.ui.button, .ui.message, .ui.segment, .ui.menu, .ui.label, .ui.modal, .ui.modal .form .button {
			border-radius: 0rem;
		}
	</style>
	<script>
    $(document).ready(function () {
      $('.menu .item').tab();
      (function(){kure.init();})();
	});
  </script>
</head>
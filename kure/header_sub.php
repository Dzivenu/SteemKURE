<?php
if (!defined('PROPER_ACCESS')) {
	exit;
}
?>
<body>
	<div class="ui container">
		<div class="ui grid">
			<div class="row"></div> <!-- TOP SPACER -->
			<div class="row">
				<!-- SIDEBAR -->
				<!--<div class="four wide column">
				</div>--><!-- end SIDEBAR -->

				<!--<div class="two wide column"></div>-->
				<!-- MAIN -->
				<div class="sixteen wide column">
					<div id="nav" class="ui blue menu">
						<a id="home" class="item active" href="./">Home</a>
						<a id="kurate" class="item" href="kurate">Kurate</a>
						<!--<a id="steemit" class="item" href="steemit">Steemit</a>-->
						
						<div class="right menu">
							<a id="loginLink" class="ui item">Login</a>
						</div>
					</div>
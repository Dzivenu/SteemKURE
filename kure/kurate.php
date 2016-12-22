<?php
define('PROPER_ACCESS', true);
require_once ('config.php');
//require_once ('init.php');

//init
require_once ('header.php');
require_once ('header_sub.php');
?>

					<div class="ui tab active">

						<div class="ui blue fluid loading segment" id="mainContent">
							<div class="ui horizontal divider">Find New Posts to Curate</div>
							<div class="content" id="newPosts">
								<div>
								</div>
								<div id="kurErrors"></div>
							</div>
							<div style="clear:both;">&nbsp;</div>
							<div class="mini ui basic button" id="genNewPosts">More Posts</div>
						</div>

						
						<!-- UNVOTE MODAL -->
						<div class="ui first coupled modal" id="unvoteModal">
						  <i class="close icon"></i>
						  <div class="header">
						    Confirm Unvote
						  </div>
						    <div class="description" id="postContent"><center><h3>Are you sure you want to unvote this post?</h3><br/><button class="ui blue basic button" type="button" id="uvoteConfirm">Unvote</button></center></div>
						    <script type="text/javascript"></script>
						  </div>
						</div>

						<!-- POST MODAL -->
						<!--<div class="ui first coupled modal" id="postModal">
						  <i class="close icon"></i>
						  <div class="header">
						    Post Title
						  </div>
						  <div class="image content">
						    <div class="image" id="postImage">
						    	<img src="" class="image">
						    </div>
						    <div class="description" id="postContent">
						    </div>
						    <script type="text/javascript"></script>
						  </div>
						</div>-->
<?php
require_once ('footer.php');
?>
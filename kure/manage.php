<?php
define('PROPER_ACCESS', true);
require_once ('config.php');
require_once ('header.php');
require_once ('header_sub.php');
?>


					<div class="ui active" id="managePage">

						<div class="ui blue fluid segment" id="mainContent">
							<div class="ui horizontal divider">Manage A List</div>
							<div class="content main">
							
								<div class="manageSections" id="manageLists">
									<div class="ui compact selection dropdown">
										<div class="text">Pick a List</div>
									  	<i class="dropdown icon"></i>
									  	<div class="menu">
									  	</div>
									</div>
									<!--<div class="left">Create</div><div class="clear"></div>-->
									
								</div>
								<div id="manageTitle"></div>

								<div class="ui segment manageSections">
									<div class="ui pointing borderless basic blue menu"  id="postMenu">
										<a class="item active" data-tab="managePosts">Posts</a>
										<a class="item" title="Add a post" id="addPostButton"><i class="ui add grey circle icon"></i></a>
									</div>
									<div class="ui tab active" data-tab="managePosts">
										<div class="ui content">
											<div class="ui container" id="managePosts">
												<div class="row">Select a List from the dropdown above</div>
											</div>
										</div>
									</div>
								</div>
								

								<div class="ui segment manageSections">
									<div class="ui pointing borderless basic blue menu"  id="memberMenu">
										<a class="item active" data-tab="manageMembers">Members</a>
										<a class="item" title="Add a member" id="addMemButton"><i class="ui add grey circle icon"></i></a>
									</div>

									<div class="ui tab active" data-tab="manageMembers">
										<div class="ui content">
											<div class="ui container" id="manageMembers">
												<div class="row">Users who are a member of the List will appear here.</div>
											</div>
										</div>
									</div>

								</div>


								<div id="kurErrors"></div>
							</div>
						</div>

<?php
require_once ('modals.php');
?>
						
					</div>
<?php
require_once ('footer.php');
?>
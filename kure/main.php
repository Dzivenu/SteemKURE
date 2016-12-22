<?php
if (!defined('PROPER_ACCESS')) {
	exit;
}
?>


					<div class="ui tab active" data-tab="first">

						<div class="ui blue fluid loading segment" id="mainContent">
							<div class="ui horizontal divider">My Curation Lists</div>
							<div class="content">
								<div class="ui pointing borderless basic blue menu" id="myMenu">
									<a class="item active" data-tab="listManage">Manage</a>
									<!--<a class="item" data-tab="listAdd">Add</a>-->
									<a class="item" data-tab="listCreate">New</a>
									<!--<a class="item" data-tab="listFind">Find</a>-->
									<a class="item" data-tab="listFollow">Follow</a>
								</div>
								<!--<div class="" id="myCurIntro"></div>-->
								<!-- MANAGE -->
								<div class="ui tab active" data-tab="listManage" id="listManage">
									<div class="intro"><h3>Manage Your Curation Lists</h3></div>
									<div class="ui message content">
										<div class="ui grid container" id="manageSelect">
											<div class="ui centered twelve wide column">
												<div class="ui segment" id="kureIntro">
													<p>SteemKURE is a network hub to easily find content others value, upvote it, and help others find content you value. For those who have bots, autovoting or trails to follow, this might not appeal to you.</p>

													<p>Create your own curation lists where others can find and upvote what you add. This creates a curation network that grows as more people interconnect and interact in each others lists.</p>

													<p><h4>Why?</h4>Do you recommend or resteem every posts you upvote?<br/>Would you want to share posts for others to find, but not resteem and clutter people's feeds?<br/>Would you recommend someone else upvote all of what you upvote?<br/>What if they are not interested in all your votes, and want to put their votes towards content they want to support?<br/>Would you recommend all 40+ of your daily upvotes as recommended picks? I don't think so.</p>

													<p>SteemKURE is where you get more control over what you what to find and share as top valued material you find on steemit.com. </p>

													<p><h4>Features:</h4>
													<ul>									
														<li>Create your own Curation Project</li>
														<li>Create your own criteria for adding to your list</li>
														<li>Submit posts to your curation lists</li>
													</ul>
													</p>

													<p><h4>In progress:</h4>
													<ul>									
														<li>Follow lists</li>
														<li>Add posts to other lists</li>
														<li>Be notified of additions to lists</li>
													</ul>
													</p>

													<p><h4>Future features include:</h4>
														<ul>
															<li>Browse any post on Steemit.com to upvote</li>
															<li>Upvote anypost on any list</li>
															<li>Vote bar</li>
															<li>Downvotes</li>
															<li>Modal reader to read onsite</li>
															<li>Viewing new posts here, to add to lists, rather than manual adding of links</li>
															<li>Notifications of new posts in followed lists</li>
															<li>Browser Extension to create lists and upvote on them from steemit.com</li>

														</ul>
													</p>
													<p>The login uses <a href="https://steemconnect.com/">SteemConnect</a>, which doesn't store your password. Use your posting key to login, not active or owner keys.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- ADD -->
								<!--<div class="ui tab" data-tab="listAdd" id="listAdd">
									<div class="intro"><h4>Add to an existing Curation List</h4></div>
									<div class="ui message content"></div>
								</div>-->
								<!-- CREATE -->
								<div class="ui tab" data-tab="listCreate" id="listCreate">
									<div class="intro"><h3>Create a new Curation List</h3></div>
									<div class="ui message content">
										<div class="ui form">
											<div class="inline field">
												<label>List Name: </label><input type="text" id="listName" readonly placeholder="Choose a name" class=""> &nbsp;
												<div  id="createList" class="mini ui button">Create</div >
											</div>
										</div>
									</div>
								</div>

								<!-- FIND LISTS to FOLLOW -->
								<div class="ui tab" data-tab="listFollow" id="listFollow">
									<div class="intro"><h3>Follow another Curation List</h3></div>
									<div class="ui message content">
										<div class="ui grid container" id="listLists">Find a list to Follow here.</div>
									</div>
								</div>
								<div id="kurErrors"></div>

							<!-- how to get rid of this on success?-->
								<!--<p>- Tech List</p>
								<p>- Name's List</p>-->

								<!--<div class="ui grid container">
									<div class="eight wide column">1</div>
									<div class="eight wide column">2</div>
									<div class="eight wide column">3</div>
									<div class="eight wide column">4</div>

								</div>-->

							</div>
						</div>
						<div class="ui blue fluid segment" id="topLists">
							<div class="ui horizontal divider">Top Curating Lists</div>
							<div class="content">
<?php
/*
require_once  'assets/medoo.php';
require_once  'config.php';

$database = new medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => DB_NAME,
	'server' => DB_HOST,
	'username' => DB_USER,
	'password' => DB_PASSWORD,
	'charset' => 'utf8',
 
	// [optional]
	'port' => 3306,

	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	]
]);

$dbres = $database->select("curation_lists", [
					"id", "id", "id"
				], [
					"LIMIT" => 2,  
					"ORDER" => "curation_lists.id DESC"

				]);

*/
?>
							</div>
						</div>
						<!--<div class="ui blue fluid segment">
							<div class="ui horizontal divider">Top Curated Posts</div>
							<div class="content">
								<p>- Post1</p>
								<p>- Post2</p>
								<p>- Post3</p>
								<p>- Post4</p>
								<p>- Post5</p>
							</div>
						</div>-->

						<div class="ui second modal" id="addPostModal">
						  <i class="close icon"></i>
						  <div class="header">
						    Add a post to the list: <span id="addListName"></span>
						  </div>
						  <div class="content">
						    <div class="description">
						    	<div class="inline field">
									<label>Steemit URL: </label><input type="text" id="postLink" data-url="" placeholder="https://steemit.com/tag/@user/permalink" maxlength="200" size="50" class="" > &nbsp;
									<div  id="addPostConfirm" class="mini ui button">Add</div >
								</div>
								</div><div class="errors"></div>
						    </div>
						  </div>
						</div>

						<div class="ui second modal" id="remPostModal">
						  <i class="close icon"></i>
						  <div class="header">
						    Are you sure you want to remove this post from the list?
						  </div>
						  <div class="content">
						    <div class="description">
									<div  id="remPostConfirm" class="mini ui button">Remove</div >
								</div>
								<div class="errors"></div>
						    </div>
						  </div>
						</div>

						<div class="ui second modal" id="remListModal">

						  <i class="close icon"></i>
						  <div class="header">
						    Are you sure you want to remove this list?
						  </div>
						  <div class="content">
						    <div class="description">
								<div  id="remListConfirm" class="mini ui button">Remove</div >
							</div>
							<div class="errors"></div>
						    </div>
						  </div>
						  
						</div>

						<div class="ui second modal" id="editListModal">
						  <i class="close icon"></i>
						  <div class="header">
						    Edit List
						  </div>
						  <div class="content">
						    <div class="description">
									<div  id="editListConfirm" class="mini ui button">Save</div >
								</div>
								<div class="errors"></div>
						    </div>
						  </div>
						</div>
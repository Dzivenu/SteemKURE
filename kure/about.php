<?php
define('PROPER_ACCESS', true);
require_once ('config.php');
require_once ('header.php');
require_once ('header_sub.php');
?>


					<div class="ui tab active" id="aboutPage">

						<div class="ui blue fluid loading segment" id="mainContent">
							<div class="ui horizontal divider">About KURE</div>
							<div class="content">
								<div class="center"><img src="assets/img/steemkure-white-40.png" /></div>
								<p>SteemKURE is a network hub to easily find content others value, upvote it, and help others find content you value. For those who have bots, autovoting or trails to follow, this might not appeal to you.</p>

								<p>Create your own curation lists where others can find and upvote what you add. This creates a curation network that grows as more people interconnect and interact in each others lists.</p>

								<p><h4>Why?</h4>Do you recommend or resteem every posts you upvote?<br/>Would you want to share posts for others to find, but not resteem and clutter people's feeds?<br/>Would you recommend someone else upvote all of what you upvote?<br/>What if they are not interested in all your votes, and want to put their votes towards content they want to support?<br/>Would you recommend all 40+ of your daily upvotes as recommended picks? I don't think so.</p>

								<p>SteemKURE is where you get more control over what you want to find and share as the most valued material you find on steemit.com.</p>

								<p><h4>Features:</h4>
								<ul>									
									<li>Create your own Curation Project</li>
									<li>Create your own criteria for adding to your list</li>
									<li>Submit posts to your curation lists</li>
									<li>Add other users as members to your list.</li>
									<li>Add posts to other lists you are a member of.</li>
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
								<p><h4>Secure Login:</h4>
								The login uses <a href="https://steemconnect.com/">SteemConnect</a>, which doesn't store your password. Use your posting key to login, not active or owner keys.</p>
								<p><h4>Contact:</h4>
								If you want to contact me, send an email to admin@steemkure.com</p>
								<p><em>Please read the <a href="#" id="showTOS">Terms of Service</a> before you start to make sure you agree to certain restrictions.</em></p>
								<div id="kurErrors"></div>
							</div>
						</div>

						<div class="ui modal" id="tosModal">
						  	<i class="close icon"></i>
						  	<div class="header">
						    	Terms of Service
						  	</div>
						  	<div class="content">
						    	<div class="description">
									<p>In using this service, you agree to the following:
									<br/>
									<ul><li>No porn lists/posts</li><li>No game-pick lottery/gambling lists/posts</li></ul>
									<p>These terms are subject to change.</p>
									<p>Thank you.</p>
								</div>
								<div class="errors"></div>
						    </div>
						</div>

<?php
require_once ('footer.php');
?>
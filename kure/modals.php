						<div class="ui modal" id="addPostModal">
						  	<i class="close icon"></i>
						  	<div class="header">
						    	Add a post to the list: <span id="addListName"></span>
						  	</div>
						  	<div class="content">
						    	<div class="description">
						    		<div class="inline field">
										<label>Steemit URL: </label><input type="text" id="postLink" data-url="" placeholder="https://steemit.com/tag/@user/permalink" maxlength="200" size="50" class="" > &nbsp;
										<div id="addPostConfirm" class="mini ui button">Add</div >
									</div>
								</div>
								<div class="errors"></div>
						    </div>
						</div>

						<div class="ui modal" id="remPostModal">
						    <i class="close icon"></i>
						  	<div class="header">
						    	Are you sure you want to remove this post?
						  	</div>
						  	<div class="content">
						    	<div class="description">
									<div id="remPostConfirm" class="mini ui button">Remove</div >
								</div>
								<div class="errors"></div>
						    </div>
						</div>


						<div class="ui modal" id="remListModal">

						  	<i class="close icon"></i>
						  	<div class="header">
						    	Are you sure you want to remove this list?
						  	</div>
						  	<div class="content">
						    	<div class="description">
									<div id="remListConfirm" class="mini ui button">Remove</div >
								</div>
								<div class="errors"></div>
						    </div>
						</div>

						<!-- Make VIEW and EDIT their own pages, 
						modal this plus above double modal no work -->
						<div class="ui modal" id="viewListModal">
						  	<i class="close icon"></i>
						  	<div class="header">
						    	View List
						  	</div>
						  	<div class="content">
						    	<div class="description">
									
								</div>
								<div class="errors"></div>
						    </div>
						</div>

						<div class="ui modal" id="addMemModal">
						  	<i class="close icon"></i>
						  	<div class="header">
						    	Add a member to the list: <span id="addMemName"></span>
						  	</div>
						  	<div class="content">
						    	<div class="description">
						    		<div class="inline field">
						    			<div class="ui compact selection dropdown">
											<div class="text">Pick a user</div>
											  	<i class="dropdown icon"></i>
											  	<div class="menu">
											  	</div>
											</div>
										<div id="addMemConfirm" class="mini ui button">Add</div >
									</div>
								</div>
								<div class="errors"></div>
						    </div>
						</div>

						<div class="ui modal" id="remMemModal">
						    <i class="close icon"></i>
						  	<div class="header">
						    	Are you sure you want to remove this member?
						  	</div>
						  	<div class="content">
						    	<div class="description">
									<div id="remMemConfirm" class="mini ui button">Remove</div >
								</div>
								<div class="errors"></div>
						    </div>
						</div>
<?php
if (!defined('PROPER_ACCESS')) {
	exit;
}
?>
					<div class="ui active" id="homePage">
						<div class="ui blue fluid segment hidden loading" id="mainContent">
						    <div class="ui horizontal divider">Quick Management</div>
						    <div class="content">
						        <div class="ui pointing borderless basic blue menu" id="myMenu">
									<a class="item active" data-tab="listManage">Lists</a>
									<!--<a class="item" data-tab="listAdd">Add</a>-->
									<a class="item" data-tab="listCreate">Create</a>
									<!--<a class="item" data-tab="listFind">Find</a>-->
									<!--<a class="item" data-tab="listFollow">Follow</a>-->
						        </div>
						        <div class="ui tab active" data-tab="listManage" id="listManage">
						            <div class="intro">
						                <h3>Lists You Manage</h3>
						            </div>
						            <div class="ui message content">
						                <div class="ui grid container" id="manageSelect">
						                    
						                </div>
						            </div>
						        </div>
						        <div class="ui tab" data-tab="listCreate" id="listCreate">
						            <div class="intro">
						                <h3>Create a new Curation List</h3>
						            </div>
						            <div class="ui message content">
						                <div class="ui form">
						                    <div class="inline field">
						                        <label>List Name: </label>
						                        <input type="text" id="listName" readonly placeholder="ex: Nature" class=""> &nbsp;
						                        <div id="createList" class="mini ui button">Create</div>
						                    </div>
						                </div>
						            </div>
						        </div>
						        <div class="ui tab" data-tab="listFollow" id="listFollow">
						            <div class="intro">
						                <h3>Follow another Curation List</h3>
						            </div>
						            <div class="ui message content">
						                <div class="ui grid container" id="listLists">Find a list to Follow here.</div>
						            </div>
						        </div>
						        <div id="kurErrors"></div>
						    </div>
						</div>

						<!-- RECENT POSTS -->
						<div class="ui blue fluid segment" id="recentPosts">
							<div class="ui horizontal divider">Recent Posts Added to SteemKure</div>
							<div class="content">
									
						<?php

						require_once  'assets/medoo.php';
						require_once  'config.php';
						$steemitURL = "https://steemit.com";
						$newPostsLimit = 10;
						$unicodeChar = '\u2022';

						$database = new medoo([
							// required
							'database_type' => 'mysql',
							'database_name' => DB_NAME,
							'server' => DB_HOST,
							'username' => DB_USER,
							'password' => DB_PASSWORD,
							'charset' => 'utf8',
							'option' => [
								PDO::ATTR_CASE => PDO::CASE_NATURAL
							]
						]);

						$datas = $database->query("SELECT p.st_url, l.name, l.id FROM curation_posts p LEFT JOIN curation_lists l ON p.list_id = l.id ORDER BY p.id DESC LIMIT ".$newPostsLimit."")->fetchAll(PDO::FETCH_ASSOC);

						$html = "";

						$html = '<ul class="titles"><li><div class="ui grid"><div class="ui fourteen wide column">Post</div><div class="ui two wide column">List</div></div></li><li><hr/></li>';
						if (!empty($datas)) {
						//echo "data: "; print_r($datas);
							foreach ($datas as $key) {
								//print_r($key);
								$url = $key['st_url'];
								$id = $key['id'];
								$name = $key['name'];

								$html .= '<li><div class="ui grid"><div class="ui fourteen wide column">'.json_decode('"'.$unicodeChar.'"').' <a href="'.$url.'">'.  str_replace($steemitURL, '', $url) .'</a></div><div class="ui two wide column"><a href="#" id="viewListButton" onclick="showViewList('.$id.', \''.$name.'\')">' . $name . '</a></div></div></li>';
							}
						}else {
							$html .= "There are no KURE lists yet. You can be the first!";
						}

						$html .= "</ul>";
						echo $html;
						?>
									
								</div>
							</div>
						</div>


						<?php
						$topListLimit = 5;
						$topListPostLimit = 6;

						?>
						<div class="ui blue fluid segment" id="topLists">
							<div class="ui horizontal divider">Top <?php echo $topListLimit ?> KURE Lists</div>
							<div class="content">
						<?php

						$datas = $database->query("SELECT l.id, l.name, l.owner_id, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts' FROM curation_lists l  ORDER BY totalposts DESC LIMIT ".$topListLimit."")->fetchAll(PDO::FETCH_ASSOC);
						$res = [];
						$html = '';
						if (!empty($datas)) {
							foreach ($datas as $key => $val) {
								$dbres = $database->query("SELECT st_url FROM curation_posts WHERE list_id = '".$val["id"]."' ORDER BY id DESC LIMIT ".$topListPostLimit."")->fetchAll(PDO::FETCH_ASSOC);

								if (!empty($dbres)) $val["titles"] = $dbres;
								$res[$key] = $val;
							}

							foreach ($res as $key) {
								//print_r($key);
								$followers = $key['followers'];
								$totalposts = $key['totalposts'];
								$id = $key['id'];
								$name = $key['name'];
								$titles = "";
								$titles = '<ul class="titles">';
								//echo $followers . $totalposts . $id . $name . $titles;
								if (isset($key['titles'])) {
									foreach ($key['titles'] as $index) {
										if (isset($index['st_url'])) $url = $index['st_url'];
										$titles .= '<li><div class="ui grid"><div class="ui sixteen wide column">'.json_decode('"'.$unicodeChar.'"').' <a href="'.$url.'">'.  str_replace($steemitURL, '', $url) .'</a></div></div></li>';
									}
								}
								$titles .= "</ul>";
								
								$html .= 
									'<div class="ui segment">
										<div data-id="' . $id . '" id="list' . $id . '">' .  
											'<div class="listTitle"><h4><a href="#" id="viewListButton" onclick="showViewList('.$id.', \''.$name.'\')">' . $name . '</a></h4></div>' . 
											'<div>' . $titles . 
											'</div>' .
											'<div class="footList"><p>Posts: <span class="post">'.$totalposts.'</span>' . 
											'</p></div>' . 
										'</div>' .
								    '</div>';
							}
						}else {
							$html = "There are no KURE lists yet. You can be the first!";
						}
						

						
						echo $html;

						?>
							
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

<?php
require_once ('modals.php');
?>
					</div>
						
<?php
define('PROPER_ACCESS', true);
require_once ('config.php');
require_once ('header.php');
require_once ('header_sub.php');
?>

					<div class="ui active" id="listsPage">
						<div class="ui blue fluid segment loading" id="mainContent">
							<div class="ui horizontal divider">KURE Lists</div>
							<div class="content main">
								<div class="manageSections" id="kureLists">
						<?php
						$topListLimit = "";
						$topListPostLimit = " LIMIT 6";
						$unicodeChar = '\u2022';

						require_once  'assets/medoo.php';
						require_once  'config.php';
						$steemitURL = "https://steemit.com";


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

						$datas = $database->query("SELECT l.id, l.name, l.owner_id, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts' FROM curation_lists l  ORDER BY totalposts DESC".$topListLimit."")->fetchAll(PDO::FETCH_ASSOC);
						$res = [];
						$html = '';
						if (!empty($datas)) {
							foreach ($datas as $key => $val) {
								$dbres = $database->query("SELECT st_url FROM curation_posts WHERE list_id = '".$val["id"]."' ORDER BY id DESC".$topListPostLimit."")->fetchAll(PDO::FETCH_ASSOC);

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
							</div>
						</div>
					</div>
<?php
require_once ('footer.php');
?>
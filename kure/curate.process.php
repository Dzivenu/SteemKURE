<?php
ini_set('display_errors', 1); //testing
error_reporting(E_ALL); //testing

require_once  'assets/medoo.php';
require_once  'config.php';

$mode = "";
$datas = "";
$listLimit = 3;

if ($_POST) {
	if (isset($_POST["mode"])) {
		$mode = $_POST['mode'];
	}
}

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


try {
	/*$fd = fopen('file.txt', "a");
   // write string
   fwrite($fd, "test" . "\n");
   // close file
   fclose($fd)*/
	/*$file = 'log_'.date("j.n.Y").'.txt';
	$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "POST: ".$_POST.PHP_EOL;
	file_put_contents($file, $log, FILE_APPEND);*/

	//add new user to the DB to match Steemit user logging in
	if ($mode == "login") {
		if (isset($_POST["user"])) {
			$user = $_POST['user'];
			if ($user != "") {
				if (!$database->has("curation_users", ["name" => $user])) {
					$database->insert("curation_users", [
						"name" => $user
					]);
				}
			}
		}
		
	}elseif ($mode == "createList") {
		if (isset($_POST["lname"])) {
			$listName = $_POST['lname'];
			$slugName = preg_replace('/\s/', '-', strtolower($listName));
			
		    if ($database->has("curation_lists", ["slug" => $slugName])) {
		    	$datas = "This list name already exists: " . $listName;
		    }else {
		    	//get username
		    	$user = "";
		    	if (isset($_POST["user"]) && $_POST["user"] != "") {
					$user = $_POST['user'];

					//get user id from name
			    	$user_id = $database->get("curation_users", "id", [
						"name" => $user
					]);
				//$datas = $database->count("curation_lists", ["owner_id" => $user_id]);
			    	if ($database->count("curation_lists", ["owner_id" => $user_id]) < $listLimit) {

				    	//insert name and owner id
						$database->insert("curation_lists", [
							"name" => $listName,
							"slug" => $slugName,
							"owner_id" => $user_id,
							"followers" => '1'
						]);
						$list_id = $database->get("curation_lists", "id", [
							"slug" => $slugName
						]);
						$database->insert("curation_list_access", [
							"user_id" => $user_id,
							"list_id" => $list_id,
							"access" => '0'
						]);

						$list_id = $database->get("curation_lists", "id", [
							"name" => $listName
						]);

						$datas = array( 0 => ["id" => $list_id, "name" => $_POST['lname'], "followers" => 1, "totalposts" => 0]);
					}else {
						$datas = "You already have the maximum of " . $listLimit . " lists. <br/><br/>This is to prevent abuse of having too many duplicate categorical lists. Thank you.";
					}
				}
		    }
		    echo json_encode($datas);
		}
		//
	}elseif ($mode == "manageLists") {
		$listLimit = 100;
    	$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}
		//get user id from name
    	$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);

//$posts = $database->query("SELECT cp.st_url, l.id, l.name, (SELECT u.name FROM curation_users u WHERE u.id = '".$user_id."') AS 'adder' FROM curation_lists l LEFT JOIN curation_posts cp ON cp.list_id = l.id WHERE l.id = '".$list_id."' ORDER BY cp.id DESC")->fetchAll(PDO::FETCH_ASSOC);

    	$datas = $database->query("SELECT l.id, l.name, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts' FROM curation_lists l WHERE l.id IN (SELECT list_id FROM curation_list_access WHERE user_id = '".$user_id."' AND (access = '0' OR access = '1')) ORDER BY name ASC LIMIT ".$listLimit."")->fetchAll(PDO::FETCH_ASSOC);
		//$datas = $database->query("SELECT l.id, l.name, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts' FROM curation_lists l WHERE l.owner_id = '".$user_id."' ORDER BY name ASC LIMIT ".$listLimit."")->fetchAll(PDO::FETCH_ASSOC);
		$res = [];
		foreach ($datas as $key => $val) {
			//print_r($val);
			//if ($val == "id") {

				//LIMIT 2 urls, only showing 3 posts per LIST in user's MANAGE LIST section,else clutter area
				//get individual post data, append to list array data
				$dbres = $database->query("SELECT st_url FROM curation_posts WHERE list_id = '".$val["id"]."' ORDER BY id DESC LIMIT ".$listLimit."")->fetchAll(PDO::FETCH_ASSOC);
				//print_r($dbres);
				//array_push($res, $val, ["titles" => $dbres]);
				//array_push($res, array_push($val, "titles" => $dbres));
				if (!empty($dbres)) { $val["titles"] = $dbres; }
				//array_push($val, ["titles" => $dbres]);
				$res[$key] = $val;
			//}else {
				//array_push($res, [$key => $val]);
			//}

		}

		echo json_encode($res, JSON_UNESCAPED_SLASHES);

	}elseif ($mode == "getLists") {
    	$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}
		//get user id from name
    	$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);

    	$lists = $database->query("SELECT l.id, l.name FROM curation_lists l INNER JOIN curation_list_access la ON la.list_id = l.id WHERE la.user_id = '".$user_id."' ORDER BY l.name")->fetchAll(PDO::FETCH_ASSOC);
    	//print_r($lists);
    	//echo json_encode($lists, JSON_UNESCAPED_SLASHES);
    	echo json_encode($lists);
	}elseif ($mode == "getListUsers") {
		$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}
		//get user id from name
    	$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);

		$users = $database->query("SELECT id, name FROM curation_users WHERE id <> '".$user_id."' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($users);

	}elseif ($mode == "getManagePosts") {
		$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}
		//get user id from name
    	$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);
		$list_id = $_POST["listid"];
//, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts'
		$posts = $database->query("SELECT cp.st_url, l.id, l.name, cp.adder_id, (SELECT u.name FROM curation_users u WHERE u.id = cp.adder_id) AS 'adder' FROM curation_lists l LEFT JOIN curation_posts cp ON cp.list_id = l.id WHERE l.id = '".$list_id."' ORDER BY cp.id DESC")->fetchAll(PDO::FETCH_ASSOC);
		$tPosts = $database->query("SELECT FOUND_ROWS() AS tPosts")->fetchAll(PDO::FETCH_ASSOC);

//problem with returning matches not part of list, ex list 74, returning 76 other user, wrong list
		$members = $database->query("SELECT u.id, u.name, la.access, la.list_id FROM curation_users u INNER JOIN curation_list_access la ON la.user_id = u.id WHERE la.list_id = '".$list_id."' AND (la.access = '0' OR la.access = '1') GROUP BY u.name")->fetchAll(PDO::FETCH_ASSOC);

		$access = $database->query("SELECT access FROM curation_list_access WHERE user_id = '".$user_id."' AND list_id = '".$list_id."'")->fetchAll(PDO::FETCH_ASSOC);

		$res = ["posts" => $posts, "members" => $members, "user" => $user_id, "access" => $access, "tPosts" => $tPosts];
		echo json_encode($res, JSON_UNESCAPED_SLASHES);

	}elseif ($mode == "addPost") {
		if (isset($_POST["url"])) {
			$listid = $_POST['id'];
			$user = $_POST['user'];
			$url = $_POST['url'];

			//check if record exists, then insert if it doesnt
			$check = $database->has("curation_posts", [
						"AND" => [
							"list_id" => $listid, 
							"st_url" => $url
						]
					]);
			if (!$check) {
				$uid = $database->get("curation_users", "id", ["name" => $user]);

				//if ($uid != "") {*/
					$database->insert("curation_posts", [
							"adder_id" => $uid,
							"list_id" => $listid,
							"st_url" => $url
						]);
				//}
				//$datas = ["id" => $id, "url" => $url]; //return list ID for updates
				//$datas = ["id" => $id, "lname" => ];
				$datas = $listid;
				echo json_encode($datas);
			}else {
				echo json_encode('<div class="ui error message"><div class="ui red basic label">This post is already in the curation list.</div></div>');
			}			
		}
	}elseif ($mode == "remPost") {
		if (isset($_POST["url"])) {
			$id = $_POST['id'];
			$user = $_POST['user'];
			$url = $_POST['url'];

			$uid = $database->get("curation_users", "id", ["name" => $user]);

			//if ($uid != "") {
				$database->delete("curation_posts", [
						"AND" => [
							"adder_id" => $uid,
							"list_id" => $id,
							"st_url" => $url
						]
					]);
			//}
			//$datas = ["id" => $id, "url" => $url]; //return list ID for updates
			$datas = $url;
			echo json_encode($datas);

		}
	}elseif ($mode == "remList") {
		if (isset($_POST["user"])) {
			$id = $_POST['id'];
			$user = $_POST['user'];

			$uid = $database->get("curation_users", "id", ["name" => $user]);

			if ($uid != "" && $uid != undefined) {
				$database->delete("curation_posts", [
						"AND" => [
							"adder_id" => $uid,
							"list_id" => $id
						]
					]);
				$database->delete("curation_lists", [
						"AND" => [
							"owner_id" => $uid,
							"id" => $id
						]
					]);
				$database->delete("curation_list_access", [
						"AND" => [
							"user_id" => $uid,
							"list_id" => $id
						]
					]);
				/* Combine the two deletes into one query...
				$database->delete("curation_posts", [
						"AND" => [
							"adder_id" => $database->get("curation_users", "id", ["name" => $user]),
							"list_id" => $id,
							"st_url" => $url
						]
					]);
					*/
			}
			//$datas = ["id" => $id, "url" => $url]; //return list ID for updates
			$datas = $name;
			echo json_encode($datas);

		}
	}elseif ($mode == "viewList") {
		//$listLimit = 3;
		// LIMIT ".$listLimit."
		$id = $_POST['id'];

		$datas = $database->query("SELECT l.id, l.name, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts', cp.st_url FROM curation_lists l LEFT JOIN curation_posts cp ON cp.list_id = l.id WHERE l.id = '".$id."' ORDER BY l.name ASC")->fetchAll(PDO::FETCH_ASSOC);
		/*$res = [];
		foreach ($datas as $key => $val) {
			//print_r($val);
			//if ($val == "id") {

				//LIMIT 2 urls, only showing 3 posts per LIST in user's MANAGE LIST section,else clutter area
				//get individual post data, append to list array data
				$dbres = $database->query("SELECT st_url FROM curation_posts WHERE list_id = '".$val["id"]."' ORDER BY id DESC LIMIT ".$listLimit."")->fetchAll(PDO::FETCH_ASSOC);
				//print_r($dbres);
				//array_push($res, $val, ["titles" => $dbres]);
				//array_push($res, array_push($val, "titles" => $dbres));
				if (!empty($dbres)) { $val["titles"] = $dbres; }
				//array_push($val, ["titles" => $dbres]);
				$res[$key] = $val;
			//}else {
				//array_push($res, [$key => $val]);
			//}

		}*/
		echo json_encode($datas);
	}elseif ($mode == "addMember") {
		$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}

		$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);
		$list_id = $_POST["id"];
		$addedUser = $_POST['addedUser'];
		$datas = false;

		if ($database->has("curation_list_access", [
			"AND" => [
				"user_id" => $user_id,
				"list_id" => $list_id,
				"access" => '0'
				]
			])) 
		{
			$database->insert("curation_list_access", [
								"user_id" => $addedUser,
								"list_id" => $list_id,
								"access" => '1'
							]);
			$datas = true;
		}
	
		echo json_encode($datas);

	}elseif ($mode == "remMember") {
		$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}

		$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);
		$list_id = $_POST["id"];
		$addedUser = $_POST['addedUser'];
		

		//$access = $database->query("SELECT access FROM curation_list_access WHERE user_id = '".$user_id."' AND list_id = '".$list_id."'")->fetchAll(PDO::FETCH_ASSOC);
		$database->delete("curation_list_access", [
				"AND" => [
					"user_id" => $addedUser,
					"list_id" => $list_id,
					"access" => '1'
				]
			]);
		$datas = true;
		//$datas = ["id" => $id, "url" => $url]; //return list ID for updates
		//$datas = $url;
		echo json_encode($datas);

	}elseif ($mode == "joinList") {
		//
	}else { //testing
		$datas = $database->select("curation_users", "name"); //testing
		echo json_encode($datas);
	}
} catch(Exception $e) {
	echo "MySQL exception: " . $e->getMessage();
}
?>
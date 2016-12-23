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
 
	// [optional]
	'port' => 3306,
 
	// [optional] Table prefix
	//'prefix' => 'PREFIX_',
 
	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	]
]);


try {
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
			$listName = strtolower($_POST['lname']);
			
		    if ($database->has("curation_lists", ["name" => $listName])) {
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
							"owner_id" => $user_id,
							"followers" => '1'
						]);

						$list_id = $database->get("curation_lists", "id", [
							"name" => $listName
						]);

						$datas = array( 0 => ["id" => $list_id, "name" => $_POST['lname'], "followers" => 1, "totalposts" => 0, "m" => "c"]);
					}else {
						$datas = "You already have the maximum of " . $listLimit . " lists. <br/><br/>This is to prevent abuse of having too many duplicate categorical lists. Thank you.";
					}
				}
		    }
		    echo json_encode($datas);
		}
		//
	}elseif ($mode == "manageLists") {
    	$user = "";
    	if (isset($_POST["user"])) {
			$user = $_POST['user'];
		}
		//get user id from name
    	$user_id = $database->get("curation_users", "id", [
			"name" => $user
		]);


		$datas = $database->query("SELECT l.id, l.name, l.followers, (SELECT COUNT(*) FROM curation_posts p WHERE p.list_id = l.id) AS 'totalposts' FROM curation_lists l WHERE l.owner_id = '".$user_id."'")->fetchAll(PDO::FETCH_ASSOC);
		$res = [];
		foreach ($datas as $key => $val) {
			//print_r($val);
			//if ($val == "id") {
				/*$dbres = $database->select("curation_posts", [
					"st_url"
				], [
					"LIMIT" => 2, 
					"list_id" => $val["id"], 
					"ORDER" => "curation_posts.id DESC"

				]);*/
				$dbres = $database->query("SELECT st_url FROM curation_posts WHERE list_id = '".$val["id"]."' ORDER BY id DESC LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
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
//print_r( $res);
		//echo json_encode($res);
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
				$uid = "";
				$uid = $database->get("curation_users", "id", ["name" => $user]);

				if ($uid != "") {
					$database->insert("curation_posts", [
							"adder_id" => $uid,
							"list_id" => $listid,
							"st_url" => $url
						]);
				}
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

			$uid = "";
			$uid = $database->get("curation_users", "id", ["name" => $user]);

			if ($uid != "") {
				$database->delete("curation_posts", [
						"AND" => [
							"adder_id" => $uid,
							"list_id" => $id,
							"st_url" => $url
						]
					]);
			}
			//$datas = ["id" => $id, "url" => $url]; //return list ID for updates
			$datas = $url;
			echo json_encode($datas);

		}
	}elseif ($mode == "remList") {
		if (isset($_POST["user"])) {
			$id = $_POST['id'];
			$user = $_POST['user'];

			$uid = "";
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
	}elseif ($mode == "joinList") {
		//
	}elseif ($mode == "addUser") {
		//
	}else { //testing
		$datas = $database->select("curation_users", "name"); //testing
		echo json_encode($datas);
	}
} catch(Exception $e) {
	echo "MySQL exception: " . $e->getMessage();
}
?>
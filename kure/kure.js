/**
 *	@author 	krnel
 *  @version 	0.1
 * 	@date 		2016-12-22
 *
 */
var kure = function(){
	var account = null;
	var username = "";
	var html = "";
	const steemitURL = "https://steemit.com";
	const site = "http://localhost/";
	steemconnect.init({
	      app: 'krnel',
	      callbackURL: site
	    });
	var isAuth = false;
	var loginURL = steemconnect.getLoginURL();
	const options = {
	    apis: ["database_api", "network_broadcast_api"],
	    url: "wss://node.steem.ws"
	  };

	function kurate(div) {
		const limit = 15;
		const excerptLimit = 110;
		const steemIMG = "https://img1.steemit.com/128x256/";		
		var image = '';
		var startAuthor = "";
		var startPermlink = "";
		var postsList = "<ul>";
		var $lastPost = null;

		$lastPost = $("#newPosts > ul > li").last();
		startAuthor = $lastPost.find("span").attr('data-id');
		startPermlink = $lastPost.find("a").attr('href');

		startPermlink = (startPermlink != undefined) 
				? startPermlink.substr(startPermlink.lastIndexOf("/")+1)
				: "";

		var loadNextPosts = false;
		if (startAuthor != undefined && startPermlink != "") {
			loadNextPosts = true;
		}

		var args = {limit: limit, start_author: startAuthor, start_permlink: startPermlink};

		try {
			steem.api.getDiscussionsByCreatedAsync(args).then((data) => {
			    console.log(data);

				for (var post in data) {
					if (loadNextPosts) {
						loadNextPosts = false;
						continue;
					}

					var val = data[post];	
					var author = val["author"];
					image = (val['json_metadata'] != "" && val['json_metadata'] != undefined) ? JSON.parse(val['json_metadata'])["image"] : "";
					var excerpt = val["body"];
					var imageLink = "";

					if (image instanceof Object) {
						//image = '<img src="' + steemIMG + image.shift() + '" />';
						excerpt = excerpt.replace(image, '');
						image = steemIMG + image.shift();
						//excerpt = val["body"].replace(image, '');
					}

					var imageLink = (image != undefined && image != "") ? '<a href="' + steemitURL + val["url"] + '" class="summaryPostImage" style="background-image: url('+ image +')"></a>' : "";
            		//excerpt = excerpt.replace(/<[\/\!]*?[^<>]*?>/g, '');
            		excerpt = cleanUp(excerpt);
            		//excerpt = excerpt.replace(/http.*(?:png|jpg|jpeg|gif)/g, '');
            		excerpt = excerpt.length > excerptLimit ? excerpt.substr(0,excerptLimit) + " ..." : excerpt;
					//excerpt = (excerpt.substr(0,excerptLimit)).replace(/http.*(?:png|jpg|jpeg|gif)/g, "");

					//voteClasses = "voting__button voting__button-up Voting__button--upvoted";
					var voteClasses = "voting__button voting__button-up";

					//voteClasses = checkUpvoted(); //put function here, see if it doesnt fuck up as async...

					var voteTitle = "Upvote";

				    postsList += '<li>\
			    			<div>\
			    				'+ imageLink +'\
				    			<div class="summaryPostContent">\
				    				<a href="' + steemitURL + val["url"] + '">' + val["title"] + '</a>\
				    				<br/>by \
				    				<a href="' + steemitURL + '/@' + author + '">\
				    					<span id="postAuthor" data-id="' + author + '">' + author + '</span>\
				    				</a>\
				    				<p>'+
				    				excerpt
				    				+'</p>'
				    			+'</div>' +
				    		'</div>\
				    	</li>';
				    	//onClick={voteUpClick} title={myVote > 0 ? 'Remove Vote' : 'Upvote'}


				    image = "";
				    
				//});
				}

				postsList += "</ul>";
				$(div).append(postsList);

				$('.voting__button a').on("click", function (e){
					e.preventDefault(); //is this really needed?
					$(this).parent().toggleClass("voting__button--upvoted");
					$(this).attr("title", function(index, val){
						return val = (val == "Upvote" ? "Remove upvote" : "Upvote"); //change to be based on "myVote > 0"
					});
				});
			});
			

		} catch (e) {
          console.log(e);
          //$("#loginErrors").show();
          
          $(div).append('<div class="ui error message"><div class="ui red basic label">Unknown error caught: ' + e + '</div></div>');
        }
	}

	function cleanUp(input) {
	    var output = input;
	    try {
	        //output = output.replace(/^([\s\t]*)([\*\-\+]|\d\.)\s+/gm, '$1');
	        output = output
	        	//Remove Tags
	        	.replace(/<[\/\!]*?[^<>]*?>/g, '')
	            //Remove HTML tags
	            .replace(/<(.*?)>/g, '$1')
	            //Remove setext-style headers
	            .replace(/^[=\-]{2,}\s*$/g, '')
	            //Remove images
	            .replace(/\!\[.*?\][\[\(].*?[\]\)]/g, '')
	            //Remove inline links
	            .replace(/\[(.*?)\][\[\(].*?[\]\)]/g, '$1')
	            // Remove Blockquotes
	            .replace(/>/g, '')
	            //Remove reference-style links?
	            .replace(/^\s{1,2}\[(.*?)\]: (\S+)( ".*?")?\s*$/g, '')
	            //Removeimages
	            .replace(/http.*(?:png|jpg|jpeg|gif)/g, '');
	    } catch (e) {
	        console.error(e);
	        return md;
	    }
	    return output;
	};

	function genManageList(res, cb) {

		cb = (typeof cb === 'undefined') ? function(v){} : cb;
		var html = "";

		if (res[0].m != "c") {
			$('#manageSelect').html("");
			
		}
		for (var key in res) {
			var followers = res[key].followers;
			var totalposts = res[key].totalposts;
			var id = res[key].id;
			var name = res[key].name;
			var titles = "";
			titles = '<ul class="titles">';
			if (typeof res[key].titles === "object") {
				
				for (var index in res[key].titles) {
					var url = res[key].titles[index].st_url;
					titles += '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a></div><div class="ui two wide column"><a title"Remove this post" href="#" onclick="showRemPost('+id+', \''+url+'\', \''+name+'\')" id="remPostButton"><i class="ui remove grey circle icon"></i></a></div></div></li>';
				}
				
			}
			titles += "</ul>";
			
			html += 
				'<div class="ui eight wide column"><div class="ui segment" data-id="' + id + '" id="list' + id + '">' +  
				'<div class="listTitle"><h4 class="left">' + name + '</h4><div class="right"><a href="#" id="editListButton" onclick="showEditList('+id+')"><i class="edit icon"></i></a>&nbsp;<a href="#" id="remListButton" onclick="showRemList('+id+', \''+name+'\')"><i class="remove icon"></i></a></div><div class="clear"></div></div>' + 
				'<div>' + titles + 
				'</div>' +
				'<div class="footList"><div class="left"><p>Followers: <span class="follow">'+followers+'</span>' + 
				'<br/>Posts: <span class="post">'+totalposts+'</span>' + 
				/*'<br/>Upvotes: <span class="upvote">'+res[key].upvotes+'</span>' + */
				'</p></div>' + 
				'<div class="right"><a href="#" onclick="showAddPost('+id+', \''+name+'\')" class="addPostButton"><i class="ui add blue circle icon"></i></a></div><div class="clear"></div></div>' +
			    '</div></div>';
		}

		$('#manageSelect').append(html);

		$( "#remPostButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("grey");
			$(this).toggleClass("red");
		  }
		);
		$( ".addPostButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("inverted");
		  }
		);
		$( "#editListButton i, #remListButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("grey");
		  }
		);	

		cb(true);
	}

	showAddPost = function(id, lname){
		if (isAuth) {
			$('#addPostConfirm').off('click').on("click", function(e){
				e.preventDefault();
				//on login click, show modal to login, if not already loggedin
				processAddPost(id, lname);
			});

			$("#addListName").html(lname);
			$("#postLink").val('');
			$('#addPostModal .errors').html('');
			$('#addPostModal')
				.modal({
			        onApprove : function() {
			            return false; //block the modal here
			        }
			    })
			    .modal('show')
		    ;
		}else { deniedAccess(); }
	}
	showRemPost = function(id, url, lname){
		if (isAuth) {
			$('#remPostModal .description').html('<p>Post: '+url+'</p><p>From List: '+lname+'</p><div  id="remPostConfirm" class="mini ui button">Remove</div >');
			$('#remPostConfirm').off('click').on("click", function(e){
				e.preventDefault();
				//on login click, show modal to login, if not already loggedin
				processRemPost(id, url, function(res){
					$("div[data-id='"+id+"'] a[href='"+url+"']").parents("li").remove();
					$('#list'+id+' .post').html(+($('#list'+id+' .post').html()) - 1);
				})
			});
			$('#remPostModal').modal('show');
		}else { deniedAccess(); }
	}
	showRemList = function(id, lname){
		if (isAuth) {
			$('#remListConfirm').off('click').on("click", function(e){
				e.preventDefault();
				//on login click, show modal to login, if not already loggedin
				processRemList(id, function(res){
					$('[data-id="'+id+'"]').parent().remove();
				})
			});
			$('#remListModal .header').html("Are you sure you want to remove this list?<br/><br/><center>"+lname+"</center>");
			$('#remListModal').modal('show');
		}else { deniedAccess(); }
	}
	
	function loginCheck() {

	    steemconnect.isAuthenticated(function(err, result) {
		    if (!err && result.isAuthenticated) {
		        isAuth = true;
		        username = result.username;

		        if (!localStorage.getItem("username")) {
		        	(function(){
		            	var mode = "login";
						var request = $.ajax({
						  url: "curate.process.php",
						  method: "POST",
						  data: { mode: mode, user : username },
						  //dataType: "json"
						});
						request.done(function( msg ) {
							localStorage.setItem("username", username);
						  //$( "#mainContent .ui.message.content" ).append( msg );
						});
						request.fail(function( jqXHR, textStatus ) {
						  $('#kurErrors').append('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
						});
					})();
		    	}

		  		$("#loginLink").html("Logout");

				if(window.location.href.indexOf("kurate") == -1) {
					getUsersLists(function(data){
						if (data != "") {
							genManageList(data, function(res){

								if (res) {
									$('#mainContent').removeClass("loading");
								}
							});
						}else {
							$('#manageSelect').html('You have no KURE Lists for curating. Please go to the "New" tab to create one.');
							$('#mainContent').removeClass("loading");
						}
					});
					$('#listName').removeAttr("readonly");
				} else { 
					$('#mainContent').removeClass("loading");
				}
		    }else {
	  			//default display if not logged in on page load
	  			$('#mainContent').removeClass("loading");
		  		$('#mainContent .ui.message.content').append('<br/><div class="ui pointing basic orange label"><i class="lock icon"></i>Please login first.</div>');
		  		$('#createList').addClass('disabled');
			 }
	    });	
	}

	function showLogin() {
		if (!isAuth) {
		    window.location = loginURL;
	  	} else {
	  		localStorage.removeItem("username");
			window.location = "https://steemconnect.com/logout";
	  	}
	}

	function deniedAccess() {
		$('#kurErrors').html('<div class="ui red basic label">Please login with your Steemit.com account.</div>');
	}

	function init() {
		loginCheck();

		$('#loginLink').on("click", function(e){
			e.preventDefault();
			//on login click, show modal to login, if not already loggedin
			showLogin();
		});

		$('#myMenu').on("click", function(e){
			e.preventDefault();
			//on login click, show modal to login, if not already loggedin
			$('#kurErrors').html("");
		});

		//menu active adjustment
		(function() {
			var path = location.pathname.split("/")[1];
			if (path != "") {
				$('#nav a[href^="' + path + '"]').addClass('active');
				$('#home').removeClass("active");
			} 
		})();

		//TODO ADD
		//make timer delay. cant spam add. can only add every minute or something.
		$('#createList').on("click", function(e){
			e.preventDefault();
			if (isAuth) {
				processCreateList();
			}else { deniedAccess(); }
		});

		$('#genNewPosts').on("click", function (e){
			e.preventDefault();
			if (isAuth) {
				kurate('#newPosts');
			}else { deniedAccess(); }
		});		
		
	}

	function processRemList(id, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "remList", id: id, user: username },
		   // dataType: "json"
		});
		request.done(function( res ) {
			cb(res);
			$('#remListModal').modal('hide');	    
		});
		request.fail(function( jqXHR, textStatus, err ) {
		    $('#kurErrors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'<br/>err: '+err+'</div>');
		});
	}

	function processRemPost(id, url, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "remPost", id: id, user: username, url: url },
		    //dataType: "json"
		});
		request.done(function( res ) {
			cb(res);
			$('#remPostModal').modal('hide');		    
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
		});
	}

	function processAddPost(id, lname) {
		$('#addPostModal .errors').html('');
		var url = $("#postLink").val();
		if (url == '' || !url.match(/^https:\/\/steemit.com\/[a-z0-9\-]+\/[a-z0-9\-\.\@]+\/[a-z0-9\-]+$/)) {
			$('#addPostModal .errors').html('<div class="ui error message"><div class="ui red basic label">You must enter a valid steemit.com URL.<br/>eg: https://steemit.com/tag/@user/permalink</div></div>');
			return false;
		}else if (url == $("#postLink").attr("data-url") && id == $("#postLink").attr("data-id")) {
			$('#addPostModal .errors').html('<div class="ui error message"><div class="ui red basic label">You haven\'t changed the URL yet.</div></div>');
			return false;
		}

		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "addPost", id: id, user: username, url: url },
		  	dataType: "json"
		});
		request.done(function( res ) {
			//res = JSON.parse(res);
			if (res.match(/^[0-9]+$/)) { 
				var out = "";
				out += '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a></div><div class="ui two wide column"><a title"Remove this post" href="#" onclick="showRemPost('+id+', \''+url+'\', \''+lname+'\')" id="remPostButton"><i class="ui remove grey circle icon"></i></a></div></div></li>';

				$('#list'+id+' .titles').prepend(out);
				$('#list'+id+' .post').html(+($('#list'+id+' .post').html()) + 1);
				$('#addPostModal').modal('hide');
			}else {
				$("#addPostModal .errors").html("Error: "+res);
				$("#postLink").attr("data-url", url);
				return false; //???
			}
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
		    $('#addPostModal').modal('hide');
		});
	}

	function processCreateList() {
		$("#kurErrors").html(""); //clear error msgs from b4, if any
		var lName = "";
		lName = $.trim($("#listName").val());
		if (lName == '' || !lName.match(/.{3,20}/)) {
			$('#kurErrors').html('<div class="ui red basic label">You must enter a List Name of at least 3 letters long.</div>');
			return false;
		}else if (lName == '' || !lName.match(/^[a-zA-Z]{3,20}[a-zA-Z\s]*$/)) {
			$('#kurErrors').html('<div class="ui red basic label">You must use letters or spaces only.</div>');
			return false;
		}
		//username = sessionStorage.getItem("username");
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "createList", lname: lName, user: username },
		    dataType: "json"
		});
		request.done(function( res ) {
			$("#listName").val("");
			//res = JSON.parse(res);

			if (typeof res === "object") {
		    	$( "#listCreate .ui.message.content" ).append( "<p>Successufully created the new list: <strong>" + res[0].name + "</strong></p>");
		    	//$('#manageSelect').html('');
		    	genManageList(res);
		    }else {
		    	$("#kurErrors").html($("#kurErrors").html()+'<div class="ui red basic label">'+res+'</div>');
		    }		    
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
		});
	}

	//can centralize ajax, ifs to call separate actions?
	function getUsersLists(cb) {
		//var mode = "getLists";
	    var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "manageLists", user: username },
		    dataType: "json"
		});
		request.done(function( msg ) {
		    //$("#mainContent .ui.message.content" ).append( msg );
		    //console.log(msg);
		    cb(msg);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui error message"><div class="ui red basic label">Unable to process request due to: '+textStatus+'</div></div>');
		});
	}

	//closure function to make init public and start the ball rolling
	return {
		init:init
	}

}();
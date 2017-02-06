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
	var pageManage = "manage";
  	var pageKurate = "kurate";
  	var pageHome = "";
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
					//var voteClasses = "voting__button voting__button-up";

					//voteClasses = checkUpvoted(); //put function here, see if it doesnt fuck up as async...

					//var voteTitle = "Upvote";

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

				/*$('.voting__button a').on("click", function (e){
					e.preventDefault(); //is this really needed?
					$(this).parent().toggleClass("voting__button--upvoted");
					$(this).attr("title", function(index, val){
						return val = (val == "Upvote" ? "Remove upvote" : "Upvote"); //change to be based on "myVote > 0"
					});
				});*/
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
/*
**************************************************
* GENERATE
**************************************************
*/
	function genManageList(res, cb, returnID = "", listLimit = 6) {

		cb = (typeof cb === 'undefined') ? function(t){} : cb;
		var html = "";
		//var listLimit = 6;

		//clear default message of no lists
		for (var key in res) {
			var followers = res[key].followers;
			var totalposts = res[key].totalposts;
			var id = res[key].id;
			var name = res[key].name;
			var titles = "";
			titles = '<ul class="titles">';
			if (typeof res[key].titles === "object") {
				
				var i = 0;
				for (var index in res[key].titles) {
					i++;
					var url = res[key].titles[index].st_url;
					titles += '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a></div><div class="ui two wide column"><a title"Remove this post" href="#" onclick="showRemPost('+id+', \''+url+'\', \''+name+'\')" id="remPostButton"><i class="ui remove grey circle icon"></i></a></div></div></li>';
				}
				//if there are more lists than the limit to show on the page
				if (i > listLimit) {
					titles += '<li>&nbsp;</li><li><a href="#" id="viewListButton" onclick="showViewList('+id+', \''+name+'\')">>> View More Posts</a></li>';
				}
				
			}
			titles += "</ul>";
			
			html += 
				'<div class="ui eight wide column"><div class="ui segment" data-id="' + id + '" id="list' + id + '">' +  
				'<div class="listTitle"><h4 class="left"><a href="#" id="viewListButton" onclick="showViewList('+id+', \''+name+'\')">' + name + '</a></h4><div class="right"><a href="#" title="Add a post" onclick="showAddPost('+id+', \''+name+'\')" id="addPostButton"><i class="ui add blue circle icon"></i></a>&nbsp;<a href="manage" title="Edit list" id="editListButton"><i class="edit icon"></i></a>&nbsp;<a href="#" title="Delete list" id="remListButton" onclick="showRemList('+id+', \''+name+'\')"><i class="remove icon"></i></a></div><div class="clear"></div></div>' + 
				'<div>' + titles + 
				'</div>' +
				'<div class="footList"><div class="left"><p><!--Followers: <span class="follow">'+followers+'</span>' + 
				'<br/>--><a href="#" id="viewListButton" onclick="showViewList('+id+', \''+name+'\')">Posts: </a><span class="post">'+totalposts+'</span>' + 
				/*'<br/>Upvotes: <span class="upvote">'+res[key].upvotes+'</span>' + */
				'</p></div>' + 
				'<div class="right"></div><div class="clear"></div></div>' +
			    '</div></div>';
		}

		if (returnID != "")
			$('#'+returnID).append(html);
		else
			$('#manageSelect').append(html);

		(function(){setOnClicks()}());

		cb(true);
	}

	function setOnClicks() {
		$( "#remPostButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("grey");
			$(this).toggleClass("red");
		  }
		);
		$( "#addPostButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("inverted");
		  }
		);
		$( "#remListButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("red");
		  }
		);
		$( "#editListButton i" ).hover(
		  function() {
		  	//console.log("t");
		    $(this).toggleClass("grey");
		  }
		);
	}

	function genListPosts(res, cb) {
		var html = "";
		//var listLimit = 6;

		for (var key in res) {
			//
		}
	}

/*
**************************************************
* SHOW
**************************************************
*/
	showRemMem = function(addedUserID, adder, listID){
		if (isAuth) {
			$('#remMemModal .description').html('<p>Member: '+adder+'</p><div  id="remMemConfirm" class="mini ui button">Remove</div >');
			$('#remMemConfirm').off('click').on("click", function(e){
				e.preventDefault();
				//on login click, show modal to login, if not already loggedin
				processRemMem(listID, addedUserID, function(res){
					$('#remMemModal').modal('hide');
					$('#mem'+addedUserID+'').remove();
					//$("p").filter(":contains('Hello')").remove().
					//$("div[data-id='"+id+"'] a[href='"+url+"']").parents("li").remove();
					//$('#list'+id+' .post').html(+($('#list'+id+' .post').html()) - 1);
				})
			});
			$('#remMemModal').modal('show');
		}else { deniedAccess(); }
	}
	showAddMem = function(id, lname){
		if (isAuth) {
			
			//click of adding member from modal
			$('#addMemConfirm').off('click').on("click", function(e){
				e.preventDefault();
				var addedUserID = $('#addMemModal .selected').attr("data-value");
				var addedUserName = $('#addMemModal .selected').text();

				processAddMem(id, addedUserID, function(res){
					//$('#addMemModal .menu').append('<div class="item" data-value="'+data[user].id+'">'+data[user].name+'</div>');
					if (res) {
						var out = '<li id="'+id+'"><div class="ui grid"><div class="fourteen wide column">\u2022 '+addedUserName+'</div><div class="two wide column"><a title="Remove this user" href="#" onclick="showRemMem('+addedUserID+', \''+addedUserName+'\', \''+id+'\')" id="remMemButton"><i class="ui remove grey circle icon"></i></a></div></div></li>';
						$('#manageMembers .titles').append(out);
						$('#addMemModal').modal('hide');
					}else {
						$("#addMemModal .errors").html("Error: You don't seem to have access to add to this list, but somehow got this far. Please contact admin@steemkure.com to resolve this bug. Please include this message. Thank you.");
					}
				});

			});

			//setup modal display
			$("#addMemName").html(lname);
			$('#addMemModal')
				.modal({
			        onApprove : function() {
			            return false; //block the modal here
			        }
			    })
			    .modal('show')
		    ;
		}else { deniedAccess(); }
	}

	showAddPost = function(id, lname){
		if (isAuth) {
			$('#addPostConfirm').off('click').on("click", function(e){
				e.preventDefault();

				$('#addPostModal .errors').html('');
				var url = $("#postLink").val();
				if (url == '' || !url.match(/^https:\/\/steemit.com\/[a-z0-9\-]+\/[a-z0-9\-\.\@]+\/[a-z0-9\-]+$/)) {
					$('#addPostModal .errors').html('<div class="ui error message"><div class="ui red basic label">You must enter a valid steemit.com URL.<br/>eg: https://steemit.com/tag/@user/permalink</div></div>');
					return false;
				}else if (url == $("#postLink").attr("data-url") && id == $("#postLink").attr("data-id")) {
					$('#addPostModal .errors').html('<div class="ui error message"><div class="ui red basic label">You haven\'t changed the URL yet.</div></div>');
					return false;
				}

				//on login click, show modal to login, if not already loggedin
				processAddPost(id, lname, url, function(res){
					if (res.match(/^[0-9]+$/)) { 
						var out = '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a></div><div class="ui two wide column"><a title"Remove this post" href="#" onclick="showRemPost('+id+', \''+url+'\', \''+lname+'\')" id="remPostButton"><i class="ui remove grey circle icon"></i></a></div></div></li>';

						$('#list'+id+' .titles').prepend(out);
						$('#list'+id+' .post').html(+($('#list'+id+' .post').html()) + 1);
						$('#addPostModal').modal('hide');
					}else {
						$("#addPostModal .errors").html("Error: "+res);
						$("#postLink").attr("data-url", url);
						//return false; //???
					}
				});
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
					$('#remPostModal').modal('hide');
					$("div[data-id='"+id+"'] a[href='"+url+"']").parents("li").remove();
					//$("div[id="+id+"]").remove();
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
					$('#remListModal').modal('hide');
					$('[data-id="'+id+'"]').parent().remove();
				})
			});
			$('#remListModal .header').html("Are you sure you want to remove this list?<br/><br/><center>"+lname+"</center>");
			$('#remListModal').modal('show');
		}else { deniedAccess(); }
	}

	showViewList = function(id, lname){
		//remove for TOP LIST view by guests? modify 1) guests 2) logged in users?
		/*if (isAuth) {*/
			//if on.click event inside modal, add here

			processViewList(id, function(res){
				//do data processing
				//console.log("Data: "+data);
				var html = "";

				//clear default message of no lists
				var titles = '<ul class="titles">';
				for (var key in res) {
					var id = res[key].id;
					var name = res[key].name;
					var url = res[key].st_url;
					//var titles = "";
					if (url != undefined) {
					titles += '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a></div></div></li>';
					}else continue;
					
				}
				titles += "</ul>";
					
				html += '<div>' + titles + '</div>';
				/*html += '<div class="footList">' +
						'<div class="right"><a href="#" onclick="showAddPost('+id+', \''+name+'\')" class="addPostButton"><i class="ui add blue circle icon"></i></a></div><div class="clear"></div></div>';*/

				//$('#manageSelect').append(html);
				$('#viewListModal .header').html(lname);
				$('#viewListModal .description').html(html);
				$('#viewListModal').modal('show');
			});
			//var data = "";
		/*}else { deniedAccess(); }*/
	}

	function getPage() {
		var currentPage = window.location.href;
		currentPage = currentPage.replace(site, '');
		return currentPage;
	}

	function loginCheck() {

	    steemconnect.isAuthenticated(function(err, result) {
		    if (!err && result.isAuthenticated) {
		        isAuth = true;
		        username = result.username;
		        localName = localStorage.getItem("username");
		        if (!localName || username != localName) {
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
		    	loginProcess();
		        
		    }else {
		    	localStorage.removeItem("username");
	  			//default display if not logged in on page load
	  			$('#mainContent').removeClass("loading");
		  		/*$('#mainContent .ui.message.content').append('<br/><div class="ui pointing basic orange label"><i class="lock icon"></i>Please login first.</div>');*/
		  		var curPage = getPage();
				if (curPage == pageManage) {
					//$("#mainContent").addClass("disabled");
					$('#mainContent .content.main').html('<div class="ui pointing basic orange label"><i class="lock icon"></i>Please login first.</div>');
				}else if (curPage == pageHome) {
		  			$('#createList').addClass('disabled');
		  		}
		  		
			 }
	    });	
	}

	function loginProcess() {
		//problem if slash in pageURL...
        
        //remove # adding on addPost click +
        /*addEventListener('click', function (ev) {
		    if (ev.target.id = 'addPostButton') {
		        ev.preventDefault();
		    }
		});*/

		/*$('#addMemModal .ui.dropdown')
		    .dropdown()
		;*/

		$('#addMemModal .ui.dropdown')
		    .dropdown({
		    	showOnFocus: false
			    /*action: 'select',
			    onChange: function(value) {
			    	//
			    }*/
			})
		;
  		

  		//check if not kurate page
		if(window.location.href.indexOf(pageKurate) == -1) {

			//$('#mainContent .ui.message.content').append('<br/><div class="ui pointing basic orange label"><i class="lock icon"></i>Please login first.</div>');

			//if (isAuth) {
			//}else deniedAccess();

			var curPage = getPage();

			if (curPage == pageManage) { //on manage page
				

					$('#manageLists .ui.dropdown')
					    .dropdown({
						    action: 'select',
						    onChange: function(value, text) {
						    	//
								processManagePage(value, function(data) {

									var posts = data["posts"];
									var members = data["members"];
									var access = data["access"][0].access;
									var totalposts = data["tPosts"][0].tPosts;
									var listID = value;
									var listName = posts[0].name;
									var userid = data["user"];

									//add post menu
									//$('#postMenu').append('<a class="item" title="Add a post" onclick="showAddPost('+value+', \''+posts[0].name+'\')" id="addPostButton"><i class="ui add grey circle icon"></i></a>');
									$('#addPostButton').attr('onclick', 'showAddPost('+value+', \''+listName+'\')');

									//remove list
									//<a href="#" title="Delete list" id="remListButton" onclick="showRemList('+id+', \''+name+'\')"><i class="remove icon"></i></a>

									//
									//how to get the ADD POST to work from other function #id .class prepend?
									//
									var res = "";
									//res = "<p>Accessing List: <strong>"+listName+"</strong><p>";
									res = '<div id="list' + listID + '"><p>Accessing List: <strong>'+listName+'</strong> - Posts: </a><span class="post">'+totalposts+'</span><hr/>';
									

									res += '<div data-id="' + listID + '"><ul class="titles">';

									//$('#list'+id+' .titles').prepend(out);

									//id="list' + posts[0].id + '"
									//$("#manageSelect").attr("id", 'list' + posts[0].id);
									//$('#list'+id+' .post').html(+($('#list'+id+' .post').html()) - 1);
									
									for (var post in posts) {
										var url = posts[post].st_url;
										if (url != null) {
											var adder = posts[post].adder;
											var del = "";

											if (posts[post].adder_id == userid || access == '0') {
												del = '<a title"Remove this post" href="#" onclick="showRemPost('+listID+', \''+url+'\', \''+listName+'\')" id="remPostButton"><i class="ui remove grey circle icon"></i></a>';
											}
											res += '<li><div class="ui grid"><div class="ui fourteen wide column">\u2022 <a href="'+url+'">'+url.replace(steemitURL, '')+'</a><br/><span>Added by: </span>'+adder+'</div><div class="ui two wide column">'+del+'</div></div></li>';
										}else {
									    	res += '<div class="row">No posts in this list.</div>';
									    	break;
									    }									
									}

									res += "</ul></div></div>";
									$('#managePosts').html(res);
									


									//$('#memberMenu').append('<a class="item" title="Add a member" onclick="showAddMem('+value+', \''+posts[0].name+'\')" id="addMemButton"><i class="ui add grey circle icon"></i></a>');
									//check if user is owner or some other access to validate adding other members
									if (access == '0') {
										$('#addMemButton').attr('onclick', 'showAddMem('+value+', \''+listName+'\')');
									}

									//$('#addMemModal').addClass("loading");
									//get users to show for members selection
									$('#addMemModal .menu').html('');
									getListUsers(function(data){
										//console.log("Data: "+data);
										if (data != "") {
											for (var user in data) {
												$('#addMemModal .menu').append('<div class="item" data-value="'+data[user].id+'">'+data[user].name+'</div>');
											}
										//}else { //if no data, then no lists
											//$('#managePosts').html('You have no KURE Lists for curating.');
											//$("#manageSelect").attr("data-id", "1");
											//$('#addMemModal').removeClass("loading");
										}

										//$('#addMemModal').removeClass("loading");
									});

									res = '<div><ul class="titles">';

									//generate members
									for (var member in members) {
										var id = members[member].id;
										if (id != null) {
											//if user access ok
											var delUser = "";
											var owner = "";
											if (access == '0' && members[member].name != username) {
												delUser = '<a title="Remove this user" href="#" onclick="showRemMem('+members[member].id+', \''+members[member].name+'\', \''+members[member].list_id+'\')" id="remMemButton"><i class="ui remove grey circle icon"></i></a>';
											}
											if (members[member].access == '0') {
												owner = " <em>(owner)</em>";
											}
											
											res += '<li id="mem'+id+'"><div class="ui grid"><div class="fourteen wide column">\u2022 '+members[member].name+owner+'</div><div class="two wide column">'+delUser+'</div></div></li>';

										}else {
									    	res = '<div class="row">No members in this list? Impossible... something went wrong.</div>';
									    	break;
									    }									
									}

									res += "</ul></div>";

								    $('#manageMembers').html(res);
								    (function(){setOnClicks()}());

								});
								
						    }
					    })
					;
				
					/* Get the post id, name, followers, total posts, and users who have access to the list
						then populate menu, posts, and members
					*/
					//DROPDOWN selection of list
					getLists(function(data){
						if (data != "") {
							for (var list in data) {
								$('#manageLists .menu').append('<div class="item" data-value="'+data[list].id+'">'+data[list].name+'</div>');
							}
						}else { //if no data, then no lists
							$('#managePosts').html('You have no KURE Lists for curating.');
							//$("#manageSelect").attr("data-id", "1");
							$('#managePage').removeClass("loading");
						}
					});

					$('#managePage').removeClass("loading");

					//$('#mainContent .ui.message.content').append('<br/><div class="ui pointing basic orange label"><i class="lock icon"></i>Please login first.</div>');
					//disabled
//console.log("no auth manage page");
					


			}else if (curPage == pageHome) {
				$( "div#mainContent" ).toggleClass( "hidden" );
				getListsHomePage(function(data){
					if (data != "") { //if data, then has lists
						$('#manageSelect').html('');
						genManageList(data, function(res){

							if (res) {
								$('#mainContent').removeClass("loading");
							}
						}, "manageSelect");
					}else { //if no data, then no lists
						$('#manageSelect').html('You have no KURE Lists for curating. Please create one.');

						$('#mainContent').removeClass("loading");
					}
				});
				$('#listName').removeAttr("readonly");
			}else
				$('#mainContent').removeClass("loading");

		}else { //if kurate then remove loading, no other action
			$('#mainContent').removeClass("loading");
		}
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

		$('.menu .item').tab();

		$('#showTOS').on("click", function(e){
			e.preventDefault();
			//on login click, show modal to login, if not already loggedin
			$('#tosModal').modal('show');
		});


		//
		// MOVE ALL BELOW to loginProcess function
		//

		//except this one???
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


/*
**************************************************
* PROCESS
**************************************************
*/
	function processViewList(id, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "viewList", id: id },
		    dataType: "json"
		});
		request.done(function( res ) {
			cb(res);	    
		});
		request.fail(function( jqXHR, textStatus, err ) {
		    $('#errors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'<br/>err: '+err+'</div>');
		});
	}

	function processRemList(id, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "remList", id: id, user: username },
		});
		request.done(function( res ) {
			cb(res);
		});
		request.fail(function( jqXHR, textStatus, err ) {
		    $('#errors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'<br/>err: '+err+'</div>');
		});
	}

	function processRemPost(id, url, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "remPost", id: id, user: username, url: url },
		});
		request.done(function( res ) {
			cb(res);	    
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#errors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
		});
	}

	function processAddPost(id, lname, url, cb) {
		/*
		* Add the permlink and author to the DB... had issues trying first time...
		*/
		/*var temp = url;
		temp = temp.match(/^https:\/\/steemit.com\/[a-z0-9\-]+\/([a-z0-9\-\.\@]+)\/([a-z0-9\-]+)$/);
		var author = temp[1].substring(1);
		var permlink = temp[2];
		var title = "";
		//console.log("author: "+author);
		//console.log("permlink: "+permlink);
		var args = { author: author, permlink: permlink }
		steem.api.getContent(args).then((data) => {
			title = data[0]["title"];
			console.log(title);
		});*/
		//permlink, title, author to db
		
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "addPost", id: id, user: username, url: url },
		  	dataType: "json"
		});
		request.done(function( res ) {
			cb(res);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#errors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
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
		    	/*if ($("#manageSelect").attr("data-id") == '1') {
		    		$("#manageSelect").attr("data-id", '0');
		    		$('#manageSelect').html('');
		    	}*/
		    	//check for "You have no KURE Lists for curating."
		    	if ($("#manageSelect").text().match(/^You.*one.$/)) {
		    		$('#manageSelect').html('');
		    	}
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
	function getListsHomePage(cb) {
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

	function getLists(cb) {
	    var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "getLists", user: username },
		    dataType: "json"
		});
		request.done(function( msg ) {
		    //$("#mainContent .ui.message.content" ).append( msg );
		    //console.log("msg: "+msg);
		    cb(msg);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui error message"><div class="ui red basic label">Unable to process request due to: '+textStatus+'</div></div>');
		});
	}
	function getListUsers(cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "getListUsers", user: username },
		    dataType: "json"
		});
		request.done(function( msg ) {
		    //$("#mainContent .ui.message.content" ).append( msg );
		    //console.log("msg: "+msg);
		    cb(msg);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui error message"><div class="ui red basic label">Unable to process request due to: '+textStatus+'</div></div>');
		});
	}

	function processManagePage(listid, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "getManagePosts", user: username, listid: listid },
		    dataType: "json"
		});
		request.done(function( msg ) {
		    //$("#mainContent .ui.message.content" ).append( msg );
		    //console.log("msg: "+msg);
		    cb(msg);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui error message"><div class="ui red basic label">Unable to process request due to ajax DB issue: '+textStatus+'</div></div>');
		});

	}

	function processAddMem(id, addedUser, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "addMember", user: username, id: id, addedUser: addedUser },
		    //dataType: "json"
		});
		request.done(function( msg ) {
		    //$("#mainContent .ui.message.content" ).append( msg );
		    //console.log("msg: "+msg);
		    cb(msg);
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#kurErrors').html('<div class="ui error message"><div class="ui red basic label">Unable to process request due to ajax DB issue: '+textStatus+'</div></div>');
		});
	}
	function processRemMem(listID, addedUser, cb) {
		var request = $.ajax({
		    url: "curate.process.php",
		    method: "POST",
		    data: { mode: "remMember", id: listID, user: username, addedUser: addedUser },
		});
		request.done(function( res ) {
			cb(res);	    
		});
		request.fail(function( jqXHR, textStatus ) {
		    $('#errors').html('<div class="ui red basic label">Unable to process request due to: '+textStatus+'</div>');
		});
	}

	//closure function to make init public and start the ball rolling
	return {
		init:init
	}

}();
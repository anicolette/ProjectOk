<?php
	require_once("teamFunctions.php");

	#Make sure the user is actually logged in and that they didn't just manually navigate here
	verifyLogin();
?>

<!-- Placeholder profile page. Just replace the html with whatever you want. -->
<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<title>
			<?php
				$name = $_SESSION["username"];
				echo "$name's profile";
			?>
		</title>
	</head>

	<body onload="loadTeams();">

		<div>
			<?php
				$name = $_SESSION["username"];
				echo "<div id=\"header\">Welcome, $name!</div>";
			?>

		<ul style="border: 1px solid #3B3B3B;">
		<li><a href="index.html">home</a></li>
		<li><a href="profile.php">user panel</a></li>
		<li><a href="getEventsForUser.php">events</a></li>
		<ul style="float:right; list-style-type:none;">
			<li><a href="login.php">login</a></li>
			<li><a href="newuser.php">register</a></li>
		</ul>
	  	</ul>


		<div id="upcomingEvents"></div>

		<div id="profinfo">

		<h2 style="color:#E0F3ED;">Teams</h2>
		<div id="TeamList"></div>
		<form class="form" action="createTeam.php" method="post"><br />
			<label style="color: #E0F3ED; padding-right:10px;" for="Create Team">Create New Team: </label><input type="text" name="teamName" placeholder="Team Name"/>
			</br></br>
			<input type="submit"/></br>
		</form>
		<br /><br />



		<script>
			function loadTeams(){
				var req;
				if(window.XMLHttpRequest){
					req = new XMLHttpRequest();
				} else{
					req = new ActiveXObject("Microsoft.XMLHTTP");
				}
				req.onreadystatechange = function request(){
					if(req.readyState == 4 && req.status == 200){
						var res = JSON.parse(req.responseText);
						var htmlRes = "";
						for(var row in res){
							htmlRes += "<a href=\"teamPage.php?&teamName=" + res[row].TName + "\">" + res[row].TName + "</a></br>";
							
						}
	
						document.getElementById("TeamList").innerHTML = htmlRes;
					}
				}
				req.open("post", "getTeamsForUser.php");
				req.send();
			}
		</script>
		</div>
		</div>
	</body>
</html>

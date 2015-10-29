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
		<div id="profile" align="center">
			<?php
				$name = $_SESSION["username"];
				echo "<div id=\"motto\">Welcome, $name!</div>";
			?>

		<form class="form" action="createTeam.php" method="post">
			<label style="color: #E0F3ED; padding-right:10px;" for="Create Team">Create Team</label><input type="text" name="teamName" placeholder="Team Name"/></br>
			<input type="submit"/></br>
		</form>
		<div id="TeamList"></div>

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
	</body>
</html>

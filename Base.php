<?php
	require_once("includes/functions.php");
	require_once("includes/session.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>OnlineJudge</title>
		<link href="includes/prettify/src/prettify.css" type="text/css" rel="stylesheet" />
		<script src="includes/prettify/src/prettify.js" type="text/javascript"></script>
		
		<style type="text/css">
		body {
			font-family: Georgia;
			width: 		 1000px;
			margin:	 	 0 auto;
			font-size: 	 12px;
		}
		h1 {
			font-size: 		 37px;
			margin-top: 	 5px;
			margin-left: 	 15px;
			text-decoration: underline;
		}
		li a {
			text-decoration: none;
			color: inherit;
		}
		
		.itemDiv
		{
			width: 			  70%;
			min-width: 		  700px;
			clear: 			  none;
			margin-top: 	  5px;
			height: 		  auto;
			padding: 		  10px;
			background-color: #ddd;
			border-radius: 	  5px;
			border: 		  1px solid black;
			overflow-y: auto;
		}
		.divName{
			margin-bottom:  30px;
			clear: 		 	none;
			font-family: 	Helvetica;
			font-size: 	 	20px;
			font-weight: 	bold;
			text-shadow: 	1px 1px 1px #ccc;
			
		}
		.announce {
			width: 			700px;
			padding:		2px;
			margin: 		0px;
			clear: 		 	none;
			font-family: 	Helvetica;
			font-size: 	 	14px;
			font-weight: 	bold;
			text-shadow: 	1px 1px 1px #ccc;
		}
		.divTopBar {
			float: right;
			clear: none;
		}
		.login {
			margin: 			100px;
			border-style:  		double;
			border-width: 		2px;
			padding: 			50px;
			border-color: 		#993333;	
			background-color: 	#C0C0C0;
		}
		.createContest {
			border-style: groove;
			width: 770px;
			height: 500px;
			padding-top: 20px;
			border-width: 2	px;

			float: right;
		}
		#header {
			margin:	 		0 auto;
			width: 			40%;
			font-size: 		50px;
			font-weight:	bolder;
			font-family:	Verdana;
			padding: 		10px;
		}
		#header a {
			color: black;
			text-decoration: none;
		}
		#header a:visited{
			color: black;
		}
		#header a:hover{
			color: black;
		}
		#topBar {
			width: 			  98%;
			height: 		  18px;
			padding: 		  8px;
			padding-left: 	  2%;
			padding-right: 	  0;
			font-weight: 	  bold;
			background-color: #855;
			color: 			  #eee;
			text-shadow: 	  1px 1px 1px #111;
			
		}
		#topBar a {
			color: #eee;
			text-decoration: none;
		} 
		#topBar a:visited {
			color: #eee;
		} 
		#topBar a:hover {
			color: #eee;
		} 
		#leftPan {
			float: 			  left;
			<!--clear: 			  none;-->
			width: 			  20%;
			min-width: 		  200px;
			height: 	  	  500px;
			border: 		  1px solid black;
			border-radius: 	  5px;
			background-color: #eee;
		}
		#leftPan li {
			margin: 		10px;
			margin-bottom: 	15px;
			font-family: 	Helvetica;
			font-weight: 	bold;
			cursor: 		pointer;
		}
		#rightPan {
			float: 			left;
			clear: 			none;
			margin-left: 	20px;
		}
		#footer {
			width: 			150px;
			margin: 		20px auto;
			clear: 			both;
			padding-top: 	1em;
		}
		div.message{
			border: 	 2px solid #8D0D19; 
			color: 		 #8D0D19;
			font-weight: bold;
			margin: 	 2em auto;
			padding: 	 1em;
		}
		div.error{
			border: 	 2px solid #FF0D19; 
			color: 		 #FF0D19; 
			font-weight: bold;
			margin: 	 2em auto; 
			padding: 	 1em ;
		}
		div.error ul{
			margin: 	 0; 
			padding-top: 1em;
		}
		</style>
	</head>
	<body onload="prettyPrint()">
		<div id="header">
			<a href="index.php">OnlineJudge</a>
		</div>
		<hr/>
		<div id="topBar">
			<?php
				if(!logged_in())
				{
					echo "<a href=\"Login.php\">Login</a> 	   &nbsp;&nbsp;|&nbsp;&nbsp;";
					echo "<a href=\"Register.php\">Sign up</a>";
				}
				else 
				{
					echo "<a href=\"Logout.php\">Logout</a>   &nbsp;&nbsp;|&nbsp;&nbsp;";
					echo "<a href=\"Profile.php\">Profile</a>";
				}
			?>
		</div>
		<hr/>
		<div id="leftPan">
			<ul>
			<li><a href="Contests.php">Contests</a></li>
			<li><a href ="rank.php">Top Coders</a></li>
			<?php
			if(logged_in()) { ?>
				<li><a href="Profile.php">Profile</a></li>
				<li><a href="CoachedTeams.php">Coached Teams</a></li>
				<li><a href="create_contest.php">Create Contest</a></li>
				<li><a href="Create_Team.php">Create Team</a></li>
				<?php
					if(isset($_SESSION["team_id"])) 
						echo "<li><a href =\"Team.php?id={$_SESSION["team_id"]}\">My Team</a> </li>";
				?>
				<li><a href="mySubmissions.php?id=<?php echo $_SESSION["id"]?>">My Submissions</a></li>
				<li><a href="MyContests.php">My Contests</a></li>
			<?php } ?>
			</ul>
		</div>
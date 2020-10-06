<?php
	$link = mysqli_connect("localhost", "root", "mahnazrafiaislam");
	if($link === false)
	{
    	die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$sql = "CREATE DATABASE othellogame";
	if(mysqli_query($link, $sql))
	{
    	//echo "Database created successfully";
	}
	else
	{
    	//echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	$db_host="localhost";
	$db_username="root";
	$db_pass="mahnazrafiaislam"; // TODO: make login using db table using proper salting and SHA256
	$db_name="othellogame";
	$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
	if($link === false)
	{
    	die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	$sql = "create table gameroom(roomnumber varchar(10) primary key,box varchar(70),turn int,gameAlive int,userCount int,time int)";
	if(mysqli_query($link, $sql))
	{
    	//echo "Table created successfully.";
	} 
	else
	{
    	//echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
	}
	// Close connection
	mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="src/popupwindow.css" rel="stylesheet" type="text/css">
	<title>Welcome To Othello!!!</title>
	<style type="text/css">
		body {
   		 background-image: url(indexbackground.jpg);
   		 background-repeat: no-repeat;
       background-size: cover;
		}
		.example_content {
		    display : none;
		    color: black;
		}
		a {
		  color: white;
		}
		h1
		{
    		text-align: center;
    		color: white;
    	}
    	h2
		{
    		text-align: center;
    		color: white;
    	}
		#wrapper
		{
			text-align: center;
			padding: 15px 32px;
			font-size: 16px;
		}
		button
		{
			width: 260px;
			height: 60px;
			background-color: #DADAE3; 
			margin: 30px;
			font-size: 18px;
		}
		form
		{
			text-align: center;
			padding: 12px;	
			margin: 18px;
			color: white;
		}
		input[name="roomnumber"]
		{
			margin: 15px;
			width: 200px;
			height: 30px;
		}
	</style>
</head>
<body>
	<h1>Welcome To Othello!!! </h1>
	<h2>A Minute to Learn ... A Lifetime to Master</h2>
	    <img src="title.png" style="  display: block;
  margin-left: auto;
  margin-right: auto;
  height: 130px;
  ">
	<div id = "container">
		<form action="play.php" method="post">
		<div id = "wrapper">
				<button name="newgame">Create New Game</button>
				<button type = "button" onclick="document.getElementById('Footer').style.display='block'" name="joingame">Join Existing Game</button>
		</div>
		<h3><a href="#" class="basic-demo_button">Read Rules</a></h3>
		<div class='example_content basic-demo'>
			<h1 style="color:black;">Rules of Othello Game</h1>
			<h3><ul>
			<li>In Othello, the two colours are Black and White and Black always plays first</li>
			<li>In Othello, the four squares in the middle of the board start with four counters already placed - white top left and bottom right; black top right and bottom left.</li>
			<li>In Othello, a player without a move simply passes, and the other player keeps playing pieces until the first player can make a move again.</li>
			<li>According to World championship rules typically give each player a total of 30 minutes to make all of their moves. This time is reduced after each turn until a player runs out of time or the game is over.</li>
			</ul>
		</h3>





		</div>
		<div id = "Footer" style="display:none">
			Enter Room Number: <input type="text" name="roomnumber"  ><br>
			<input type="submit" value="Submit" name="submit1">
			</form>
			
		</div>	

	</div>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="src/popupwindow.js"></script>
<script>

var Demo = (function($, undefined){

  $(function(){
        QuickDemo();
    });
  function QuickDemo(){
        $(".basic-demo").PopupWindow({
            autoOpen    : false,
            nativeDrag: false
        });
        $(".basic-demo_button").on("click", function(event){
            $(".basic-demo").PopupWindow("open");
        });
    }
  })(jQuery);
</script>
</body>
</html>

<?php

	if(isset($_GET['time']))
	{
		$roomnumber = $_GET['roomnumber'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="mahnazrafiaislam";
		$db_name="othellogame";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "select time from gameroom where roomnumber = '{$roomnumber}'";
		$res = mysqli_query($link, $sql);
		$row = mysqli_fetch_assoc($res);
		$res1 = $row["time"];
		$t = time() - $res1;
		$row["time"] = $t;
		echo json_encode($row);
	}
	elseif(isset($_GET['grid']) && isset($_GET['turn'])&&isset($_GET['roomnumber']) )
	{
		$updTurn = $_GET['turn'];
		$roomnumber = $_GET['roomnumber'];
		$grid = $_GET['grid'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="mahnazrafiaislam";
		$db_name="othellogame";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$t = time();
		$sql = "update gameroom set box = '{$grid}',turn = '{$updTurn}',time = '{$t}' where roomnumber = '{$roomnumber}'";
		mysqli_query($link, $sql);

	}
	else
	{
		if(isset($_GET['roomnumber']))
		{
			$roomnumber = $_GET['roomnumber'];
			$db_host="localhost";
			$db_username="root";
			$db_pass="mahnazrafiaislam";
			$db_name="othellogame";
			$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
			if($link === false)
			{
				die("ERROR: Could not connect. " . mysqli_connect_error());
			}
			$sql = "select box,turn,gameAlive,userCount from gameroom where roomnumber = '{$roomnumber}'";
			$res = mysqli_query($link, $sql);
			$row = mysqli_fetch_assoc($res);
			echo json_encode($row);
		}
	}
	if(isset($_GET['gameAlive']))
	{
		$game = $_GET['gameAlive'];
		$roomnumber = $_GET['roomnumber'];
		$db_host="localhost";
		$db_username="root";
		$db_pass="mahnazrafiaislam";
		$db_name="othellogame";
		$link = mysqli_connect($db_host,$db_username,$db_pass,$db_name);
		if($link === false)
		{
    		die("ERROR: Could not connect. " . mysqli_connect_error());
		}
		$sql = "update gameroom set gameAlive = '{$game}' where roomnumber = '{$roomnumber}'";
		mysqli_query($link, $sql);
	}
	
?>
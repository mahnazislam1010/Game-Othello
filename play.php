<?php
	include("mysql_connection.php");
	session_start();
	if(isset($_POST["newgame"]))
	{
		$roomnumber = openssl_random_pseudo_bytes(4);
 
		//Convert the binary data into hexadecimal representation.
		$roomnumber = bin2hex($roomnumber);
		$userId = 1;
		InsertIntoTable($roomnumber);
		$_SESSION["roomNumber"]=$roomnumber;
		$_SESSION["userId"]=$userId;

	}
	elseif (isset($_POST["submit1"]))
	{
		# code...
		$userId = 2;
		$enteredRoom = $_POST["roomnumber"];
		LookIntoTable($enteredRoom);
		$_SESSION["roomNumber"]=$enteredRoom;
		$_SESSION["userId"]=$userId;
    $roomnumber = $enteredRoom;
	}
?>
<!DOCTYPE html>
<html>

	
<head>

    <style type="text/css"> 	
     body
     {
       background-image: url('background.jpg') ;
       background-repeat: no-repeat;
       background-attachment: fixed;
     } 
    marquee{
      color: #ffffff;
      }
    .green
    {
        background-color :#006400;
        width: 50px;
        height:50px;

    }
    .green:hover{
        cursor: pointer;
    }
    
    #container
    {
        padding: 10px;
        background-color: ;
    }
    /*#othellobox
    {
        width: 800px;
        vertical-align: top;
        display: inline-block;
        float: right;
        background-color: ;
    }*/
    #othellobox
    {
        width: 400px;
        vertical-align: top;
        display: inline-block;
        float: right;
        background-color: black;
        position: absolute;
    }
    #scoreboard
    {
        width : 500px;
        height: 1000px;
        float: left;
        color: white;
    }
    table {
    border-collapse: separate;
    border-spacing: 4px;
}
   
</style>
</head>
<body>
    <img src="title.png" style="  display: block;
  margin-left: auto;
  margin-right: auto;
 height: 100px;
  ">
    <div id = "container" >
      <marquee scrollamount="20" width="90%" color="#ffffff"><h2>A Minute to Learn ... A Lifetime to Master</h2></marquee> <br>
    <div id="scoreboard" >
        <h2>Your Score : <span id="yourScore"></span></h2>
        <h2>Opponent's Score : <span id="oppScore"></span></h2>
        <h2>Timer : <span id="timer"></span></h2>
        <h2>Room Number : <span id="roomnumber"><?php echo $roomnumber ?></span> </h2>
        <h2>You are Player No : <span id="userId"><?php echo $userId ?></span>
        <h2>Game Status : <span id ="status">Ongoing...</span></h2> 
        <h2>Next Move : Player <span id ="turn">1</span></h2> 
    </div>
    <div> <img src="othellodown.png" style="width: 400px;"></div>
    <div id="othellobox"> </div>
    </div>
    <script type="text/javascript">

    var data1,turn;
    var table = "<table>";
    for (var i = 1; i < 9; i++)
    {
        var tr = "<tr>";
        for (var j = 1; j < 9; j++)
        {
            var td;
            td = "<td class='green' id ='block" + ((i-1)* 8 + j) +"'  onclick='Fun(" + ((i-1)* 8 + j) + ")'>";
            tr += td;
            tr += "</td>"
        }
        table += tr + "</tr>";
    }
    document.getElementById('othellobox').innerHTML = table;
	  var roomnumber = document.getElementById("roomnumber").innerHTML;
    table += "</table>"
    document.getElementById("block28").style.backgroundImage = "url('black.png')";
    document.getElementById("block37").style.backgroundImage = "url('black.png')";
    document.getElementById("block29").style.backgroundImage = "url('white.png')";
    document.getElementById("block36").style.backgroundImage = "url('white.png')";
    document.getElementById("oppScore").innerHTML = 2;
    document.getElementById("yourScore").innerHTML = 2;
    var id = document.getElementById("userId").innerHTML;
    var gameAlive = 1;
    var userCount = 1;
    var flag = false;
    var mv = 0;
    var flag1 = 0; 
    var myvar;
    var xx;
    var data;
    var other;
    var cnt = 0;
    var won = 0;
    if(id == 1)
      other = 2;
    else
      other = 1;

    function check(gridval)
    {
      var a=0,b=0,c=0;
      for(var i=0;i<gridval.length;i++)
      {
        if(gridval.charAt(i)==0)
          a = 1;
        else if(gridval.charAt(i)==1)
          b = 1;
        else
          c = 1;  
      }
      if(a+b+c!=3)
      {
        var urScore = document.getElementById("yourScore").innerHTML;
        var oppScore = document.getElementById("oppScore").innerHTML;
        if(urScore>oppScore)
        {
          document.getElementById("status").innerHTML = "You Win....1";
          won = 1;
        }
        else if (oppScore>urScore)
        {
          document.getElementById("status").innerHTML = "You Lose....1";
        }
        else
        {
          document.getElementById("status").innerHTML = "Draw....1";
        }
        sendGameOverSignal(2);
        gameAlive = 2;
        clearInterval(myvar);
      }
      
    }  

    function changeTime() {
      var init = 1800;
      
      myvar = setInterval(function()
      {
        var xhttp = new XMLHttpRequest();
        var obj;
        xhttp.open("GET","queryDatabase.php?time=0&roomnumber="+roomnumber,true);
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            obj = JSON.parse(this.responseText);
            obj = obj.time;
            document.getElementById("timer").innerHTML = (init-obj)+" Seconds";
            if(init-obj<0)
            {
                document.getElementById("timer").innerHTML = 0+"s";
                sendGameOverSignal(0);
                clearInterval(xx);
                gameAlive = 0;
                clearInterval(myvar);
            }
        }
      };
        xhttp.send();
    }, 1000);
    }  
    function requestEverySecond() {
    	// body...	
    	xx = setInterval(function()
    	{
    		var xhttp = new XMLHttpRequest();
    		var obj;
    		xhttp.open("GET","queryDatabase.php?roomnumber="+roomnumber,true);
    		xhttp.onreadystatechange = function() {
    		if (this.readyState == 4 && this.status == 200) {
      			obj = JSON.parse(this.responseText);
      			data1  = obj.box;
      			turn = obj.turn;
            gameAlive = obj.gameAlive;
            userCount = obj.userCount;
            if(turn == id && flag1 == 0 && userCount == 2)
            {
              changeTime();
              document.getElementById("turn").innerHTML = turn;
              flag1 = 1;
            }
            if(gameAlive == 0)
            {
              document.getElementById("status").innerHTML = "You win as your opponent ran out of time";
              won = 1;
              clearInterval(xx);
            }
            if(gameAlive == 2)
            {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....3";
                won = 1;
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....2";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....2";
              }
              clearInterval(xx);
            }
            updateGrid(data1);
            updScore(data1);
            check(data1);
    		}
  		};
  		xhttp.send();
    	},1000);
    }
    String.prototype.replaceAt=function(index, replacement) {
    return this.substr(0, index) + replacement+ this.substr(index + replacement.length);
	}

	requestEverySecond();

  function updateGrid(data) {
     // body...
     for(var i=0;i<data.length;i++)
        {
          if(data.charAt(i)=='1')
          {
            //alert(i+1);
            document.getElementById("block"+(i+1)).style.backgroundImage = "url('black.png')";
          }
          else if(data.charAt(i)=='2')
          {
            document.getElementById("block"+(i+1)).style.backgroundImage = "url('white.png')";
          }
        }
   } 
   function sendGameOverSignal(signal)
   {
          var xhttp = new XMLHttpRequest();
          if(signal == 0)
              xhttp.open("GET","queryDatabase.php?gameAlive=0&roomnumber="+roomnumber,true);
          else
              xhttp.open("GET","queryDatabase.php?gameAlive=2&roomnumber="+roomnumber,true);
          xhttp.send();
          if(signal == 0)
              document.getElementById("status").innerHTML = "You lose because you ran out of time";
          else
          {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....4";
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....4";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....3";
              }
          }
   }
   function updScore(data)
   {
        var own=0,sec=0;
        for(var i =0;i<data.length;i++)
        {
          if(data.charAt(i)==id)
             own = own+1;
          else if(data.charAt(i)==other)
             sec = sec+1;  
        }
        document.getElementById("oppScore").innerHTML = sec;
        document.getElementById("yourScore").innerHTML = own;
    }

    var data;
    function checkAbove(row,col)
    {
        var i,j;
        i = row-1,j=col;
        while(i>=0 && data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
        }
        if( flag == true && i>=0 && data.charAt(i*8+j) == id)
        {
          i = row-1,j=col;
          while(i>=0 && data.charAt(i*8+j)==other)
          {
            mv = 1;
            data = data.replaceAt(i*8+j,id);
            i--;
          }
        }
    }
    function checkBelow(row,col)
    {
        var i,j=col;
        i=row+1;
        while(i<8 && data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
        }
        if( flag == true && i<8 && data.charAt(i*8+j) == id)
        {
          i=row+1;
          while(i<8 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            i++;
          }
        }
    }

    function checkLeft(row,col)
    {
        var i,j;
        i=row,j=col-1;
        while(j>=0 && data.charAt(i*8+j)==other)
        {
          flag = true;
          j--;
        }
        if(flag == true && j>=0 && data.charAt(i*8+j) == id)
        {
          i=row,j=col-1;
          while(j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
          }
        }
    }

    function checkRight(row,col)
    {
        // body...
        var i,j;
        i=row,j=col+1;
        while(j<8 && data.charAt(i*8+j)==other)
        {
          flag = true;
          j++;
        }
        if(flag == true && j<8 && data.charAt(i*8+j) == id)
        {
          i=row,j=col+1;
          while(j<8 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
          }
        }
    }
    function checkTopLeft(row,col)
    {
        var i,j;
        i=row-1,j=col-1;
        while(i>=0&&j>=0&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
          j--;
        }
        if(flag == true && i>=0&&j>=0 && data.charAt(i*8+j) == id)
        {
          i=row-1,j=col-1;
          while(i>=0&&j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
            i--;
          }
        }
    }
    function checkTopRight(row,col)
    {
        var i,j;
        i=row-1,j=col+1;
        while(i>=0&&j<8&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i--;
          j++;
        }
        if(flag == true && i>=0&&j<8 && data.charAt(i*8+j) == id)
        {
          i=row-1,j=col+1;
          while(i>=0&&j<8&& data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
            i--;
          }
        }
    }

    function checkBottomLeft(row,col)
    {
        var i,j;
        i=row+1,j=col-1;
        while(i<8&&j>=0&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
          j--;
        }
        if(flag == true && i<8&&j>=0 && data.charAt(i*8+j) == id)
        {
          i=row+1,j=col-1;
          while(i<8&&j>=0 && data.charAt(i*8+j)==other)
          {mv = 1;
            data = data.replaceAt(i*8+j,id);
            j--;
            i++;
          }
        }
    }
    function checkBottomRight(row,col)
    {
        var i,j;
        i=row+1;
        j=col+1;
        while(i<8&&j<8&&data.charAt(i*8+j)==other)
        {
          flag = true;
          i++;
          j++;
        }
        if(flag == true && i<8&&j<8 && data.charAt(i*8+j) == id)
        {
          i=row+1,j=col+1;
          while(i<8&&j<8 && data.charAt(i*8+j)==other)
          { mv = 1;
            data = data.replaceAt(i*8+j,id);
            j++;
            i++;
          }
        }
    }
    function Fun(num)
    {
    	//turn = 1;
      //requestEverySecond();
    	var row = Math.floor((num-1)/8);
    	var col = (num-1)%8;
    	//check valid move;
    	data = data1;
    	if(document.getElementById("status").innerHTML == "You Lose.....5")
        gameAlive = 0;
    	if(userCount==2 && turn == id&& data.charAt(row*8+col)==0 && gameAlive==1)
    	{
    		//check above
    		flag = false;
        mv = 0;
    		checkAbove(row,col);
   			//check below
   			flag = false;
   			checkBelow(row,col);

   			// //check left
   			flag = false;
   			checkLeft(row,col);

   			// //check right
   			flag = false;
   			checkRight(row,col);

   			// //check topLeft
   			flag = false;
   			checkTopLeft(row,col);

   			// //check topRight
   			flag = false;
   		  checkTopRight(row,col);

   			// //check bottom left
   			flag = false;
   			checkBottomLeft(row,col);

   			// //check bottom right
   			flag = false;
   			checkBottomRight(row,col);

        if(mv == 1)
   			{
          clearInterval(myvar);
          cnt = 0;
          data = data.replaceAt(num-1,id);	
     			turn = other;
          document.getElementById("turn").innerHTML = turn;
     			var xhttp = new XMLHttpRequest();
     			xhttp.open("GET","queryDatabase.php?grid="+data+"&turn="+turn+"&roomnumber="+roomnumber,true);
     			xhttp.send();
          updateGrid(data);
          flag1 = 0;
        }
        else
        {
          var valid=false;
          var pre = data;
          for(var i=0;i<data.length;i++)
          {
            if(data.charAt(i)=='0')
            {
              var row1 = Math.floor((i)/8);
              var col1 = (i)%8;
              flag = false;
              mv = 0;
              checkAbove(row1,col1);
              //check below
              flag = false;
              checkBelow(row1,col1);
              // //check left
              flag = false;
              checkLeft(row1,col1);
              // //check right
              flag = false;
              checkRight(row1,col1);

              // //check topLeft
              flag = false;
              checkTopLeft(row1,col1);

              // //check topRight
              flag = false;
              checkTopRight(row1,col1);

              // //check bottom left
              flag = false;
              checkBottomLeft(row1,col1);

              // //check bottom right
              flag = false;
              checkBottomRight(row1,col1);
              if(mv == 1)
              {
                valid = true;
                break;
              }
            }
          }
          data = pre;
          if(valid == false)
          {
            clearInterval(myvar);
            alert("No valid move exists !! Passing to Other player");
            turn = other;
            cnt = cnt+1;
            if(cnt == 2)
            {
              var urScore = document.getElementById("yourScore").innerHTML;
              var oppScore = document.getElementById("oppScore").innerHTML;
              if(urScore>oppScore)
              {
                document.getElementById("status").innerHTML = "You Win....5";
              }
              else if (oppScore>urScore)
              {
                document.getElementById("status").innerHTML = "You Lose....6";
              }
              else
              {
                document.getElementById("status").innerHTML = "Draw....4";
              }
              sendGameOverSignal(2);
              gameAlive = 2;
              clearInterval(myvar);
            }
            else
            {
              document.getElementById("turn").innerHTML = turn;
              var xhttp = new XMLHttpRequest();
              xhttp.open("GET","queryDatabase.php?grid="+data+"&turn="+turn+"&roomnumber="+roomnumber,true);
              xhttp.send();
              flag = false;
            }
          }
          else
          {
            alert("This is not a Valid Position.");
          }
        }

    	}
    	else
    	{
        if(won)
          alert("Congratulations! You have won the game.");
        else if(!gameAlive)
          alert("Sorry! You have Lost the game.");
        else if(userCount==1)
          alert("Wait for Second Player...");
        else
    		  alert("Wait for your opponent's move");
    	}
    }
    </script>
     
   

</body>
</html>

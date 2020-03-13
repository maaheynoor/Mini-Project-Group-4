<?php
$con=mysqli_connect("localhost","root","")or die("Unable to connnect");
mysqli_select_db($con,"bbgame");
session_start();
$name=$_SESSION['name'];
 ?>
<html>
<head>
<title>Brick Breakout Game</title>
<style>
canvas{
  background-color: #32ddff;
  position:absolute;
  left:10%;
    background-image: url('bg2.jpg');
background-repeat:no-repeat;
 background-size: cover;
}
body{
 background: linear-gradient(to top, #99ccff 0%, #3399ff 100%);
}
</style>
</head>
<body onload="startGame()">
  <form method="POST" action="" id="level2">
  <input type="hidden" name="time2" id="time">
  </form>
<script>
var ball;
var paddle;
var brick;
var score = 0;
var lives=2;
var enter=false;
var px=Math.random()*5;
var offset=-60;
//Timer
var isPaused = true;
var time=0;
var t=document.getElementById("time");
var myVar = setInterval(myTimer ,1000);
function myTimer() {

     if (!isPaused){
     ++time;
  }
}

function pause()
{
	isPaused = true;
}

function resume()
{
    isPaused = false;
}

function startGame() {
  // document.getElementById("play").style.display="none";
    game.start();
    ball=new drawball(game.canvas.width/2,game.canvas.height-10-15,px,-3,10);
    paddle = new drawpaddle(game.canvas.width/2-75,15,150);
    brick= new drawbrick(8,16,50,20,10,offset,10);
}

var game = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 1000;
        this.canvas.height = 600;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.interval = setInterval(updateGame,10);
        this.interval=setInterval(moveBricks,2000);
    },
    clear : function(){
      this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

function drawbrick(row,col,width, height,padding,offtop,offleft) {
  this.row = row;
  this.col = col;
  this.width = width;
  this.height = height;
  this.padding =padding;
  this.offtop = offtop;
  this.offleft = offleft;
  this.bricks=[];
  this.count=this.row*this.col-2*sum(this.row-1);
  for(var c=0;c<this.col;c++){
    this.bricks[c]=[];
    for(var r=0;r<this.row;r++){
      this.bricks[c][r]={x:0,y:0,status:1,hidden:0};
    }
  }
  this.updatebricks=function(){
    for(var r=0; r<this.row; r++) {
    for(var c=0; c<this.col-r; c++) {
       var x = (c*(this.width+this.padding))+this.offleft;
       var y = (r*(this.height+this.padding))+offset;
       if(y<30)
       {
         this.bricks[c][r].hidden = 1;
       }
       else {
         this.bricks[c][r].hidden = 0;
       }
       if(c<r)
       {
         this.bricks[c][r].status = 0;
       }
       if(this.bricks[c][r].status == 1 && this.bricks[c][r].hidden == 0) {
         this.bricks[c][r].x=x;
         this.bricks[c][r].y=y;
         ctx = game.context;
         ctx.beginPath();
         ctx.rect(x,y, this.width,this.height);
         ctx.fillStyle = "#df9f9f";
         ctx.fill();
         ctx.closePath();
       }
     }
   }
  }
}

function collision() {
    for(var c=0; c<brick.col; c++) {
        for(var r=0; r<brick.row; r++) {
            var b = brick.bricks[c][r];
            if(b.status == 1 && b.hidden== 0) {
                if(ball.x +ball.radius > b.x && ball.x -ball.radius < b.x+brick.width && ball.y +ball.radius  > b.y && ball.y -ball.radius  < b.y+brick.height)
				{
                    ball.dy = -ball.dy;
                    b.status = 0;
					score+=10;
					if(score == brick.count*10) {
                       //alert(brick.count)
						brick.updatebricks(); //added here not checked
                        alert("YOU WIN, CONGRATULATIONS!");
                        var form=document.getElementById("level2");
                        form.submit();
                        //window.location.replace("level3.html");
                        clearInterval(interval); // Needed for Chrome to end game
                    }
                }
				if(b.y+brick.height>game.canvas.height-paddle.height)
              {

                document.location.reload();
                alert("GAME OVER");
                clearInterval(game.interval);
              }

            }
        }
    }
}

function sum(num)
{
  sum=0;
  for(var i=1;i<=num;i++)
  {
    sum=sum+i;
  }
  return sum;
}
function drawball(x,y,dx,dy,radius)
{
  this.x=x;
  this.y=y;
  this.dx=dx;
  this.dy=dy;
  this.radius=radius;
  this.updateball=function(){
    con=game.context;
    con.beginPath();
    con.arc(this.x,this.y, this.radius, 0, Math.PI*2);
    con.fillStyle = "#bf4080";
    con.fill();
    con.closePath();
  }
}

function drawpaddle(x,height,width){
  this.x=x;
  this.width=width;
  this.height=height;
  this.rightPressed = false;
  this.leftPressed = false;
  document.addEventListener("keydown", keyDownHandler, false);
  document.addEventListener("keyup", keyUpHandler, false);
  this.updatepaddle=function(){
  con=game.context;
  con.beginPath();
  con.rect(this.x, game.canvas.height-this.height, this.width, this.height);
  con.fillStyle = "#29a3a3";
  con.fill();
  con.closePath();
  }
}

function keyDownHandler(e) {
    if(e.key == "Right" || e.key == "ArrowRight") {
        paddle.rightPressed = true;
		enter=true;
    resume();
    }
    else if(e.key == "Left" || e.key == "ArrowLeft") {
        paddle.leftPressed = true;
		enter=true;
    resume();
    }
	if(e.keyCode == 13)
	{
	enter=true;
  resume();
	}
}

function keyUpHandler(e) {
    if(e.key == "Right" || e.key == "ArrowRight") {
        paddle.rightPressed = false;
    }
    else if(e.key == "Left" || e.key == "ArrowLeft") {
        paddle.leftPressed = false;
    }

}


function drawText() {
	ctx=game.context;
	ctx.beginPath();
    ctx.rect(0,20, game.canvas.width,1);
    ctx.fillStyle = "#038cfc";
    ctx.fill();
    ctx.closePath();
    ctx.font = "16px Arial";
    ctx.fillStyle = "#038cfc";
    ctx.fillText("Level: Intermediate", 8, 20);
      ctx.fillText("Score: "+score, game.canvas.width/2-150, 20);
  	ctx.fillText("Time Elapsed: "+time, game.canvas.width/2+30, 20);
      ctx.fillText("Lives: "+lives, game.canvas.width-80, 20);
      t.value=time;

}

function updateGame() {
    game.clear();
    if(ball.x + ball.dx > game.canvas.width-ball.radius || ball.x + ball.dx < ball.radius) {
        ball.dx = -ball.dx;
    }
    if(ball.y + ball.dy < 20+ball.radius) {
        ball.dy = -ball.dy;
    }
    else if(ball.y  > game.canvas.height-paddle.height-ball.radius)
    {
        if(ball.x > paddle.x && ball.x < paddle.x + paddle.width)
        {
            ball.dy = -ball.dy;
        }
        else
        {
    			if(lives<=0)
          {

                document.location.reload();
                alert("GAME OVER");
                clearInterval(game.interval); // Needed for Chrome to end game
    			}
    			else
          {
    			lives-=1;
    			enter=false;
          pause();
				var px=Math.random()*5;
    			ball=new drawball(game.canvas.width/2,game.canvas.height-10-15,px,-3,10);
    			paddle = new drawpaddle(game.canvas.width/2-75,15,150);
    			}
        }
    }
	if(enter==true)
	{
    ball.x+=ball.dx;
    ball.y+=ball.dy;
	}
    ball.updateball();

    if (paddle.rightPressed && paddle.x < game.canvas.width - paddle.width)
    {
        paddle.x += 7;
    }
    else if (paddle.leftPressed && paddle.x > 0)
    {
        paddle.x -= 7;
    }
    paddle.updatepaddle();
    brick.updatebricks();
    collision();
	  drawText();
}
function moveBricks()
{
  if(enter==true)
	{
  offset+=5;
  }
}

</script>
</body>
</html>

<?php
if(isset($_SESSION['name']))
{
  if(isset($_POST['time2']))
  {
    $time2=$_POST['time2'];
    $getquery="SELECT `level2` FROM `userdb` WHERE `name`='$name'";
		$getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel2=$row['level2'];
    }
    if($oldlevel2==0 || $oldlevel2>$time1)
    {
      $query="UPDATE `userdb` SET `level2`='$time2' WHERE `name`='$name'";
  		$result=mysqli_query($con,$query);
  		if($result)
  			{
  				echo "<script type='text/javascript'>alert('Your High Score for Level 2')</script>";
  			}
    }
    else {
      echo "<script type='text/javascript'>alert('Your High Score for Level 2 is the previous one')</script>";

    }
    echo "<script>window.location.replace('level3.php')</script>";
  }
}
 ?>

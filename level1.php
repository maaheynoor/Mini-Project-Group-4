<?php
$con=mysqli_connect("localhost","root","")or die("Unable to connnect");
mysqli_select_db($con,"bbgame");
session_start();
$name=$_SESSION['name'];
 ?>
<html>
<head>
<title>Brick Breakout Game</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
canvas{
position:absolute;
left:20px;
background-image: url('game_images/bg1.jpg');
background-repeat:no-repeat;
background-size: cover
}
body{
background: linear-gradient(to bottom,  #ccffb3 0%, #99ccff 100%);
}
.info{
  position:absolute;
  left:1040px;
  height:600px;
  width:250px;
  background-color:#f5f5f0;
  color:#2a586f;
  padding-left:20px;
  padding-right: 20px;
  border:2px solid #ccccb3;
  border-radius:20px;
  font-size:20px;
  font-family: Lucida;
}

</style>
</head>
<body onload="startGame()">
  <form method="POST" action="" id="level1">
  <input type="hidden" name="time1" id="time">
  </form>
<audio id="audio" src="bat_hit_ball.mp3" style="display:none"></audio>
<audio id="audio2" src="bounce_ball.mp3" style="display:none"></audio>
  <div class="info">
    <p>Level: Beginner</p>
    <p>Player Name: <?php echo $name;?></p>
    <p id="timeelapsed"></p>
    <p id="score"></p>
    <p id="lives"></p>
    <?php
    $getquery="SELECT `level1` FROM `userdb` WHERE `name`='$name'";
    $getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel1=$row['level1'];
    }
    if($oldlevel1!=0){
    ?>
    <p>Your Best Time: <?php echo $oldlevel1;?> seconds</p>
  <?php }?>
    <button id="gamebutton" class="btn btn-outline-dark" onclick="gamebutton(this)" style="display:none;"><i class="fa fa-pause"></i></button>
    <br>
    <button class="btn btn-outline-danger" onClick="javascript:if(confirm('Do you want to Quit the game?')){window.location.replace('quit.php')}">Quit Game</button>
  </div>

<!-- <button id="play" onclick="startGame()">Play</button> -->
<script>
var ball;
var paddle;
var brick;
var score = 0;
var lives=2;
var enter=false;
var px=Math.random()*3;
//Timer
var timePaused = true;
var time=0;
var t=document.getElementById("time");
var te=document.getElementById("timeelapsed");
var s=document.getElementById("score");
var l=document.getElementById("lives");
var gamepaused=false;
function gamebutton(b)
{
  if(gamepaused==true){
    resume();
    enter=true;
    gameplay();
    b.innerHTML="<i class='fa fa-pause'></i>";
  }
  else {
    pause();
    enter=false;
    gamepause();
    b.innerHTML="<i class='fa fa-play'></i>";
  }

}

function gamepause()
{
  gamepaused=true;
}
function gameplay()
{
  gamepaused=false;
}

var myVar = setInterval(myTimer ,1000);
function myTimer() {

     if (!timePaused){
     ++time;
  }
}

function pause()
{
	timePaused = true;
}

function resume()
{
    timePaused = false;
}

function startGame() {
  // document.getElementById("play").style.display="none";
    game.start();
    ball=new drawball(game.canvas.width/2,game.canvas.height-10-15,px,-2,10);
    paddle = new drawpaddle(game.canvas.width/2-75,15,150);
    brick= new drawbrick(3,12,75,20,10,30,10);
}

var game = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 1000;
        this.canvas.height = 600;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.interval = setInterval(updateGame,10);

    },
    clear : function(){
      this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

function drawbrick(row,col,width, height,padding,offtop,offleft) {
  this.row = row;
  this.col =col;
  this.width = width;
  this.height = height;
  this.padding =padding;
  this.offtop = offtop;
  this.offleft = offleft;
  this.bricks=[];
  for(var c=0;c<this.col;c++){
    this.bricks[c]=[];
    for(var r=0;r<this.row;r++){
      this.bricks[c][r]={x:0,y:0,status:1};
    }
  }
  this.updatebricks=function(){
    for(var c=0; c<this.col; c++) {
     for(var r=0; r<this.row; r++) {
       if(this.bricks[c][r].status == 1) {
         var x = (c*(this.width+this.padding))+this.offleft;
         var y = (r*(this.height+this.padding))+this.offtop;
         this.bricks[c][r].x=x;
         this.bricks[c][r].y=y;
         ctx = game.context;
         const img = new Image();
         img.src = 'game_images/bricks1.jpg';
         ctx.drawImage(img, x, y, this.width, this.height);
         // ctx.beginPath();
         // ctx.rect(x,y, this.width,this.height);
         // ctx.fillStyle = "#975102";
         // ctx.fill();
         // ctx.closePath();
       }
     }
   }
  }
}


function collision() {
    for(var c=0; c<brick.col; c++) {
        for(var r=0; r<brick.row; r++) {
            var b = brick.bricks[c][r];
            if(b.status == 1) {
                if(ball.x +ball.radius > b.x && ball.x -ball.radius < b.x+brick.width && ball.y +ball.radius  > b.y && ball.y -ball.radius  < b.y+brick.height) {
                    ball.dy = -ball.dy;
                    b.status = 0;
					score+=10;
          var audio = document.getElementById("audio");
          audio.src = "bat_hit_ball.mp3";
          audio.play();
					if(score == brick.row*brick.col*10) {
                        alert("YOU WIN, CONGRATULATIONS!");
                        var form=document.getElementById("level1");
                        form.submit();

                        //window.location.replace("level2.html");
                        clearInterval(interval); // Needed for Chrome to end game
                    }
                }
            }
        }
    }
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
    var grd = con.createRadialGradient(this.x,this.y, this.radius/5,this.x,this.y, this.radius);
    grd.addColorStop(0,"#ffb84d");
    grd.addColorStop(1," #ff6600");
    con.fillStyle =grd;
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
//  document.addEventListener("mousemove", mouseMoveHandler, false);
  this.updatepaddle=function(){
  con=game.context;
  // con.beginPath();
  // con.rect(this.x, game.canvas.height-this.height, this.width, this.height);
  // con.fillStyle = "#559E54";
  // con.fill();
  // con.closePath();
  const paddleimg = new Image();
  paddleimg.src = 'game_images/paddle1.jpg';
  con.drawImage(paddleimg, this.x, game.canvas.height-this.height, this.width, this.height);
  }
}

function keyDownHandler(e) {
  if(gamepaused==false)
  {
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
  if(enter==true)
  {
    document.getElementById("gamebutton").style.display="block";
  }
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
// //mouse handler
// function mouseMoveHandler(e) {
//     var relativeX = e.clientX;
//     if(relativeX > 0 && relativeX < game.canvas.width) {
//         paddle.x = relativeX - paddle.width/2;
// 		enter=true;
// 		resume();
//     }
// }
function drawText() {
	ctx=game.context;
    ctx.font = "16px Arial";
    ctx.fillStyle = "white";
    ctx.fillText("Score: "+score, 8, 20);
	  ctx.fillText("Time Elapsed: "+time, game.canvas.width/2, 20);
    ctx.fillText("Lives: "+lives, game.canvas.width-80, 20);
    t.value=time;
    te.innerHTML="Time Elapsed: "+time;
    s.innerHTML="Score: "+score;
    l.innerHTML="Lives: "+lives;
}



function updateGame() {
    game.clear();
    var audio = document.getElementById("audio2");


    if(ball.x + ball.dx > game.canvas.width-ball.radius || ball.x + ball.dx < ball.radius) {
        ball.dx = -ball.dx;
        audio.src = "bounce_ball.mp3";
        audio.play();
    }
    if(ball.y + ball.dy < ball.radius) {
        ball.dy = -ball.dy;
        audio.src = "bounce_ball.mp3";
        audio.play();
    }
    else if(ball.y  > game.canvas.height-paddle.height-ball.radius) {
        if(ball.x > paddle.x && ball.x < paddle.x + paddle.width) {
            ball.dy = -ball.dy;
            audio.src = "bounce_ball.mp3";
            audio.play();
        }
        else {
			if(lives<=0){
            alert("GAME OVER");
            document.location.reload();
            clearInterval(game.interval); // Needed for Chrome to end game
			}
			else{
			lives-=1;
			enter=false;
			pause();
				px=Math.random()*3;
			    ball=new drawball(game.canvas.width/2,game.canvas.height-10-15,px,-2,10);
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

    if (paddle.rightPressed && paddle.x < game.canvas.width - paddle.width) {
           paddle.x += 7;
       }
       else if (paddle.leftPressed && paddle.x > 0) {
           paddle.x -= 7;
       }
    paddle.updatepaddle();
    brick.updatebricks();
    collision();
	  drawText();
}


</script>
</body>
</html>
<?php
if(isset($_SESSION['name']))
{
  if(isset($_POST['time1']))
  {
    $time1=$_POST['time1'];
    $getquery="SELECT `level1` FROM `userdb` WHERE `name`='$name'";
		$getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel1=$row['level1'];
    }
    if($oldlevel1==0 || $oldlevel1>$time1)
    {
      $query="UPDATE `userdb` SET `level1`='$time1' WHERE `name`='$name'";
  		$result=mysqli_query($con,$query);
  		if($result)
  			{
  				echo "<script type='text/javascript'>alert('Your High Score for Level 1')</script>";
  			}
    }
    else {
      echo "<script type='text/javascript'>alert('Your High Score for Level 1 is the previous one')</script>";

    }
    echo "<script>window.location.replace('level2.php')</script>";
  }
}
 ?>

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
  top:10px;
  background-image: url('game_images/bg2.jpg');
background-repeat:no-repeat;
 background-size: cover;
}
body{

 background: linear-gradient(to top, #5badb8 0%, #5badb8 100%);
}
.info{
  position:absolute;
  left:1040px;
  top:10px;
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
  <form method="POST" action="" id="level2">
  <input type="hidden" name="time2" id="time">
  </form>

  <div class="info">
    <p>Level: Intermediate</p>
    <p>Player Name: <?php echo $name;?></p>
    <p id="timeelapsed"></p>
    <p id="score"></p>
    <p id="lives"></p>
    <audio id="audio" src="bat_hit_ball.mp3" style="display:none"></audio>
    <audio id="audio2" src="bounce_ball.mp3" style="display:none"></audio>
    <?php
    $getquery="SELECT `level2` FROM `userdb` WHERE `name`='$name'";
    $getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel2=$row['level2'];
    }
    if($oldlevel2!=0){
    ?>
    <p>Your Best Time: <?php echo $oldlevel2;?> seconds</p>
  <?php }?>
    <button id="gamebutton" class="btn btn-outline-dark" onclick="gamebutton(this)" style="display:none;"><i class="fa fa-pause"></i></button>
    <br>
    <button class="btn btn-outline-danger" onClick="javascript:if(confirm('Do you want to Quit the game?')){window.location.replace('quit.php')}">Quit Game</button>
  </div>

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
var timePaused = true;
var time=0;
var t=document.getElementById("time");
var te=document.getElementById("timeelapsed");
var s=document.getElementById("score");
var l=document.getElementById("lives");
var gamepaused=false;
var pause_enter=enter;
var myVar = setInterval(myTimer ,1000);

function gamebutton(b)
{
  if(gamepaused==true){
    resume();
    gameplay();
    if(pause_enter)
    {
      enter=true;
    }
    b.innerHTML="<i class='fa fa-pause'></i>";
  }
  else {
    pause();
    pause_enter=enter;
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
         const img = new Image();
         img.src = 'game_images/bricks2.jpg';
         ctx.drawImage(img, x, y, this.width, this.height);
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
          var audio = document.getElementById("audio");
          audio.src = "bat_hit_ball.mp3";
          audio.play();
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
    //arc center x,y radius,from angle to angle
    con.arc(this.x,this.y, this.radius, 0, Math.PI*2);
    con.fillStyle = "#ac3939";
    var grd = con.createRadialGradient(this.x-2,this.y-2, this.radius/20,this.x,this.y, this.radius);
    grd.addColorStop(0,"#ff99bb");
    grd.addColorStop(1,"#ac3939");
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
  this.updatepaddle=function(){
  con=game.context;
  const paddleimg = new Image();
  paddleimg.src = 'game_images/paddle2.gif';
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
}
if(enter)
{
  document.getElementById("gamebutton").style.display="block";
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
  var grd = ctx.createLinearGradient(0, 0, 0, 20);
  grd.addColorStop(0, "#261943");
  grd.addColorStop(1, "#4f1d75");
	ctx.beginPath();
  ctx.rect(0,0, game.canvas.width,20);
  ctx.fillStyle = grd;
  ctx.fill();
  ctx.closePath();
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
    if(ball.y + ball.dy < 20+ball.radius) {
        ball.dy = -ball.dy;
        audio.src = "bounce_ball.mp3";
        audio.play();
    }
    else if(ball.y  > game.canvas.height-paddle.height-ball.radius)
    {
        if(ball.x > paddle.x && ball.x < paddle.x + paddle.width)
        {
            ball.dy = -ball.dy;
            audio.src = "bounce_ball.mp3";
            audio.play();
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
				  px=Math.random()*5;
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

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
  background-image: url('game_images/bg3.jpg');
background-repeat:no-repeat;
 background-size: cover;
}
body{

 background: linear-gradient(to top, #aaff80 0%, #206020 100%);
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
  <form method="POST" action="" id="level3">
  <input type="hidden" name="time3" id="time">
  </form>

  <div class="info">
    <p>Level: Difficult</p>
    <p>Player Name: <?php echo $name;?></p>
    <p id="timeelapsed"></p>
    <p id="score"></p>
    <p id="lives"></p>
    <audio id="audio" src="bat_hit_ball.mp3" style="display:none"></audio>
    <audio id="audio2" src="bounce_ball.mp3" style="display:none"></audio>
    <?php
    $getquery="SELECT `level3` FROM `userdb` WHERE `name`='$name'";
    $getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel3=$row['level3'];
    }
    if($oldlevel3!=0){
    ?>
    <p>Your Best Time: <?php echo $oldlevel3;?> seconds</p>
  <?php }?>
    <button id="gamebutton" class="btn btn-outline-dark" onclick="gamebutton(this)" style="display:none;"><i class="fa fa-pause"></i></button>
    <br>
    <button class="btn btn-outline-danger" onClick="javascript:if(confirm('Do you want to Quit the game?')){window.location.replace('quit.php')}">Quit Game</button>
  </div>

<script>
var ball=[];
var paddle;
var brick;
var score = 0;
var lives=10;
//move first ball on clicking enter,and second on clicking second time
var enter=[false,false];
//number of times enter is clicked
//var count=1;
var px=[Math.random()*6-3,Math.random()*6-3];
var px2=Math.random()*3;
var offset=-60;
var colours=["#0059b3","#ff3333"]
//Timer
var timePaused = true;
var time=0;
var t=document.getElementById("time");
var te=document.getElementById("timeelapsed");
var s=document.getElementById("score");
var l=document.getElementById("lives");
var myVar = setInterval(myTimer ,1000);
var gamepaused=false;
//check ball was active before pause
var pause_enter0=enter[0],pause_enter1=enter[1];
function gamebutton(b)
{
  if(gamepaused==true){
    resume();
    gameplay();
    if(pause_enter0)
    {
      enter[0]=true;
    }
    if(pause_enter1)
    {
      enter[1]=true;
    }
    b.innerHTML="<i class='fa fa-pause'></i>";
  }
  else {
    pause();
    pause_enter0=enter[0];
    pause_enter1=enter[1];
    enter[0]=enter[1]=false;
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
	//draw balls paddle and brick
	//drawball(x,y,dx,dy,radius)
    ball[0]=new drawball(game.canvas.width/2-20,game.canvas.height-10-15,px[0],-3,10,colours[0]);
	ball[1]=new drawball(game.canvas.width/2+20,game.canvas.height-10-15,px[1],-3,10,colours[1]);
	//drawpaddle(x,height,width)
    paddle = new drawpaddle(game.canvas.width/2-60,15,120);
	//drawbrick(row,col,width, height,padding,offtop,offleft)
    brick= new drawbrick(10,16,50,20,10,offset,10);
}

//create canvas
var game = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 1000;
        this.canvas.height = 600;
        this.context = this.canvas.getContext("2d");
        document.body.insertBefore(this.canvas, document.body.childNodes[0]);
        this.interval = setInterval(updateGame,10);
        this.interval=setInterval(moveBricks,500);
    },
    clear : function(){
      this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    }

}

//draw bricks
function drawbrick(row,col,width, height,padding,offtop,offleft) {
  this.row = row;
  this.col = col;
  this.width = width;
  this.height = height;
  this.padding =padding;
  this.offtop = offtop;
  this.offleft = offleft;
  this.bricks=[];
  //total number of bricks
  this.count=this.row*this.col-2*sum(this.row-1);
  for(var c=0;c<this.col;c++){
    this.bricks[c]=[];
    for(var r=0;r<this.row;r++){
      this.bricks[c][r]={x:0,y:0,status:1,hidden:0};
	  //hidden=0 means beyond the screen, status=1 means brick not broken
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
         img.src = 'game_images/bricks3.png';
         ctx.drawImage(img, x, y, this.width, this.height);
         // ctx.beginPath();
         // ctx.rect(x,y, this.width,this.height);
         // ctx.fillStyle = "#df9f9f";
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
        		for(var i=0;i<2;i++)
        		{
              //check all bricks that are not broken
              if(b.status == 1 && b.hidden== 0){
                //collision of each non broken bricks with first ball then after bricks broken by first ball check with second ball
                if(ball[i].x + ball[i].radius > b.x && ball[i].x - ball[i].radius < b.x+brick.width && ball[i].y+ball[i].radius > b.y && ball[i].y -ball[i].radius< b.y+brick.height) {
                    ball[i].dy = -ball[i].dy;
					               //status of brick=0 means brick is broken
						           b.status = 0;
                       //update the score
						           score+=10;
                       var audio = document.getElementById("audio");
                       audio.src = "bat_hit_ball.mp3";
                       audio.play();
					             if(score == brick.count*10) {
                       //alert(brick.count)
                        brick.updatebricks();
                        alert("YOU COMPLETED ALL THE LEVELS, CONGRATULATIONS! Your Score"+score);
                        var form=document.getElementById("level3");
                        form.submit();
                        //window.location.replace("winner.html");
                        clearInterval(interval); // Needed for Chrome to end game
                    }
                  }
                }
              }
              //check non broken bricks collision with paddle at the bottom
              if(b.status == 1 && b.hidden== 0){
              if(b.y+brick.height>game.canvas.height-paddle.height)
              {
                brick.updatebricks();
                document.location.reload();
                alert("GAME OVER");
                clearInterval(game.interval);
              }
            }
        }
    }
}



//to calculate number of bricks
function sum(num)
{
  sum=0;
  for(var i=1;i<=num;i++)
  {
    sum=sum+i;
  }
  return sum;
}

function drawball(x,y,dx,dy,radius,colour)
{
  this.x=x;
  this.y=y;
  this.dx=dx;
  this.dy=dy;
  this.radius=radius;
  this.colour=colour;
  this.updateball=function(){
    con=game.context;
    con.beginPath();
    //arc center x,y radius,from angle to angle
    con.arc(this.x,this.y, this.radius, 0, Math.PI*2);
    con.fillStyle = this.colour;
    var grd = con.createRadialGradient(this.x-2,this.y-2, this.radius/20,this.x,this.y, this.radius);
    grd.addColorStop(0,"#fff");
    grd.addColorStop(1,this.colour);
    con.fillStyle =grd;
    con.fill();
    con.closePath();
  }
}

function ballcollision()
{
  // distance between centers
  var cx=ball[0].x-ball[1].x,
  cy=ball[0].y-ball[1].y,
  dist=Math.sqrt(cx*cx+cy*cy);
  if(dist<=ball[0].radius+ball[1].radius)
  {
    // var angle=Math.atan2(cy,cx),
    //  sin=Math.sine(angle),
    //  cos=Math.cosine(angle);

     var v1x = ball[0].dx,
      v2x = ball[1].dx,
      v1y = ball[0].dy,
      v2y =ball[1].dy;
        //Calculate the new velocity components
        var vel1x = (2*v2x)/2,
            vel2x = (2*v1x)/2,
            vel1y = (2*v1y)/2,
            vel2y = (2*v2y)/2;

        //Set the new velocities
        ball[0].dx = vel1x;
        ball[1].dx = vel2x;
        ball[0].dy = vel1y;
        ball[1].dy = vel2y;
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
  // con.beginPath();
  // con.rect(this.x, game.canvas.height-this.height, this.width, this.height);
  // con.fillStyle = "#29a3a3";
  // con.fill();
  // con.closePath();
  const paddleimg = new Image();
  paddleimg.src = 'game_images/paddle3.gif';
  con.drawImage(paddleimg, this.x, game.canvas.height-this.height, this.width, this.height);
  }
}

function keyDownHandler(e) {
//on right or left click move both balls
if(gamepaused==false)
{
    if(e.key == "Right" || e.key == "ArrowRight") {
        paddle.rightPressed = true;
		enter[0]=true;
		enter[1]=true;
    resume();
    }
    else if(e.key == "Left" || e.key == "ArrowLeft") {
        paddle.leftPressed = true;
		enter[0]=true;
		enter[1]=true;
    resume();
    }
	//enter pressed first time
	if(e.keyCode == 13 && enter[0]==false)
	{
	enter[0]=true;
  resume();
	}
	//enter pressed second time
	else if(e.keyCode == 13 && enter[1]==false)
	{
	enter[1]=true;
  resume();
	}
}
if(enter[0]|| enter[1])
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
  grd.addColorStop(0, "#206020");
  grd.addColorStop(1, "#000");
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
	//collision with walls
  for(i=0;i<2;i++)
  {
  //collision with side walls
    if(ball[i].x + ball[i].dx > game.canvas.width-ball[i].radius || ball[i].x + ball[i].dx < ball[i].radius) {
        ball[i].dx = -ball[i].dx;
        audio.src = "bounce_ball.mp3";
        audio.play();
    }
	//collision with top wall
    if(ball[i].y + ball[i].dy < 20+ball[i].radius) {
        ball[i].dy = -ball[i].dy;
        audio.src = "bounce_ball.mp3";
        audio.play();
    }
	//ball fallen down
    else if(ball[i].y > game.canvas.height-paddle.height-ball[i].radius)
    {
        if(ball[i].x > paddle.x && ball[i].x < paddle.x + paddle.width)
        {
            ball[i].dy = -ball[i].dy;
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
    			enter[i]=false;
          if(enter[0]==false && enter[1]==false)
          {
            pause();
          }
				//change speed of ball
				 px[i]=Math.random()*6-3;

    			ball[i]=new drawball(game.canvas.width/2-Math.pow(-1,i)*20,game.canvas.height-10-15,px[i],-3,10,colours[i]);
    			paddle = new drawpaddle(game.canvas.width/2-60,15,120);
    			}
        }
    }
  }

  for(i=0;i<2;i++)
  {
	if(enter[i]==true)
	{
    ball[i].x+=ball[i].dx;
    ball[i].y+=ball[i].dy;
	}
    ball[i].updateball();
  }

    if (paddle.rightPressed && paddle.x < game.canvas.width - paddle.width)
    {
        paddle.x += 8;
    }
    else if (paddle.leftPressed && paddle.x > 0)
    {
        paddle.x -= 8;
    }
    paddle.updatepaddle();
    brick.updatebricks();
    collision();
    ballcollision();
	  drawText();
}
//bricks moving down
function moveBricks()
{
  if(enter[0]==true || enter[1]==true)
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
  if(isset($_POST['time3']))
  {
    $time2=$_POST['time3'];
    $getquery="SELECT `level3` FROM `userdb` WHERE `name`='$name'";
		$getresult=mysqli_query($con,$getquery);
    if($getresult->num_rows > 0)
    {
        $row=$getresult->fetch_assoc();
        $oldlevel2=$row['level3'];
    }
    if($oldlevel2==0 || $oldlevel2>$time1)
    {
      $query="UPDATE `userdb` SET `level3`='$time2' WHERE `name`='$name'";
  		$result=mysqli_query($con,$query);
  		if($result)
  			{
  				echo "<script type='text/javascript'>alert('Your High Score for Level 3')</script>";
  			}
    }
    else {
      echo "<script type='text/javascript'>alert('Your High Score for Level 3 is the previous one')</script>";

    }
    echo "<script>window.location.replace('winner.html')</script>";
  }
}
 ?>

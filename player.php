<?php
$con=mysqli_connect("localhost","root","")or die("Unable to connnect");
mysqli_select_db($con,"bbgame");
session_start();
 ?>


<html>
<head>
<title>Players</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="jquery.min.js"></script>
  <script src="bootstrap.min.js"></script>
  <style>
  body{
		background-image: url("black.jpg");
		height: 100%;
		background-position: center;
		background-repeat: no-repeat;
		background-size: cover;
    font-size:25px;
  }
.container{
  margin-top:50px;
  margin-left: 150px;
}
  .toggle-btn
  {
    border-radius:10px;
    background-color: #42a2a9;
    border-color: #929eaa;
    margin-bottom: 3px;
    font-weight:700;
    height: 70px;
    border-radius: 10px;
  	margin: 15px;
  	-webkit-transition: .3s all ease-in-out;
    -o-transition: .3s all ease-in-out;
    transition: .3s all ease-in-out;
  }
  .form
  {
    display:none;
    padding:10px;
  }

label
  {
    color: white;
  }
a
  {
    color: white;
  }

  a:hover {
    color:#b3b3cc;
    cursor:pointer;
  text-decoration:none;
}

a:active {
  color:#666699;
}
.btn{
  margin:5px;
}

.loader-wrapper
{
  width:100%;
  height:100%;
  background-color: #5522aa;
}
.ball1{
    display: flex;
    justify-content: center;
}
.ball1:before{
    height: 20px;
    width: 60px;
    position: absolute;
    content: "";
    top:60%;
    left:30%;
    border-radius: 50%;
    animation: shrink 1s infinite;
    -webkit-animation: shrink 1s infinite;
    background-color: rgba(8,8,8,0.15);

}

.ball1:after{
    position: absolute;
    content: "";
    height: 60px;
    width: 60px;
    background-color: white;
    border-radius: 50%;
    animation: bounce 1s infinite ;
    -webkit-animation: bounce 1s infinite ;
    top:20%;
    left:30%;
    }


    .ball2{
        display: flex;
        justify-content: center;
    }
    .ball2:before{
        height: 20px;
        width: 60px;
        position: absolute;
        content: "";
        top:60%;
        left:50%;
        border-radius: 50%;
        animation: shrink 1s infinite;
        -webkit-animation: shrink 1s infinite;
        background-color: rgba(8,8,8,0.15);

    }

    .ball2:after{
        position: absolute;
        content: "";
        height: 60px;
        width: 60px;
        background-color: white;
        border-radius: 50%;
        animation: bounce 1s infinite ;
        -webkit-animation: bounce 1s infinite ;
        top:20%;
        left:50%;
        }

        .ball3{
            display: flex;
            justify-content: center;
        }
        .ball3:before{
            height: 20px;
            width: 60px;
            position: absolute;
            content: "";
            top:60%;
            left:70%;
            border-radius: 50%;
            animation: shrink 1s infinite;
            -webkit-animation: shrink 1s infinite;
            background-color: rgba(8,8,8,0.15);

        }

        .ball3:after{
            position: absolute;
            content: "";
            height: 60px;
            width: 60px;
            background-color: white;
            border-radius: 50%;
            animation: bounce 1s infinite ;
            -webkit-animation: bounce 1s infinite ;
            top:20%;
            left:70%;
            }


    @keyframes shrink{
        50%{
            transform: scaleX(0.5);
        }
      }

@keyframes bounce{
    10%{
        height: 60px;
        width: 60px;
    }
    30%{
        height: 65px;
        width: 55px;
    }
    50%{
        height:50px;
        width: 65px;
        transform: translateY(210px);
    }
    75%{
        height: 65px;
        width:55px;
    }
    100%{
        transform: translateY(0px);
    }
}


  </style>
  <!-- loader script -->
  <script>
  $(window).on('load',function() {
    $('.loader-wrapper').fadeOut(500,function(){
      $('.container').fadeIn(500);
    });
  });
  </script>
</head>
<body>
  <!-- loader -->
<div class="loader-wrapper">
    <div class="ball1"></div>
    <div class="ball2"></div>
    <div class="ball3"></div>
</div>
<!-- main content -->
  <div class="container">
    <div class="row">
      <!-- New player form -->
      <div class="col-sm-6">
        <button class="toggle-btn btn btn-secondary"><i class="fa fa-user-plus" style="font-size:36px"> New Player</i></button>
        <form class="form" method="post" action="">
          <label>Player Name:</label><input type="textbox" name="name" required><br><br>
          <input type="submit" name="start" class="btn btn-success btn-lg" value="Start Game">
        </form>
      </div>
      <!-- Existing players -->
      <div class="col-sm-6">
        <button class="toggle-btn btn btn-secondary"><i class="fa fa-users" style="font-size:36px"> Existing Players</i></button>
        <form class="form" method="post" action="">
        <?php
        // Get all player names from userdb table
        $query="SELECT * FROM userdb";
        $result=mysqli_query($con,$query);
        if($result->num_rows > 0)
        {
          while($row = $result->fetch_assoc())
          {
            //echo '<button name="oldplayer" value="'.$row['name'].'">'.$row['name'].'</button><br>';
            echo '<div><a class="toggle-player">'.$row['name'].'</a>';
            echo '<div  style="display:none;">';
            for($i=1;$i<=3;$i++)
            {
              //name of the button in level number and its value is name of the player
            echo '<button  class="btn btn-primary"  type="submit" name="level'.$i.'" value="'.$row['name'].'"';
            if($i!=1)
            {
              $j='level'.($i-1);
              if($row[$j]==0)
              {
                echo ' disabled ';
              }
            }
            echo '> Level '.$i.'</button><br>';
            }
            echo '</div>';
            echo '</div>';
          }
        }
        ?>
      </form>
      </div>
    </div>
  </div>
  <script>
  //form toggle new player and existing player
      $(function(){
      $(".toggle-btn").click(function(){
        $(this).siblings('.form').slideToggle(500);
      });
      });

      $(function(){
      $(".toggle-player").click(function(){
        $(this).siblings('div').toggle();
      });
      });
  </script>
</body>
</html>



<?php
//for new player start with level 1
if(isset($_POST['start']))
{
  $name=$_POST['name'];
  $query="INSERT INTO userdb(`name`) VALUES('$name')";
  $result=mysqli_query($con,$query);
  if($result)
  {
    $_SESSION['name']=$name;
    echo "<script type='text/javascript'>alert('Begin Game?')</script>";
    echo "<script>window.location.replace('level1.php')</script>";
  }
  else {
    echo "<script type='text/javascript'>alert('Player name already exists')</script>";
  }
}
// for existing player start with level on which the player clicked
if(isset($_POST['level1']))
{
  $name=$_POST['level1'];
  $_SESSION['name']=$name;
  echo "<script type='text/javascript'>alert('Begin Game?')</script>";
  echo "<script>window.location.replace('level1.php')</script>";
}
else if(isset($_POST['level2']))
{
  $name=$_POST['level2'];
  $_SESSION['name']=$name;
  echo "<script type='text/javascript'>alert('Begin Game?')</script>";
  echo "<script>window.location.replace('level2.php')</script>";
}
else if(isset($_POST['level3']))
{
  $name=$_POST['level3'];
  $_SESSION['name']=$name;
  echo "<script type='text/javascript'>alert('Begin Game?')</script>";
  echo "<script>window.location.replace('level3.php')</script>";
}

 ?>

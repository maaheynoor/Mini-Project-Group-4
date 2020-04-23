<?php
$con=mysqli_connect("localhost","root","")or die("Unable to connnect");
mysqli_select_db($con,"bbgame");
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
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
      <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css?family=Comfortaa:300&display=swap" rel="stylesheet">
   <style>
   body{
      background-image: linear-gradient(#8FB9A8 ,#FEFAD4, #FCD0BA, #F1828D ,#765D69 );
 		height: 100%;
 		background-position: center;
 		background-repeat: no-repeat;
 		background-size: cover;

   }
   .toggle-btn
   {
    margin:10px;
    padding: 10px 10px;
	   font-size: 25px;
    border: 2px solid white;
    outline: none;
    color: white;
    background-color: #AB6C82;
    -webkit-transition: .3s all ease-in-out;
    -o-transition: .3s all ease-in-out;
    transition: .3s all ease-in-out;

   }
   .toggle-btn:hover,.toggle-btn:active{
     background-color: #D8737F;
   }
   .card{
  font-size:20px;
  padding:10px;
  background-color: #EFEEEE;
  color:#4D5E72;
  box-shadow: 0 8px 16px -8px rgba(0,0,0,0.4);
  text-shadow: 2px 3px 5px rgba(0,0,0,0.25);
  border-radius: 6px;
  overflow: hidden;
  position: relative;
  margin: 15px;
   }

   .fa{
     font-size:25px;
   }
   .first{
     color:gold;
   }
   .second{
     color:#c0c0c0;
   }
   .third{
     color:#cd7f32 ;
   }
   .fa-award{
     color:#568EA6;
   }
   .name{
     padding:10px;
   }
   h3{
     color:white;
     text-shadow: 2px 3px 5px rgba(0,0,0,0.25);

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
       $('.container-fluid').fadeIn(500);
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
<div class="container-fluid">
  <h3>Here are the Best Players of the Game!</h3><br>
  <div class="row">
    <div class="col-md-3">
     <button class="toggle-btn"><i class="material-icons left">filter_1</i>Level 1</button>
     <div style="display:none;">
       <?php
       // Get all player names from userdb table
       $query="SELECT * FROM userdb ORDER BY level1 ASC";
       $result=mysqli_query($con,$query);
       if($result->num_rows > 0)
       {
         $i=1;
         while($row = $result->fetch_assoc())
         {
           if($row['level1']!=0)
           {
             echo '<div class = "card"><i class = "fa fa-';
             if($i<=3)
             {
               echo 'trophy';
               if($i==1)
               {
                 echo ' first';
               }
               else if($i==2)
               {
                 echo ' second';
               }
               else if($i==3)
               {
                 echo ' third';
               }
              }
              else{
                echo 'award';
              }
             echo '"></i>';
             echo '<span class = "name">'.$row['name'].' '.$row['level1'].'</span></div>';
             $i++;
           }

         }
       }
       ?>
     </div>
    </div>
    <div class="col-md-3">
      <button class="toggle-btn"><i class="material-icons left">filter_2</i>Level 2</button>
      <div style="display:none;">
        <?php
        // Get all player names from userdb table
        $query="SELECT * FROM userdb ORDER BY level2 ASC";
        $result=mysqli_query($con,$query);
        if($result->num_rows > 0)
        {
          $i=1;
          while($row = $result->fetch_assoc())
          {
            if($row['level2']!=0)
            {
              echo '<div class = "card"><i class = "fa fa-';
              if($i<=3)
              {
                echo 'trophy';
                if($i==1)
                {
                  echo ' first';
                }
                else if($i==2)
                {
                  echo ' second';
                }
                else if($i==3)
                {
                  echo ' third';
                }
               }
               else{
                 echo 'award';
               }
              echo '"></i>';
              echo '<span class = "name">'.$row['name'].' '.$row['level2'].'</span></div>';
              $i++;
            }

          }
        }
        ?>
      </div>
    </div>
    <div class="col-md-3">
      <button class="toggle-btn"><i class="material-icons left">filter_3</i>Level 3</button>
      <div style="display:none;">
         <?php
         // Get all player names from userdb table
         $query="SELECT * FROM userdb ORDER BY level3 ASC";
         $result=mysqli_query($con,$query);
         if($result->num_rows > 0)
         {
           $i=1;
           while($row = $result->fetch_assoc())
           {
             if($row['level3']!=0)
             {
               echo '<div class = "card"><i class = "fa fa-';
               if($i<=3)
               {
                 echo 'trophy';
                 if($i==1)
                 {
                   echo ' first';
                 }
                 else if($i==2)
                 {
                   echo ' second';
                 }
                 else if($i==3)
                 {
                   echo ' third';
                 }
                }
                else{
                  echo 'award';
                }
               echo '"></i>';
               echo '<span class = "name">'.$row['name'].' '.$row['level3'].'</span></div>';
               $i++;
             }
           }
         }
         ?>
       </div>
    </div>
    <div class="col-md-3">
      <button class="toggle-btn">All Levels</button>
      <div style="display:none;">
         <?php
         // Get all player names from userdb table
         $query="SELECT * FROM userdb ORDER BY level1+level2+level3 ASC";
         $result=mysqli_query($con,$query);
         if($result->num_rows > 0)
         {
           $i=1;
           while($row = $result->fetch_assoc())
           {
             if($row['level1']!=0 && $row['level2']!=0 && $row['level3']!=0)
             {
               echo '<div class = "card"><i class = "fa fa-';
               if($i<=3)
               {
                 echo 'trophy';
                 if($i==1)
                 {
                   echo ' first';
                 }
                 else if($i==2)
                 {
                   echo ' second';
                 }
                 else if($i==3)
                 {
                   echo ' third';
                 }
                }
                else{
                  echo 'award';
                }
               echo '"></i>';
               echo '<span class = "name">'.$row['name'].' '.($row['level1']+$row['level2']+$row['level3']).'</span></div>';
              $i++;
             }

           }
         }
         ?>
       </div>
    </div>
  </div>
</div>
   <script>
   //form toggle new player and existing player
       $(function(){
       $(".toggle-btn").click(function(){
         $(this).siblings('div').slideToggle(500);
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

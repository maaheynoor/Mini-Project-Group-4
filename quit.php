<?php
//destroy session on logout
session_start();
session_destroy();
echo "<script type='text/javascript'>alert('You quit the game')</script>";
header('Location:index.html');
exit;
?>

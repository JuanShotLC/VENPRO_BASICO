<?php 
$consulta=mysqli_query($con,"SELECT * FROM users where user_name='".$_SESSION['user_name']."'");
    $row= mysqli_fetch_array($consulta);
	?>
<?php
	session_start();

	session_destroy();
	unset($_SESSION['UserEmail']);
	unset($_SESSION['apikey']);

	if(isset($_GET['location']))
	{
		header('Location:'.$_GET['location']);
	}
	else{
		header('Location:../index.php');
	}
?>
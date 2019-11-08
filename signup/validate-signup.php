<?php
	include '../php/config.php';

	if(!isset($_POST['email']) || !validateEmail($_POST['email']))
	{
		echo 'No Email entered!';
	}
	else if(!isset($_POST['password']) || !validatePassword($_POST['password']))
	{
		echo 'No password entered!';
	}
	else if(!isset($_POST['name']) || !validateName($_POST['name']))
	{
		echo 'No name entered!';
	}
	else if(!isset($_POST['surname']) || !validateName($_POST['surname']))
	{
		echo 'No surname entered!';
	}
	else{
		if(validateSignUp($_POST['email'],$_POST['password'],$_POST['name'],$_POST['surname']) )
		{
			//echo 'Success!';
			
			header('HTTP/1.1 200 OK');
			if( isset($_GET['location']) ){
				header('Location:'.$_GET['location']);
			}else{
				header('Location:../index.php');
			}
			//header('Location:../index.php');
		}else{
			//echo 'Failed!';
			
			header('HTTP/1.1 401 Unauthorized');
			header('Location:../signup/signup.php');
		}
		
	}

	function validateSignUp($email,$pass,$name,$surname)
	{
		$online = true;
		$UserPass = 'u18171185:Philstembul108102@';
		$parms = 'type=register&email='.$email.'&name='.$name.'&surname='.$surname
					 .'&password='.$pass.'&return[]=key&return[]=email';
		
		$handle = curl_init();

		if($online){
			curl_setopt($handle, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
			curl_setopt($handle, CURLOPT_URL,'https://'.$UserPass.'wheatley.cs.up.ac.za/u18171185/api.php');
		}
		else{
			curl_setopt($handle, CURLOPT_URL,'http://filmformation/api.php');
		}

		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS,$parms);

		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($handle);
		curl_close ($handle);
		
		$result = json_decode($result);
		
		if($result && $result->status == 'success'){
			
			$_SESSION['UserEmail'] = $result->data->email;
			$_SESSION['apikey'] = $result->data->key;
			
			return true;
		}
		return false;
	}

	function validateName($name)
	{
		$regex = "/^((?=.*[a-z])|(?=.*[A-Z]))(?=.{2,})/";
		return !((preg_match($regex, $name)) ? false : true);
	}
	
	function validateEmail($email)
	{
		$regex = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix";
		return !((preg_match($regex, $email)) ? false : true);
	}
	
	function validatePassword($password)
	{
		$regex = "/^((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]))(?=.{8,})/";
		return !((preg_match($regex, $password)) ? false : true);
	}
?>















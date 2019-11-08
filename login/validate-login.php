<?php
	include '../php/config.php';

	if(!isset($_POST['email']))
	{
		echo 'No Email entered!';
	}
	else if(!isset($_POST['password']))
	{
		echo 'No password entered!';
	}
	else{
		if(validateLogin($_POST['email'],$_POST['password']))
		{
			//echo 'Success!';
			
			header('HTTP/1.1 200 OK');
			if( isset($_GET['location']) ){
				header('Location:'.$_GET['location']);
			}else{
				header('Location:../index.php');
			}
		}else{
			//echo 'Failed!';
			
		
			header('HTTP/1.1 401 Unauthorized');
			header('Location:../login/login.php');
		}
		
	}

	function validateLogin($email,$pass)
	{
		$online = true;		
		$parms = 'type=login&email='.$email.'&password='.$pass.'&return[]=key&return[]=email';
			
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
			
		var_dump($result);
		
		$result = json_decode($result);
		
		if($result && $result->status == 'success'){
			
			$_SESSION['UserEmail'] = $result->data->email;
			$_SESSION['apikey'] = $result->data->key;
			
			return true;
		}
		return false;
		
	}
	
?>
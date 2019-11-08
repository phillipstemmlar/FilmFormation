<!DOCTYPE html>
<html>
    <head>
        <title>Filmformation</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
        <link rel="stylesheet" href="../login/login.css" >
		<script type="application/javascript" src="../extra/config.js"></script>
		<script type="application/javascript" src="signup.js"></script>
    </head>
	<body>
		<?php
	
			include '../php/header.php';
			
			$prev = '';
			if( isset($_GET['location']) )
			{
				$prev = '?location='.$_GET['location'];
			}
		
			echo'
				<form id="signUpForm" method="POST" action="../signup/validate-signup.php?'.$prev.'" >
					<input id="nameIn" 	name="name" 	type="text"	 placeholder="Name..." 	/>
					<br/>
					<input id="surnameIn"  name="surname" type="text"  placeholder="Surname..." />
					<br/>
					<input id="emailIn" 	name="email" 	type="email"	 placeholder="Email..." />
					<br/>
					<input id="passwordIn"  name="password" type="password"  placeholder="Password..." />
					<br/>
					<input class="signUpButton" type="button" value="Register" onclick="attemptSignUp();"/>
				</form>
				';

			include '../php/footer.php';
		?>
	</body>
</html>
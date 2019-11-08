<!DOCTYPE html>
<html>
    <head>
        <title>Filmformation</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
        <link rel="stylesheet" href="login.css" >
		<script type="application/javascript" src="../extra/config.js"></script>
		<script type="application/javascript" src="login.js"></script>
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
			<div id="Body">
				<form id="loginForm" method="POST" action="../login/validate-login.php'.$prev.' ">
						<input id="emailIn" 	name="email" 	type="email"	 placeholder="Email..." 	/>
						<br/>
						<input id="passwordIn"  name="password" type="password"  placeholder="Password..." />
						<br/>
						<input class="logButton" type="button" value="Log in" onclick="attemptLogin();"/>
				</form>
			</div>
			';

			include '../php/footer.php';
		?>
	</body>
</html>
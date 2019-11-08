<!DOCTYPE html>     <!-- orange #FFA500 --> <!-- bacground #010310 -->
<html>
    <head>
        <title>Filmformation - Latest</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
        <link rel="stylesheet" href="../latest/latest.css" >
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script type="application/javascript" src="../extra/config.js" ></script>
		<script type="application/javascript" src="../extra/structs.js" ></script>
		<script type="application/javascript" src="../extra/request.js" ></script>
		<script type="application/javascript" src="../latest/latest.js" ></script>
    </head>
    <body onload="setCurPage('latest');onBodyLoad();">
        <?php
			include '../php/header.php';
		
			echo'
				<div id="Body">            
					<section id="Movies" > 
					</section>                        
				</div>
				';
		
			include '../php/footer.php';
		?>
    </body>    
</html>
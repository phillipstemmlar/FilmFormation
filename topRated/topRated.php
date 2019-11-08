<!DOCTYPE html>     <!-- orange #FFA500 --> <!-- bacground #010310 -->
<html>
    <head>

        <title>Filmformation - Top Rated</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
        <link rel="stylesheet" href="../topRated/topRated.css" >
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script type="application/javascript" src="../extra/config.js"></script>
		<script type="application/javascript" src="../extra/structs.js"></script>
		<script type="application/javascript" src="../extra/request.js"></script>
		<script type="application/javascript" src="../topRated/topRated.js"></script>
    </head>
    <body onload="setCurPage('topRated');onBodyLoad();" >		<!-- onBodyLoad() -->
        <?php
			include '../php/header.php';
		
        	echo '
				<div id="Body">          
					<section id="Movies">
					</section>
				</div>
				';
        
			include '../php/footer.php';
		?>
    </body>    
</html>
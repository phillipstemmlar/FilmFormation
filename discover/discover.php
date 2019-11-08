<!DOCTYPE html>     <!-- orange #FFA500 --> <!-- bacground #010310 -->
<html>              <!-- article.Movie { background-color: rgb(7, 17, 82); } -->
    <head>
        <title>Filmformation - Discover</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
        <link rel="stylesheet" href="../discover/discover.css" >
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script type="application/javascript" src="../extra/config.js" ></script>
		<script type="application/javascript" src="../extra/structs.js" ></script>
		<script type="application/javascript" src="../extra/request.js" ></script>
		<script type="application/javascript" src="../discover/discover.js" ></script>
	</head>
    <body onload="setCurPage('discover');onBodyLoad();">
        <?php
			include "../php/header.php";
			include "../php/filterBlock.php";
			
        	echo'
				<div id="Body">
					<section id="Movies">
					</section>
				</div>
			';
        
			include "../php/footer.php";
		?>
    </body>    
</html>

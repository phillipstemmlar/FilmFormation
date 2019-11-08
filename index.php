<!DOCTYPE html>
<html>
    <head>
        <title>Filmformation</title>
		<link rel="shortcut icon" href="./extra/icon.ico" />
        <link rel="stylesheet" href="./extra/main.css" >
        <link rel="stylesheet" href="./extra/launch.css" >
		<script type="application/javascript" src="extra/config.js"></script>
    </head>
    <body onload="setCurPage('home');">
       <?php
			include './php/header.php';
			echo '
				<div id="Body">
					<div id="Movies">
						<div class="container">
							<a href="./latest/latest.html">
								<h4>Latest</h4>
								<hr color="orange"/>
								<table>
									<tr>
										<td>
											<div class="image"
												 style="background-image: url(https://storage.googleapis.com/vibescout-movies/images/za/how-to-train-your-dragon-the-hidden-world-2019/poster/tmdb/normal-400-600.jpeg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(http://t1.gstatic.com/images?q=tbn:ANd9GcQ1bDkDLq-_bteASakhnC1XYwlkErFuqcof7KMhFpRwVhCTh1Vo)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(https://storage.googleapis.com/vibescout-movies/images/za/alita-battle-angel-2019/poster/tmdb/normal-400-600.jpeg)" />
										</td>
									</tr>
								</table>
							</a>
						</div>
						<div class="container">
							<a href="./featured/featured.html">
								<h4>Featured</h4>
								<hr color="orange"/>
								<table>
									<tr>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BMTU5MjMyODcxMF5BMl5BanBnXkFtZTgwMzIwMDM2NDE@._V1_SY1000_CR0,0,666,1000_AL_.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(http://www.gstatic.com/tv/thumb/v22vodart/173063/p173063_v_v8_ab.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BNWYxZWFiNTItN2FkNS00ZDJmLWE1MWItYjMyMTgyOTI4MmQ4XkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SY1000_SX675_AL_.jpg)" />
										</td>
									</tr>
								</table>
							</a>
						</div>
						<div class="container">
							<a href="./topRated/topRated.html">
								<h4>Top Rated</h4>
								<hr color="orange"/>
								<table>
									<tr>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SY1000_CR0,0,675,1000_AL_.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BNzA5ZDNlZWMtM2NhNS00NDJjLTk4NDItYTRmY2EwMWZlMTY3XkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SY1000_CR0,0,675,1000_AL_.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BM2MyNjYxNmUtYTAwNi00MTYxLWJmNWYtYzZlODY3ZTk3OTFlXkEyXkFqcGdeQXVyNzkwMjQ5NzM@._V1_SY1000_CR0,0,704,1000_AL_.jpg)" />
										</td>
									</tr>
								</table>
							</a>
						</div>
						<div class="container">
							<a href="./discover/discover.html">
								<h4>Discover</h4>
								<hr color="orange"/>
								<table>
									<tr>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BMjAyMzExMDM1N15BMl5BanBnXkFtZTcwNTcyMTQ5Mg@@._V1_.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(http://www.gstatic.com/tv/thumb/v22vodart/163191/p163191_v_v8_al.jpg)" />
										</td>
										<td>
											<div class="image"
												 style="background-image: url(https://m.media-amazon.com/images/M/MV5BMjMxNjY2MDU1OV5BMl5BanBnXkFtZTgwNzY1MTUwNTM@._V1_SY1000_CR0,0,674,1000_AL_.jpg)" />
										</td>
									</tr>
								</table>
							</a>
						</div>
					</div>
				</div>
				';
		
		
			include './php/footer.php';
		?>
    </body>    
</html>M
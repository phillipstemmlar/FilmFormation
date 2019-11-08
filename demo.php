<!DOCTYPE html>
<html>
    <head>
        <title>Filmformation</title>
		<link rel="shortcut icon" href="./extra/icon.ico" />
        <link rel="stylesheet" href="./extra/main.css" >
        <style>
			div#Body {
				text-align: center;
				padding-top: 20px;
			}
			
			input[type="button"] {
				width: 50%;
				height: 25px;

				min-width: 100px;
				max-width: 400px;

				margin-bottom: 15px;

				font-weight: bold;

				border : 0px solid black;
				border-radius: 3px;

				background: orange;
				color: rgb(1, 3, 16);
			}

			input[type="button"]:hover {
				background: rgb(255,195,30);
				border : 1px solid white;
			}
			
		</style>
		<script type="application/javascript" src="extra/config.js"></script>
		<script type="application/javascript">
			var req = function(parm)
			{	
				var req = new XMLHttpRequest();
				req.onreadystatechange = function()
				{
					if(req.readyState == 4 && req.status == 200)
					{
						var res = req.responseText;
						document.getElementById('display').innerHTML = res;
					}
				}
				req.open('POST','http://filmformation/api.php',true);
				req.send(parm);
				//req.send();
			}
		</script>
    </head>
    <body>
       <?php
		include './php/header.php';

		$res = '';
		
		$post_parms = 'type=update&key=ad8sad8a6d87a8da86d87a8da87da78d&imdbID=tt0416449'
					.'&return[]=title&return[]=year&return[]=imdbID&return[]=poster&return[]=synopsis&return[]=imdbRating';
		
		$parms = $post_parms;
		
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

		$res = curl_exec($handle);
		curl_close ($handle);

		$res = str_replace('\\','',$res);

		echo '<p>'.$parms.'</p>';
		
		
		echo '
			<div id="Body">
				<p id="display">'.$res.'</p>
			</div>
			';


		include './php/footer.php';
		?>
    </body>    
</html>
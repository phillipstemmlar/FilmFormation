<!DOCTYPE html> 	<!-- orange #FFA500 --> <!-- bacground #010310 -->
<!DOCTYPE html> 	<!-- orange #FFA500 --> <!-- bacground #010310 -->
<html>				<!-- article.Movie { background-color: rgb(7, 17, 82); } -->
    <head>
        <title>Filmformation</title>
		<link rel="shortcut icon" href="../extra/icon.ico" />
        <link rel="stylesheet" href="../extra/main.css" >
		<link rel="stylesheet" href="../calendar/calendar.css" >
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script type="application/javascript" src="../extra/config.js"></script>
		<script type="application/javascript" src="../extra/request.js"></script>
		<script type="application/javascript" src="../calendar/calendar.js"></script>
    </head>
    <body onload="setCurPage('calendar');onBodyLoad();">
        <?php
			include '../php/header.php';
		
		
			echo '
				<div id="Body">
					<div id="calendar">
						<div id="cal-controls" >
							<div id="cal-month" class="cal-control">
								<div id="cal-month-title" class="cal-control-title">April</div>
								<div id="cal-month-controls" class="cal-control-input">
									<div id="loading-bar">adadada</div>
									<input class="cal-btnPrev" type="button" value="Previous Month"  onclick="onPrevMonth()"/>
									<input class="cal-btnNext" type="button" value="Next Month" onclick="onNextMonth()"/>
									<select id="cal-month-select" class="cal-cmb" onchange="onSelectMonth()"></select>
								</div>
							</div>
							<div id="cal-year" class="cal-control">
								<div id="cal-year-title" class="cal-control-title">2019</div>
								<div id="cal-year-controls" class="cal-control-input">
									<input class="cal-btnPrev" type="button" value="Previous Year" onclick="onPrevYear()"/>
									<input class="cal-btnNext" type="button" value="Next Year" onclick="onNextYear()"/>
									<select id="cal-year-select" class="cal-cmb" onchange="onSelectYear()"></select>
								</div>
							</div>

						</div>

						<div id="cal-content">
							<div id="cal-days">
								<div class="cal-day">Monday</div>
								<div class="cal-day">Tuesday</div>
								<div class="cal-day">Wednesday</div>
								<div class="cal-day">Thursday</div>
								<div class="cal-day">Friday</div>
								<div class="cal-day">Saterday</div>
								<div class="cal-day">Sunday</div>
							</div>
							<div id="cal-rows">
							</div>
						</div>
					</div>	
				</div>
				';
        
			include '../php/footer.php';
		?>
    </body>    
</html>












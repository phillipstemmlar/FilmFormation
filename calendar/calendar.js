const monthCount = 12;
const weekDays = 7;
const firstYear = 1878;

const monthLength = [31,28,31,30,31,30,31,31,30,31,30,31];
const monthName = ['January','February','March','April','May','June','July','August','September','October','November','December'];

var lastYear;

var curYear = -1;
var curMonth = -1;
var curDay = -1;

var Month = -1;
var Year = -1;

var MovieIDs = [];
var MovieData = [];

var overlayIDs = [];

var onBodyLoad = function()
{
	loadSelectOptions();
	Year = curYear;
	Month = curMonth;
	
	loadDate(Month,Year);
	
	createOverlay();
}

var loadSelectOptions = function()
{
	if(curYear == -1 || curMonth == -1 || curDay == -1){
		setCurrentDate();
	}
	
	for(var m = 1; m <= monthCount; m++)
	{
		var option = document.createElement('option');
		option.value = getMonthString(m);
		option.innerHTML = option.value;
		document.getElementById('cal-month-select').append(option);
	}
	document.getElementById('cal-month-select').value = getMonthString(curMonth);
	
	for(var y = lastYear; y >= firstYear; y--)
	{
		var option = document.createElement('option');
		option.value = y;
		option.innerHTML = option.value;
		document.getElementById('cal-year-select').append(option);
	}
	document.getElementById('cal-year-select').value = curYear;
}

var loadDate = function(monthNumber, yearNumber)
{
	if(yearNumber > lastYear || yearNumber < firstYear){ return; }
	
	$('div#loading-bar').html(loadingSVG_small());
	
	$('div#cal-month-title').html(getMonthString(monthNumber));
	$('div#cal-year-title').html(yearNumber);
	
	document.getElementById('cal-month-select').value = getMonthString(monthNumber);
	document.getElementById('cal-year-select').value = yearNumber;
	
	Month = monthNumber;
	Year = yearNumber;
	
	//console.log('current: '+ getMonthString(Month) + ' '+ Year);
	loadContent();
}

var loadContent = function()
{
	$('div#cal-rows').html("");
	
	var dow = dayOfWeek(1,Month,Year);
	var d = 1;
	
	var row_count = Math.ceil((dow -1 + getMonthLength())/7);
	
	for(var i = 0; i < row_count; i++)
	{
		var row = createRow(dow,d);
		document.getElementById('cal-rows').appendChild(row);
		d += weekDays - (dow - 1);
		dow = 1;
	}
	
	var todayID = '' + curYear + ((curMonth < 10)? '0'+curMonth : curMonth) + ((curDay < 10)? '0'+curDay : curDay);
	var todayCell = document.getElementById(todayID);
	if(todayCell){
		todayCell.style.backgroundColor = '#060815';
		todayCell.style.borderColor = 'orange';
		todayCell.style.color = 'orange';
		
		todayCell.onmouseover = function(){
			todayCell.style.backgroundColor = 'navy';
			todayCell.style.borderColor = 'white';
			todayCell.style.color = 'white';
		}
		todayCell.onmouseout = function(){
			todayCell.style.backgroundColor = '#060815';
			todayCell.style.borderColor = 'orange';
			todayCell.style.color = 'orange';
		}
	}
	
	getMonthData();
}

var createRow = function(dow, sday)
{
	var row = document.createElement('div');
	row.classList.add('cal-row');
	
	for(var i = 1; i < dow; i++)
	{
		row.appendChild(createEmptyCell());
	}
	
	var mLen = getMonthLength();
	var ld = getMonthLength();
	
	for(var i = 0; i <= 7 - dow; i++)
	{
		if(sday + i <= mLen){
			row.appendChild(createCell(sday + i));
		}
		else
		{
			ld = sday +i;
			break;
		}
	}	
	
	if(ld < getMonthLength())
	{
		for(var i = 0; i <= getMonthLength()-ld; i++)
		{
			row.appendChild(createEmptyCell());
		}
	}
	
	var daysExtra = weekDays-(getMonthLength()-sday +1);
	if(	daysExtra > 0)
	{
		for(var i = 0; i < daysExtra; i++)
		{
			row.appendChild(createEmptyCell());
		}
	}
		
	return row;
}

var createEmptyCell = function()
{
	var cell = document.createElement('div');
	cell.classList.add('cal-cell');
	cell.id = '-1';
	cell.style.border = '1px solid rgb(6,6,28)';
	cell.innerHTML = '<div class="cal-cell-content"></div>';
	return cell;	  
}

var createCell = function(day)
{
	var cell = document.createElement('div');
	cell.classList.add('cal-cell');
	cell.classList.add('cal-cell-day');
	
	var m = (Month < 10)? '0'+Month : Month;
	var d = (day < 10)? '0'+day : day;
	
	cell.id = '' + Year + m + d;
	
	cell.onclick = onClickCell;
	
	cell.innerHTML = '<div class="cal-cell-num">'+day+'</div>'+'\n'
					+'<div class="cal-cell-content">'+'\n'
					+'<ul></ul>'+'\n';
					+'</div>'+'\n';
	return cell;
}

var getMonthData = function()
{
	//logging = true;
	
	var dl = Year + '-'+ Month + '-01';
	var dh = Year + '-'+ Month + '-' + getMonthLength();
	
	var filter = 'region=US&year='+Year+'&monthStart='+dl+'&monthEnd='+dh;
	filterMovies(filter,populateCells);
	//logging = false;
}

var populateCells = function(json_str,id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);
	//console.log(obj);
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("loading-bar").innerHTML = "";
	}
	
	for(var i = 0; i < obj.data.length; i++)
	{
		var li = document.createElement('li');
		li.id = obj.data[i].tmdbID
		
		requestByID_TMDB(obj.data[i].tmdbID,getID);
		
		li.innerHTML = obj.data[i].title;
		
		var relD = obj.data[i].release;
		dateID = relD.substr(0,4) + relD.substr(5,2)+ relD.substr(8,2);
		//console.log(relD + ': ' + dateID ) ;
		
		$('div#'+dateID+' ul').append(li);
	}
}

var getID = function(json_str, id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);
	//console.log(obj);
	
	id = id + "";
	MovieIDs[id] = obj.data.imdbID;
	requestByID_OMDB(obj.data.imdbID,getDATA);
}

var getDATA = function(json_str,id)
{
	id = id + "";
	MovieData[id] = json_str;
	//console.log(json_str);
}

var onNextYear = function()
{
	loadDate(Month,Year+1)
}

var onPrevYear = function()
{
	loadDate(Month,Year-1)
}

var onNextMonth = function()
{
	var y = Year;
	var m = Month +1;
	if( m > monthCount){
		m -= monthCount;
		y++;
	}
	loadDate(m,y)
}

var onPrevMonth = function()
{
	var y = Year;
	var m = Month -1;
	if( m <= 0){
		m += monthCount;
		y--;
	}
	loadDate(m,y)
}

var onSelectMonth = function()
{
	var m = document.getElementById('cal-month-select').selectedIndex + 1;
	loadDate(m,Year);
}

var onSelectYear = function()
{
	var y = $('select#cal-year-select').val();
	loadDate(Month,y);
}

var dayOfWeek = function(day, month, year)
{
	var t = [0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4 ];
	year -= (month < 3) ? 1 : 0;
	var w = Math.floor(( year + year/4 - year/100 + year/400 + t[month-1] + day) % weekDays);
	if( w == 0){ w = weekDays}
	return w; 
}

var setCurrentDate = function()
{
	var date = new Date();
	curYear = date.getFullYear();
	curMonth = date.getMonth() +1;
	curDay =  date.getDate();
	lastYear = curYear + 2;
}

var getMonthString = function(monthNumber)
{
	return monthName[monthNumber-1];
}

var getMonthLength = function()
{
	return (Month == 2 && leapYear())? 29 : monthLength[Month-1];
}

var leapYear = function()
{
	if(Year == -1){return false;}
	
	if(Year % 4 != 0){return false;}
	
	if(Year % 100 == 0 )
	{
		if(Year % 400 == 0 ){
			return true;
		}
		return false;
	}
	return true;
}

var onClickCell = function()
{
	var items = this.getElementsByTagName('ul')[0].childNodes;
	if(items.length <= 0){ return; }
	
	overlayIDs = [];
	for(var i = 0; i < items.length; i++)
	{
		overlayIDs[i] = items[i].id;
	}
	
	//console.log(overlayIDs);
	createOverlay();
}

var createOverlay = function()
{
	if(overlayIDs.length <= 0){return;}

	document.body.style.overflow = 'hidden';
	
	var overlay = document.createElement('div');
	
	overlay.innerHTML = overlayContent(overlayIDs[0]);
	overlay.id = 'overlay';
	
	document.body.appendChild(overlay);
	
	overlayChangeMovie();
}

var overlayContent = function(id)
{
	return   '<div id="overCont">'
	
			+'	<div id="over-img">'
			+'		<img src="" alt="No Image">'
			+'	</div>'
	
			+'	<div id="over-info">'
			+'		<div id="over-title" ><h3></h3></div>'
			+'		<div id="over-released" >Release Date:<span id="relDate"></span></div>'
			+'		<div id="over-overview" ><h5>Overview</h5>'
			+'			<div id="over-plot" ></div>'
			+'		</div>'
			+'		<div id="over-genres" >Genre:<span id="genreList"></span></div>'
			+'	</div>'
			+'	<select id="over-Movie" onchange="overlayChangeMovie()" >'+overlaySelectItems()+'</select>'
			+'	<input type="button" value="Close" onclick="closeOverlay()" />'
			+'<div/>';
}

var overlayChangeMovie = function()
{
	var i = document.getElementById('over-Movie').selectedIndex;
	
	var obj = JSON.parse(MovieData[MovieIDs[overlayIDs[i]]]).data;
	
	$('div#over-title h3').html(obj['title']);
	$('div#over-released span#relDate').html(obj['release']);
	$('div#over-plot').html(obj['synopsis']);
	$('div#over-genres span#genreList').html(obj['genre']);
	$('div#over-img img').attr('src',obj['poster']);
}

var overlaySelectItems = function()
{
	var str = '';
	for(var i = 0; i < overlayIDs.length; i++)
	{
		var obj = JSON.parse(MovieData[MovieIDs[overlayIDs[i]]]).data;
		str += '<option value="'+overlayIDs[i]+'" >'+obj.title+'</option>\n';
	}
	return str;
}

var closeOverlay = function()
{
	overlayIDs = [];
	document.body.style.overflow = 'visible';
	document.body.removeChild(document.getElementById('overlay'));
}

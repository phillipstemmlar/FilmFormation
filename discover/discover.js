var MovieIDs = null;  //;["tt0416449","tt4154756","tt0120737","tt1057500","tt0816692"];
var responseIDs = null;
var resultNodes = null;

const yearCount = 150;
var maxMovieCount = 15;
var movieCount = maxMovieCount;

var yearFilter = -1;

var animNode, animateInterval;

var onBodyLoad = function()
{
	movieCount = maxMovieCount;
	requestGenres(getGenreList);
	MovieIDs = new Queue();
	responseIDs = new Queue();
	resultNodes = new Queue();
	onfilter();
}

var getData = function(res,id)
{
	//console.log(res);
	
	var obj = JSON.parse(res);
	
	//console.log(obj);
	
	if(obj.data.imdbID)
	{
		var key = dateToNum(obj.data.release);
		if(!key){ key = -1;}
		MovieIDs.push(res,key);	
	}
	else{
		movieCount--;
	}
	
	if(movieCount > 0)
	{
		if(MovieIDs.length >= movieCount)
		{
			resultNodes.empty();
			while(!MovieIDs.isEmpty())
			{
				addMovieNode(MovieIDs.pop());	  
			}
		}
	}
	else{
		noResults();
	}
	
}

var addMovieNode = function(json_str)
{
    var article = document.createElement("article");
    article.classList.add("Movie");
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("Movies").innerHTML = "";
	}
	
	var obj = JSON.parse(json_str);
	
    article.innerHTML = movieArticleStr(obj.data); 
    
	article.style.opacity = 0;
	
	document.getElementById("Movies").appendChild(article);
	
	resultNodes.push(article);
	
	if(resultNodes.length >= movieCount)
	{
		document.getElementById('Body').style.minHeight = resultNodes.length*500 +'px';
		
		const time = 200; //ms
		animateInterval = setInterval(animateResults,time);
	}
}

var movieArticleStr = function(obj)
{	
    var str ='<table class="movieAlignment">'+'\n'
            +'  <tr>'+'\n'
            +'      <td>'+'\n'
            +'          <div class="imgMovie">' +'\n'
            +'              <img src="'+obj['poster']+'" alt="No Image" />'+'\n'
            +'          </div>'+'\n'
            +'      </td>'+'\n'
            +'      <td>'+'\n'
            +'          <div class="movieProperties">'+'\n'
            +'              <div class="movieProperty movie_Title">'+'\n'
            +'                      <h3>'+obj['title']+'</h3>'+'\n'
            +'                      <hr color="orange"/>'+'\n'
            +'              </div>'
            +'              <div class="movieProperty movie_ReleaseDate">'+'\n'
            +'                  Release Date: '+obj['release']+'\n'
            +'              </div>'
            +'              <div class="movieProperty movie_Overview">'+'\n'
            +'                  <h5>Overview</h5>'+'\n'
            +'                  '+obj['synopsis']+'\n'
            +'              </div>'+'\n'
            +'              <div class="movieProperty movie_Rating">'+'\n'
            +'                  Rating: 8'+'\n'
            +'              </div>'+'\n'
            +'              <div class="movieProperty movie_IMDb_Rating">'+'\n'
            +'                  <img class="imgIMDb" src="../extra/imdb.png" alt="iMDB" />'+'\n'
            +'                    <span class="rating_num rating_score"> '+obj['imdbRating']+'</span>'+'\n'
            +'                    <span class="rating_num rating_total">/10 '+'\n'
            +'                    <span class="rating_num rating_votes">'+obj['imdbVotes']+'</span> votes</span> '+'\n'
            +'              </div>'+'\n'
			+'              <div class="movieProperty movie_Genres">'+'\n'
            +'                  Genre: <span class="genreList">'+obj['genre']+'</span>'+'\n'
            +'              </div>'+'\n'
            +'              <div class="movieProperty movie_AgeRestriction">'+'\n'
            +'                  Age Restriction: '+obj['ageRestriction']+'\n'
            +'              </div>'+'\n'
            +'          </div>'+'\n'
            +'      </td>'+'\n'
            +'  </tr>'+'\n'
            +'</table>'+'\n';
    return str;
}

var loadFilters = function()
{
		document.getElementById('SelectGenre').innerHTML = "";
		document.getElementById('SelectYear').innerHTML = "";
	
		var item = document.createElement('option');
		item.value = 'none';
		item.innerHTML = item.value;
		document.getElementById('SelectGenre').appendChild(item);
	
	//alert(genreList.length + ' ' + genreNames.length);
	for(var i = 0; i < genreNames.length; i++)
	{
		item = document.createElement('option');
		item.value = genreNames[i];
		item.innerHTML = item.value;
		document.getElementById('SelectGenre').appendChild(item);
	}
	
	item = document.createElement('option');
		item.value = 'none';
		item.innerHTML = item.value;
		document.getElementById('SelectYear').appendChild(item);
	var year = new Date().getFullYear();
	
	for(var y = 0; y < yearCount; y++)
	{
		item = document.createElement('option');
		item.value = year - y;
		item.innerHTML = item.value;
		document.getElementById('SelectYear').appendChild(item);
	}
}

var getGenreList = function(json_str, id)
{
	//console.log(json_str);
	
	var obj = JSON.parse(json_str);
	for (var i = 0; i < obj.data.length; i++)
	{
		genreList[obj.data[i].genreName] = obj.data[i].genreID;
		genreNames[i] = obj.data[i].genreName;
	}
	
	loadFilters();
}

var onfilter = function()
{
	var genreID = -1;
	var yearValue = -1;
	var filters = '';
	
	var i = document.getElementById('SelectGenre').selectedIndex-1;
	if(i >= 0){
		genreID = genreList[genreNames[i]];
	}
	
	i = document.getElementById('SelectYear').selectedIndex-1;
	if(i >= 0){
		yearValue = document.getElementById('SelectYear').value;
	}
	
	if(genreID >= 0)
	{
		if(yearValue >= 0){
		   filters = 'genre='+genreID+'&year='+yearValue;// + '&sort_by=release_date.desc';
		}else{
			filters = 'genre='+genreID;// + '&sort_by=release_date.desc';
		}
	}else{
		if(yearValue >= 0){
		    filters = 'year=' + yearValue;// + '&sort_by=release_date.desc';
			yearFilter = yearValue;
		}else{
			filters = '';
		}  
	}
	
	MovieIDs.empty();
	
	var searchTerm = document.getElementById('SearchBar').value;
	if(searchTerm.length > 0)
	{
		search(searchTerm+'&'+filters);
	}else
	{
		filterMovies(filters,FILTERpage);
	}
	
	
	//filterMovies(filters+'&page=2',FILTERpage);
}

var FILTERpage = function(json_str,id)
{
	var obj = JSON.parse(json_str);
	
	//console.log(obj);
	
	if(obj.status === 'failed'){
		noResults();
		return;
	}

	movieCount = (obj.data.length < maxMovieCount)? obj.data.length : maxMovieCount;
	
	document.getElementById("Movies").innerHTML = loadingSVG();
	
	if(movieCount > 0)
	{
		for (var i = 0; i < movieCount; i++)
		{
			//addMovieNode(obj.data[i]);	  
			requestByID_TMDB(obj.data[i].tmdbID,getTMDB_ID);
		}
	}else{
		noResults();
	}
}

var getTMDB_ID = function(json_str,id)
{
	//console.log(json_str);
	
	var obj = JSON.parse(json_str);

	//console.log(obj);
	
	requestByID_OMDB(obj.data.imdbID,getData);
	/*
	responseIDs.push(obj.data.imdbID);
	requestByID_OMDB(responseIDs.pop(),getData);*/	 
}

var validDate = function(date)
{
	return true;
	if(yearFilter == -1){ return true;}
	var year = date.substr(date.length-4,4);
	
	var test = (year == yearFilter);
	console.log(year + ': ' + test);
	
	return (year == yearFilter);
}

var search = function(movieName)
{
	searchMovies(movieName,FILTERpage);
}

var onSearch = function()
{
	var searchTerm = document.getElementById('SearchBar').value;
	if(searchTerm.length > 0){
		onfilter();
	}
}

var dateToNum = function(date)
{
	if(date == 'N/A'){return NaN};
	var str = date.substr(date.length-4, 4) + getMonth(date.substr(3,3)) + date.substr(0,2);
	var num = parseInt(str);
	//console.log(date + ': ' + date.substr(date.length-4, 4) +' '+ getMonth(date.substr(3,3))+' ' + date.substr(0,2));
	//console.log(date + ': ' + num);
	return num;
}

var getMonth = function(strMonth)
{
	if(strMonth == 'Jan'){ return '01';}
	else if(strMonth == 'Feb'){ return '02';}
	else if(strMonth == 'Mar'){ return '03';}
	else if(strMonth == 'Apr'){ return '04';}
	else if(strMonth == 'May'){ return '05';}
	else if(strMonth == 'Jun'){ return '06';}
	else if(strMonth == 'Jul'){ return '07';}
	else if(strMonth == 'Aug'){ return '08';}
	else if(strMonth == 'Sep'){ return '09';}
	else if(strMonth == 'Oct'){ return '10';}
	else if(strMonth == 'Nov'){ return '11';}
	else { return '12';}
}

var noResults = function()
{
	var article = document.createElement("article");
    article.classList.add("noResults");
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("Movies").innerHTML = "";
	}
	
    article.innerHTML = '<h3>No results found!</h3>'; 
    document.getElementById("Movies").appendChild(article);
}

var onkeySearch = function(e)
{
	var charCode = (typeof e.which === "number") ? e.which : e.keyCode;
	
	if (charCode === 13) {
    	e.preventDefault();
    	document.getElementById("SearchButton").click();
  	}
}

var animateResults = function()
{
	if(resultNodes.isEmpty())
	{
		document.getElementById('Body').style.minHeight = 'calc(60vh - 50px)';
		clearInterval(animateInterval);
	}
	else
	{		
		
		const topFirst = 250;
		
		var nodeNumber = movieCount - resultNodes.length;
		var node = resultNodes.pop();
		
		
		var topStart = topFirst*1.1 + node.offsetHeight* nodeNumber;	//100;
		var leftStart = 2000;
		
		
		node.style.position = 'absolute';
		node.style.left = leftStart +'px';
		node.style.top = topStart +'px';
		
		node.style.opacity = 1;
		
		var animTime = 5;
		animNode = setInterval(animateNode,animTime,node);
	}
}

var animateNode = function(node)
{
	if(!node){return;}
	
	//var opac = parseFloat(node.style.opacity) + 0.1;
	//node.style.opacity = opac;
	
	var left = parseFloat(node.style.left) - 50;
	node.style.left = left + 'px';
	
	if(parseFloat(node.style.left) <= 600)
	{
		node.style = '';
		//node.style.position = 'relative';
		//node.style.left = 0;
		clearInterval(animNode);
	}
}


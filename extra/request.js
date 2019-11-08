/*const OMDB_URL_name = "https://www.omdbapi.com/?r=json&t=";
const OMDB_URL_id = "https://www.omdbapi.com/?r=json&i=";
const OMDB_ID = "&apikey=fea5ba46";

const FANART_URL_IMG = "https://webservice.fanart.tv/v3/movies/";
const FANART_ID  = "?api_key=30b4104d3e710fbdc79b3f2b9cf1969c"; 

const TMDB_URL_ID = "https://api.themoviedb.org/3/movie/";
const TMDB_URL_SEARCH = "https://api.themoviedb.org/3/search/movie?query=";
const TMDB_URL_FILTER = "https://api.themoviedb.org/3/discover/movie?";
const TMDB_URL_GENRES = "https://api.themoviedb.org/3/genre/movie/list?";
const TMDB_ID = "api_key=c74013a77702b3a36e9276a80d24dddd";
*/

var apiURL = (online)? 'https://wheatley.cs.up.ac.za/u18171185/api.php' : 'http://filmformation/api.php';

var genreList = [];
var genreNames = [];

var logging = true;

var apikey = 'ad8sad8a6d87a8da86d87a8da87da78d';

//['title','released','poster','synopsis','imdbRating','imdbVotes','Genre','ageRestriction']

var searchMovies = function(movieName,func,asnc = true)			//WERK
{
	var parms = 'type=info&title='+movieName;
	//var returns = ['title','release','poster','synopsis','imdbRating','imdbVotes','genre','ageRestriction']
	var returns = ['tmdbID']
	
	for(var i = 0; i < returns.length; i++)
	{
		parms = parms + '&return[]=' + returns[i];
		//console.log(parms);
	}
	
	parms += '&key='+apikey;
	
	//if(logging) { console.log(parms); }
	
	requestFromAPI("POST", apiURL, parms, movieName, apikey, func, asnc); 
}

var filterMovies = function(filters,func,asnc = true)			//WERK
{
	var parms = 'type=info&'+filters;
	//var returns = ['title','release','poster','synopsis','imdbRating','imdbVotes','genre','ageRestriction']
	var returns = ['tmdbID','title','release'];
	
	for(var i = 0; i < returns.length; i++)
	{
		parms = parms + '&return[]=' + returns[i];
		//console.log(parms);
	}
	
	parms += '&key='+apikey;
	
	//if(logging) { console.log(parms); }
	
	requestFromAPI("POST", apiURL, parms, filters, apikey, func, asnc); 
}

var requestGenres = function(func)
{
	var parms = 'type=genres';
	var returns = ['genreID','genreName']
	
	for(var i = 0; i < returns.length; i++)
	{
		parms = parms + '&return[]=' + returns[i];
		//console.log(parms);
	}
	
	parms += '&key='+apikey;
	
	//if(logging) { console.log(parms); }
	
	requestFromAPI("POST", apiURL, parms, '', apikey, func, true); 
	
	//requestFromAPI("POST", TMDB_URL_GENRES, '', TMDB_ID, func, true); 
}

var requestFanart = function(movieID,func,asnc = true)
{
	var parms = 'type=fanart&imdbID='+movieID;
	var returns = ['posters']

	for(var i = 0; i < returns.length; i++)
	{
	parms = parms + '&return[]=' + returns[i];
	//console.log(parms);
	}

	parms += '&key='+apikey;

	//if(logging) { console.log(parms); }

	requestFromAPI("POST", apiURL, parms, movieID, apikey, func, asnc);
}

var requestVideo = function(movieID,func,asnc = true)
{
	var parms = 'type=video&imdbID='+movieID;
	var returns = ['videoKey']

	for(var i = 0; i < returns.length; i++)
	{
	parms = parms + '&return[]=' + returns[i];
	//console.log(parms);
	}

	parms += '&key='+apikey;

	//if(logging) { console.log(parms); }

	requestFromAPI("POST", apiURL, parms, movieID, apikey, func, asnc);
	
	//requestFromAPI("GET", TMDB_URL_ID,movieID,'/videos?'+TMDB_ID,func,asnc); 
}

var requestByTitle = function(movieName,asnc = true)
{
         
}

var requestByID_OMDB = function(movieID,func,asnc = true)
{
	var parms = 'type=info&imdbID='+movieID;
	var returns = ['title','release','poster','synopsis','imdbID','imdbRating',
				   'imdbVotes','genre','ageRestriction','runtime','country']

	for(var i = 0; i < returns.length; i++)
	{
		parms = parms + '&return[]=' + returns[i];
	}

	parms += '&key='+apikey;

	//console.log(parms);

	requestFromAPI("POST", apiURL, parms, movieID, apikey, func, asnc);  
}

var requestByID_TMDB = function(movieID,func,asnc = true)
{
	var parms = 'type=info&tmdbID='+movieID;
	var returns = ['imdbID']

	for(var i = 0; i < returns.length; i++)
	{
		parms = parms + '&return[]=' + returns[i];
	}

	parms += '&key='+apikey;

	//console.log(parms);

	requestFromAPI("POST", apiURL, parms, movieID, apikey, func, asnc); 
}

var requestFromAPI = function(HttpMethod, URL, parms, mID, apikey, func, asnc = true)
{  
	
	var req = new XMLHttpRequest();
    
    req.onreadystatechange = function()
	{
		if(this.readyState == 4 && this.status == 200)
		{
			func(this.responseText,mID);
		}
	};
    
    URL = URL.replace(/ /g, '+');
    
	if(logging == true){
		//console.log(HttpMethod+': '+URL);
	}
	
    req.open(HttpMethod, URL, asnc);
    req.send(parms);
}

var loadingSVG = function()
{
     return   '<div class="loading">'+'\n'
             +'    <small>Loading...</small><br/>'+'\n'
             +'    <svg class="Triangle_Load" '+'\n'
             +'        width="200px" height="200px"'+'\n'
             +'        viewbox="-4 -1 38 28">'+'\n'
             +'        <polygon fill="transparent"'+'\n'
             +'            stroke="orange"'+'\n'
             +'            stroke-width="2"'+'\n'
             +'            points="15,30 30,0 0,0">'+'\n'
             +'        </polygon>'+'\n'
             +'    </svg>'+'\n'
             +'</div>'+'\n';
}

var loadingSVG_small = function()
{
     return   '<div class="loading">'+'\n'
             +'    <svg class="Triangle_Load" '+'\n'
             +'        width="50px" height="50px"'+'\n'
             +'        viewbox="-4 -1 38 28">'+'\n'
             +'        <polygon fill="transparent"'+'\n'
             +'            stroke="orange"'+'\n'
             +'            stroke-width="2"'+'\n'
             +'            points="15,30 30,0 0,0">'+'\n'
             +'        </polygon>'+'\n'
             +'    </svg>'+'\n'
             +'</div>'+'\n';
}

var test = function(json_str, id)
{
	console.log(json_str);
}
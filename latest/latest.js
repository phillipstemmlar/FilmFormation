var movies = ["tt4154664","tt1987680","tt4633694","tt5968394","tt1477834","tt1727824","tt0437086","tt3513498","tt2386490"]

var MovieIDs = null;

const youtubeEMBED = 'https://www.youtube.com/embed/';
var ytIDs = [];

var thisYear = '-1';

var maxMovieCount = 15;
var movieCount = maxMovieCount;

var onBodyLoad = function()
{
	document.getElementById("Movies").innerHTML = loadingSVG();
	
	MovieIDs = new Queue();
	
	var date = new Date();
	thisYear =  date.getFullYear();
	filterMovies('year='+thisYear,getMovies);
}

var getMovies = function(json_str,id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);
	//console.log(obj);
	
	if(obj.status == 'failed'){return;}
	
	movieCount = (obj.data.length < maxMovieCount)? obj.data.length : maxMovieCount;
	
	for(var i = 0; i < movieCount; i++)
	{
		requestByID_TMDB(obj.data[i].tmdbID,getMovieData);
	}
}

var getMovieData = function(json_str, id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);
	//console.log(obj);
	
	if(obj.status == 'failed'){ movieCount--; return;}
	
	MovieIDs.push(obj.data.imdbID);
	
	if(MovieIDs.length >= movieCount)
	{
		while(!MovieIDs.isEmpty())
		{
			requestVideo(MovieIDs.pop(),getTrailer);
		}
	}
}

var getData = function(json_str,id)
{
	//console.log(json_str);
	addMovieNode(json_str);
}

var getTrailer = function (json_str,id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);	
	//console.log(obj);
	
	if(obj.status == 'failed'){return;}
	
	ytIDs[id] = youtubeEMBED + obj.data.videoKey;	
	requestByID_OMDB(id,getData);
}

var addMovieNode = function(json_str)
{
    var article = document.createElement("article");
    article.classList.add('Movie');
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("Movies").innerHTML = "";
	}
	
    article.innerHTML = movieArticleStr(json_str); 
    document.getElementById("Movies").appendChild(article);
}

var movieArticleStr = function(json_str)
{
    var obj = JSON.parse(json_str).data;
	
	//console.log(ytIDs[obj['imdbID']]);
	
    var str ='<div class="title">'+'\n'
			+'	<h3>'+obj['title']+'</h3>'+'\n'
			+'	<hr color="orange" />'+'\n'
			+'</div>'+'\n'
			+'<div class="CONTENT">'+'\n'
			+'	<div class="IMG">'+'\n'
			+'		<div class="wrapperIMG" >'+'\n'
			+'			<img class="wrapper-image" alt="No Image"'+'\n'
			+'				 src="'+obj['poster']+'" />'+'\n'
			+'			<div class="movieIMG-overlay"><div class="movieIMG-content">'+'\n'
			+'				<div class="Properties"><table><tr>'+'\n'
			+'					<div class="Country">'+'\n'
			+'						<td>Country:</td>'+'\n'
			+'						<td><div class="values">'+'\n'
			+'							'+obj['country']+'\n'
			+'							</div></td></div>'+'\n'
			+'					</tr><tr><div class="ReleaseDate">'+'\n'
			+'						<td>Release:</td><td>'+'\n'
			+'						<div class="values">'+obj['release']+'</div></td></div>'+'\n'
			+'					</tr><tr><div class="Runtime">'+'\n'
			+'						<td>Runtime:</td><td>'+'\n'
			+'						<div class="values">'+obj['runtime']+'</div>'+'\n'
			+'						</td></div></tr></table>'+'\n'
			+'	</div></div></div></div></div>'+'\n'
			+'	<div class="VID" ">'+'\n'
			+'		<iframe width="560" height="315" src="'+ytIDs[obj['imdbID']]+'" frameborder="0" '+'\n'
			+'				allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>'+'\n'
			+'	</iframe></div></div>'+'\n';
	
    return str;
}

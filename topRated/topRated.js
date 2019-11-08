var MovieIDs = ["tt0468569","tt0068646","tt0108052","tt0167260","tt1375666","tt0120737","tt0109830","tt0133093","tt0816692","tt4154756"];

var titleMinHeight = 0;

var reqQueue = new Queue();

var onBodyLoad = function()
{
	reqQueue.empty();
	document.getElementById("Movies").innerHTML = loadingSVG();
	for(var i = 0; i < MovieIDs.length; i++)
    {
        requestByID_OMDB(MovieIDs[i],getData);
    }
}

var go = function()
{
	while(!reqQueue.isEmpty())
	{
		addMovieNode(reqQueue.pop());	  
	}
}

var getData = function(json_str, id)
{
	//console.log(json_str);
	var obj = JSON.parse(json_str);
	//console.log(obj);
	
	reqQueue.push(json_str,parseFloat(obj.data.imdbRating));
	
	if(reqQueue.length == MovieIDs.length)
	{
		while(!reqQueue.isEmpty())
		{
			addMovieNode(reqQueue.pop());	  
		}
	}
}

var addMovieNode = function(json_str)
{
	var article = document.createElement('article');
	article.class = "Movie";
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("Movies").innerHTML = "";
	}
		
	article.innerHTML = movieArticleStr(json_str);
	document.getElementById("Movies").appendChild(article);
	
	adjustTitle(json_str);
}

var movieArticleStr = function(json_str)
{
	var obj = JSON.parse(json_str).data;
	
	var str ='<table class="outerTable">'+'\n'
			+'<tr><td class="title"><div class="movieProperties"><div id="'+obj['imdbID']+'" class="movieProperty movie_Title">'+'\n'
			+'	<h5>'+obj['title']+'</h5>'+'\n'
			+'</div></div></td></tr>'+'\n'
			+'<tr><td class="imgTD"><div class="imgMovie"> '+'\n'
			+'	<img src="'+obj['poster']+'" alt="No Image" />'+'\n'
			+'	<div class="wrapper-overlay"><div class="movieInfo"><div class="movieProperties innerprops">'+'\n'
			+'    <div class="movieProperty movie_Genre">'+'\n'
			+'    Genre:<div class="genres subporps">'+'\n'
			+'    	<span class="genreText">Crime</span>,'+'\n'
			+'      <span class="genreText">Drama</span>'+'\n'
			+'    </div></div><div class="movieProperty movie_boxOffice">'+'\n'
			+'        Box office: <div class="money subporps">'+obj['BoxOffice']+'</div> '+'\n'    
			+'    </div></div></div></div></div></td></tr>'+'\n'
			+'<tr><td><div class="movieProperty movie_IMDb_Rating"><img class="imgIMDb" src="../extra/imdb.png" alt="iMDB" />'+'\n'
			+'	<span class="rating_num rating_score"> '+obj['imdbRating']+'</span><span class="rating_num rating_total">/10'+'\n'
			+'	<span class="rating_num rating_votes">'+obj['imdbVotes']+'</span> votes</span>'+'\n'
			+'</div></td></tr></table>'+'\n';
	return str;
}

var adjustTitle = function(json_str)
{
	const defaultSize = 16;			//pt
	const maxLineLength = 25;
	const ratio = 0.8;
	const defaultMinHeight = 50;	//px
	
	var obj = JSON.parse(json_str).data;
	var id = obj['imdbID'];
	
	var len = obj['title'].length;
	
	
	var div = Math.floor(len/maxLineLength);
	var mod = len % maxLineLength;
	
	var size = Math.floor(defaultSize * Math.pow(ratio,div));
	
	//console.log(obj['title']);
	//console.log(div);
	
	var mH = defaultMinHeight
	if(div > 3){
		mH += (div - 3)*8;
	}
	
	if(mH > titleMinHeight){ titleMinHeight = mH; }
	
	$('div.movie_Title').attr("style",'min-height: '+titleMinHeight + 'px;');	
	$('div#'+id+' h5').attr("style",'font-size: '+ size + 'pt;');
	
}

















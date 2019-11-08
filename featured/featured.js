var MovieIDs = ["tt0489099","tt1666801","tt0109830","tt1810683","tt0387564","tt2387559","tt0393162","tt0473075","tt6823368","tt6966692"];

var titleMinHeight = 0;

var onBodyLoad = function()
{
	document.getElementById("Movies").innerHTML = loadingSVG();
    for(var i = 0; i < MovieIDs.length; i++)
    {
        requestByID_OMDB(MovieIDs[i],getData);
    }
}

var getData = function(res,id)				//request from OMDB
{
	addMovieNode(res);
	requestFanart(id,getFanart);
}

var getFanart = function(res,id)				//request from fanart.tv
{	
	var obj = JSON.parse(res);
	
	console.log(id);
	console.log(obj);
	
	var i = 4;
	do{	i--; } while(!obj.data.posters[3] && i > 0);
	
	var url = obj.data.posters[i].url;
	console.log(url);
	
	$('img#'+id).attr('src',url);
}

var addMovieNode = function(json_str)
{
    var article = document.createElement("article");
    article.class = "container-Movie";
	
	if(document.getElementsByClassName("loading").length > 0)
	{
		document.getElementById("Movies").innerHTML = "";
	}
	
    article.innerHTML = movieArticleStr(json_str); 
    document.getElementById("Movies").appendChild(article);
	
	adjustTitle(json_str);
}

var movieArticleStr = function(json_str,fanart_url)
{
	var obj = JSON.parse(json_str).data;
    var str =   '<table class="table-Movie">'+'\n'
			    +'	<tr class="content" >'+'\n'
				+'		<td>'+'\n'
				+'			<div class="movieProperty movie_Title" id="'+obj['imdbID']+'">'+'\n'
				+'				<h3>'+obj['title']+'</h3>'+'\n'
				+'			</div>'+'\n'
				+'		</td>'+'\n'
				+'	</tr>'+'\n'
				+'	<tr>'+'\n'
				+'		<td>'+'\n'
				+'			<div class="fanartIMG" >'+'\n'
				+'				<img src="" alt="No Image" id="'+obj['imdbID']+'"/>'+'\n'
				+'				<div class="movieIMG">'+'\n'
				+'					<img src="'+obj['poster']+'" alt="No Image"/>'+'\n'
				+'				</div>'+'\n'
				+'			</div>'+'\n'
				+'		</td>'+'\n'
				+'	</tr>'+'\n'
				+'	<tr>'
				+'		<td>'+'\n'
				+'          <div class="movieProperty movie_IMDb_Rating">'+'\n'
				+'              <img class="imgIMDb" src="../extra/imdb.png" alt="iMDB" />'+'\n'
				+'                <span class="rating_num rating_score"> '+obj['imdbRating']+'</span>'+'\n'
				+'                <span class="rating_num rating_total">/10 '+'\n'
				+'                <span class="rating_num rating_votes">'+obj['imdbVotes']+'</span> votes</span> '+'\n'
				+'              </div>'+'\n'
				+'			<div class="movieProperty movie_AgeRestriction">'+'\n'
				+'				<b>Age Restriction: '+obj['ageRestriction']+'</b>'+'\n'
				+'			</div>'+'\n'
				+'		</td>'+'\n'
				+'	</tr>'+'\n'
				+'</table>'+'\n';
		
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
	
	console.log(obj['title']);
	console.log(div);
	
	var mH = defaultMinHeight
	if(div > 3){
		mH += (div - 3)*8;
	}
	
	if(mH > titleMinHeight){ titleMinHeight = mH; }
	
	$('div.movie_Title').attr("style",'min-height: '+titleMinHeight + 'px;');	
	$('div#'+id+' h3').attr("style",'font-size: '+ size + 'pt;');
	
}
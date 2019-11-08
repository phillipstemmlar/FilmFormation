<?php
session_start();

const br = '<br/>';

const OMDB_URL_name = "https://www.omdbapi.com/?r=json&t=";
const OMDB_URL_id = "https://www.omdbapi.com/?r=json&i=";
const OMDB_ID = "&apikey=fea5ba46";

const FANART_URL_IMG = "https://webservice.fanart.tv/v3/movies/";
const FANART_ID  = "?api_key=30b4104d3e710fbdc79b3f2b9cf1969c"; 

const TMDB_URL_ID = "http://api.themoviedb.org/3/movie/";
const TMDB_URL_SEARCH = "http://api.themoviedb.org/3/search/movie?query=";
const TMDB_URL_FILTER = "http://api.themoviedb.org/3/discover/movie?";
const TMDB_URL_GENRES = "http://api.themoviedb.org/3/genre/movie/list?";
const TMDB_ID = "api_key=c74013a77702b3a36e9276a80d24dddd";

$online = true;
$root = ($online)? '/u18171185/' : '/';
$root_file = dirname(__FILE__).'/../';
$root_img = ($online)? $root : '../';

$UserPass = 'u18171185:Philstembul108102@';

$logoIMG 	  = $root_img."extra/logo.png";
$searchBtnIMG = $root_img."extra/searchButton.png";

$home_href 		= $root."index.php";
$discover_href  = $root."discover/discover.php";
$featured_href  = $root."featured/featured.php";
$latest_href 	= $root."latest/latest.php";
$topRated_href  = $root."topRated/topRated.php";
$calendar_href  = $root."calendar/calendar.php";

require_once $root_file.'php/Database.php';
require_once $root_file.'php/struct.php';

?>
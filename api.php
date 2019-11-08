<?php
require_once './php/config.php';

parse_str(file_get_contents('php://input'),$_POST);

header('Content-Type: application/json');

echo API::getInstance()->receive((object)$_POST);

return;

class API {
	private $_connection;
	private static $_instance; //The single instance
	private $handle;
	
	private $types = ['info','update','login','register','rate','trakt','genres','fanart','video','changePassword'];
	
	private $genreList = [];
	private $genreNames = [];
	private $genres = [];
	
	private $logged;
	
	private $movieCount = 20;
	private $minMovies = 20;
	
	public static function getInstance() {
		if(!self::$_instance) { 			// If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	private function __clone() { }
	
	private function __construct() {
		$this->handle = curl_init();
		$this->logged = true;
	}  
	public function __destruct(){
		curl_close($this->handle);
	}
	
	public function remoteRequest($url,$parms = '')
	{	
		curl_setopt($this->handle, CURLOPT_PROXY, "phugeet.cs.up.ac.za:3128");
		curl_setopt($this->handle, CURLOPT_URL, $url.'?'.$parms);
		curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
		
		$result = curl_exec($this->handle);
		return json_decode($result);
	}
	
	public function receive($req)
	{		
		$result = new stdClass();
		$result->status = 'success';
		$result->timestamp = time();
		
		$failed = false;
		
		$result->data = $this->getRequestData($req);

		if($result->data == false) {
			$failed = true;
		}

		if($failed){
			$fail = new stdClass();
			$fail->status = 'error';
			$fail->timestamp = $result->timestamp;
			return json_encode($fail);
		}
		return json_encode($result);
	}
	
	private function getRequestData($vals)
	{
		//['info,'genres','fanart','video','register','login','update','rate','trakt']
		if(!isset($vals->type))
		{
			return false;
		}
		else if($vals->type == 'info')
		{
			$omdb = false;
			$byID = false;
			$byYear = false;
			$byTitle = false;
			$byGenre = false;
			$byTMDB_ID = false;
			
			$apikey = '';
			$title = '';
			$id = '';
			$year = '';
			$genreID = '';
			
			if( isset($vals->imdbID))
			{
				$omdb = true;
				$id = '&i='.$vals->imdbID;
				$byID = true;
			}
			
			if(isset($vals->title)){
				if($vals->title == '*'){
					$omdb = false;
				}else{
					if($omdb)
					{
						$title = '&t='.$vals->title;
					}
					else{ 
						$byTitle = true;
						$title = 'query='.$vals->title;;
					}
				}
			}
			if(isset($vals->year)) {
				if(!$omdb){
					$byYear = true;
					$year = '&primary_release_year='.$vals->year;
				}else{
					$year = '&y='.$vals->year;
				}
			}
			if(isset($vals->genre)) {
				if(!$omdb){
					$byGenre = true;
					$genreID = '&with_genres='.$vals->genre;
				}else{
					$year = '&y='.$vals->year;
				}
			}
			
			if( isset($vals->tmdbID) )
			{
				if(!$omdb)
				{
					$byTMDB_ID = true;
					$id = $vals->tmdbID;
				}
			}
			
			$returnTypes = $vals->return;
			$res = '';
			
			if($omdb){												//werk
				$apikey = '&apikey=fea5ba46';
				
				if($byID){
					$parms = 'r=json'.$id.$title.$year.$apikey;
				}else{
					$parms = 'r=json'.$title.$id.$year.$apikey;
				}
				
				$url = 'http://www.omdbapi.com/';
				
				$res = $this->remoteRequest($url,$parms);
			
				//var_dump($res);
				
				if($res == false){ return false; }
							
				//return vals
				$data = new stdClass();
				
				if(in_array('year',$returnTypes)){$data->year = $res->Year;}
				if(in_array('imdbID',$returnTypes)){$data->imdbID = $res->imdbID;}
				if(in_array('imdbRating',$returnTypes)){$data->imdbRating = $res->imdbRating;}
				if(in_array('title',$returnTypes)){$data->title = $res->Title; }
				if(in_array('release',$returnTypes)){$data->release = $res->Released;}
				if(in_array('synopsis',$returnTypes)){$data->synopsis = $res->Plot;}
				if(in_array('poster',$returnTypes)){$data->poster = $res->Poster;}
				if(in_array('year',$returnTypes)){$data->year = $res->Year;}
				if(in_array('genre',$returnTypes)){$data->genre = $res->Genre;}	
				if(in_array('ageRestriction',$returnTypes)){$data->ageRestriction = $res->Rated;}
				if(in_array('imdbID',$returnTypes)){$data->imdbID = $res->imdbID;}
				if(in_array('imdbRating',$returnTypes)){$data->imdbRating = $res->imdbRating;}
				if(in_array('imdbVotes',$returnTypes)){$data->imdbVotes = $res->imdbVotes;}
				
				if(in_array('runtime',$returnTypes)){$data->runtime = $res->Runtime;}
				if(in_array('country',$returnTypes)){$data->country = $res->Country;}
				
			}
			else{
				//$this->movies = new Queue();
				
				$apikey = "&api_key=c74013a77702b3a36e9276a80d24dddd";
				$ulr = '';
				$parms = '';
				
				if($byTMDB_ID)
				{
					$url = 'http://api.themoviedb.org/3/movie/'.$id;
					$apikey = "api_key=c74013a77702b3a36e9276a80d24dddd";
					$parms .= $apikey;
					$res = $this->remoteRequest($url,$parms);
					
					//var_dump($res);
					
					if($res == false){ return false; }
										
					$data = new stdClass();		
					if(in_array('imdbID',$returnTypes)){$data->imdbID = $res->imdb_id;}
										
					return $data;
				}
				else if($byTitle)				//search				//werk
				{
					$url = 'http://api.themoviedb.org/3/search/movie';
					$parms .= $title;
					$parms .= $apikey;
					$res = $this->remoteRequest($url,$parms);
					
					if($res == false){ return false; }
					
					$data = [];
										
					for ($i = 0; $i < $this->minMovies; $i++)	//count((array)$res['results']) ;$i++)
					{
						$data[$i] = new stdClass();
						
						if(in_array('title',$returnTypes)){$data[$i]->title = $res->results[$i]->title; }
						if(in_array('release',$returnTypes)){$data[$i]->release = $res->results[$i]->release_date;}
						if(in_array('synopsis',$returnTypes)){$data[$i]->synopsis = $res->results[$i]->overview;}
						if(in_array('poster',$returnTypes)){$data[$i]->poster = 'http://image.tmdb.org/t/p/w185'.$res->results[$i]->poster_path;}
						if(in_array('year',$returnTypes)){$data[$i]->year = substr($res->results[$i]->release_date,0,4);}
						if(in_array('genre',$returnTypes)){$data[$i]->genre = $this->genres($res->results[$i]->genre_ids);}
						if(in_array('ageRestriction',$returnTypes)){$data[$i]->ageRestriction = 'N/A';}
						if(in_array('imdbID',$returnTypes)){$data[$i]->imdbID = 'N/A';}
						if(in_array('imdbRating',$returnTypes)){$data[$i]->imdbRating = 'N/A';}
						if(in_array('imdbVotes',$returnTypes)){$data[$i]->imdbVotes = 'N/A';}
						if(in_array('tmdbID',$returnTypes)){$data[$i]->tmdbID = $res->results[$i]->id;}
					}
				}
				else	//filter									//werk
				{
					$url = 'http://api.themoviedb.org/3/discover/movie';
					if($byYear) { $parms .= '&primary_release_year='.$vals->year; }
					if($byGenre) { $parms .= $genreID; }
					if( isset($vals->region) ) { $parms = $parms.'&region='.$vals->region;}
					if( isset($vals->monthStart) ) { $parms = $parms.'&primary_release_date.gte='.$vals->monthStart;}
					if( isset($vals->monthEnd) ) { $parms = $parms.'&primary_release_date.lte='.$vals->monthEnd;}

					//var_dump($url.'?'.$parms);
					
					$parms .= $apikey;
					$res = $this->remoteRequest($url,$parms);
					
					if($res == false){ return false; }
					
					$data = [];
										
					for ($i = 0; $i < $this->minMovies; $i++)	//count((array)$res['results']) ;$i++)
					{
						$data[$i] = new stdClass();
						
						if(in_array('title',$returnTypes)){$data[$i]->title = $res->results[$i]->title; }
						if(in_array('release',$returnTypes)){$data[$i]->release = $res->results[$i]->release_date;}
						if(in_array('synopsis',$returnTypes)){$data[$i]->synopsis = $res->results[$i]->overview;}
						if(in_array('poster',$returnTypes)){$data[$i]->poster = 'http://image.tmdb.org/t/p/w185'.$res->results[$i]->poster_path;}
						if(in_array('year',$returnTypes)){$data[$i]->year = substr($res->results[$i]->release_date,0,4);}
						if(in_array('genre',$returnTypes)){$data[$i]->genre = $this->genres($res->results[$i]->genre_ids);}
						if(in_array('ageRestriction',$returnTypes)){$data[$i]->ageRestriction = 'N/A';}
						if(in_array('imdbID',$returnTypes)){$data[$i]->imdbID = 'N/A';}
						if(in_array('imdbRating',$returnTypes)){$data[$i]->imdbRating = 'N/A';}
						if(in_array('imdbVotes',$returnTypes)){$data[$i]->imdbVotes = 'N/A';}
						if(in_array('tmdbID',$returnTypes)){$data[$i]->tmdbID = $res->results[$i]->id;}
					}
				}
			}
			
			return $data;
			
		}
		else if($vals->type == 'genres')
		{
			$this->loadGenres();
			if(count($this->genres) > 0)
			{
				$returnTypes = $vals->return;
				$data = [];
				for($i = 0; $i < count($this->genreList); $i++)
				{
					$data[$i] = new stdClass();
					if(in_array('genreID',$returnTypes)){$data[$i]->genreID = $this->genreList[$this->genres[$i]];}
					if(in_array('genreName',$returnTypes)){$data[$i]->genreName = $this->genres[$i];}
				}
				return $data;
			}
			return false;
		}
		else if($vals->type == 'fanart')
		{
			if( isset($vals->imdbID)){ $id = $vals->imdbID; }
			else{return false;}
			
			$url = 'http://webservice.fanart.tv/v3/movies/'.$id;
			$apikey = "api_key=30b4104d3e710fbdc79b3f2b9cf1969c";
			$parms = $apikey;
			$res = $this->remoteRequest($url,$parms);

			//var_dump($res);

			if($res == false){ return false; }
			
			$returnTypes = $vals->return;
			
			$data = new stdClass();		
			if(in_array('posters',$returnTypes)){$data->posters = $res->movieposter;}

			return $data;
		}
		else if($vals->type == 'video')
		{
			if( isset($vals->imdbID)){ $id = $vals->imdbID; }
			else{return false;}

			$url = "http://api.themoviedb.org/3/movie/".$id."/videos";
			$apikey = "api_key=c74013a77702b3a36e9276a80d24dddd";
			$parms = $apikey;
			$res = $this->remoteRequest($url,$parms);

			//var_dump($res);

			if($res == false){ return false; }
			if(count((array)$res->results) <= 0){return false;}
			
			$returnTypes = $vals->return;
			
			$data = new stdClass();		
			if(in_array('videoKey',$returnTypes)){$data->videoKey = $res->results[0]->key;}

			return $data;
			//requestFromAPI("GET", TMDB_URL_ID,movieID,'/videos?'+TMDB_ID,func,asnc); 
		}
		else if($vals->type == 'register')
		{	
			$email = '';
			$name = '';
			$surname = '';
			$pass = '';
			
			if(isset($vals->email))	  { $email = $vals->email; }
			if(isset($vals->name))	  { $name = $vals->name; }
			if(isset($vals->surname)) { $surname = $vals->surname; }
			if(isset($vals->password)){ $pass = $vals->password; }

			//generate apikey
			$apikey = $this->generateAPIkey();
			
			$salt = $this->generateSalt();
			$pass_h = $this->hashing($pass,$salt);

			$success = Database::getInstance()->insertUser($name,$surname,$email,$pass_h,$salt,$apikey);
			
			//var_dump($success);
			
			$returnTypes = $vals->return;	
			//var_dump($returnTypes);
			
			
			if($success)
			{
				$data = new stdClass();
				if(in_array('key',$returnTypes)){ $data->key = $apikey; }
				if(in_array('email',$returnTypes)){ $data->email = $email; }
				
				return $data;
			}
			
		}
		else if($vals->type == 'login')
		{
			$email = '';
			$name = '';
			$surname = '';
			$pass = '';
			
			if(isset($vals->email))	  { $email = $vals->email; }
			if(isset($vals->password)){ $pass  = $vals->password; }
			
			$result = Database::getInstance()->selectUsers('email = "'.$email.'" ');
			
			$returnTypes = $vals->return;	
			
			if($result)
			{
				$correct = ($this->hashing($pass,$result[0]->salt) == $result[0]->password_h);
				
				if($correct){
					$data = new stdClass();
					if(in_array('key',$returnTypes)){ $data->key = $result[0]->apikey; }
					if(in_array('email',$returnTypes)){ $data->email = $result[0]->email; }
					if(in_array('pref_Genre',$returnTypes)){ $data->pref_Genre = $result[0]->pref_Genre; }
					if(in_array('pref_Year',$returnTypes)){ $data->pref_Year = $result[0]->pref_Year; }
					if(in_array('pref_Rating',$returnTypes)){ $data->pref_Rating = $result[0]->pref_Rating; }
					return $data;
				}
			}
		
		}
		else if($vals->type == 'update')
		{
			$apikey = '';
			$email = '';
			$pref_Genre = '';
			$pref_Year = '';
			$pref_Rating = '';
			
			$Settings = '';
			
			if(isset($vals->key)){ $apikey  	  = $vals->key; }
			if(isset($vals->email))		{ $Settings = $Settings.', email = "'.$vals->email.'" '; }
			if(isset($vals->pref_Genre)){ $Settings = $Settings.', pref_Genre = "'.$vals->pref_Genre.'" '; }
			if(isset($vals->pref_Year)) { $Settings = $Settings.', pref_Year = "'.$vals->pref_Year.'" '; }
			if(isset($vals->pref_Rating)) { $Settings = $Settings.', pref_Rating = "'.$vals->pref_Rating.'" '; }
			
			
			$filter = 'APIkey = "'.$apikey.'" ';
			
			
			
			if(strlen($Settings) <= 0){ return false; }
			
			$Settings = substr($Settings,2);
			
			$result = Database::getInstance()->updateUser($Settings,$filter);
			
			$returnTypes = $vals->return;	
			
			if($result)
			{
				$data = new stdClass();
				if(in_array('key',$returnTypes)){ $data->key = $apikey; }
				return $data;
			}
		}
		else if($vals->type == 'changePassword')
		{
			$apikey = '';
			$oldPassword = '';
			$newPassword = '';
			
			if(isset($vals->key)){ $apikey  	 = $vals->key; 	}else{ return false; }
			if(isset($vals->old)){ $oldPassword  = $vals->old;  }else{ return false; }
			if(isset($vals->new)){ $newPassword  = $vals->new;  }else{ return false; }
			
			$result1 = Database::getInstance()->selectUsers('APIkey = "'.$apikey.'" ');
			
			if($result1){

				$correct = ($this->hashing($oldPassword,$result1[0]->salt) == $result1[0]->password_h);

				//$correct = true;
				
				if($correct){
				
					$salt = $this->generateSalt();
					$newPassword_h = $this->hashing($newPassword,$salt);
					
					$pass_parm = 'password = "'.$newPassword_h.'" , salt = "'.$salt.'" ';
					$filter_parm = 'APIkey = "'.$apikey.'" ';

					$result2 = Database::getInstance()->updateUser($pass_parm,$filter_parm);

					$returnTypes = $vals->return;	

					if($result2)
					{
						$data = new stdClass();
						$data->key = $apikey;
						return $data;
					}
				}
			}
			
		}
		else if($vals->type == 'rate')
		{
			
		}
		else if($vals->type == 'trakt')
		{
			if($vals->method == 'update')
			{
				/*
				$post_parms =
					type=trakt
					&method=update
					&key=ad8sad8a6d87a8da86d87a8da87da78d
					&videoSource=file:///C:/Programming/nodeJS_project/sample.mp4
					&videoName=Sample
					&time=100			//(s)
					&return[]=key
					&return[]=videoSource
					&return[]=videoName
					&return[]=time
				*/
				
				$apikey = '';
				$videoSource = '';
				$videoName = '';
				$time = 0;
				
				if(isset($vals->key))	  	{ $apikey = $vals->key; }
				if(isset($vals->videoSource))  { $videoSource  = $vals->videoSource; }
				if(isset($vals->videoName))	{ $videoName = $vals->videoName; }
				if(isset($vals->time))		{ $time  = $vals->time; }
				
				$result = Database::getInstance()->insertTrakt($apikey,$videoSource,$videoName,$time);
				
				if($result){
					return true;
				}else{
				
					$result = Database::getInstance()->updateTrakt($time,'APIkey = "'.$apikey.'"','VideoSource = "'.$videoSource.'"');

					$returnTypes = $vals->return;	

					if($result)
					{
						$data = new stdClass();

						if(in_array('key',$returnTypes)){ $data->key = $apikey; }
						if(in_array('videoSource',$returnTypes)){ $data->videoSource = $videoSource; }
						if(in_array('videoName',$returnTypes)){ $data->videoName = $videoName; }
						if(in_array('time',$returnTypes)){ $data->time = $time; }

						return $data;
					}
				}
			}
			else
			{
				
				
				/*
				$post_parms =
					type=trakt
					&method=retrieve
					&key=ad8sad8a6d87a8da86d87a8da87da78d
					&videoSource=file:///C:/Programming/nodeJS_project/sample.mp4
					&return[]=key
					&return[]=videoSource
					&return[]=time
				*/
				
				$apikey = '';
				$videoSource = '';
				
				if(isset($vals->key))	  	  { $apikey = $vals->key; }
				if(isset($vals->videoSource)) { $videoSource  = $vals->videoSource; }
				
				$result = Database::getInstance()->selectTrakts('APIkey = "'.$apikey.'"','VideoSource = "'.$videoSource.'"');
				
				$returnTypes = $vals->return;	
				
				if($result)
				{
					$data = new stdClass();
					if(in_array('key',$returnTypes)){ $data->apikey = $result[0]->apikey; }
					if(in_array('videoSource',$returnTypes)){ $data->videoSource =  $result[0]->videoSource; }
					if(in_array('time',$returnTypes)){ $data->time = $result[0]->time; }
					
					return $data;
				}
			}
			
		}
		else if( in_array($vals->type,$this->types) )
		{
			return 'other';
		}
		
		return false;
	}
	
	private function getIMDB_ID($tmdb_id)
	{
		$url = 'https://api.themoviedb.org/3/movie/'.$tmdb_id;
		$apikey = "api_key=c74013a77702b3a36e9276a80d24dddd";
		$parms = $apikey;
		$res = $this->remoteRequest($url,$parms);

		if($res == false){ return; }
		
		if($res->imdb_id)
		{
			$this->getOMDB_Data($res->imdb_id);
		}
		else{
			$this->minMovies--;
		}
	}
	
	private function getOMDB_Data($imdbID)
	{
		$apikey = '&apikey=fea5ba46';
		$parms = 'r=json&i='.$id.$apikey;

		$url = 'http://www.omdbapi.com/';

		$res = $this->remoteRequest($url,$parms);

		if($res == false){ return false; }

		//return vals
		$data = new stdClass();

		if(in_array('year',$returnTypes)){$data->year = $res->Year;}
		if(in_array('imdbID',$returnTypes)){$data->imdbID = $res->imdbID;}
		if(in_array('imdbRating',$returnTypes)){$data->imdbRating = $res->imdbRating;}
		if(in_array('title',$returnTypes)){$data[$i]->title = $res->Title; }
		if(in_array('release',$returnTypes)){$data[$i]->release = $res->Released;}
		if(in_array('synopsis',$returnTypes)){$data[$i]->synopsis = $res->Plot;}
		if(in_array('poster',$returnTypes)){$data[$i]->poster = $res->Poster;}
		if(in_array('year',$returnTypes)){$data[$i]->year = $res->Year;}
		if(in_array('genre',$returnTypes)){$data[$i]->genre = $res->Genre;}	
		if(in_array('ageRestriction',$returnTypes)){$data[$i]->ageRestriction = $res->Rated;}
		if(in_array('imdbID',$returnTypes)){$data[$i]->imdbID = $res->imdbID;}
		if(in_array('imdbRating',$returnTypes)){$data[$i]->imdbRating = $res->imdbRating;}
		if(in_array('imdbVotes',$returnTypes)){$data[$i]->imdbVotes = $res->imdbVotes;}
		
		$this->movies->push($data);
	}
	
	private function genres($genre_ids)
	{
		$this->loadGenres();
		$res = [];
		for($i = 0; $i < count($genre_ids); $i++)
		{
			$res[$i] = $this->genreNames[$genre_ids[$i]];
		}
		return $res;
	}
	
	private function loadGenres()
	{
		if(count($this->genres) <= 0)
		{
			$apikey = 'api_key=c74013a77702b3a36e9276a80d24dddd';
			$url = 'http://api.themoviedb.org/3/genre/movie/list';
			$parms = $apikey;
			$res = $this->remoteRequest($url,$parms);

			if($res == false){ return;}

			for($i = 0; $i < count($res->genres); $i++)
			{
				$this->genreList[$res->genres[$i]->name] = $res->genres[$i]->id;
				$this->genreNames[$res->genres[$i]->id] = $res->genres[$i]->name;
				$this->genres[$i] = $res->genres[$i]->name;
			}
		}
	}
	
	private function validateRequest($vals)
	{		
		if( !isset($vals->type) ) 					{ return false; }
		if( !$this->logged && !isset($vals->key) )  { return false; }
		if( !isset($vals->return) ) 				{ return false; }
		if( count((array)$vals->return) <= 0) 		{ return false; }
		return true;
	}
	
	public function getError()
	{
		return curl_error();
	}
	
	public function generateAPIkey()
	{
		$minlength = 10;
		$maxlength = 30;
		$valid = false;
		
		do{
			$length = random_int($minlength,$maxlength);
			$token  = bin2hex(random_bytes($length));

			if(!Database::getInstance()->selectUsers('APIkey = "'.$token.'" ') ){
				$valid = true;
			}
		}while(!$valid);
		
		return $token;
	}
	
	public function generateSalt()
	{
		$minlength = 10;
		$maxlength = 30;
		$valid = false;
		
		do{
			$length = random_int($minlength,$maxlength);
			$token  = bin2hex(random_bytes($length));

			if(!Database::getInstance()->selectUsers('salt = "'.$token.'" ') ){
				$valid = true;
			}
		}while(!$valid);
		
		return $token;
	}
	
	public function hashing($string,$salt)
	{
		$string = $string.$salt;
		$algo = "sha256";
		$h = hash($algo,$string);
		return $h;
	}
	
}

?>
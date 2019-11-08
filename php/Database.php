<?php

class Database {
	private $_connection;
	private static $_instance; //The single instance
	private $_host = "wheatley.cs.up.ac.za";
	private $_username = "u18171185";
	private $_password = "Philstembul108102";
	private $_database = "u18171185_COS216";
	
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	private function __construct() {
		$this->_connection = mysqli_connect($this->_host, $this->_username, 
			$this->_password, $this->_database);
	
		// Error handling
		if($this->_connection->connect_error) {
			trigger_error("Failed to conencto to MySQL: " . $this->_connection->connect_error,
				 E_USER_ERROR);
		}
	}   
	public function __destruct(){
		$this->_connection->close();
	}
	private function __clone() { }
	private function getConnection() {
		return $this->_connection;
	}
	
	public function getError(){	
		return	$this->getConnection()->connect_error;
	}
	
	public function insertUser($name, $surname, $email, $password, $salt, $apikey){
		$sql_query = 'INSERT INTO Users (name, surname, email, password, salt, APIkey, pref_Genre, pref_Year, pref_Rating)
			VALUES("'.$name.'","'.$surname.'","'.$email.'","'.$password.'","'.$salt.'","'.$apikey.'",null,null,null);';
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}
	public function deleteUser($filter = ""){
		if(strlen($filter) > 0){
			$filter = ' WHERE ('.$filter.')';
		}
		
		$sql_query = 'DELETE FROM Users'.$filter.';';
		
		//echo $sql_query . "</br>";
		
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}
	public function updateUser($SetValuesString,$filter = ""){
		if(strlen($filter) > 0){
			$filter = ' WHERE ('.$filter.')';
		}
		//UPDATE Users SET password = "5d5c2eb512032dd69ca0" , salt = "5d5c2eb512032dd69ca0" WHERE APIkey = "e0064432f2eddb1959f572dac95f3f3a39";
		$sql_query = 'UPDATE Users SET '.$SetValuesString.' '.$filter.';';		
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}
	public function selectUsers($filter = "") {
		if(strlen($filter) > 0){
			$filter = ' WHERE ('.$filter.')';
		}
		
		$sql_query = 'SELECT * FROM Users'.$filter.';';		
		$stmt = $this->_connection->prepare($sql_query);
		
		if(!$stmt){ return false; }
		
        $stmt->execute();
        $stmt->bind_result($id,$name, $surname, $email, $password, $salt, $apikey, $genre, $year, $rating);

        $users = [];
        $i = 0;
		
        while( $stmt->fetch() ) {
			$users[$i++] = (object)["apikey" => $apikey, "id" => $id, "name" => $name,  "surname" => $surname,
									"email" => $email, "password_h" => $password,
									"salt" => $salt, "pref_Genre" => $genre, "pref_Year" => $year, "pref_Rating" => $rating];
        }
        $stmt->close();
		return $users;
	}

	public function insertTrakt($apikey,$videoSource,$videoName,$time){
		$sql_query = 'INSERT INTO Trakt (APIkey, VideoSource, VideoName, Time)
			VALUES("'.$apikey.'","'.$videoSource.'","'.$videoName.'","'.$time.'");';
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}	
	public function deleteTrakt($filter1 = "", $filter2 = ""){
		$filter = '';
		if(strlen($filter1) > 0 && strlen($filter2) > 0){
			$filter = ' WHERE ('.$filter1.') AND ('.$filter2.')';
		}
		else if(strlen($filter1) > 0){
			$filter = ' WHERE ('.$filter1.')';
		}
		else{
			$filter = ' WHERE ('.$filter2.')';
		}
		
		$sql_query = 'DELETE FROM Trakt'.$filter.';';
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}	
	public function selectTrakts($filter1 = "", $filter2 = "") {
		$filter = '';
		if(strlen($filter1) > 0 && strlen($filter2) > 0){
			$filter = ' WHERE ('.$filter1.') AND ('.$filter2.')';
		}
		else if(strlen($filter1) > 0){
			$filter = ' WHERE ('.$filter1.')';
		}
		else{
			$filter = ' WHERE ('.$filter2.')';
		}
		
		$sql_query = 'SELECT * FROM Trakt'.$filter.';';		
		$stmt = $this->_connection->prepare($sql_query);
		
		if(!$stmt){ return false; }
		
        $stmt->execute();
        $stmt->bind_result($apikey,$videoSource, $videoName, $time);

        $users = [];
        $i = 0;
		
        while( $stmt->fetch() ) {
			$users[$i++] = (object)["apikey" => $apikey,
								   	"videoSource" => $videoSource,
									"videoName" => $videoName,
									"time" => $time,
								   ];
        }
        $stmt->close();
		return $users;
	}
	public function updateTrakt($time,$filter1 = "", $filter2 = "") {
		$filter = '';
		if(strlen($filter1) > 0 && strlen($filter2) > 0){
			$filter = ' WHERE ('.$filter1.') AND ('.$filter2.')';
		}
		else if(strlen($filter1) > 0){
			$filter = ' WHERE ('.$filter1.')';
		}
		else{
			$filter = ' WHERE ('.$filter2.')';
		}
		
		$sql_query = 'UPDATE Trakt SET Time = "'.$time.'"'.$filter.';';		
		$result = $this->getConnection()->query($sql_query);
		return ($result === true);
	}
	
}

?>
<?php 		
	require_once 'config.php';

	$displayLogin = '';
	$logged = false;			

	if(isset($_SESSION['UserEmail'])){
		$user = Database::getInstance()->selectUsers(' email = "'.$_SESSION['UserEmail'].'" ');
		if($user){
			$logged = true;
		}
	}

	if($logged)
	{
		
		
		$displayLogin = '<span id="Username">' .$user[0]->name.' '.$user[0]->surname. '</span>
						<div id="login-controls">
							<input type="button" value="Log Out" onclick="Logout();"/>
						</div>';
	}
	else{
		$displayLogin = '<span id="Username">You are not logged in</span>
						<div id="login-controls">
							<input type="button" value="Log In" onclick="Login();" />
							<input type="button" value="Register" onclick="SignUp();"/>
						</div>';
	}


	echo '
		<div id="Head">
            <div id="logo">
                <a href="'.$root.'">
                    <img src="'.$logoIMG.'" alt="Logo Image" />
                </a>
				<div id="log">
					'.$displayLogin.'
				</div>
            </div>
            <div id="nav" >
                <nav>
                    <a href="'.$home_href.'" id="home" >Home</a> |
                    <a href="'.$discover_href.'" id="discover" >Discover</a> |
                    <a href="'.$featured_href.'" id="featured" >Featured</a> |
                    <a href="'.$latest_href.'" id="latest" >Latest</a> |
                    <a href="'.$topRated_href.'" id="topRated" >Top Rated</a> |
                    <a href="'.$calendar_href.'" id="calendar" >Calendar</a>
                </nav>
                <div id="SearchNav">
                    <input type="search" id="SearchBar" placeholder=" Search Movies..." onkeyup="onkeySearch(event)"/>
                    <input type="image" id="SearchButton" src="'.$searchBtnIMG.'" alt="" onClick="onSearch()"/>
                </div>
            </div>
        </div>
		';		
?>
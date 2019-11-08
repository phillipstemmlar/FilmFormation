var online = true;
var root = (online)? "/u18171185/" : "/";

var setCurPage = function(linkID)
{
	document.getElementById(linkID).classList.add("curPage");
}

var Login = function()
{
	window.location= root+"login/login.php?location="+window.location;
}

var Logout = function()
{
	window.location= root+"login/logout.php?location="+window.location;
}

var SignUp = function()
{
	window.location = root+"signup/signup.php?location="+window.location;
}
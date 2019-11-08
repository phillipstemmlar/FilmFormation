var attemptSignUp = function()
{
	//validate if all fields are filled and valid.
	var valid = true;
	
	if(!validateName(document.getElementById('nameIn').value))
	{
		valid = false;
		console.log("name is wrong!");
	}
	
	if(!validateName(document.getElementById('surnameIn').value))
	{
		valid = false;
		console.log("Surname is wrong!");
	}
	
	if(!validateEmail(document.getElementById('emailIn').value))
	{
		valid = false;
		console.log("Email is wrong!");
	}
	
	if(!validatePassword(document.getElementById('passwordIn').value))
	{
		valid = false;
		console.log("Password is wrong!");
	}
	
	//send data to server for validation
	if(valid)
	{
		document.getElementById('signUpForm').submit();
		console.log('valid!');
	}
	else{
		console.log('not valid!');
	}
}

function validateEmail(email) {
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regex.test(String(email).toLowerCase());
}

function validatePassword(password) {
	var regex = /^((?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*]))(?=.{8,})/;
	return regex.test(String(password));
}

function validateName(name) {
	var regex = /^((?=.*[a-z])|(?=.*[A-Z]))(?=.{2,})/;
	return regex.test(String(name));
}










<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>LCSC Workout App Test Login</title>
  <meta name="description" content="LCSC Workout App Test Login">
  <meta name="author" content="SitePoint">



</head>

<body>

<form name="myForm" id="loginForm">
  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input id="email" type="email" placeholder="Enter Email" name="email" required>
<br>
    <label for="psw"><b>Password</b></label>
    <input id="password" type="password" placeholder="Enter Password" name="psw" required>
<br>
    <button type="submit">Login</button>

  </div>


</form>


<div id="response"></div>







	<script type="text/javascript">

		
		
		// your function
		var submit_login = function(event) {
			console.log("Working");
		    event.preventDefault();
		    var password = document.getElementById("password");
		    var username = document.getElementById("email");
		    
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "http://isoptera.lcsc.edu/~tcherbert/lcsc_workout_app/includes/process_login.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
			xhttp.send('password=' + password.value + '&email=' + username.value);
			
			xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			      console.log(this.responseText);
			      
			      var returnedJSON = JSON.parse(this.responseText);
			      console.log(returnedJSON.message);
			      
      		      var responseDiv = document.getElementById("response");
      		      responseDiv.innerHTML = returnedJSON.message;			      
			    }
			 };		    



		};
		
		// your form
		var form = document.getElementById("loginForm");


		form.addEventListener("submit", submit_login, true);
		
		
		

	</script>

</body>
</html>
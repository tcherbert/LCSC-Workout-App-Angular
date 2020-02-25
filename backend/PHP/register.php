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
    
    <label for="name"><b>Name</b></label>
    <input id="name" type="text" placeholder="Enter Name" name="name" required>
<br>    
    <label for="email"><b>Email</b></label>
    <input id="email" type="email" placeholder="Enter Email" name="email" required>
<br>
    <label for="psw"><b>Password</b></label>
    <input id="password" type="password" placeholder="Enter Password" name="psw" required>
<br>
    <button type="submit">Login</button>

  </div>


</form>



<p id="response"></p>









	<script type="text/javascript">

		
		
		// your function
		var submit_login = function(event) {
		    console.log('This is firing.');
		    
		    
		    event.preventDefault();
		    var name 		= document.getElementById("name");
		    var password 	= document.getElementById("password");
		    var email 		= document.getElementById("email");
		    
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "http://isoptera.lcsc.edu/~tcherbert/lcsc_workout_app/includes/register.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	
	
			//var data = JSON.stringify({ "name": name.value, "email": email.value, "password": password.value });
			
			// Converting JSON data to string 
/*
            var data = { "name": name.value, "email": email.value, "password": password.value };
            var jsonData = JSON.stringify(data); 
*/

			var data = 'name=' + name.value + '&email=' + email.value + '&password=' + password.value;
            

  
            // Sending data with the request 
            xhttp.send(data);
			
			xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			      console.log(this.responseText);
			      
			      var returnedJSON = JSON.parse(this.responseText);
			      console.log(returnedJSON);
			      
			     var responseElement = document.getElementById("response");
			     responseElement.innerHTML = returnedJSON.name;
			    
			      
			    } else {
					//var returnedJSON = JSON.parse(this.responseText);
				    //console.log(returnedJSON);
			    }
			 };		    



		}
		
		// your form
		var form = document.getElementById("loginForm");


		form.addEventListener("submit", submit_login, true);
		
		
		

	</script>

</body>
</html>
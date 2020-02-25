<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Workout App API Example</title>


<!--   <link rel="stylesheet" href="css/styles.css?v=1.0"> -->

</head>

<body>


	<p>
		*** Key *** <br>
		[0]User -- name, <br>
		[1]User -- about, <br> 
		[2]User -- joined_date, <br> 
		[3]User Image -- url, <br>
		[4]Cover Image -- image_url <br>
	</p>
	<button onClick="callAPI()">Call API</button>	
		

	<script type="text/javascript">

		function callAPI(){
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST", "http://isoptera.lcsc.edu/~tcherbert/php/data.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

			xhttp.send("id=1");
			
			xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			      //console.log(this.responseText);
			      
			      var returnedJSON = JSON.parse(this.responseText);
			      console.log(returnedJSON);
			      
			    }
			 };
		}

	</script>
</body>
</html>
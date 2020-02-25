<?php
//header("Content-Type:application/json");


//$link is DB Connection
include_once("./connection/db.php");

//$link -> select_db("workout_app");

/*
$result = $mysqli -> query("SELECT DATABASE()");
  $row = $result -> fetch_row();
  echo "Default database is " . $row[0];
  $result -> close();


echo '<pre>';
var_dump($result);
echo '</pre>';
*/


/*
echo 'This is the return value!';
die;
*/




function get_profile($link){

//$firstname = $mysqli -> real_escape_string($_POST['firstname']);


	$id = $link -> real_escape_string($_REQUEST['id']);


	$sql = '
				SELECT U.name, U.about, U.joined_date, UI.url, CI.image_url 
				FROM users as U, user_images as UI, cover_images as CI
				WHERE U.id=' . $id . '
				AND UI.user_id = U.id 
				AND U.active_profile_image_id = UI.id
				AND CI.user_id = U.id
				AND U.cover_image_id = CI.image_id;
		   ';
	
	$result = mysqli_query($link, $sql);
	
	
	
	if($result = mysqli_query($link, $sql)){
	    
	    
	    //$row = $result -> fetch_row();
		$fetch_result = array();
		$counter = 0;
		while($row = $result -> fetch_row()) {
			$fetch_result[$counter] = $row;
			//$counter++;
		}
		
		$encoded_result = json_encode($fetch_result);
		echo $encoded_result;

		
		
/*
		echo '<br><br><pre>';
		var_dump($row);
		echo '</pre>';
		die;
*/



	}
		
/*
	    if(mysqli_num_rows($result) > 0){
	        echo "<table>";
	            echo "<tr>";
	                echo "<th>id</th>";
	                echo "<th>first_name</th>";
	                echo "<th>last_name</th>";
	                echo "<th>email</th>";
	            echo "</tr>";
	        while($row = mysqli_fetch_array($result)){
	            echo "<tr>";
	                echo "<td>" . $row['id'] . "</td>";
	                echo "<td>" . $row['first_name'] . "</td>";
	                echo "<td>" . $row['last_name'] . "</td>";
	                echo "<td>" . $row['email'] . "</td>";
	            echo "</tr>";
	        }
	        echo "</table>";
	        // Free result set
	        mysqli_free_result($result);
	    } else{
	        echo "No records matching your query were found.";
	    }
	} else{
	    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link) . '<br>';
	    echo '<pre>';
	    var_dump($link);
	    echo '</pre>';
	    
	}
*/
}

get_profile($link);


//Close Connection
mysqli_close($link);



/*

function get_price($name)
{
	$products = [
		"book"=>20,
		"pen"=>10,
		"pencil"=>5
	];
	
	foreach($products as $product=>$price)
	{
		if($product==$name)
		{
			return $price;
			break;
		}
	}
}
echo '<br><pre>';
var_dump($_GET);
echo '</pre></br>';


if(!empty($_GET['name']))
{
	$name=$_GET['name'];
	$price = get_price($name);
	
	echo $price . 'Hello<br>';
	
	if(empty($price))
	{
		response(200,"Product Not Found",NULL);
	}
	else
	{
		response(200,"Product Found",$price);
	}
	
}
else
{
	//response(400,"Invalid Request",NULL);
}

function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}
*/
<?php

    include_once "facebook.php";
    

	$facebook = new Facebook(array(
  		'appId' => "you app id", //  Update your app id
  		'secret' => "your app seceret", // Update your app secret
  		'cookie' => false, // enable optional cookie support
	));


// This function handles inserts the data to your db
	
	function db_insert($insert_query)
		{
		// Instantiate the mysqli class
		$mysqli = new mysqli();
		

//		Provide your DB credentials here

		// Connect to the database server and select a database
		$mysqli->connect('your db server', 'your db id', 'your password', 'your db name');
	
		//Execute query 

		$result = $mysqli->query($insert_query);

		//commit
		$commit = $mysqli->commit();
	
	    	// Close the connection
    		$mysqli->close();    		
 		
		}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Purple BI: Facebook Data Extraction & Analytics </title>
</head> 
<body> 
	<h2>Facebook Data Extraction</h2> 
	
	<h3>Retrieves and stores friends list</h3> 

<?php

    $loginUrl   = $facebook->getLoginUrl(
            array(
                'scope'         => 'user_about_me',
                'redirect_uri'  => "http://your URL here/get_friends_list.php" // Give absolute path of current file
                
            )
    );
    
    $logoutUrl  = $facebook->getLogoutUrl();

	$user       = $facebook->getUser();
	

    //if user is logged in and session is valid.

	if ($user) {
	
		try {
  		$friends = $facebook->api('/me/friends?limit=100'); // Number limited to 100; You can change this
		$count = count($friends['data']);
		echo $count; echo " friends retrieved"; // Displays number of friends retrieved
		} catch (FacebookApiException $e) {
  		error_log($e);
  		echo "Error in retrieving data";
	}



	//Prepare insert query string	
	$query = "INSERT INTO friends_list(name, uid) VALUES ('Nawendu',10000000),"; // You can insert your name as one of the rows
	
	for ($i = 0; $i < $count; $i++) {
    	$query .= '(' . "'" . $friends[data][$i]['name'] . "'" . ',' . $friends[data][$i]['id'] . ')' ;
    	  	if ( $i !==$count -1 ) $query.= ', '; 
		}

	//Call DB insert function
	db_insert($query);

	echo '<a href="http://nawendubharti.com"><p>Go back to Purple BI</a>'; // You can have your homepage URL here
 

	} else {
  		echo '<a href="' . $loginUrl . '"><p>Login to Facebook to proceed</a>';
	}

?>

</body> 
</html>

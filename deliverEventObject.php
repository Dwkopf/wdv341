
<?php

	try {
	  
	  require "dbconnect.php";	//CONNECT to the database
	  
	  //Create the SQL command string
	  $sql = "SELECT * FROM wdv341_events LIMIT 1";   //get all rows from events table
	  //$sql .= "event_title, ";
 	  
	  //$sql .= "event_setup_date "; //Last column does NOT have a comma after it.
	 // $sql .= "FROM ";
	  
	  //PREPARE the SQL statement
	  $stmt = $conn->prepare($sql);
	  
	  //EXECUTE the prepared statement
	  $stmt->execute();		
	  
	  //Prepared statement result will deliver an associative array
	  $stmt->setFetchMode(PDO::FETCH_ASSOC);
  }
  
  catch(PDOException $e)
  {
	  $message = "There has been a problem. The system administrator has been contacted. Please try again later.";

	  error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
	  error_log($e->getLine());
	  error_log(var_dump(debug_backtrace()));
  
	  //Clean up any variables or connections that have been left hanging by this error.		
	
		//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
  }

  $outputObj = new stdClass();
  while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
	$outputObj->event_id = $row['event_id'];
	$outputObj->event_name = $row['event_name'];// load event name into PHP object
	$outputObj->event_description = $row['event_description']; 		// load description
	$outputObj->event_presenter = $row['event_presenter'];
	$outputObj->event_date = $row['event_date']; 
}

	$returnObj = json_encode($outputObj);	//create the JSON object
	//echo $eventObj;
	echo $returnObj;							//send results back to calling program

?>

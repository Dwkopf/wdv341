<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();
if (!$_SESSION['validUser']) {
	header('Location: login.php');
}

	try {
	  
	  require "dbconnect.php";	//CONNECT to the database
	  
	  //Create the SQL command string
	  $sql = "SELECT * FROM wdv341_events";   //get all rows from events table

	  
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

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Presenting Information Technology</title>

	<link rel="stylesheet" href="css/events.css">

</head>

<body>

<div id="container">

	<header>
    	<h1>Presenting Information Technology</h1>
    </header>
    

    
    <main>
    
        <h1>Available Events</h1>
        
        <?php 
			while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
		?>		
				<div class="eventBlock">
					<div class="row">
						<span class="eventTitle"><?php echo $row['event_id']; ?></span>
					</div>
					<div class="row">
						<span class="eventDescription">Event name:<?php echo $row['event_name']; ?></span>
					</div>               
					<div class="row">
						<span class="eventAddress">Description:<?php echo $row['event_description']; ?></span>
					</div>              
					<div class="row">
                        <div class="col-1-2">
                        	<span class="eventAddress">Presenter: <?php echo $row['event_presenter']; ?></span>
                        </div>
						<div class="col-1-2">
                        	<span class="eventAddress">Date: <?php echo $row['event_date']; ?></span>
                        </div>
                        <div class="col-1-2">
                        	<span class="eventAddress">Time: <?php echo $row['event_time']; ?></span>
                        </div>
					</div>      
                    <a href='updateEvents.php?recId=<?php echo $row['event_id']; ?>'><button>Update Event</button></a>
                   
                    <a href='deleteEvents.php?recId=<?php echo $row['event_id']; ?>'><button>Delete Event</button></a>

				</div><!-- Close Event Block -->
                
        <?php
			}
		?>	
  	
        
	</main>
    <a href="login.php"><button type='button'>Cancel</button></a>
	<footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>




</div>
</body>
</html>




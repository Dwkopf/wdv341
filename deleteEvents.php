<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();

if (!$_SESSION['validUser']) {
	header('Location: login.php');
}

$updateRecID = $_GET['recId'];

if (isset($_POST['deleteEvent']) )	{
    try {
	  
        require "dbconnect.php";	//CONNECT to the database
        
        //Create the SQL command string
        $sql = "DELETE FROM wdv341_events WHERE event_id='$updateRecID'";   //get record from events table
    
        
        //PREPARE the SQL statement
        $stmt = $conn->prepare($sql);
        
        //EXECUTE the prepared statement
        $stmt->execute();		
       $message = "Event deleted";
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
}
else {
try {
	  
    require "dbconnect.php";	//CONNECT to the database
    
    //Create the SQL command string
    $sql = "SELECT * FROM wdv341_events WHERE event_id='$updateRecID'";   //get record from events table

    
    //PREPARE the SQL statement
    $stmt = $conn->prepare($sql);
    
    //EXECUTE the prepared statement
    $stmt->execute();		
    
    //Prepared statement result will deliver an associative array
    $stmt->setFetchMode(PDO::FETCH_ASSOC);

    $row=$stmt->fetch(PDO::FETCH_ASSOC);	 

	$event_id = $row['event_id'];   
	$event_name=$row['event_name'];
	$event_description=$row['event_description'];
	$event_presenter=$row['event_presenter'];
	$event_date=$row['event_date'];
	$event_time=$row['event_time'];		
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

    
    
    <form method="post" name="loginForm" action="deleteEvents.php?recId=<?php if (isset($event_id)) echo $event_id; ?>" >
<?php 
    if (isset($_POST['deleteEvent']) )  {   ?>
        <h2><?php echo $message;?> </h2>
        <p><a href="listEvents.php">Back to Events List</a></p> <?php }

    else { ?>


        <h2>Are you SURE you want to delete this event?</h2>
        <div class="eventBlock">
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
        </div>  


        <input name="deleteEvent" value="Delete Event" type="submit" />
        <a href="login.php"><button type='button'>Cancel</button></a>
    <?php } ?>
    </form>
                   
   
    
</body>



<?php
session_start();
//Only allow a valid user access to this page
if (!$_SESSION['validUser']) {
	header('Location: login.php');
}

$updateRecID = $_GET['recId'];

$event_name = "";
$event_description = "";
$event_presenter = "";
$event_date = "";
$event_time = "";
$error_message = "";
$message = "";
$valid_form = false;

if (isset($_POST["submit"]))  {
	$event_name = $_POST["event_name"];
	$event_description = $_POST["event_description"];
	$event_presenter = $_POST["event_presenter"];
	$event_date = $_POST["event_date"];
	$event_time = $_POST["event_time"];

	//Begin data validation!!!! 
	
    if ($event_name == "") 	
        $error_message .= " Please enter event name";

    if ($event_description == "") 
        $error_message .= " Please enter event description";

    if ($event_presenter == "") 
        $error_message .= " Please enter event presenter";

    if ($event_date == "") 	
        $error_message .= " Please enter event day";

    if ($event_time == "") 
		$error_message .= " Please enter event time";

	if ($error_message == "")
		$valid_form = true;

	if ($valid_form) {	//process the update
		try {
			require 'dbconnect.php';
			$sql = "UPDATE wdv341_events SET ";
			$sql .= "event_name='$event_name',";
			$sql .= "event_description='$event_description',";
			$sql .= "event_presenter='$event_presenter',";
			$sql .= "event_date='$event_date',";
			$sql .= "event_time='$event_time'";
			$sql .= "WHERE event_id='$updateRecID'";
			
			//PREPARE the SQL statement
			$stmt = $conn->prepare($sql);
				
			//EXECUTE the prepared statement
			$stmt->execute();	
				
			$message = "The Event has been Updated.";
		}
		catch(PDOException $e) {
			$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

			error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
			error_log(var_dump(debug_backtrace()));	
		
			header('Location:error_page.php');	//sends control to a User friendly page					
			}

	}
	else	{		// not valid form

	}
}
else {		// not updated yet
	try {
		require 'dbconnect.php';

		$sql = "SELECT * FROM wdv341_events WHERE event_id=$updateRecID";
		 //PREPARE the SQL statement
		 $stmt = $conn->prepare($sql);
		  
		 //EXECUTE the prepared statement
		 $stmt->execute();		
		 
		 //RESULT object contains an associative array
		 $stmt->setFetchMode(PDO::FETCH_ASSOC);	
		 
		 $row=$stmt->fetch(PDO::FETCH_ASSOC);	 
			   
		   $event_name=$row['event_name'];
		   $event_description=$row['event_description'];
		   $event_presenter=$row['event_presenter'];
		   $event_date=$row['event_date'];
		   $event_time=$row['event_time'];			
				
	 }
	 catch(PDOException $e) {
		$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

		error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
		error_log(var_dump(debug_backtrace()));	
	
		header('Location:error_page.php');	//sends control to a User friendly page					
		}
	
}
?>


<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Presenting Information Technology</title>
	<link rel="stylesheet" href="css/events.css"> 
  

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> 
 	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    

	<script>
		$(function() {
			$('#event_date').datepicker({dateFormat: "yy-mm-dd"});	//set datepicker format to yyyy-mm-dd to match database expected format
		} );	
		
	</script>


</head>

<body>

<div id="container">
    
    <main>
    
        
		<?php
            //If the form was submitted and valid and properly put into database display the INSERT result message
			if($valid_form)
			{
				?>
			<h1><?php echo $message ?></h1>
				
				<?php
			}
			else	//display form
			{
        ?>
        <form id="updateEventForm" name="updateEventForm" method="post" action="updateEvents.php?recId=<?php echo $updateRecID; ?>"; ?>
        	<fieldset>
              <legend>Update Event</legend>
              <p>
                <label for="event_title">Event Name: </label>
                <input type="text" name="event_name" id="event_name" value="<?php echo $event_name;  ?>" /> 
                <span class="errMsg"> <?php echo $error_message; ?></span>
              </p>
              <p>
                <label for="event_description">Event Description:</label>
                  <textarea name="event_description" id="event_description" maxlength="700"><?php echo $event_description; ?></textarea>
                <span class="errMsg"><?php echo $error_message; ?></span>                
              </p>
              <p>
                <label for="event_presenter">Presenter: </label>
                <input type="text" name="event_presenter" id="event_presenter" value="<?php echo $event_presenter;  ?>" />
                <span class="errMsg"><?php echo $error_message; ?></span>                      
              </p>
              <p>
                <label for="event_date">Event Day: </label> 
                <input type="text" name="event_date" id="event_date" value="<?php echo $event_date;  ?>"/>
                <span class="errMsg"><?php echo $error_message; ?></span>      
              </p>
              <p>
                <label for="event_time">Event Time: </label> 
                <input type="text" name="event_time" id="event_time" value="<?php echo $event_time;  ?>"/>
                <span class="errMsg"><?php echo $error_message; ?></span>                
              </p>
              <p>
           
                       
              
          </fieldset>
         	<p>
            	<input type="submit" name="submit" id="submit" value="Update" />
            	<a href="login.php"><button type='button'>Cancel</button></a>
        	</p>  
      </form>
        <?php
			}//end else
        ?>    	
	</main>
    
	<footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>



</div>
</body>
</html>

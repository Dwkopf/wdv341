<?php
    $message="";
	$event_name = "";
	$event_description = "";
    $event_presenter = "";
    $event_date = "";
    $event_time = "";
    $event_errMsg = "";
    $validForm = true;		//form validation flag, assume all valid data

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

// submit name-value pair is prod_submit="Submit"

if( isset($_POST['event_submit']) ) {
	//yes it has that name-value pair
	//Start processing the form...
    //echo "<h1>processing form</h1>";
    //  if ($_POST['event_name']!="")
    $event_name =  test_input($_POST['event_name']);
	$event_description =  test_input($_POST['event_description']);
    $event_presenter =  test_input($_POST['event_presenter']);
    $event_date = test_input($_POST['event_day']);
    $event_time =  test_input($_POST['event_time']);
	
    // echo "<p>event_name: $event_name</p>";
	// echo "<p>event_description:$event_description</p>";
    // echo "<p>event_presenter:$event_presenter</p>";
    // echo "<p>event_day:$event_day</p>";
    // echo "<p>event_time:$event_time</p>";
	// echo "<p>event_errMsg:$event_errMsg</p>";
	
	//Begin data validation!!!! 
	
	
    if ($event_name == "") {
        $validForm = false;	
        $event_errMsg .= " Please enter event name";}

    if ($event_description == "") {
        $validForm = false;	
        $event_errMsg .= " Please enter event description";}

    if ($event_presenter == "") {
        $validForm = false;	
        $event_errMsg .= " Please enter event presenter";}

    if ($event_date == "") {
        $validForm = false;	
        $event_errMsg .= " Please enter event day";}

    if ($event_time == "") {
        $validForm = false;	
        $event_errMsg .= " Please enter event time";}
//echo($validForm);
//echo($event_date);
        if($validForm)
		{
			
			try {
				
                require 'dbconnect.php';	//CONNECT to the database
                //Create the SQL command string
                // $sql = "INSERT INTO wdv341_events(event_name,event_description,event_presenter,event_date,event_time)
                //  VALUES ('$event_name','$event_description','$event_presenter','$event_date','$event_time')";
				$sql = "INSERT INTO wdv341_events(event_name,event_description,event_presenter,event_date,event_time)
                 VALUES (:eName,:eDesc,:ePresenter,:eDate,:eTime)";
                //PREPARE the SQL statement
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':eName', $event_name);
                $stmt->bindParam(':eDesc', $event_description);
                $stmt->bindParam(':ePresenter', $event_presenter);
                $stmt->bindParam(':eDate', $event_date);
                $stmt->bindParam(':eTime', $event_time);
                //EXECUTE the prepared statement
                $stmt->execute();	
                $message = "The Event has been registered.";
            }
            catch(PDOException $e)
			{
				$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
	
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				error_log(var_dump(debug_backtrace()));
			
				//Clean up any variables or connections that have been left hanging by this error.		
			
				//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
            }
        }
        //echo($message);}
        //else {
	//no it does not have that name-value pair
	//Display EMPTY form to the customer They need to enter input...
	 //echo "<h1>Empty Form requested!</h1>";
}       
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Self posting form</title>

    <style>
        /* COMPACT CAPTCHA */

        .capbox {
            background-color: #ACEE4F;
            background-image: linear-gradient(#BBBBBB, #9E9E9E);
            border: #2A7D05 0px solid;
            border-width: 2px 2px 2px 20px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            display: inline-block;
            padding: 5px 8px 5px 8px;
            border-radius: 4px 4px 4px 4px;
            }

        .capbox-inner {
            font: bold 12px arial, sans-serif;
            color: #000000;
            background-color: #E3E3E3;
            margin: 0px auto 0px auto;
            padding: 3px 10px 5px 10px;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
            }

        #CaptchaDiv {
            color: #000000;
            font: normal 25px Impact, Charcoal, arial, sans-serif;
            font-style: italic;
            text-align: center;
            vertical-align: middle;
            background-color: #ACEE4F;
            user-select: none;
            display: inline-block;
            padding: 3px 14px 3px 8px;
            margin-right: 4px;
            border-radius: 4px;
            }

        #CaptchaInput {
            border: #ACEE4F 2px solid;
            margin: 3px 0px 1px 0px;
            width: 105px;
            }        /* COMPACT CAPTCHA */
    

        form {
           background-image: url("images/event.jpg");
           border:2px solid brown;
           padding:200px;
           color:#ACEE4F;
           text-align: center;
          
        }
        input {
            background-color:#4D6B23;
            color:#ACEE4F;
        }

        legend  {
            font-size: 2em;
        }
    </style>
</head>
<body>
<form action="eventsForm.php" method ="post"  ><!-- insert onsubmit="return checkform(this);" to activate captcha-->
<?php 
    if ($message == "The Event has been registered.")
        echo("<h2>$message</h2>");

?>    
    <fieldset>
        <legend>Enter Event Information</legend>
    <label for="eName">Event Name:</label>
    <input type="text"  id="eName" name="event_name" value="<?php echo $event_name; ?>"><br><br>
    
    <label for="eDesc">Event Description:</label>
    <input type="text" id="eDesc" name="event_description" value="<?php echo $event_description; ?>"><br><br>

    <label for="ePresenter">Event Presenter:</label>
    <input type="text" id="ePresenter" name="event_presenter" value="<?php echo $event_presenter; ?>"><br><br>

    <label for="eDay">Event Day:</label>
    <input type="text" id="eDay" name="event_day" value="<?php echo $event_date; ?>"><br><br>

    <label for="eTime">Event Time:</label>
    <input type="text" id="eTime" name="event_time" value="<?php echo $event_time; ?>"><br><br>


    <!-- START CAPTCHA -->
<br>
<div class="capbox">

<div id="CaptchaDiv"></div>

<div class="capbox-inner">
Type the number:<br>

<input type="hidden" id="txtCaptcha">
<input type="text" name="CaptchaInput" id="CaptchaInput" size="15"><br>

</div>
</div>
<br><br>
<!-- END CAPTCHA -->


    <input type="submit" value="Submit" id="event_submit" name="event_submit">
    <input type="reset" value="Reset" name="reset" id="button">
</fieldset>
</form>
<script type="text/javascript">

    // Captcha Script

    function checkform(theform){
    var why = "";

    if(theform.CaptchaInput.value == ""){
    why += "- Please Enter CAPTCHA Code.\n";
    }
    if(theform.CaptchaInput.value != ""){
    if(ValidCaptcha(theform.CaptchaInput.value) == false){
    why += "- The CAPTCHA Code Does Not Match.\n";
    }
    }
    if(why != ""){
    alert(why);
    return false;
    }
    }

    var a = Math.ceil(Math.random() * 9)+ '';
    var b = Math.ceil(Math.random() * 9)+ '';
    var c = Math.ceil(Math.random() * 9)+ '';
    var d = Math.ceil(Math.random() * 9)+ '';
    var e = Math.ceil(Math.random() * 9)+ '';

    var code = a + b + c + d + e;
    document.getElementById("txtCaptcha").value = code;
    document.getElementById("CaptchaDiv").innerHTML = code;

    // Validate input against the generated number
    function ValidCaptcha(){
    var str1 = removeSpaces(document.getElementById('txtCaptcha').value);
    var str2 = removeSpaces(document.getElementById('CaptchaInput').value);
    if (str1 == str2){
    return true;
    }else{
    return false;
    }
    }

    // Remove the spaces from the entered and generated code
    function removeSpaces(string){
    return string.split(' ').join('');
    }
</script>



</body>
</html>
<?php

	$prod_name = "";
	$prod_price = "";
	$prod_wagon = "";
	$prod_wagon_errMsg = "";

// submit name-value pair is prod_submit="Submit"

if( isset($_POST['prod_submit']) ) {
	//yes it has that name-value pair
	//Start processing the form...
	echo "<h1>Form has been SUBMITTED!!!!</h1>";
	
	$prod_name = $_POST['prod_name'];
	$prod_price = $_POST['prod_price'];

	if( isset($_POST['prod_wagon']) ){
		$prod_wagon = $_POST['prod_wagon'];		
	}
	else {
		$prod_wagon = "na";		//assign a default if no color is selected
	}
	
	echo "<p>prod_name: $prod_name</p>";
	echo "<p>prod_price: $prod_price</p>";
	echo "<p>prod_wagon: $prod_wagon</p>";
	
	//Begin data validation!!!! 
	
	$validForm = true;		//form validation flag, assume all valid data
	
	//Product color Validation - is required!!!!
	if($prod_wagon == "na") {
		//ERROR!!!!!!!!  Required Field
		$validForm=false;		//bad input field, bad form...
		$prod_wagon_errMsg = "Please select a color";
	}
	else {
		//Okay, continue processing
	}
	
	//end data validation
	
	if($validForm)	{
		//put data into our database using SQL...
		//require 'dbConnect.php'; ...
	}
	else {
		//Invalid form data
		//display form with error messages
		//display form with CONTENT in the fields like they entered
	}
}
else {
	//no it does not have that name-value pair
	//Display EMPTY form to the customer They need to enter input...
	echo "<h1>Empty Form requested!</h1>";
}
	


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>

<h1>WDV341 Intro PHP </h1>
<h2>Unit-8 Self Posting Form</h2>
<h3>Example Form</h3>
<p>Converting a form to a self posting form.</p>
<form name="form1" method="post" action="selfPostExample.php">
  <p>
    <label for="prod_name">Product Name: </label>
    <input type="text" name="prod_name" id="prod_name" value="<?php echo $prod_name; ?>">
  </p>
  <p>
    <label for="prod_price">Product Price: </label>
    <input type="text" name="prod_price" id="prod_price" value="<?php echo $prod_price; ?>">
  </p>
  <p>Product Color: <span><?php echo $prod_wagon_errMsg; ?></span></p>
  <p>
    <input type="radio" name="prod_wagon" id="prod_red" value="prod_red">
    <label for="prod_red">Red Wagon<br>
    </label>
    <input type="radio" name="prod_wagon" id="prod_green" value="prod_green">
    <label for="prod_green">Green Wagon</label>
  </p>
  <p>
    <input type="submit" name="prod_submit" id="prod_submit" value="Submit">
    <input type="reset" name="Reset" id="button" value="Reset">
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
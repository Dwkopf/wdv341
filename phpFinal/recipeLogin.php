<?php 
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();
if (!isset($_SESSION['validUser'])) {
    $_SESSION['validUser'] = false;	
}
 

	$message = "";
	$errMessage = ""; 
 
	if ($_SESSION['validUser'])				//valid user?
	{
		//User is already signed on.  Skip the rest.
		$message = "Welcome Back!";	//Create greeting for VIEW area		
	}
	else
	{
		if (isset($_POST['submitLogin']) )		{	//submitted form?
		//echo"<script>alert('nice try')</script>";
			$inUsername = $_POST['loginUsername'];	//pull the data from the form
            $inPassword = $_POST['loginPassword'];	
            
            
			try {
			  
                require 'recipeDBconnect.php';	//CONNECT to the database dbconnectpw for online
                
                //mysql DATE stores data in a YYYY-MM-DD format
                $todaysDate = date("Y-m-d");		//use today's date as the default input to the date( )
                
                //Create the SQL command string
                  $sql = "SELECT  recipe_username,recipe_password ";
                //$sql .= "event_user_name ";
                // $sql .= "event_user_password";  	  
                                  
                  $sql .= "FROM recipe_user ";
                  $sql .= "WHERE recipe_username = :username  AND recipe_password = :password";
  
  
                //PREPARE the SQL statement
                $stmt = $conn->prepare($sql);
                
                //BIND the values to the input parameters of the prepared statement
                $stmt->bindParam(':username', $inUsername);
                $stmt->bindParam(':password', $inPassword);
                                              
                //EXECUTE the prepared statement
                $stmt->execute();		
                
                //RESULT object contains an associative array
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
            }
            
            catch(PDOException $e)
            {
                $message = "There has been a problem. The system administrator has been contacted. Please try again later.";
          
                error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
                error_log($e->getLine());
                error_log(var_dump(debug_backtrace()));
            
                //Clean up any variables or connections that have been left hanging by this error.		
            
                header('Location: error_page.php');	//sends control to a User friendly page					
            }
            
                $row = $stmt->fetch();
              
              
              if ($row['recipe_username'] === $inUsername)
              {
                  //echo "<h1>VALID USER!!!</h1>";
                  $_SESSION['validUser'] = true;				//this is a valid user so set your SESSION variable
                  $message = "Welcome Back! $inUsername";					
              }
              else
              {
                  //error in processing login.  Logon Not Found...
                  $_SESSION['validUser'] = false;					
                  $errMessage = "Sorry, there was a problem with your username or password. Please try again.";					
              }
            
  		
          }//end if submitted
          else
          {
              //user needs to see form
          }//end else submitted
          
      }//end else valid user
      
  
  ?>
  <!DOCTYPE html>
  <html>
  <head>
  <link type="text/css" rel="stylesheet" href="recipe.css">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WDV341 Intro PHP - Login and Control Page</title>
  <style>

  </style>
  </head>

<body>



<?php

	if ( !empty($message) )
	{
		echo "<h2>$message</h2>";	
	}
	else
	{
		echo "<p class='errMsg'>$errMessage</p>";	
	}
	
?>
<?php
	if ($_SESSION['validUser'])	//This is a valid user.  Show them the Administrator Page
	{
		
//turn off PHP and turn on HTML
?>
    <div id="admin">
		<h3>Recipe Project Administrator Options:</h3>
        <p><a href="recipeInsert.php">Create a New Recipe</a></p>
        <p><a href="listRecipes.php">Modify/Delete a Current Recipe</a></p>
        <p><a href="recipeLogout.php">Logout of Recipe Admin System</a></p>	
    </div>			
<?php
	}
	else									//The user needs to log in.  Display the Login Form
	{
?>
			
                <form method="post" name="recipeLoginForm" action="recipeLogin.php" >
               
                <h1>Recipe Project Admin System</h1>
                <h2>Please login to the Recipe Administrator System</h2>
                  <p>Username: <input name="loginUsername" type="text" /></p>
                  <p>Password: <input name="loginPassword" type="password" /></p>

                  <p><input name="submitLogin" value="Login" type="submit" /> <input name="" type="reset" />&nbsp;</p>
                </form>
                
<?php //turn off HTML and turn on PHP
	}//end of checking for a valid user
			

?>

</body>
</html>
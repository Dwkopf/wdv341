<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php

try {
  
  require "recipeDBconnect.php";	//CONNECT to the database
  
  //Create the SQL command string
  $sql = "SELECT * FROM recipe ORDER BY rName DESC"; 

  //PREPARE the SQL statement
  $stmt = $conn->prepare($sql);
  
  //EXECUTE the prepared statement
  $stmt->execute();		
  //Prepared statement result will deliver an associative array
     $stmt->setFetchMode(PDO::FETCH_ASSOC);
    // $result =$stmt->fetchAll(PDO::FETCH_COLUMN, 'product_name');
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


</head>
<body>

    <?php 
        while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
    ?>		
        <h1>recipe</h1>
            <p><?php echo $row['rName']; ?></p>
            <p><?php echo $row['category']; ?></p>
            <?php
		}
		    ?>	
        
</body>
</html>
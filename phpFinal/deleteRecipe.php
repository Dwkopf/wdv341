<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();

if (!$_SESSION['validUser']) {
	header('Location: recipeIndex.php');
}

$deleteRecID = $_GET['recId'];

if (isset($_POST['deleteRecipe']) )	{       // if set delete
    try {
	  
        require "recipeDBconnect.php";	//CONNECT to the database
        
        //Create the SQL command string
        $sql = "DELETE FROM recipe WHERE recipe_id = :rID";   //get record from events table
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':rID', $deleteRecID);
        
        //PREPARE the SQL statement
      
        
        //EXECUTE the prepared statement
        $stmt->execute();		
       $message = "Recipe deleted";
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
else {      // not set yet, display recipe
    try {
		require 'recipeDBconnect.php';

		$sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients  FROM recipe WHERE recipe_id=$deleteRecID";
		 //PREPARE the SQL statement
		 $stmt = $conn->prepare($sql);
		  
		 //EXECUTE the prepared statement
		 $stmt->execute();		
		 
		 //RESULT object contains an associative array
		 $stmt->setFetchMode(PDO::FETCH_ASSOC);	
		 
		 $row=$stmt->fetch(PDO::FETCH_ASSOC);	 
			   
         $rName = $row['rName'];
         $recipeImage=$row['recipeImage'];
         $category = $row['category'];
         $serves = $row['serves'];
         $prepTime = $row['prepTime'];
         $cookTime = $row['cookTime'];
        //  $ingredients = $row['ingredients'];
        //  $instructions = $row['instructions'];  // string

         $ingredients = explode("*",$row['ingredients']);   //an array now
         $ingredients = json_encode($ingredients);
         $instructions = explode("*",$row['instructions']);
         $instructions = json_encode($instructions);
         //echo"<script>alert('$instructions')</script>";
	 }
	 catch(PDOException $e) {
		$message = "There has been a problem. The system administrator has been contacted. Please try again later.";

		error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
		error_log(var_dump(debug_backtrace()));	
	
		header('Location:error_page.php');	//sends control to a User friendly page					
		}
	
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Recipe Project</title>
<meta name="author" content="David Kopf">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--Author: David Kopf 
	  Date: October 18, 2020-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="recipes.js"></script> 
<script src="recipesLoaded.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


<link type="text/css" rel="stylesheet" href="recipe.css">
<link type="text/css" rel="stylesheet" href="recipe.scss">
</head>

<body>

    
    
    <form method="post" name="deleteForm" action="deleteRecipe.php?recId=<?php echo $deleteRecID; ?>" >
<?php 
    if (isset($_POST['deleteRecipe']) )  {   ?>
        <h2 ><?php echo $message;?> </h2>
        <p><a href="listRecipes.php" id="deleted">Back to Recipe List</a></p> <?php }

    else { ?>
        <script>
            let recipeToDelete = new Recipe();
            recipeToDelete.ingredients = '<?php echo $ingredients ?>';  // set ingredients/instruction lists
            recipeToDelete.instructions = '<?php echo $instructions ?>'; 
            recipeToDelete.ingredients = JSON.parse(recipeToDelete.ingredients);
            recipeToDelete.instructions = JSON.parse(recipeToDelete.instructions);
            recipeToDelete.name = '<?php echo $rName; ?>';
            recipeToDelete.image = '<?php echo $recipeImage; ?>';
            recipeToDelete.category = '<?php echo $category; ?>';
            recipeToDelete.serves = '<?php echo $serves; ?>';
            recipeToDelete.preparationTime = '<?php echo $prepTime; ?>';
            recipeToDelete.cookTime = '<?php echo $cookTime; ?>';
    
</script>  

        <h2>Are you SURE you want to delete this recipe?</h2>
        <h2>You cannot undo this!</h2>
 <!-- detail structure for displayFullRecipe -->
 <div id="details" class="showRecipe"> 
        <h1>Recipe Name: </h1>
        <img class="">
        <h3>Servings</h3>
        <h3>Preparation Time</h3>
        <h3>Cook Time</h3>
        

    </div>


        <input name="deleteRecipe" value="Delete Recipe" type="submit" />
        <a href="recipeLogin.php"><button type='button'>Cancel</button></a>
        <script>displayFullRecipe(recipeToDelete);</script>
    <?php } ?>
    </form>
    
   
    
</body>
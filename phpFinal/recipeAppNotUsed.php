<?php
try {
  
  require "recipeDBconnect.php";	//CONNECT to the database
  
  //Create the SQL command string
  $sql = "SELECT * FROM recipe WHERE category='Appetizer' ORDER BY RAND() LIMIT 3"; 

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



<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Recipe Project</title>
<meta name="author" content="David Kopf">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--Author: David Kopf 
	  Date: October 18, 2020-->

<script src="recipes.js"></script> 
<script src="recipesLoaded.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<link type="text/css" rel="stylesheet" href="recipe.css">
<link type="text/css" rel="stylesheet" href="recipe.scss">
<script>
    $(document).ready(function() {
        
        document.querySelector("#addRecipe").addEventListener("click",addARecipe);
        document.querySelector("#viewRecipes").addEventListener("change",displayRecipe);
        document.querySelector("#search").addEventListener("click",searchRecipe);
       })
</script>   


</head>

<body>  
    <h1>Recipes R Us</h1>  
    <h2><a href="recipeStore.html" alt="recipe shop">Shop Our Store!</a></h2>
    <h2>Creating extraordinary memories with unique meals</h2>
    <div id ="home">
        <input type ="text" name="search" id="searchHere" label="recipe search" placeholder="Search recipes">
        <input type="button"  id = "search" value="Search"><span></span>     <!--  search bar -->
        <p id = "pickRecipes">
            <label for="recipeType">What are you looking for?:</label>
            <select name= "recipeType" id="viewRecipes">         <!--recipe category selection  -->
              <option value="Magic">Magic</option>
              <option value="Drink">Drinks</option>
              <option value="Appetizer">Appetizer</option>
              <option value="Entree">Entree</option>
              <option value="Dessert">Dessert</option>
              <option value="myRecipes" id="myRecipe">My Recipes</option>
              
            </select>
          </p>
       
    </div>

    <div id="disRecipes">    <!-- structure for displaying 3 choices -->
    <?php 
        while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
    ?>		
        <div id="rec1" class="gridItem">
            <h3><?php echo $row['rName'] ?></h3>
            <img src = "<?php echo $row['image'] ?>">
            <p><button>View</button></p>
        </div>
        
        <?php } ?></div> 
    </div>
<!-- detail structure for displayFullRecipe -->
    <!-- <div id="details" class="container1"> 
        <h1>Recipe Name: </h1>
        <img class="">
        <h3>Servings</h3>
        <h3>Preparation Time</h3>
        <h3>Cook Time</h3>
        

    </div> -->

            
    <p><button id="addRecipe">Send us your recipe!</button></p>

</body>
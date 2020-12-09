<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();
if (!$_SESSION['validUser']) {
	header('Location: recipeIndex.php');
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $recipe_type="Magic";   // default

  if (isset($_POST['search'])) {      // are they searcbing for a recipe?
    //echo"<script>alert('Hello')</script>";
   $key_word = test_input($_POST['search']);
   if ($key_word != "")  {
       try {
 
           require "recipeDBconnect.php";	//CONNECT to the database
           
           //Create the SQL command string
           $sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe WHERE rName LIKE '%$key_word%'ORDER BY RAND() LIMIT 3"; 
         
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
   }
}   // end search
else {
   if  (isset($_POST['recipeType']))   // show recipes of type 'recipeType
       $recipe_type = $_POST['recipeType'];

   try {
   
       require "recipeDBconnect.php";	//CONNECT to the database
       if ($recipe_type == "Magic")
           //Create the SQL command string
           $sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe ORDER BY RAND() LIMIT 3"; 
       else 
           $sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe WHERE category='$recipe_type' ORDER BY RAND() LIMIT 3"; 

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

<h2>Recipe Project Admin Area</h2>
    <div id ="home">
        <form action="listRecipes.php" method ="post" >  
            <input type ="text" name="search" id="searchHere" label="recipe search" placeholder="Search recipes">
            <input type="submit"  id = "search" value="Search"><span></span>     <!--  search bar -->
        </form>

        <p id = "pickRecipes">
            <form action="listRecipes.php" method ="post" >                
                <label for="recipeType">What are you looking for?:</label>
                    <select name= "recipeType" id="viewRecipes" onchange="this.form.submit()">         <!--recipe category selection  -->
                    <option value="Magic"<?php if($recipe_type=="Magic") echo "selected" ?>>Magic</option>
                    <option value="Drink" <?php if($recipe_type=="Drink") echo "selected" ?>>Drinks</option>
                    <option value="Appetizer" <?php if($recipe_type=="Appetizer") echo "selected" ?>>Appetizer</option>
                    <option value="Entree" <?php if($recipe_type=="Entree") echo "selected" ?>>Entree</option>
                    <option value="Dessert" <?php if($recipe_type=="Dessert") echo "selected" ?>>Dessert</option>
                    <option value="myRecipes" id="myRecipe" <?php if($recipe_type=="myRecipes") echo "selected" ?>>My Recipes</option>
                    </select>
            </form>
          </p>
       
    </div>

    <div id="disRecipes">    <!-- structure for displaying 3 choices -->
    <?php 
        $index=0;
        
        while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
            $ing = explode("*",$row['ingredients']);
            $ins = explode("*",$row['instructions']);

            //echo $ing[1];
            $index = $index +1;
        ?>		
        <script>
            let recipeDisplayed<?php echo $index ?> = new Recipe();
            recipeDisplayed<?php echo $index ?>.name = "<?php echo $row['rName'] ?>";
            recipeDisplayed<?php echo $index ?>.image = "<?php echo $row['recipeImage'] ?>";
            recipeDisplayed<?php echo $index ?>.category = "<?php echo $row['category'] ?>";
            recipeDisplayed<?php echo $index ?>.serves = "<?php echo $row['serves'] ?>";
            recipeDisplayed<?php echo $index ?>.preparationTime = "<?php echo $row['prepTime'] ?>";
            recipeDisplayed<?php echo $index ?>.cookTime = "<?php echo $row['cookTime'] ?>";
            recipeDisplayed<?php echo $index ?>.ingredients = <?php echo json_encode($ing) ?>;
            recipeDisplayed<?php echo $index ?>.instructions = <?php echo json_encode($ins) ?>;
           
            
        </script>
        <div id="rec<?php echo $index ?>" class="gridItem">
            <h3><?php echo $row['rName'] ?></h3>
            <img src = "<?php echo $row['recipeImage'] ?>" alt ='recipe imge' >
            <p>
                <a href='modifyRecipe.php?recId=<?php echo $row['recipe_id'] ?>'><button>Update Recipe</button></a>
                    
                <a href='deleteRecipe.php?recId=<?php echo $row['recipe_id']; ?>'><button>Delete Recipe</button></a>
            </p>
        </div>
        
        <?php } ?></div> 
    </div>

        

    <a href="recipeLogin.php"><button type='button'>Cancel</button></a>
	<footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>




</div>
</body>
</html>
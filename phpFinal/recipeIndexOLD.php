
<?php
$recipe_type="Magic";   // default
if (isset($_POST['search'])) {
    try {
        require "recipeDBconnect.php";
        $search = $_POST['search'];
        $sql = "SELECT * FROM recipe WHERE rName LIKE %$search% ORDER BY RAND() LIMIT 3";
        $stmt = $conn->prepare($sql);
    
        //EXECUTE the prepared statement
        $stmt->execute();		
        //Prepared statement result will deliver an associative array
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        //echo "$stmt";
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
else {
    if  (isset($_POST['recipeType']))   
        $recipe_type = $_POST['recipeType'];

    try {
    
        require "recipeDBconnect.php";	//CONNECT to the database
        if ($recipe_type == "Magic")
            //Create the SQL command string
            $sql = "SELECT * FROM recipe ORDER BY RAND() LIMIT 3"; 
        else 
            $sql = "SELECT * FROM recipe WHERE category='$recipe_type' ORDER BY RAND() LIMIT 3"; 

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
    }}

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
        
        document.querySelector("#addIng").addEventListener("click",addIngredient);
        document.querySelector("#addRecipe").addEventListener("click",addARecipe);
        document.querySelector("#addStep").addEventListener("click",addAStep); 
        //document.querySelector("#viewRecipes").addEventListener("change",displayRecipe);
        document.querySelector("#checkImg").addEventListener("click",checkImage);
        //document.querySelector("#search").addEventListener("click",searchRecipe);
        document.querySelector("#quit").addEventListener("click",addARecipe);
        document.querySelector("#deleteIng").addEventListener("click",removeIng);
        document.querySelector("#deleteStep").addEventListener("click",removeStep);

       })
</script>   


</head>

<body>  
    <h1>The Recipe Project</h1>  
    <h2><a href="recipeStore.html" alt="recipe shop">Shop Our Store!</a></h2>
    <h2>Creating extraordinary memories with unique meals</h2>
    <div id ="home">
    <form action="recipeIndex.php" method ="post" >  
        <input type ="text" name="search" id="searchHere" label="recipe search" placeholder="Search recipes">
        <input type="submit"  id = "search" value="Search"><span></span>     <!--  search bar -->
    </form>
        <p id = "pickRecipes">
        <form action="recipeIndex.php" method ="post" >                
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
            recipeDisplayed<?php echo $index ?>.image = "<?php echo $row['image'] ?>";
            recipeDisplayed<?php echo $index ?>.category = "<?php echo $row['category'] ?>";
            recipeDisplayed<?php echo $index ?>.serves = "<?php echo $row['serves'] ?>";
            recipeDisplayed<?php echo $index ?>.preparationTime = "<?php echo $row['prepTIme'] ?>";
            recipeDisplayed<?php echo $index ?>.cookTime = "<?php echo $row['cookTime'] ?>";
            recipeDisplayed<?php echo $index ?>.ingredients = <?php echo json_encode($ing) ?>;
            recipeDisplayed<?php echo $index ?>.instructions = <?php echo json_encode($ins) ?>;
            
            
        </script>
        <div id="rec<?php echo $index ?>" class="gridItem">
            <h3><?php echo $row['rName'] ?></h3>
            <img src = "<?php echo $row['image'] ?>" alt ='recipe imge' >
            <p><button onclick="displayRecipe<?php echo $index ?>()">View</button></p>
        </div>
        
        <?php } ?></div> 
    </div>





<!-- detail structure for displayFullRecipe -->
    <div id="details" class="container1"> 
        <h1>Recipe Name: </h1>
        <img class="">
        <h3>Servings</h3>
        <h3>Preparation Time</h3>
        <h3>Cook Time</h3>
        

    </div>
    


            
 
<!-- overlay form for submitting recipe-->

<p><button id="addRecipe">Send us your recipe!</button></p>
    <!-- form for submitting recipe-->
    <form action="processRecipe.php" method="post" id="recipeForm">
        <h2>Submit your recipe:</h2>
        <p>Recipe name: <input type ="text" name="recipeName" id="recipeName" ></p>

        <label>Would you like to upload an image of your recipe?
            <select name="recipeImage" id="recipeImage" onChange="addImageSelector()">
                <option value="s">Select One</option>
                <option value="y">Yes</option>
                <option value="n">No</option>>
            </select>
        </label>
        
        <div id="getImage"><!-- upload images-->
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload" label="Image">
            <input type="button" id="checkImg" value = "Check Image" name = "checkImg">
        </div><span id = "imagePreview"></span>

        <!-- Category-->
        <p id = "pickCategory">
            <label for="category">Choose a category:</label>
            <select name= "category" id="category">
              <option value="Select">Select one</option>
              <option value="Appetizer">Appetizer</option>
              <option value="Dessert">Dessert</option>
              <option value="Entree">Entree</option>
              <option value="Drink">Drink</option>
            </select>
          </p>

        <!-- serving size-->
        <p>How many servings? 
            <input type ="text" name="servings" id="servings" placeholder="Enter serving size" label="Serving Size">
        </p>
        <!-- prep time-->
        <p>Preparation time: 
            <input type ="text" name="prepTime" id="prepTime" placeholder="Enter preparation time." label="Preparation Time">
        </p>
             <!-- Cook time -->
            <p>Cook time: 
                <input type ="text" name="cookTime" id="cookTime" placeholder="Enter cook time" label="Cook Time">
            </p>

        <!-- Ingredients-->
        <p>Ingredient list (amount and type): </p>
        <input type="hidden" id="ingredientsValue" name="ingredientsValue" value="">
        <span id="ingredientList"></span>  <!-- ingredient list posted here -->
        <p>First Ingredient:<input type ="text" name="ingredient" id="ingredient" placeholder="Ingredients" label="Ingredients"></p>
        <button id="addIng">Add this ingredient</button>

        <p><select name="removeIngItem" id="removeIngItem">
        <option>Choose ingredient</option></select></p>
        <button id="deleteIng">Remove this ingredient</button>

        <!-- Instructions -->
        <p>Instructions (step by step): </p>
        <input type="hidden" id="instructionsValue" name="instructionsValue" value="">
        <span id="instructionList"></span>  <!-- instruction list posted here -->
        <p>First step: <input type ="text" name="step" id="step" label="Instructions" placeholder="Instructions"></p>
        <button id="addStep">Add this step</button>

        <p><select name="removeItems" id="removeItems">
        <option>Choose step</option></select></p>
        <button id="deleteStep">Remove this step</button>

        <!-- submt/reset buttons -->
        <p>
            <input type="submit" name="submit" onclick="submitRecipe()" />
            <input type="reset" name="resetButton"/>
            <button name="cancel" id="quit" label ="quit recipe submit">Quit</button>
        </p>
        <p><span id="errorMsg"></span></p>
    </form>




</body>
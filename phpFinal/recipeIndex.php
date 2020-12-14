
<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();


function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
$validForm = true;
$message ="";
$recipe_type="Magic";   // default

if (isset($_POST['recipeSubmit']) && $_POST['name']=="")    {
    //$name=$_POST['name'];
    //echo "<script>alert($name)</script>";
    
    if ($_POST['recipeImage']!='y')
        $recipeImage = "images/notAvailable.jpg";
    else  {
        $recipeImage="images/".basename($_FILES["fileToUpload"]["name"]); 
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if $uploadOk is set to 0 by an error
        
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $message .="The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";}
        else {
                $message .= "Sorry, there was an error uploading your file.";
                }
    }
    //echo"<script>alert('$recipeImage')</script>";
    // basic data validation
    $recipe_name = test_input($_POST['recipeName']);
    $category = test_input($_POST['category']);
    $serves = test_input($_POST['servings']);
    $prepTime = test_input($_POST['prepTime']);
    $cookTime = test_input($_POST['cookTime']);
    $ingredients = test_input($_POST['ingredientArray']);
    $instructions = test_input($_POST['instructionArray']);

    $ingString = explode(",",$ingredients);   
    $ingString = implode("*",$ingString);

    $instString = explode(",",$instructions); 
    $instString = implode("*",$instString);

    if ($recipe_name == "") {
        $validForm = false;
        $message .= "Please enter a recipe name. ";
    }
    if ($category == "Select") {
        $validForm = false;
        $message .= "Please select a category. ";
    }
    if ($serves == "") {            // needs working int check
        $validForm = false;
        $message .= "Please enter the recipe serving size. ";}
        
    if ($prepTime == "") {
        $validForm = false;
        $message .= "Please enter the recipe preparation time. ";
    }
    if ($cookTime == "") {
        $validForm = false;
        $message .= "Please enter the recipe cooking time. ";
    }
    if ($ingString == "") {
        $validForm = false;
        $message .= "Please enter the recipe ingredients. ";
    }
    if ($instString == "") {
        $validForm = false;
        $message .= "Please enter the recipe instructions. ";
    }

    if($validForm)      {
        try {
            require "recipeDBconnect.php";	//CONNECT to the database

            $sql = "INSERT INTO recipe(rName,recipeImage,category,serves,prepTime,cookTime,ingredients,instructions)
                VALUES (:rName,:rImage,:rCategory,:rServes,:rPrepTime,:rCookTime,:rIngredients,:rInstructions)";
        
            //$sql = "INSERT INTO recipe (rName) VALUES (:rName)";
        
            $stmt = $conn->prepare($sql);
        
            $stmt->bindParam(':rName', $recipe_name);
            $stmt->bindParam(':rImage', $recipeImage);
            $stmt->bindParam(':rCategory', $category);
            $stmt->bindParam(':rServes', $serves);
            $stmt->bindParam(':rPrepTime', $prepTime);
            $stmt->bindParam(':rCookTime', $cookTime);
            $stmt->bindParam(':rIngredients', $ingString);
            $stmt->bindParam(':rInstructions', $instString);
        
            $stmt->execute();	
            $message = "The recipe has been saved.";
            
            //echo"<script>alert('$message')</script>";
        }
        
        catch (PDOException $e) {
            echo "Problems inserting...";
        
            error_log($e->getMessage() );
            error_log(var_dump(debug_backtrace() ) );
        }
    }// validForm yes

    else {              // validForm no
        if ($ingString != "") {
            $ingString = explode("*",$ingString);    
            $ingString = json_encode($ingString);};
        if($instString != "")   {
            $instString = explode("*",$instString);
            $instString = json_encode($instString);};
    }   
       
}// end check for recipe submit 

if (isset($_POST['search'])) {      // are they searcbing for a recipe?
     //echo"<script>alert('Hello')</script>";
    $key_word = test_input($_POST['search']);
    if ($key_word != "")  {
        try {
  
            require "recipeDBconnect.php";	//CONNECT to the database
            
            //Create the SQL command string
            $sql = "SELECT rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe WHERE rName LIKE '%$key_word%'ORDER BY RAND() LIMIT 3"; 
          
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
            $sql = "SELECT rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe ORDER BY RAND() LIMIT 3"; 
        else 
            $sql = "SELECT rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe WHERE category='$recipe_type' ORDER BY RAND() LIMIT 3"; 

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
<script>
    $(document).ready(function() {

        //document.querySelector("#addIng").addEventListener("click",addIngredient);
        //document.querySelector("#addRecipe").addEventListener("click",addARecipe);
        //document.querySelector("#addStep").addEventListener("click",addAStep); 
        //document.querySelector("#viewRecipes").addEventListener("change",displayRecipe);
        document.querySelector("#checkImg").addEventListener("click",checkImage);
        //document.querySelector("#search").addEventListener("click",searchRecipe);
        //document.querySelector("#quit").addEventListener("click",addARecipe);
        //document.querySelector("#deleteIng").addEventListener("click",removeIng);
        //document.querySelector("#deleteStep").addEventListener("click",removeStep);

       })
</script>   


</head>

<body>  
 
<!-- navigation -->
<nav class="navbar navbar-expand-md bg-custom navbar-dark">
    <div class="container-fluid">
      <div>
        <a class="navbar-brand mr-3 glow" id="nsf" href="recipeStore.html"><h1>The Recipe Project</h1></a>
        <p>Make dinner amazing.</p>
      </div>
        
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav" id="myNav">
                <a href="recipeIndex.php" class="nav-item nav-link glow">Home</a>
                <a href="recipeAbout.html"class="nav-item nav-link glow" >About</a>
                <a href="recipeStore.html"class="nav-item nav-link glow" >Shop</a>
                <a href="recipeContact.php" class="nav-item nav-link glow">Contact</a>
                <a href="recipeLogin.php" class="nav-item nav-link glow">Admin</a>
            </div>
        </div>
        <img src="images/recipeSLogo.gif" class="glow responsive" alt="Recipe Project logo">
    </div>
  </nav>

    <p><span><?php if ($validForm == true)   echo $message;?></span></p>

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
    <form  action="recipeIndex.php" method="post" name='theRecipe'id="recipeForm" enctype="multipart/form-data">
        <h2>Submit your recipe:</h2>
        <p>Recipe name: <input type ="text"  name="recipeName" id="recipeName" value="<?php if($validForm==false) echo $recipe_name; ?>" onkeypress="return noenter()"></p>

        <label>Would you like to upload an image of your recipe?
            <select name="recipeImage" id="recipeImage" onChange="addImageSelector()">
                <option value="s">Select One</option>
                <option value="y" >Yes</option>
                <option value="n">No</option>
            </select>
        </label>
        
        <div id="getImage"><!-- upload images-->
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload" label="Image file" onkeypress="return noenter()">
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
            <input type ="text" value="<?php if($validForm==false) echo $serves; ?>" name="servings" id="servings"  placeholder="Enter serving size" label="Serving Size" onkeypress="return noenter()">
        </p>
        <!-- prep time-->
        <p>Preparation time: 
            <input type ="text" name="prepTime" id="prepTime" value="<?php if($validForm==false) echo $prepTime; ?>" placeholder="Enter preparation time." label="Preparation Time" onkeypress="return noenter()">
        </p>
             <!-- Cook time -->
            <p>Cook time: 
                <input type ="text" name="cookTime" id="cookTime" value="<?php if($validForm==false) echo $cookTime; ?>" placeholder="Enter cook time" label="Cook Time" onkeypress="return noenter()">
            </p>

        <!-- Ingredients-->
        <p>Ingredient list (amount and type): </p>
        <input type="hidden" name="ingredientArray" value="" label = "ingredientArray" id="ingredientArray">
        <span id="ingredientList"></span>  <!-- ingredient list posted here -->
        <p>First Ingredient:<input type ="text" name="ingredient" id="ingredient" placeholder="Ingredients" label="Ingredients" onkeypress="return noenter()"></p>
        <button id="addIng" type="button" onclick="addIngredient(newRecipe)">Add this ingredient</button>

        <p><select name="removeIngItem" id="removeIngItem">
        <option>Choose ingredient</option></select></p>
        <button id="deleteIng" onclick="removeIng(newRecipe)" type="button"> Remove this ingredient</button>

        <!-- Instructions -->
        <p>Instructions (step by step): </p>
        <input type="hidden" name="instructionArray" value="" label = "instructionArray" id="instructionArray">
        <span id="instructionList"></span>  <!-- instruction list posted here -->
        <p>First step: <input type ="text" name="step" id="step" label="Instructions" placeholder="Instructions" onkeypress="return noenter()"></p>
        <button id="addStep" type="button"onclick=" addAStep(newRecipe)">Add this step</button>

        <p><select name="removeItems" id="removeItems">
        <option>Choose step</option></select></p>
        <button id="deleteStep" onclick="removeStep(newRecipe)" type="button">Remove this step</button>

        <input type="text" class="honeypot" name="name" placeholder="Leave Blank If Human" autocomplete="off">
        
        <!-- submt/reset buttons -->
        <p>
            <input type="submit" name="recipeSubmit">
            <input type="reset" name="resetButton"/>
            <button name="cancel" id="quit" label ="quit recipe submit" type="button">Quit</button>
        </p>
        <p><span id="errorMsg"></span></p>
    </form>
            

    <?php 

    if (isset($_SESSION['validUser'])) 
        if ($_SESSION['validUser'] && !isset($_POST['recipeSubmit']))
            echo "<script>addARecipe();</script>";
    
    if (($validForm == false) && isset($_POST['recipeSubmit']) )    {
    ?>
        <script>
            newRecipe.ingredients = '<?php echo $ingString ?>';  
            newRecipe.instructions = '<?php echo $instString ?>'; 
            newRecipe.ingredients = JSON.parse(newRecipe.ingredients);
            newRecipe.instructions = JSON.parse(newRecipe.instructions);
            
            numOfIng = newRecipe.ingredients.length;
            numSteps = newRecipe.instructions.length;

            let ingSoFar="";        // build display of ingredients
            for (i=1;i<=numOfIng;i++) {
                ingSoFar+=`${i} : ${newRecipe.ingredients[i-1]} <br>`;  // backtick form
            }
            document.querySelector("#ingredientList").innerHTML=ingSoFar; // list the ingredients so far
            document.querySelector("#ingredientArray").value = newRecipe.ingredients;

            let instructionSet = "";
            for (i=1;i<=numSteps;i++) {         
                instructionSet += i + ": " + newRecipe.instructions[i-1] + "<br>"; // build the display
            }
            document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far
            document.querySelector("#instructionArray").value = newRecipe.instructions;
        
        </script>
        <?php
        echo "<script>document.querySelector('#errorMsg').innerHTML = '$message';</script>";
       
        
     } ?>

<footer class="page-footer font-small pt-4">
        <!-- Footer Links -->
        <div class="container-fluid text-center text-md-left">
          <!-- Grid row -->
          <div class="row">
            <!-- Grid column -->
            <div class="col-md-4 mt-md-0 mt-3">
              <!-- Content -->
              <h5 class="text-uppercase glow">The Recipe Project</h5>
              <p>123 Main St.</p>
              <p>Chicago, Il 65021</p> 
            </div>
            <!-- Grid column -->
            <!-- Grid column -->
            <div class="col-md-4 mb-md-0 mb-3">
              <!-- Links -->
              <p>Ph: (111)-697-9988</p>
              <p>Email:info@TheRecipeProject.com</p>
            </div>
            <!-- Grid column -->
      
            <!-- Grid column -->
            <div class="col-md-4 mb-md-0 mb-3">
            <p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());
                <img src="images/facebook.png" class="glow" alt="facebook icon">
                <img src="images/twitter.png"class="glow"  alt="twitter icon">
                <img src="images/recipeSLogo.gif" class="glow" alt="recipe project logo">
            </div>
            <!-- Grid column -->
          </div>
          <!-- Grid row -->
        </div>
        <div>
        </div>
      </footer>
      <!-- Footer -->
</body>
</html>
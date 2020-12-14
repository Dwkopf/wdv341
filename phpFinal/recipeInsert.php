
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
            $ingString = json_encode($ingString);
        };
        if($instString != "")   {
            $instString = explode("*",$instString);
            $instString = json_encode($instString);
        };


           
    }   
       
}// end check for recipe submit  


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
        let newRecipe = new Recipe();
        let numOfIng = 0;
        let numSteps = 0;

    

        <?php 

    
    if (($validForm == false) )    {
    ?>
        newRecipe.ingredients = '<?php echo $ingString ?>';  
        newRecipe.instructions = '<?php echo $instString ?>';
        if (newRecipe.ingredients !="")   { 
            newRecipe.ingredients = JSON.parse(newRecipe.ingredients);
            numOfIng = newRecipe.ingredients.length;}
        if (newRecipe.instructions !="")  {  
            newRecipe.instructions = JSON.parse(newRecipe.instructions);
            numSteps = newRecipe.instructions.length;}

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
        
        <?php
        echo "document.querySelector('#errorMsg').innerHTML = '$message'";
       
        
     }; ?>
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

<p><span><?php if ($validForm == true)   echo $message;?></span></p>


    <!-- form for submitting recipe-->
    <form  action="recipeInsert.php" method="post" name='theRecipe'id="recipeForm" enctype="multipart/form-data">
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
            <a href="recipeLogin.php"><button name="cancel" id="quit" label ="quit recipe submit" type="button">Quit</button></a>
        </p>
        <p><span id="errorMsg"></span></p>
    </form>
            
    
</body>
</html>
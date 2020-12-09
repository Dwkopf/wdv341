<?php
session_cache_limiter('none');  //This prevents a Chrome error when using the back button to return to this page.
session_start();
if (!$_SESSION['validUser']) {
	header('Location: recipeIndex.php');
}

//echo"<script>alert('valid user...I guess')</script>";
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

$updateRecID = $_GET['recId'];

$rName = "";
$recipeImage = "";
$category = "";
$serves = "";
$prepTime = "";
$cookTime = "";
$ingredients = "";
$instructions = "";
$error_message = "";
$message = "";


if (isset($_POST["recipeUpdate"]) && $_POST['name']=="")  {
   
    if ($_POST['recipeImage']=='y') // set new image
    {
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
    else {      //don't replace image 
        $recipeImage="images/notAvailable.jpg";
    }
    // basic data validation
    $rName = test_input($_POST['recipeName']);
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


  
        try {
            require "recipeDBconnect.php";	//CONNECT to the database

            $sql = "UPDATE recipe SET rName=:rName,recipeImage=:rImage,category=:rCategory,serves=:rServes,prepTime=:rPrepTime,cookTime=:rCookTime,ingredients= :rIngredients,instructions=:rInstructions WHERE recipe_id=:updateID";
            
            
            //$sql = "INSERT INTO recipe (rName) VALUES (:rName)";
        
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':rName', $rName);
            $stmt->bindParam(':rImage', $recipeImage);
            $stmt->bindParam(':rCategory', $category);
            $stmt->bindParam(':rServes', $serves);
            $stmt->bindParam(':rPrepTime', $prepTime);
            $stmt->bindParam(':rCookTime', $cookTime);
            $stmt->bindParam(':rIngredients', $ingString);
            $stmt->bindParam(':rInstructions', $instString);
            $stmt->bindParam(':updateID', $updateRecID);

        
            $stmt->execute();	
            $message = "The recipe has been saved.";
            
            //echo"<script>alert('$message')</script>";
        }
        
        catch (PDOException $e) {
            echo "Problems inserting...";
        
            error_log($e->getMessage() );
            error_log(var_dump(debug_backtrace() ) );
        }
    
  
       
}// end check for recipe update done
else {      // haven't updated yet
    try {
		require 'recipeDBconnect.php';

		$sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients  FROM recipe WHERE recipe_id=$updateRecID";
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



<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Recipe Project Admin Updates</title>
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
        let recipeToUpdate = new Recipe();
    </script>   

    <style>
        #deleteIng, #deleteStep,#removeItems,#removeIngItem {
            display:inline-block;
        }
    </style>

</head>

<body>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if (isset($_POST["recipeUpdate"]))    {
	
		?>
		<h2 id="updated">Recipe Updated!</h2>
		<p><a href="recipeLogin.php">Back to Recipe Admin Page</a></p>	
        <p><a href="recipeLogout.php">Logout of Recipe Admin System</a></p>	
		<?php
	}
	else	//display form
	{
    ?>
        <!-- form for updating recipe-->
        <form  action="modifyRecipe.php?recId=<?php echo $updateRecID ?>" method="post" name='theRecipe'id="recipeUpdate" enctype="multipart/form-data">
            <h2>Update your recipe:</h2>
            <p>Recipe name: <input type ="text"  name="recipeName" id="recipeName" value="<?php  echo $rName; ?>" onkeypress="return noenter()"></p>

            <label>Do you want to upload a recipe image?
                <select name="recipeImage" id="recipeImage" onChange="addImageSelector()">
                    <option value="s">Select One</option>
                    <option value="y">Yes</option>
                    <option value="n">No</option>
                </select>
            </label>
            
            <div id="getImage"><!-- upload images-->
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload" value="<?php echo $recipeImage; ?>" label="Image file" onkeypress="return noenter()">
                <input type="button" id="checkImg" value = "Check Image" name = "checkImg">
            </div><span id = "imagePreview"></span>

            <!-- Category-->
            <p id = "pickCategory">
                <label for="category">Choose a category:</label>
                <select name= "category" id="category">
                <option value="Select">Select one</option>
                <option value="Appetizer" <?php if ($category=='Appetizer') echo 'selected';?>>Appetizer</option>
                <option value="Dessert" <?php if ($category=='Dessert') echo 'selected';?>>Dessert</option>
                <option value="Entree" <?php if ($category=='Entree') echo 'selected';?>>Entree</option>
                <option value="Drink" <?php if ($category=='Drink') echo 'selected';?>>Drink</option>
                </select>
            </p>

            <!-- serving size-->
            <p>How many servings? 
                <input type ="text" value="<?php echo $serves; ?>" name="servings" id="servings"  placeholder="Enter serving size" label="Serving Size" onkeypress="return noenter()">
            </p>
            <!-- prep time-->
            <p>Preparation time: 
                <input type ="text" name="prepTime" id="prepTime" value="<?php echo $prepTime; ?>" placeholder="Enter preparation time." label="Preparation Time" onkeypress="return noenter()">
            </p>
            <!-- Cook time -->
            <p>Cook time: 
                <input type ="text" name="cookTime" id="cookTime" value="<?php  echo $cookTime; ?>" placeholder="Enter cook time" label="Cook Time" onkeypress="return noenter()">
            </p>

            <!-- Ingredients-->
            <p>Ingredient list (amount and type): </p>
            <input type="hidden" name="ingredientArray" value="" label = "ingredientArray" id="ingredientArray">
            <span id="ingredientList"></span>  <!-- ingredient list posted here -->
            <p>First Ingredient:<input type ="text" name="ingredient" id="ingredient" placeholder="Ingredients" label="Ingredients" onkeypress="return noenter()"></p>
            <button id="addIng" type="button" onclick="addIngredient(recipeToUpdate)">Add this ingredient</button>

            <p><select name="removeIngItem" id="removeIngItem">
            <option>Choose ingredient</option></select></p>
            <button id="deleteIng" onclick="removeIng(recipeToUpdate)" type="button"> Remove this ingredient</button>

            <!-- Instructions -->
            <p>Instructions (step by step): </p>
            <input type="hidden" name="instructionArray" value="" label = "instructionArray" id="instructionArray">
            <span id="instructionList"></span>  <!-- instruction list posted here -->
            <p>First step: <input type ="text" name="step" id="step" label="Instructions" placeholder="Instructions" onkeypress="return noenter()"></p>
            <button id="addStep" type="button" onclick=" addAStep(recipeToUpdate)">Add this step</button>

            <p><select name="removeItems" id="removeItems">
            <option>Choose step</option></select></p>
            <button id="deleteStep" onclick="removeStep(recipeToUpdate)" type="button">Remove this step</button>

            <input type="text" class="honeypot" name="name" placeholder="Leave Blank If Human" autocomplete="off">
            
            <!-- submt/reset buttons -->
            <p>
                <input type="submit" value="Update" name="recipeUpdate">
                <input type="reset" name="resetButton"/>
                <button name="cancel" id="quit" label ="quit recipe update" type="button">Quit</button>
            </p>
            <p><span id="errorMsg"></span></p>
        </form>


        <script>    // insert image first
            document.querySelector("#imagePreview").innerHTML = "<img alt = 'recipe image' src = '<?php echo $recipeImage; ?>'/>";
                    
            recipeToUpdate.ingredients = '<?php echo $ingredients ?>';  // set ingredients/instruction lists
            recipeToUpdate.instructions = '<?php echo $instructions ?>'; 
            recipeToUpdate.ingredients = JSON.parse(recipeToUpdate.ingredients);
            recipeToUpdate.instructions = JSON.parse(recipeToUpdate.instructions);
            
            numOfIng = recipeToUpdate.ingredients.length;
            numSteps = recipeToUpdate.instructions.length;

            let ingSoFar="";        // build display of ingredients
            for (i=1;i<=numOfIng;i++) {
                ingSoFar+=`${i} : ${recipeToUpdate.ingredients[i-1]} <br>`;  // backtick form
                let result = document.querySelector("#removeIngItem");    // for removing ingredients, create the dropdown list
                let option = document.createElement("option");
                option.value = i;
                option.text = recipeToUpdate.ingredients[i-1];
                result.appendChild(option); 
            }
            document.querySelector("#ingredientList").innerHTML=ingSoFar; // list the ingredients so far
            document.querySelector("#ingredientArray").value = recipeToUpdate.ingredients;

            let instructionSet = "";
            for (i=1;i<=numSteps;i++) {         
                instructionSet += i + ": " + recipeToUpdate.instructions[i-1] + "<br>"; // build the display instructions
                let result = document.querySelector("#removeItems");    // for removing steps, create the dropdown list
                let option = document.createElement("option");
                option.value = i;
                option.text = i;
                result.appendChild(option);  
            }
            document.querySelector("#instructionList").innerHTML=instructionSet; // list the instructions so far
            document.querySelector("#instructionArray").value = recipeToUpdate.instructions;

            document.querySelector("#checkImg").addEventListener("click",checkImage);
            document.querySelector("#quit").addEventListener("click",quit);
        </script>

        <?php 
    } ?>
    
	<footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>

</body>
</html>

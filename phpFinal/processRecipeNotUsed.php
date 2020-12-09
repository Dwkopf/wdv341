<?php

$message ="";
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



$recipe_name = $_POST['recipeName'];
$category = $_POST['category'];
$serves = $_POST['servings'];
$prepTime = $_POST['prepTime'];
$cookTime = $_POST['cookTime'];
$ingredients = $_POST['ingredientArray'];
$instructions = $_POST['instructionArray'];

try {
    require "recipeDBconnect.php";	//CONNECT to the database

    //$sql = 'INSERT INTO recipe(rName) VALUES("$recipe_name")';
    //$sql = "INSERT INTO recipe(rName) VALUES ('BEANS')";

    //$sql = 'INSERT INTO recipe(rName) VALUES($recipe_name)';
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
    $stmt->bindParam(':rIngredients', $ingredients);
    $stmt->bindParam(':rInstructions', $instructions);
   
    $stmt->execute();	
    $message = "The recipe has been saved.";
    
    //echo"<script>alert('$message')</script>";
}

catch (PDOException $e) {
    echo "Problems inserting...";

    error_log($e->getMessage() );
    error_log(var_dump(debug_backtrace() ) );
}

$conn=null;

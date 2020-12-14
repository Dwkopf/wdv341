<?php
class Recipe {
    public $name;
    public $image;
    public $serves;
    public $category;
    public $prepTime;
    public $cookTime;
    public $ingredients;
    public $instructions;
}
$recipe_type=$_GET['recipeType'];
//$recipe_type= 'Magic';

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
// $row=$stmt->fetch(PDO::FETCH_ASSOC);
// $ing = explode("*",$row['ingredients']);
// $ins = explode("*",$row['instructions']);

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

<div id="disRecipes">    <!-- structure for displaying 3 choices -->
        <?php 
        while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {  
            $ing = explode("*",$row['ingredients']);
            $ins = explode("*",$row['instructions']); ?>
            <div id="rec<?php echo $row['recipe_id'] ?>">
            <?php
                $rImage =  $row['recipeImage'];
                $recipe_name = $row['rName'];
                $category = $row['category'];
                $serves = $row['serves'];
                $prepTime = $row['prepTime'];
                $cookTime = $row['cookTime'];
                $ingredients = $row['ingredients'];
                $instructions = $row['instructions'];
            ?>
                <div id="showRecipes" class="gridItem">
                    <h3><?php echo $row['rName'] ?></h3>
                    <img src = "<?php echo $rImage ?>" alt ='recipe imge' >
                    <p><button onclick="displayRecipe('<?php echo $recipe_name ?>','<?php echo $rImage ?>','<?php echo $category ?>','<?php echo $serves ?>','<?php echo $prepTime ?>','<?php echo $cookTime ?>','<?php echo $ingredients ?>','<?php echo $instructions ?>')">View</button></p>
                </div>
            </div>
        <?php } ?>

 </div>
</body>
</html>
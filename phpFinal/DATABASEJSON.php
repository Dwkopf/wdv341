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
$recipe_type="Magic";

try {
    
    require "recipeDBconnect.php";	//CONNECT to the database
    if ($recipe_type == "Magic")
        //Create the SQL command string
        $sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe ORDER BY RAND() LIMIT 1"; 
    else 
        $sql = "SELECT recipe_id,rName,recipeImage,category,serves,prepTime,cookTime,instructions,ingredients FROM recipe WHERE category='$recipe_type' ORDER BY RAND() LIMIT 1"; 

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
$row=$stmt->fetch(PDO::FETCH_ASSOC);
$ing = explode("*",$row['ingredients']);
$ins = explode("*",$row['instructions']);

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
            let recipeDisplayed = new Recipe();
            $index = <?php echo $row['recipe_id']?>;
            recipeDisplayed.name = "<?php echo $row['rName'] ?>";
            recipeDisplayed.image = "<?php echo $row['recipeImage'] ?>";
            recipeDisplayed.category = "<?php echo $row['category'] ?>";
            recipeDisplayed.serves = "<?php echo $row['serves'] ?>";
            recipeDisplayed.preparationTime = "<?php echo $row['prepTime'] ?>";
            recipeDisplayed.cookTime = "<?php echo $row['cookTime'] ?>";
            recipeDisplayed.ingredients = <?php echo json_encode($ing) ?>;
            recipeDisplayed.instructions = <?php echo json_encode($ins) ?>;
            recipeDisplayed = JSON.stringify(recipeDisplayed);
            console.log(recipeDisplayed);
           
            
</script>

</head>
<body>

<div id = "here"></div>
<script>document.querySelector('#here').innerHTML = recipeDisplayed;</script>
</body>
</html>



<script>
            let recipeDisplayed = new Recipe();
            index = "<?php $row['recipe_id']?>";
            recipeDisplayed.name = "<?php echo $row['rName'] ?>";
            recipeDisplayed.image = "<?php echo $row['recipeImage'] ?>";
            recipeDisplayed.category = "<?php echo $row['category'] ?>";
            recipeDisplayed.serves = "<?php echo $row['serves'] ?>";
            recipeDisplayed.preparationTime = "<?php echo $row['prepTime'] ?>";
            recipeDisplayed.cookTime = "<?php echo $row['cookTime'] ?>";
            recipeDisplayed.ingredients = <?php echo json_encode($ing) ?>;
            recipeDisplayed.instructions = <?php echo json_encode($ins) ?>;
            recipeDisplayed = JSON.stringify(recipeDisplayed);
            console.log(recipeDisplayed);
           
            
        </script>

<?php
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

    

    <h2>Creating extraordinary memories with unique meals</h2>
    <div id ="home">
        <form action="recipeIndexNoSubmitAJAX.php" method ="post" >  
            <input type ="text" name="search" id="searchHere" label="recipe search" placeholder="Search recipes">
            <input type="submit"  id = "search" value="Search"><span></span>     <!--  search bar -->
        </form>


        <p id = "pickRecipes">
                       
                <label for="recipeType">What are you looking for?:</label>
                    <select name= "recipeType" id="viewRecipes" onchange="getRecipes()">         <!--recipe category selection  -->
                    <option value="Magic"<?php if($recipe_type=="Magic") echo "selected" ?>>Magic</option>
                    <option value="Drink" <?php if($recipe_type=="Drink") echo "selected" ?>>Drinks</option>
                    <option value="Appetizer" <?php if($recipe_type=="Appetizer") echo "selected" ?>>Appetizer</option>
                    <option value="Entree" <?php if($recipe_type=="Entree") echo "selected" ?>>Entree</option>
                    <option value="Dessert" <?php if($recipe_type=="Dessert") echo "selected" ?>>Dessert</option>
                    </select>
           
          </p>
       
    </div>

    
        <div id="showRecipes" class="gridItem">
     
        </div>
<?php
        if (isset($_POST['search']))    {   ?>
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
        
        
        <?php } 
        else { ?>
            <script>getRecipes();</script>
        <?php } ?>




<!-- detail structure for displayFullRecipe -->
    <div id="details" class="container1"> 
        <h1>Recipe Name: </h1>
        <img class="">
        <h3>Servings</h3>
        <h3>Preparation Time</h3>
        <h3>Cook Time</h3>
        

    </div>
    


<!-- overlay form for submitting recipe-->






    
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
<?php
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  $validForm = false;
 if (isset($_POST['button'])) {
    $cust_name = test_input($_POST['custName']);
    $cust_email = test_input($_POST['custEmail']);
    $cust_message = test_input($_POST['custMessage']);

    if ($cust_name != "" && $cust_message != "") {
        $validForm=true;
        try {
            require "recipeDBconnect.php";	//CONNECT to the database

            $sql = "INSERT INTO email_list(user_name,user_email)
                VALUES (:uName,:uEmail)";
        
            //$sql = "INSERT INTO recipe (rName) VALUES (:rName)";
        
            $stmt = $conn->prepare($sql);
        
            $stmt->bindParam(':uName', $cust_name);
            $stmt->bindParam(':uEmail', $cust_email);

        
            $stmt->execute();	
            $message = "Your message was recieved. Someone will be in touch within a few days.";
            
            //echo"<script>alert('$message')</script>";
        }
        
        catch (PDOException $e) {
            echo "Problems inserting...";
        
            error_log($e->getMessage() );
            error_log(var_dump(debug_backtrace() ) );
        }
    }
    echo $validForm;
 }




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David Kopf">
    <title>Recipe Project Contact page</title>
    <link type="text/css" rel="stylesheet" href="recipe.css">
    <script src="recipes.js"></script> 
    <script src="recipesLoaded.js"></script> 

    <style>
        .row {
            margin:30px;
        }

        a +img {
            width:400px;
            height:300px;
        }

        #button, #button2 {
            background-color: #5C5247;
            color:#DBA367;
        }
    </style>

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

  <img src="images/contact.jpg" alt="contact us image" class="responsive" id="contact">

  <?php 
  if ($validForm) {
      echo "<h3>$message</h3>";
  }
  else {
?>
  <form method="post" action="recipeContact.php">
    <fieldset>
    <p class="form-group">
       <label for="floatName">NAME</label>
          <input type="text" name="custName" id="custName" placeholder="Full Name" required>
    </p>
     <p class="form-group">
         <label for="floatEmail">EMAIL</label>
         <input type="text" name="custEmail" id="custEmail" placeholder="Email Address" required>
     </p>

     <input type="text" class="honeypot" name="name" placeholder="Leave Blank If Human" autocomplete="off">

     <p class="form-group">
         <label for="floatMessage">MESSAGE</label>
           <textarea name="custMessage" id="custMessage" required placeholder="Your message"></textarea>
     </p>
     <div>
         <input type="submit" name="button" id="button" value="Submit" class="btn btn-primary glow">
         <input type="reset" name="button2" id="button2" value="Reset"class="btn btn-primary glow" >
     </div>
    </fieldset>
   
</form>
  <?php }?>

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
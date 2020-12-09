<?php
$userName = "root"; // username used to sign on to db
$password = "";  //password used to sign on to db

// XAMPP default username = 'root and password=''
// will need different username, password, database for dbConnect on my server

$serverName = "localhost"; // identifies the db server
                            // most common is localhost
$databaseName ="recipes"; // db to access


try {
    $conn = new PDO("mysql:host=$serverName;dbname=$databaseName",$userName,$password);
    
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
    $conn -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // prepared statements

    $sql = 'INSERT INTO recipe (rName,recipeImage,category,serves,prepTime,cookTime,ingredients,instructions)
    VALUES ("Twix Caramel Cheesecake", "images/twixCheesecake.jpg","Dessert","12","15 minutes","15 minutes",
    "1.5 Cups Graham Cracker Crumbs about 1 1/2 Packages*4 Tablespoons Melted Butter*1 Teaspoon Sugar*16 oz Cream Cheese, softened
    *.33 Cup Sugar*1 Teaspoon Vanilla Extract*.25 Cup Caramel Sauce*2 Large Eggs*1 Cup Halved Mini Twix divided",
    "Preheat the oven to 350.*
    In a bowl, add the butter, graham cracker crumbs and sugar, stirring to combine. Press cupcake wrappers into the holes of a muffin tin and fill each one evenly with the graham crackers. Bake for 5 minutes and remove to cool.
    *In a bowl, beat the cream cheese with a hand mixer until smooth and creamy. Add the sugar and vanilla extract and beat again.
    *Next, add one egg and beat on low smooth until just incorporated. Add the next egg and repeat.
    *Gently fold 1/2 of the twix into the batter and spoon evenly between the cupcake liners. Drizzle a little caramel over each top and using a toothpick, swirl it carefully in. Sprinkle however many remaining twix as you would like over each top.
    *Bake for 15-16 minutes and then remove to cool. Once the cheesecakes have cooled for a bit, place in the fridge for 1-2 hours.
    *Enjoy!!")';
    $conn->exec($sql);
    echo "New record created successfully";
    
    // echo "connected successfully";
}

catch (PDOException $e) {
    echo "Problems...";

    error_log($e->getMessage() );
    error_log(var_dump(debug_backtrace() ) );
}





$conn=null;

?>
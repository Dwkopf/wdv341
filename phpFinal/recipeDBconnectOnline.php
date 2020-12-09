<?php 
    /* connects your PHP application to a database. Allows them to communicate.*/

    // Note: DO NOT inlude this file in your repo. Use .gitignore to leave this out

    $userName = "dwk_recipes"; // username used to sign on to db
    $password = "vikes1";  //password used to sign on to db

    // XAMPP default username = 'root and password=''
    // will need different username, password, database for dbConnect on my server

    $serverName = "localhost"; // identifies the db server
                                // most common is localhost
    $databaseName ="dwk_recipes"; // db to access

    try {
        $conn = new PDO("mysql:host=$serverName;dbname=$databaseName",$userName,$password);
        
        $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
        $conn -> setAttribute(PDO::ATTR_EMULATE_PREPARES, false);  // prepared statements
        
        //echo "connected successfully";
    }

    catch (PDOException $e) {
        echo "Problems connecting...";

        error_log($e->getMessage() );
        error_log(var_dump(debug_backtrace() ) );
    }
        // eventually put $conn=null;

?>
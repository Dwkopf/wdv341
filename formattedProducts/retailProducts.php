<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>

<?php

	try {
	  
	  require "dbconnect.php";	//CONNECT to the database
	  
	  //Create the SQL command string
	  $sql = "SELECT * FROM wdv341_products ORDER BY product_name DESC"; 

	  //PREPARE the SQL statement
	  $stmt = $conn->prepare($sql);
	  
	  //EXECUTE the prepared statement
	  $stmt->execute();		
	  //Prepared statement result will deliver an associative array
	     $stmt->setFetchMode(PDO::FETCH_ASSOC);
	  //$result =$sth->fetchAll(PDO::FETCH_COLUMN, product_name);
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

?>
	
	<style>
	
		.productBlock	{
			
			width:300px;
			background-color: aquamarine;
			border:thin solid black;
		}
		
		.productImage img {
			display:block;
			margin-left:auto;
			margin-right:auto;
			width:280px;
			height:280px;				
		}
	
		.productName {
			text-align:center;
			font-size: large;
		}	
		
		.productDesc {
			margin-left:10px;
			margin-right:10px;
			text-align:justify;
		}
		
		.productPrice {
			text-align: center;
			font-size:larger;
			color:blue;
		}
		
		.productStatus {
			text-align:center;
			font-weight:bolder;
			color:darkslategray;
		}
		
		.productInventory {
			text-align:center;
		}
		
		.productLowInventory {
			color:red;
		}
		
	</style>
</head>

<body>
	
	<h1>DMACC Electronics Store!</h1>
	<h2>Products for your Home and School Office</h2>


	<?php 
			while($row=$stmt->fetch(PDO::FETCH_ASSOC) ) {
		?>		
				<div class="productBlock">
					<div class="productImage">
						<image src="productImages/<?php echo $row['product_image']; ?>">
					</div>
					<p class="productName"><?php echo $row['product_name']; ?></p>	
					<p class="productDesc"><?php echo $row['product_description']; ?></p>
					<p class="productPrice"><?php echo $row['product_price']; ?></p>
					<?php if ($row['product_status']!="") { ?>
						<p class="productStatus"><?php echo $row['product_status']; ?></p>
						<?php } ?>
					<p class="productInventory <?php if ($row['product_inStock']<10) echo 'productLowInventory' ?>"><?php echo $row['product_inStock']; ?></p>
				</div>
				<?php
			}
		?>	

</body>
</html>


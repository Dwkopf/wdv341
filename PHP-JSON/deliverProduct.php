<?php

	/*
	connect to db
	create the sql statement  SELECT eventName ... FROM table LIMIT 1
									or			WHERE eventId = 
	prepare the stmt
	execute the stmt

	$row=$stmt->fetch(PDO::FETCH_ASSOC)

	use a fetchAll()
		taek the vlues of ea column and putit into an object property
		obj ->eventName=?
		obj->eventDescription=?
		obj->eventPresenter=?
		obj->eventDate
		obj->eventTime

	*/
	
	$productObj = new stdClass();
	
	$productObj->productName = "PHP Textbook";// load book name into PHP object
	$productObj->productPrice="$129.95";		// load price
	$productObj->productPageCount=327;
	$productObj->productISBN = "13-1234435690";

	//echo $productObj;

	//$productObj->productPrice = "$1.99";
//
	$returnObj = json_encode($productObj);	//create the JSON object
//	
	echo $returnObj;							//send results back to calling program
?>
<?php 
    echo date("l F j, Y",strtotime("9/19/2020") );



    function displayFullDate($inDate)  {
        echo '<p></p>';
        echo  date("l F j, Y", strtotime($inDate));
    }

    displayFullDate("9/22/2020");
?>
<?php 
    function displayDate($inDate) {
        echo nl2br(date("m/d/Y", strtotime($inDate))."\n");
    }

    function displayIntDate($inDate){
        echo nl2br(date("d/m/Y", strtotime($inDate))."\n");
    }

    function checkString($inString) {
        echo nl2br("$inString\n");
        echo nl2br("There are ".strlen($inString)." characters in the string\n");
        $trimx=trim($inString);
        echo nl2br ("There are ".strlen($trimx)." characters in the string without leading or trailing whitespace\n");
        echo nl2br("All lowercase the string is: ".strtolower($inString)."\n");
        
        if (strpos(strtolower($inString),"dmacc")) 
            echo nl2br("DMACC is in the string\n");
        else echo nl2br("DMACC is not in the string\n");
    }

    function formatNumber($inNumber) {
        $formatNum=number_format($inNumber,2,".",",");
        echo nl2br("$formatNum\n");
    }

    function convertToUSD($inNum) {
        $formatNum = number_format($inNum,2,'.',',');
        $formatNum="$".$formatNum;
        echo nl2br("$formatNum\n");
    }

    displayDate("10/24/2020");
    displayIntDate("10/24/2020");
    echo '<p></p>';

    checkString("Going to school at Dmacc this fall    ");
    echo '<p></p>';
    checkString(" Not Going to School Anywhere Else   ");
    echo '<p></p>';
   

    formatNumber(1234567890);
    convertToUSD(123456);
?>
<?php   
    
     
    include "formatUSD.php";
$totalSales=1234.567;

function formatCurrency($inNum, $inCountryCode, $inCurrencyCode) {
    $formatter= new NumberFormatter($inCountryCode,NumberFormatter::formatCurrency);
    return $formatter->formatCurrency($inNum,$inCurrencyCode);
}



?>
<h2>Total Sales for this month.<?php echo formatUSD($totalSales);?><h2>





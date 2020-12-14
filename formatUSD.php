<?php

function formatUSD($inNum) {
       
       $formatter=new NumberFormatter('en_US',NumberFormatter::CURRENCY);
       return $formatter->formatCurrency($inNum,'USD');
   }
   ?>
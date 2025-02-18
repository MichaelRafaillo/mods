<?php

$GOLD_PRICE = 1157.14;

$weight = $_GET['weight'];
$level = $_GET['level'];

//Levels Prices
if ($weight <= 4 ) {
	if ($level == 'FS') {    $levelprice = 300;  };
	if ($level == 'A1')	{    $levelprice = 300;  };
	if ($level == 'A1P'){    $levelprice = 350;  };
	if ($level == 'A2') {    $levelprice = 350;  };
	if ($level == 'A3') {    $levelprice = 400;  };
	if ($level == 'A3P'){    $levelprice = 400;  };
	if ($level == 'A4') {    $levelprice = 400;  };
	if ($level == 'A5') {    $levelprice = 430;  };
}
if ($weight > 4) {
	if ($level == 'FS') {    $levelprice = 300;  };
	if ($level == 'A1') {    $levelprice = 300;  };
	if ($level == 'A1P'){    $levelprice = 330;  };
	if ($level == 'A2') {    $levelprice = 330;  };
	if ($level == 'A3') {    $levelprice = 380;  };
	if ($level == 'A3P'){    $levelprice = 380;  };
	if ($level == 'A4') {    $levelprice = 390;  };
	if ($level == 'A5') {    $levelprice = 430;  };
}

$finalprice = ($GOLD_PRICE+$levelprice)*$weight;
echo $finalprice;
echo '<br>';
$finalprice = round($finalprice/50)*50;
echo $finalprice;
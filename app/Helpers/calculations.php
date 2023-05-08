<?php

//calculate percentage
function percentageCalc($value, $percent)
{
    return ($value/100)*$percent;
}

//claculate tax
function taxCalc($gross_amount, $tax_rate = 2, $included = 'Enabled') 
{

	$tax = 0;
	if($included == 'Enabled') {
		
		$tax_rate = 1+($tax_rate/100);

		$net_amount = ($gross_amount/$tax_rate);
		$tax = ($gross_amount - $net_amount);
	}
	else {
		$tax = ($gross_amount *($tax_rate/100));
	}

	return round($tax, 2);
}

//claculate order total
function orderTotalCalc(int|float $subTotal, int|float $discount, int|float $deliveryFee) 
{
    return number_format(($subTotal+$deliveryFee) - $discount, 2);
}
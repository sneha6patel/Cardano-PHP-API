<?php
/* some additional helper functions to go along with the API request */

// CONVERT TO ADA
// Function  : Convert Lovelaces to ADA
//           : 1,000,000 Lovelace = 1 ADA
// Parameters: 
//            $amount = How much Lovelace to convert to ADA
//

function convert_to_ada($amount) {

	$ada = $amount / 1000000;
	return $ada;

}


// CONVERT TO LOVELACE
// Function  : Convert ADA to Lovelace
//             1 ADA = 1,000,000 Lovelace
// Parameters: 
//            $amount = How much ADA to convert to Lovelace
//

function convert_to_lovelace($amount) {

	$lovelace = $amount * 1000000;
	return $lovelace;

}

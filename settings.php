<?php

// GET CURRENT NODE SETTINGS
// Function  : Gets the nodes settings.
// Parameters: 
//
//              NONE
//

function cardano_settings() {

	// SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

    // API END POINT
    $end_point	= "/api/v1/node-settings";

    // CARDANO CLIENT CERTIFICATE
    $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // INIT CURL
    $curl = curl_init();
   
   	// OPTIONS
    curl_setopt($curl, CURLOPT_URL, $host.':'.$port.$end_point);
    curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSLCERT, $cert_path);

    // LET'S GET CURLY
    ob_start(); 
    curl_exec($curl);
    $data = ob_get_contents();
    ob_end_clean();

    // HTTP CODES
    $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // ERRORS
    if ($data === false) {
        echo 'Curl error: ' . curl_error($curl);
        return false;
    }

    // BYE FELICIA
    curl_close($curl);

    // GIMME ALL YOUR DATA
    return $data;
}


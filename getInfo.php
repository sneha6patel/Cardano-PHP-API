<?php

// GET CURRENT NODE INFO
// Function  : Delete a wallet and all its associated accounts.
// Parameters: 
//
//				NONE
//


function cardano_get_info($force_ntp = "false") {

	// SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

    // SETTINGS (QUERY PARAMETERS)
    $settings = array(

                    "force_ntp_check"  => (boolean)$force_ntp
                );
                
    $query      = http_build_query($settings);
    
    // API END POINT
    if ($force_ntp == "true") {
        
        $end_point = "/api/v1/node-info/?=" . $query;

    }
      
    $end_point	= "/api/v1/node-info";

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


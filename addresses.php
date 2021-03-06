<?php

// GET ALL ADDRESSES IN WALLET\
// Function  : Returns a list of the addresses.
// Parameters:
//
//          $page          : (integer) The page number you want to get accounts from.
//          $per_page      : (integer) The number of entries per page. Value: 1 MIN to 50 MAX
//

function cardano_get_all_addresses($page = "1", $per_page = "50") {

	// SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

    // CARDANO CLIENT CERTIFICATE
    $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // SETTINGS (QUERY PARAMETERS)
    $settings = array(

                    "page"      => (integer)$page,
                    "per_page"  => (integer)$per_page
                );

    $query      = http_build_query($settings);

    // API END POINT
    $end_point  = "/api/v1/wallets/addresses/?" . $query;

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

// GET SPECIFIC ADDRESS INFO <ADDRESS>
// Function  : Get details about a specific address within a wallet.
// Parameters:
//
//          $address  : (string) The address you want to look up.
//

function cardano_get_address_info($address) {

    // SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // API END POINT
    $end_point  = "/api/v1/addresses/" . $address;

    // CARDANO CLIENT CERT
    $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

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

// CREATE A NEW ADDRESS WITHIN EXISTING WALLET, IF VALID AND AVAILABLE
// Function  : Creates a new address within an existing wallet.
// Parameters:
//              $wallet_id        : (string) The wallet ID of the wallet you want to create the address in.
//              $account_idx      : (string) The account index of the account to create the address in.
//              $spending_password: (string) The wallets spending password.
//

function cardano_create_address($wallet_id, $account_idx, $spending_password) {

    // SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // API END POINT
    $end_point  = "/api/v1/addresses/";

    // CARDANO CLIENT CERT
    $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // POST FIELDS

    $post_fields = array(
                "accountIndex"      => (integer)$account_idx,
                "walletId"          => (string)$wallet_id,
                "spendingPassword"  => (string)$spending_password
                );

    // INIT CURL
    $curl       = curl_init();

    $headers = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json; charset=utf-8"
    );

    // OPTIONS
    curl_setopt($curl, CURLOPT_URL, $host.':'.$port.$end_point);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSLCERT, $cert_path);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_fields));

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

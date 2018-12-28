<?php
// GET SPECIFIC ACCOUNT INFO 
// Function  : Retrieves a specific Account.
// Parameters: 
//
//          $wallet_id  : (string) The ID of the wallet that contains the account you want to look up.
//			$account_idx: (integer) The account index of the account you want to look up.
//
//

function cardano_get_account_info($wallet_id, $account_idx) {

    // SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // API ENDPOINT
    $end_point  = "/api/v1/wallets/" . (string)$wallet_id . "/accounts/" . (integer)$account_idx;

    // CARDANO CLIENT CERTIFICATE
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

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

// UPDATE ACCOUNT
// Function  : Update an Account for the given Wallet.
// Parameters: 
//
//				$wallet_id   : (string) The ID of the wallet that contains the account you want to look up.
//				$account_idx : (integer) The account index of the account you want to look up.
//				$account_name: (string) The new name for the account.
//

function cardano_update_account_name($wallet_id, $account_idx, $account_name) {

	// SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

	// API END POINT
    $end_point	= "/api/v1/wallets/" . (string)$wallet_id . "/accounts/" . (integer)$account_idx;

    // CARDANO CLIENT CERTIFICATE
    $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // PUT FIELDS
    $put_fields = array("name" => (string)$account_name);
    
    // INIT CURL
    $curl = curl_init();
   
    $headers = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json; charset=utf-8"        
    );

   	// OPTIONS
    curl_setopt($curl, CURLOPT_URL, $host.':'.$port.$end_point);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSLCERT, $cert_path);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);              
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($put_fields));
    
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

// DELETE ACCOUNT
// Function  : Deletes an Account.
// Parameters: 
//
//				$wallet_id   : (string) The wallet_id of the wallet containing the account to delete.
//				$account_idx : (integer) The account index of the account you want to delete.
//

function cardano_delete_account($wallet_id, $account_idx) {

	// SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

	// API END POINT
    $end_point	= "/api/v1/wallets/" . $wallet_id . "/accounts/" . (integer)$account_idx;

   
    // CARDANO CLIENT CERTIFICATE
    $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // INIT CURL
    $curl = curl_init();
   

    $headers = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json; charset=utf-8"        
    );

   	// OPTIONS
    curl_setopt($curl, CURLOPT_URL, $host.':'.$port.$end_point);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSLCERT, $cert_path);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);              
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
   
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

// GET ALL ADDRESSES IN ALL WALLETS
// Function  : Get all the addresses in all wallets.
// Parameters: 
//
//          $wallet_id: The wallet ID to get addresses from.
//          $page    : (integer) The page number you want to get accounts from.
//          $per_page: (integer) The number of entries per page. Value: 1 MIN to 50 MAX
//
//

function cardano_get_all_accounts($wallet_id, $page = "1", $per_page = "50") {

	// HOST SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";

    // CARDANO CLIENT CERT
    $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

     // QUERY PARAMETERS
    $settings = array(

                    "page"      => (integer)$page,
                    "per_page"  => (integer)$per_page 
                );
                
    $query      = http_build_query($settings);

    // API END POINT
    $end_point  = "/api/v1/wallets/" . (string)$wallet_id . "/accounts/?" . $query;

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

// CREATE A NEW ACCOUNT WITHIN EXISTING WALLET, IF VALID AND AVAILABLE
// Function  : Creates a new Account for the given Wallet.
// Parameters: 
//              $wallet_id        : (string) The wallet ID of the wallet you want to create the address in.
//              $spending_password: (string) The wallets spending password.
//				$account_name     : (string) The name of the new account.
//

function cardano_create_account($wallet_id, $spending_password, $account_name) {

    // SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // API END POINT
    $end_point  = "/api/v1/wallets/" . (string)$wallet_id . "/accounts";

    // CARDANO CLIENT CERT
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // POST FIELDS
    $post_fields = array(
                "spendingPassword"  => (string)$spending_password,
                "name"          	=> (string)$account_name
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

// GET ONLY ADDRESSES
// Function  : Retrieve only account's addresses.
// Parameters: 
//
//          $wallet_id : The wallet ID to get account from.
//          $acount_idx: The account index to get account from.
//          $page      : (integer) The page number you want to get accounts from.
//          $per_page  : (integer) The number of entries per page. Value: 1 MIN to 50 MAX
//          $address   : (string) A FILTER operation on a WalletAddress. (https://cardanodocs.com/technical/wallet/api/v1/#tag/Accounts%2Fpaths%2F~1api~1v1~1wallets~1%7BwalletId%7D~1accounts~1%7BaccountId%7D~1addresses%2Fget)
//

function cardano_get_only_addresses($wallet_id, $account_idx, $page = "1", $per_page = "50", $address = "") {

    // HOST SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // CARDANO CLIENT CERT
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

     // QUERY PARAMETERS
    $settings = array(

                    "page"      => (integer)$page,
                    "per_page"  => (integer)$per_page, 
                    "address"   => (string)$address
                );
                
    $query      = http_build_query($settings);

    // API END POINT
    $end_point  = "/api/v1/wallets/" . (string)$wallet_id . "/accounts/" . (integer)$account_idx . "/addresses";

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

// GET ACCOUNT BALANCE
// Function  : Retrieve only account's balance.
// Parameters: 
//
//          $wallet_id  : The wallet ID to get account from.
//          $account_idx: The account index of the account. 

function cardano_get_account_balance($wallet_id, $account_idx) {

    // HOST SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // CARDANO CLIENT CERT
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // API END POINT
    $end_point  = "/api/v1/wallets/" . $wallet_id . "/accounts/" . $account_idx . "/amount";

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
<?php
// GET SPECIFIC ACCOUNT INFO
// Function  : Get details about a specific account within a wallet.
// Parameters:
//
//          $wallet_id  : (string) The ID of the wallet that contains the account you want to look up.
//			$account_idx: (string) The account index of the account you want to look up.
//
//

function cardano_get_account_info(string $wallet_id, string $account_idx) {

        // SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // API ENDPOINT
        $end_point  = "/api/v1/wallets/" . $wallet_id . "/accounts/" . $account_idx;

        // CARDANO CLIENT CERTIFICATE
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
        $data       = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        return $data;
}

// UPDATE ACCOUNT
// Function  : Update the name of an account.
// Parameters:
//
//				$wallet_id   : (string) The ID of the wallet that contains the account you want to look up.
//				$account_idx : (string) The account index of the account you want to look up.
//				$account_name: (string) The new name for the account.
//

function cardano_update_account_name(string $wallet_id, string $account_idx, string $account_name) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

		// API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id . "/accounts/" . $account_idx;



        // CARDANO CLIENT CERTIFICATE
        $cert_path	= "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // PUT FIELDS
        $put_fields = array("name" => $account_name);

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
        $data       = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        return $data;

}

// DELETE ACCOUNT
// Function  : Delete an account from a specific wallet and all its associated addresses.
// Parameters:
//
//				$wallet_id   : (string) The wallet_id of the wallet containing the account to delete.
//				$account_idx : (string) The account index of the account you want to delete.
//

function cardano_delete_account(string $wallet_id, string $account_idx) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

		// API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id . "/accounts/" . $account_idx;


        // CARDANO CLIENT CERTIFICATE
        $cert_path	= "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

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
        $data       = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        return $data;
}

// GET ALL ADDRESSES IN ALL WALLETS
// Function  : Get all the addresses in all wallets.
// Parameters:
// Settings  :
//
//          $page    : (string) The page number you want to get accounts from.
//          $per_page: (string) The number of entries per page. Value: 1 MIN to 50 MAX
//
//


function cardano_get_all_accounts(string $wallet_id) {

		// HOST SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

		// SETTINGS
		$page = "1";
		$per_page = "50";

        // CARDANO CLIENT CERT
        $cert_path	= "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

         // QUERY PARAMETERS
        $settings = array(

                        "page"      => $page,
                        "per_page"  => $per_page
                    );

        $query      = http_build_query($settings);

        // API END POINT
        $end_point  = "/api/v1/wallets/" . $wallet_id . "/accounts/?" . $query;

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
        $data       = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        return $data;
}

// CREATE A NEW ACCOUNT WITHIN EXISTING WALLET, IF VALID AND AVAILABLE
// Function  : Creates a new account within an existing wallet.
// Parameters:
//              $wallet_id        : (string) The wallet ID of the wallet you want to create the address in.
//              $spending_password: (string) The wallets spending password.
//				$account_name     : (string) The name of the new account.
//

function cardano_create_account(string $wallet_id, string $spending_password, string $account_name) {

        // SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // API END POINT
        $end_point  = "/api/v1/wallets/" . $wallet_id . "/accounts";

        // CARDANO CLIENT CERT
        $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // POST FIELDS
        $post_fields = array(
                    "spendingPassword"  => $spending_password,
                    "name"          	=> serialize($account_name)
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
        $data       = curl_exec($curl);
        $httpCode   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        return $data;
}

<?php

// GET ALL AVAILABLE WALLETS
// Function  : Returns a list of the available wallets.
// Parameters: 
//
//          $page          : (integer) The page number you want to get accounts from.
//          $per_page      : (integer) The number of entries per page. Value: 1 MIN to 50 MAX
//          $filter_id     : (string) A filter operation on a Wallet. (https://cardanodocs.com/technical/wallet/api/v1/#tag/Wallets)
//          $filter_balance: (string) A filter operation on a Wallet. (https://cardanodocs.com/technical/wallet/api/v1/#tag/Wallets)
//          $filter_sort   : (string) A filter operation on a Wallet. (https://cardanodocs.com/technical/wallet/api/v1/#tag/Wallets)
//

function cardano_get_all_wallets($page = "1", $per_page = "50", $filter_id = "", $filter_balance = "", $filter_sort = "") {

		// HOST SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
        
        // SETTINGS (QUERY PARAMETERS)
        $parameters = array(

				        "page"		=> (integer)$page,
				        "per_page" 	=> (integer)$per_page, 
                        "id"        => (string)$filter_id,
                        "balance"   => (string)$filter_balance,
                        "sort_by"   => (string)$filter_sort
				    );
        			
        $query 		= http_build_query($parameters);

        // API END POINT
		$end_point	= "/api/v1/wallets/?" . $query;

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

// GET WALLET BY ID
// Function  : Returns the Wallet identified by the given walletId.
// Parameters: 
//
//			$wallet_id: (string) The wallet_id to look up
//

function cardano_get_wallet_by_id($wallet_id) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

        // API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id;
       

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

// CREATE NEW WALLET
// Function  : Creates a new or restores an existing Wallet.
// Generate  : BIP39 backup phrase here: https://iancoleman.io/bip39/
// Generate  : Spending password is generated automatically and printed to the screen when the function is completed.
// Parameters: 
//			
//			$post_fields: array( 
//             		 "backupPhrase" => "array("word", "word", "word", "word", "word", "word", "word", "word", "word", "word","word", "word")
//					 "spendingPassword" => Spending password is generated automatically and printed to the screen when the function is completed.
//					 "assuranceLevel" => "normal" or "strict"
//             		 "name" => "Wallet Name",
//             		 "operation" => "create" or "restore"
//           	);

function cardano_create_new_wallet($backup_phrase, $assurance_level, $wallet_name, $wallet_operation) {

		// HOST SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // API END POINT
        $end_point  = "/api/v1/wallets/";

        // CARDANO CLIENT CERTIFICATE
        $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // GENERATE SPENDING PASSWORD BECAUSE.. 
        // "Using a computer to randomly generate a passphrase is best, as humans aren't a good source of randomness."
       
        $spending_password 			= random_bytes(32);
        $spending_password_hash  	= hash("sha256", $spending_password); 
        $spending_password_base16 	= substr(bin2hex($spending_password_hash),1, 64);

        // IMPORTANT INFORMATION
        echo "Write down your spending password: <strong>" . $spending_password_base16 . "</strong><br /><strong>DO NOT LOSE THIS YOU WILL NOT SEE IT AGAIN.</strong>";

        // POST FIELDS
        $post_fields = array(
                    "backupPhrase"      => $backup_phrase,
                    "spendingPassword"  => $spending_password_base16,
                    "assuranceLevel"  	=> $assurance_level,
                    "name" 				=> $wallet_name,
                    "operation" 		=> $wallet_operation
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

// UPDATE SPENDING PASSWORD
// Function  : Updates the password for the given Wallet.
// Generate  : New spending password is generated automatically and printed to the screen when the function is completed.
// Parameters: 
//
//				$wallet_id   : (string) The wallet_id of the wallet to change password.
//				$old_password: (string) The wallets current password.

function cardano_update_spending_password($wallet_id, $old_password) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

        // API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id . "/password";

        // CARDANO CLIENT CERTIFICATE
        $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // GENERATE NEW SECURE PASSWORD
        $spending_password 			= random_bytes(32);
        $spending_password_hash  	= hash("sha256", $spending_password); 
        $spending_password_base16 	= substr(bin2hex($spending_password_hash),1, 64);
        
        // IMPORTANT INFORMATION
        echo "Write down your NEW spending password: <strong>" . $spending_password_base16 . "</strong><br /><strong>DO NOT LOSE THIS YOU WILL NOT SEE IT AGAIN.</strong>";

        // PUT FIELDS
        $put_fields = array(

        				"old" => $old_password,
        				"new" => $spending_password_base16
        			);
        
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

// UPDATE WALLET 
// Function  : Update the Wallet identified by the given walletId.
// Parameters: 
//
//				$wallet_id   	: (string) The wallet_id of the wallet to change name and assurance level.
//				$assurance_level:  (string) "normal" or "strict"
//				$wallet_name    : (string) The wallets new name.
//

function cardano_update_wallet($wallet_id, $assurance_level, $wallet_name) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

        // API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id;

        // CARDANO CLIENT CERTIFICATE
        $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // PUT FIELDS
        $put_fields = array(

        				"assuranceLevel" => $assurance_level,
        				"name" => $wallet_name
        			);
        
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

// DELETE WALLET
// Function  : Deletes the given Wallet and all its accounts.
// Parameters: 
//
//				$wallet_id   : (string) The wallet_id of the wallet to delete.
//

function cardano_delete_wallet($wallet_id) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";

        // API END POINT
        $end_point	= "/api/v1/wallets/" . $wallet_id;

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
        $data        = curl_exec($curl);
        $http_code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        // -- THERE IS NO DATA TO RETURN, JUST A RESPONSE CODE --
        // return $data;

        // RESPOND TO CODE INSTEAD OF JSON DATA
        if ($http_code == "204") {

            echo "Deleted wallet: " . $wallet_id;
        }

        else {
            echo "No wallet found.";
        
        }

}

// GET UTXO STATS
// Function  : Returns Utxo statistics for the Wallet identified by the given walletId.
// Parameters: 
//
//              $wallet_id   : (string) The wallet_id of the wallet to get UTXO stats from.
//

function cardano_get_utxo($wallet_id) {

        // SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // API END POINT
        $end_point  = "/api/v1/wallets/" . $wallet_id . "/statistics/utxos";

        // CARDANO CLIENT CERTIFICATE
        $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

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
        $data        = curl_exec($curl);
        $http_code   = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // ERRORS
        if(curl_exec($curl) === false)
        {
            throw new Exception('Curl error: ' . curl_error($curl));
        }


        // BYE FELICIA
        curl_close($curl);

        // GIMME ALL YOUR DATA
        // -- THERE IS NO DATA TO RETURN, JUST A RESPONSE CODE --
        // return $data;

        // RESPOND TO CODE INSTEAD OF JSON DATA
        if ($http_code == "204") {

            echo "Deleted wallet: " . $wallet_id;
        }

        else {
            echo "No wallet found.";
        
        }

}




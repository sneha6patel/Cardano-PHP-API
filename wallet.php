<?php

// GET ALL AVAILABLE WALLETS
// Function  : Get all the available wallets.
// Parameters: 
//
//				PAGE: 		(string) Page number to display.
//				DEFAULT: 	1
//
//				PER_PAGE: 	(string) How many entries per page. Min: 1, Max: 50
//				DEFAULT:	50 
//

function cardano_get_all_wallets(string $page = "1", string $per_page = "50") {

		// HOST SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
        
        // QUERY PARAMETERS
        $parameters = array(

				        "page"		=> $page,
				        "per_page" 	=> $per_page 
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
// Function  : Get details on a speficic wallet by wallet_id.
// Parameters: 
//
//				WALLET_ID: (string) The wallet_id to look up
//

function cardano_get_wallet_by_id(string $wallet_id) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
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

function cardano_create_new_wallet(array $backup_phrase, string $assurance_level, string $wallet_name, string $wallet_operation) {

		// HOST SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // API END POINT
        $end_point  = "/api/v1/wallets/";

        // CARDANO CLIENT CERTIFICATE PATH
        $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // GENERATE SPENDING PASSWORD BECAUSE.. 
        // "Using a computer to randomly generate a passphrase is best, as humans aren't a good source of randomness."
       
        $spending_password 			= random_bytes(32);
        $spending_password_hash  	= hash("sha256", $spending_password); 
        $spending_password_base16 	= substr(bin2hex($spending_password_hash),1, 64);
             
        // POST FIELDS
        $post_fields = array(
                    "backupPhrase"      => $backup_phrase,
                    "spendingPassword"  => $spending_password_base16,
                    "assuranceLevel"  	=> $assurance_level,
                    "name" 				=> serialize($wallet_name),
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

        // IMPORTANT INFORMATION
        echo "Write down your spending password: <strong>" . $spending_password_base16 . "</strong><br /><strong>DO NOT LOSE THIS YOU WILL NOT SEE IT AGAIN.</strong>";
}

// UPDATE SPENDING PASSWORD
// Function  : Update a wallets spending password.
// Generate  : New spending password is generated automatically and printed to the screen when the function is completed.
// Parameters: 
//
//				WALLET_ID   : (string) The wallet_id of the wallet to change password.
//				OLD_PASSWORD: (string) The wallets current password.

function cardano_update_spending_password(string $wallet_id, string $old_password) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
        $end_point	= "/api/v1/wallets/" . $wallet_id . "/password";

       

        // CARDANO CLIENT CERTIFICATE
        $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // GENERATE NEW SECURE PASSWORD
        $spending_password 			= random_bytes(32);
        $spending_password_hash  	= hash("sha256", $spending_password); 
        $spending_password_base16 	= substr(bin2hex($spending_password_hash),1, 64);

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

         // IMPORTANT INFORMATION
        echo "Write down your NEW spending password: <strong>" . $spending_password_base16 . "</strong><br /><strong>DO NOT LOSE THIS YOU WILL NOT SEE IT AGAIN.</strong>";

}

// UPDATE WALLET 
// Function  : Update a wallets name and assurance level. 
// Parameters: 
//
//				WALLET_ID   	: (string) The wallet_id of the wallet to change name and assurance level.
//				ASSURANCE_LEVEL : (string) "normal" or "strict"
//				WALLET_NAME     : (string) The wallets new name.
//

function cardano_update_wallet(string $wallet_id, string $assurance_level, string $wallet_name) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
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
// Function  : Delete a wallet and all its associated accounts.
// Parameters: 
//
//				WALLET_ID   : (string) The wallet_id of the wallet to delete.
//

function cardano_delete_wallet(string $wallet_id) {

		// SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";
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



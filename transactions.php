<?php
// GET TRANSACTION HISTORY
// Function  : Returns the transaction history, i.e the list of all the past transactions.
// Parameters: 
//
//          $wallet_id  : The wallet ID to get transactions from.
//          $account_idx: The account index of the account you want transaction history from.
//
// Settings  : 
//
//          $page    : (string) The page number you want to get accounts from.
//          $per_page: (string) The number of entries per page. Value: 1 MIN to 50 MAX
//

function cardano_get_all_transactions($wallet_id, $account_idx, $address, $page = "1", $per_page = "50", $filter_id = "", $filter_created = "", $filter_sort = "") {

	// HOST SETUP
	$host 		= "https://127.0.0.1";
	$port 		= "8090";
    
    // QUERY PARAMETERS
    $parameters = array(

    				"wallet_id" 	=> (string)$wallet_id,
    				"account_index" => (integer)$account_idx,
			        "page"			=> (integer)$page,
			        "per_page" 		=> (integer)$per_page, 
                    "address"       => (string)$address,
                    "id"            => (string)$filter_id,
                    "created_at"    => (string)$created_at,
                    "sort_by"       => (string)$sort_by,
			    );
    			
    $query 		= http_build_query($parameters);

    // API END POINT
	$end_point	= "/api/v1/transactions/?" . $query;

    // CARDANO CLIENT CERTIFICATE
    $cert_path	= "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // INIT CURL
    $curl = curl_init();
   
   	// OPTIONS
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
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
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }


    // BYE FELICIA
    curl_close($curl);

    // GIMME ALL YOUR DATA
    return $data;
}

// MAKE A TRANSACTION
// Function  : Generates a new transaction from the source to ONE target addresses.
// Settings:
//
//			$grouping_policy: "OptimizeForSecurity" or "OptimizeForHighThroughput"
//
// Parameters: 
//			
//			$post_fields: 
//				
//				array( 
//             		 "source" => array(
//                    				"accountIndex"  => Account index of the senders account.
//                    				"walletId"		=> Wallet ID in which the senders account is located.
//                  			);
//
//                  /* the destination is an array of arrays */
//
//					 "destination" => array(  
//                                          array(
//                    						    "address"	=> The address you are sending funds to.
//                    						    "amount"  	=> The amount you want to send. 
//                    					    );
//                                     );
//					 "groupingPolicy" => $grouping_policy
//             		 "spendingPassword" => The wallets spending password. 
//           	);
//

function cardano_make_transaction($account_idx, $wallet_id, $destination, $amount, $spending_password) {

	// HOST SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // SETTINGS
    $grouping_policy = "OptimizeForSecurity";

    // API END POINT
    $end_point  = "/api/v1/transactions";

    // CARDANO CLIENT CERTIFICATE
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // POST FIELDS
	$source_array 		= array(
    						"accountIndex" 		=> (integer)$account_idx,
    						"walletId"			=> (string)$wallet_id
    					);

	$destination_array  = array(
                                array(
                                    "amount"  => floatval($amount),
                                    "address" => (string)$destination
                                )
                        );

	$post_fields		= array(
		                    "source"     	 	=> (array)$source_array,
		                    "destinations"   	=> (array)$destination_array,
		                    "groupingPolicy"  	=> (string)$grouping_policy,
		                    "spendingPassword" 	=> (string)$spending_password
	                    );

    // INIT CURL
    $curl    = curl_init();
   
    $headers = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json; charset=utf-8"        
    );

    // OPTIONS
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($curl, CURLOPT_URL, $host.':'.$port.$end_point);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
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
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }
    
    //if(curl_exec($curl) === false) {
    //    throw new Exception('Curl error: ' . curl_error($curl));
    //}

    // BYE FELICIA
    curl_close($curl);

    // GIMME ALL YOUR DATA
    return $data;

}
 
// ESTIMATE TRANSACTION FEES
// Function  : Estimate the fees which would originate from the payment.
// Settings:
//
//			$grouping_policy: "OptimizeForSecurity" or "OptimizeForHighThroughput"
//
// Parameters: 
//			
//			$post_fields: 
//				
//				array( 
//             		 "source" => array(
//                    				"accountIndex"  => Account index of the senders account.
//                    				"walletId"		=> Wallet ID in which the senders account is located.
//                  			);
//
//					 "destination" => array(
//                                       array(
//                    						"amount"  	=> The amount you want to send. 
//                                          "address"   => The address you are sending funds to.
//                    					)
//                                    );
//					 "groupingPolicy"   => $grouping_policy
//             		 "spendingPassword" => The wallets spending password. 
//           	);
//

function cardano_estimate_transaction_fees($account_idx, $wallet_id, $destination, $amount, $spending_password) {

	// HOST SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // SETTINGS
    $grouping_policy = "OptimizeForHighThroughput";

    // API END POINT
    $end_point  = "/api/v1/transactions/fees";

    // CARDANO CLIENT CERTIFICATE
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // POST FIELDS
	$source_array 		= array(
    						"accountIndex"  	=> (integer)$account_idx,
    						"walletId"			=> (string)$wallet_id
    					);

	$destination_array 	= array(
                                array(
                                    "amount"  => floatval($amount),
                                    "address" => (string)$destination
                                )
    					);

    $post_fields = array(
		                    "source"     	 	=> (array)$source_array,
		                    "destinations"      => (array)$destination_array,
		                    "groupingPolicy"  	=> (string)$grouping_policy,
		                    "spendingPassword"	=> (string)$spending_password
	                    );
  
    if (empty($destination_array)) { var_dump($destination_array); }

    // INIT CURL
    $curl       = curl_init();
   
    $headers = array(
        "Cache-Control: no-cache",
        "Content-Type: application/json; charset=utf-8",
        "Accept: application/json; charset=utf-8"        
    );

    // OPTIONS
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
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
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }

    // BYE FELICIA
    curl_close($curl);

    // GIMME ALL YOUR DATA
    return $data;

}

// REDEEM CERTIFICATE
// Function  : Redeem a certificate
// Parameters: 
//          
//          $post_fields: array( 
//                   "redemptionCode"   => The redemption code associated with the Ada to redeem.
//                   "mnemonic"         => This must be provided for a paper certificate redemption in format: array("word", "word", "word", "word","word", "word","word", "word","word", "word","word", "word",)
//                   "spendingPassword" => This must match the password for the provided wallet ID and account index.
//                   "walletID"         => The wallet ID of the wallet to redeem the certificte into. 
//                   "accountIndex"     => The account index of the account within the wallet to redeem the certificte into.
//              );
//

function cardano_redeem_certificate($redemtion_code, $backup_phrase, $account_idx, $wallet_id, $spending_password) {

    // HOST SETUP
    $host       = "https://127.0.0.1";
    $port       = "8090";

    // API END POINT
    $end_point  = "/api/v1/transactions/certificates";

    // CARDANO CLIENT CERTIFICATE
    $cert_path  = "/var/www/1234ada/cardano-sl/state-wallet-mainnet/tls/client/client.pem";

    // POST FIELDS
    
    $post_fields = array(
                            "redemptionCode"    => (string)$redemtion_code,
                            "mnemonic"          => $backup_phrase,
                            "walletId"          => (string)$wallet_id,
                            "accountIndex"      => (integer)$account_idx,
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
    curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
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
        echo 'Curl error: ' . curl_error($ch);
        return false;
    }

    // BYE FELICIA
    curl_close($curl);

    // GIMME ALL YOUR DATA
    return $data;

}
<?php
// GET TRANSACTION HISTORY
// Function  : Get all the transactions from an account within a wallet.
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

function cardano_get_all_transactions($wallet_id, $account_idx) {

		// HOST SETUP
		$host 		= "https://127.0.0.1";
		$port 		= "8090";


        // SETTINGS
        $page = "1";
        $per_page = "50";

        // QUERY PARAMETERS
        $parameters = array(

        				"wallet_id" 	=> $wallet_id,
        				"account_index" => (integer)$account_idx,
				        "page"			=> (integer)$page,
				        "per_page" 		=> (integer)$per_page
				    );

        $query 		= http_build_query($parameters);

        // API END POINT
		$end_point	= "/api/v1/transactions/?" . $query;

	    // CARDANO CLIENT CERTIFICATE
        $cert_path	= "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

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

// MAKE A TRANSACTION
// Function  : Send ADA from $sender to $destination address.
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

//					 "destination" => array(
//                    						"address"	=> The address you are sending funds to.
//                    						"amount"  	=> The amount you want to send.
//                    					);
//					 "groupingPolicy" => $grouping_policy
//             		 "spendingPassword" => The wallets spending password.
//           	);
//

function cardano_make_transaction($account_idx, $wallet_id, $destination, $amount, $spending_password) {

		// HOST SETUP
        $host       = "https://127.0.0.1";
        $port       = "8090";

        // SETTINGS
        $grouping_policy = "OptimizeForHighThroughput";

        // API END POINT
        $end_point  = "/api/v1/transactions";

        // CARDANO CLIENT CERTIFICATE
        $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // POST FIELDS
		$source_array 		= array(
	    						"accountIndex" 		=> (integer)$account_idx,
	    						"walletId"			=> $wallet_id
	    					);

		$destination_array    = array(
                                    array(
                                        "amount"  => floatval($amount),
                                        "address" => $destination
                                    )
                            );

		$post_fields		= array(
			                    "source"     	 	=> $source_array,
			                    "destinations"   	=> $destination_array,
			                    "groupingPolicy"  	=> $grouping_policy,
			                    "spendingPassword" 	=> $spending_password
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

// ESTIMATE TRANSACTION FEES
// Function  : Estimate the transaction fees from $sender to $destination address.
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
//                    						"amount"  	=> The amount you want to send.
//                                          "address"   => The address you are sending funds to.
//                    					);
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
        $cert_path  = "./cardano-sl/state-wallet-mainnet/tls/client/client.pem";

        // POST FIELDS
		$source_array 		= array(
	    						"accountIndex"  	=> (integer)$account_idx,
	    						"walletId"			=> $wallet_id
	    					);

		$destination_array 	= array(
                                    array(
                                        "amount"  => floatval($amount),
                                        "address" => $destination
                                    )
        					);

        $post_fields = array(
			                    "source"     	 	=> $source_array,
			                    "destinations"      => $destination_array,
			                    "groupingPolicy"  	=> $grouping_policy,
			                    "spendingPassword"	=> $spending_password
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

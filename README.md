# Whats Going On Here?

A collection of functions to make interacting with the Cardano SL (v1) API easier when using PHP. 

# What You Need To Do:

You'll need to have [Cardano SL node](https://github.com/input-output-hk/cardano-sl/blob/develop/docs/how-to/build-cardano-sl-and-daedalus-from-source-code.md) running on your local server and you'll need to update the `$cert_path` var to reflect the location of your installation of Cardano SL. 

# What This Can Do For You:

**ADDRESSES**

1. Returns a list of the addresses.
2. Creates a new Address.
3. Returns interesting information about an address, if available and valid.

**WALLETS**

1. Returns a list of the available wallets.
2. Creates a new or restores an existing Wallet.
3. Updates the password for the given Wallet.
4. Returns the Wallet identified by the given walletId.
5. Update the Wallet identified by the given walletId.
6. Deletes the given Wallet and all its accounts.
7. Returns Utxo statistics for the Wallet identified by the given walletId.

**ACCOUNTS**

1. Retrieves a specific Account.
2. Update an Account for the given Wallet.
3. Deletes an Account.
4. Retrieves the full list of Accounts.
5. Creates a new Account for the given Wallet.
6. Retrieve only account's addresses.
7. Retrieve only account's balance.

**TRANSACTIONS**

1. Returns the transaction history, i.e the list of all the past transactions.
2. Generates a new transaction from the source to one or multiple target addresses.
3. Estimate the fees which would originate from the payment.
4. Redeem a certificate

**SETTINGS**

1. Retrieves the static settings for this node.

**INFO**

1. Retrieves the dynamic information for this node.

**INTERNAL** (NOT AVAILABLE YET)

1. Version of the next update (404 if none)
2. Apply the next available update
3. Discard and postpone the next available update
4. Clear wallet state and all associated secret keys
5. Import a Wallet from disk.

**WIP** (NOT AVAILABLE YET)
Note: I don't have a hardware wallet to test these functions with, so for now, I am not going to include them until I get the hardware to test them with. 

1. Check if this external wallet is presented in the node.
2. Deletes the given external wallet and all its accounts.
3. Creates a new or restores an existing external wallet (mobile client or hardware wallet).
4. Creates a new unsigned transaction (it will be signed externally).
5. Publish an externally-signed transaction.

# How to use it..

This in and of itself doesnt do anything. Its just a collection of functions for you to include in your project to make working with the Cardano SL (v1) API easier if your using PHP. Ive done my best to document and explain how to use each function to make it as easy as possible for anybody to use (even beginners!). 

# Why I Made This..

Some of you may have struggled with that "other" PHP Cardano API thats available here on Github, I know I did. It was incredibly frustrating that it barely works and I had to do so many tweaks on it to make it barely work that I decided that enough was enough and I would roll my own starting from scratch. So thats what we have here. This is a collection of individual PHP componenets that make up all the various Cardano API endpoints. If you want, please fork it and combine it all into a class or use it in something more formal.





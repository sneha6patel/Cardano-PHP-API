# Whats Going On Here?
A wrapper for the Cardano API (v1) in PHP. 


# What You Need To Do:

You'll need to have a Cardano-SL node running on your local server and you'll need to update the $cert_path var to reflect the location of your installation of Cardano-SL.

# What This Can Do For You:

ACCOUNTS: 
  Retrieves a specific Account.
  Update an Account for the given Wallet.
  Deletes an Account.
  Retrieves the full list of Accounts.
  Creates a new Account for the given Wallet.

ADDRESSES:
  Returns a list of the addresses.
  Creates a new Address.
  Returns interesting information about an address, if available and valid.

GETINFO:
  Retrieves the dynamic information for this node.

SETTINGS:
  Retrieves the static settings for this node.

TRANSACTIONS:
  Returns the transaction history, i.e the list of all the past transactions.
  Generates a new transaction from the source to one or multiple target addresses.
  Estimate the fees which would originate from the payment.

WALLETS:
  Returns a list of the available wallets.
  Creates a new or restores an existing Wallet.
  Updates the password for the given Wallet.
  Returns the Wallet identified by the given walletId.
  Update the Wallet identified by the given walletId.
  Deletes the given Wallet and all its accounts.


# Why I Made This..

Some of you may have struggled with that "other" PHP Cardano API thats available here on Github, I know I did. It was incredibly frustrating that it barely works and I had to do so many tweaks on it to make it barely work that I decided that enough was enough and I would roll my own starting from scratch. So thats what we have here. This is a collection of individual PHP componenets that make up all the various Cardano API endpoints. If you want, please fork it and combine it all into a class or use it in something more formal.

PS: Im putting the finishing touches on the rest of the scripts. So #comingsoon :)




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









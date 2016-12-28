<?php 
namespace Avalara;
/*
 * AvaTax API Client Library
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   AvaTax client libraries
 * @package    Avalara.AvaTaxClient
 * @author     Ted Spence <ted.spence@avalara.com>
 * @author     Bob Maidens <bob.maidens@avalara.com>
 * @copyright  2004-2016 Avalara, Inc.
 * @license    https://www.apache.org/licenses/LICENSE-2.0
 * @version    
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */

include_once __DIR__."/vendor/autoload.php";

use GuzzleHttp\Client;

/*****************************************************************************
 *                              API Section                                  *
 *****************************************************************************/

/**
 * An AvaTaxClient object that handles connectivity to the AvaTax v2 API server.
 */
class AvaTaxClient 
{
    /**
     * @var Client     The Guzzle client to use to connect to AvaTax.
     */
    private $client;

    /**
     * @var array      The authentication credentials to use to connect to AvaTax.
     */
    private $auth;

    /**
     * Construct a new AvaTaxClient 
     *
     * @param string $environment  Indicates which server to use; acceptable values are "sandbox" or "production"
     * @param string $appName      Specify the name of your application here.  Should not contain any semicolons.
     * @param string $appVersion   Specify the version number of your application here.  Should not contain any semicolons.
     * @param string $machineName  Specify the machine name of the machine on which this code is executing here.  Should not contain any semicolons.
     */
    public function __construct($environment, $appName, $appVersion, $machineName)
    {
        // Determine startup environment
        $env = 'https://rest.avatax.com';
        if ($environment == "sandbox") {
            $env = 'https://sandbox-rest.avatax.com';
        }

        // Configure the HTTP client
        $this->client = new Client([
            'base_url' => $env
        ]);
        
        // Set client options
        $this->client->setDefaultOption('headers', array(
            'Accept' => 'application/json',
            'X-Avalara-Client' => "{$appName}; {$appVersion}; PhpRestClient; ; {$machineName}"));
            
        // For some reason, Guzzle reports that 'https://sandbox-rest.avatax.com' is a self signed certificate, even though Verisign issued it
        if ($environment == "sandbox") {
            $this->client->setDefaultOption('verify', false);
        }
    }

    /**
     * Configure this client to use the specified username/password security settings
     *
     * @param string $username     The username for your AvaTax user account
     * @param string $password     The password for your AvaTax user account
     * @return AvaTaxClient
     */
    public function withSecurity($username, $password)
    {
        $this->auth = [$username, $password];
        return $this;
    }

    /**
     * Configure this client to use Account ID / License Key security
     *
     * @param int $accountId       The account ID for your AvaTax account
     * @param string $licenseKey   The private license key for your AvaTax account
     * @return AvaTaxClient
     */
    public function withLicenseKey($accountId, $licenseKey)
    {
        $this->auth = [$accountId, $licenseKey];
        return $this;
    }



    /**
     * Retrieve all accounts
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<AccountModel>
     */
    public function queryAccounts($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new account
     * 
     * @param AccountModel $model The account you wish to create.
     * @return AccountModel
     */
    public function createAccount($model)
    {
        $path = "/api/v2/accounts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve subscriptions for this account
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<SubscriptionModel>
     */
    public function listSubscriptionsByAccount($accountId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new subscription
     * 
     * @param List<SubscriptionModel> $model The subscription you wish to create.
     * @return List<SubscriptionModel>
     */
    public function createSubscriptions($accountId, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single subscription
     * 
     * @return SubscriptionModel
     */
    public function getSubscription($accountId, $id)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single subscription
     * 
     * @param SubscriptionModel $model The subscription you wish to update.
     * @return SubscriptionModel
     */
    public function updateSubscription($accountId, $id, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single subscription
     * 
     * @return ErrorResult
     */
    public function deleteSubscription($accountId, $id)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve users for this account
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<UserModel>
     */
    public function listUsersByAccount($accountId, $include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts/{$accountId}/users";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create new users
     * 
     * @param List<UserModel> $model The user or array of users you wish to create.
     * @return List<UserModel>
     */
    public function createUsers($accountId, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/users";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single user
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @return UserModel
     */
    public function getUser($id, $accountId, $include)
    {
        $path = "/api/v2/accounts/{$accountId}/users/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single user
     * 
     * @param UserModel $model The user object you wish to update.
     * @return UserModel
     */
    public function updateUser($id, $accountId, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/users/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single user
     * 
     * @return ErrorResult
     */
    public function deleteUser($id, $accountId)
    {
        $path = "/api/v2/accounts/{$accountId}/users/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve all entitlements for a single user
     * 
     * @return UserEntitlementModel
     */
    public function getUserEntitlements($id, $accountId)
    {
        $path = "/api/v2/accounts/{$accountId}/users/{$id}/entitlements";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single account
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @return AccountModel
     */
    public function getAccount($id, $include)
    {
        $path = "/api/v2/accounts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single account
     * 
     * @param AccountModel $model The account object you wish to update.
     * @return AccountModel
     */
    public function updateAccount($id, $model)
    {
        $path = "/api/v2/accounts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single account
     * 
     * @return ErrorResult
     */
    public function deleteAccount($id)
    {
        $path = "/api/v2/accounts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Reset this account's license key
     * 
     * @param ResetLicenseKeyModel $model A request confirming that you wish to reset the license key of this account.
     * @return LicenseKeyModel
     */
    public function accountResetLicenseKey($id, $model)
    {
        $path = "/api/v2/accounts/{$id}/resetlicensekey";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve geolocation information for a specified address
     * 
     * @param String $line1 Line 1
     * @param String $line2 Line 2
     * @param String $line3 Line 3
     * @param String $city City
     * @param String $region State / Province / Region
     * @param String $postalCode Postal Code / Zip Code
     * @param String $country Two character ISO 3166 Country Code (see /api/v2/definitions/countries for a full list)
     * @param Decimal? $latitude Geospatial latitude measurement
     * @param Decimal? $longitude Geospatial longitude measurement
     * @return AddressResolutionModel
     */
    public function resolveAddress($line1, $line2, $line3, $city, $region, $postalCode, $country, $latitude, $longitude)
    {
        $path = "/api/v2/addresses/resolve";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country, 'latitude' => $latitude, 'longitude' => $longitude],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve geolocation information for a specified address
     * 
     * @param AddressInfo $model The address to resolve
     * @return AddressResolutionModel
     */
    public function resolveAddressPost($model)
    {
        $path = "/api/v2/addresses/resolve";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all batches
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<BatchModel>
     */
    public function queryBatches($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/batches";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all companies
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<CompanyModel>
     */
    public function queryCompanies($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create new companies
     * 
     * @param List<CompanyModel> $model Either a single company object or an array of companies to create
     * @return List<CompanyModel>
     */
    public function createCompanies($model)
    {
        $path = "/api/v2/companies";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all transactions
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<TransactionModel>
     */
    public function listTransactionsByCompany($companyCode, $include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single transaction by code
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @return TransactionModel
     */
    public function getTransactionByCode($companyCode, $transactionCode, $include)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Correct a previously created transaction
     * 
     * @param AdjustTransactionModel $model The adjustment you wish to make
     * @return TransactionModel
     */
    public function adjustTransaction($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/adjust";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Change a transaction's code
     * 
     * @param ChangeTransactionCodeModel $model The code change request you wish to execute
     * @return TransactionModel
     */
    public function changeTransactionCode($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/changecode";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Commit a transaction for reporting
     * 
     * @param CommitTransactionModel $model The commit request you wish to execute
     * @return TransactionModel
     */
    public function commitTransaction($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/commit";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Perform multiple actions on a transaction
     * 
     * @param SettleTransactionModel $model The settle request containing the actions you wish to execute
     * @return TransactionModel
     */
    public function settleTransaction($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/settle";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Verify a transaction
     * 
     * @param VerifyTransactionModel $model The settle request you wish to execute
     * @return TransactionModel
     */
    public function verifyTransaction($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/verify";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Void a transaction
     * 
     * @param VoidTransactionModel $model The void request you wish to execute
     * @return TransactionModel
     */
    public function voidTransaction($companyCode, $transactionCode, $model)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}/void";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all batches for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<BatchModel>
     */
    public function listBatchesByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/batches";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new batch
     * 
     * @param List<BatchModel> $model The batch you wish to create.
     * @return List<BatchModel>
     */
    public function createBatches($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/batches";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single batch
     * 
     * @return BatchModel
     */
    public function getBatch($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/batches/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single batch
     * 
     * @param BatchModel $model The batch you wish to update.
     * @return BatchModel
     */
    public function updateBatch($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/batches/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single batch
     * 
     * @return ErrorResult
     */
    public function deleteBatch($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/batches/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve contacts for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<ContactModel>
     */
    public function listContactsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/contacts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new contact
     * 
     * @param List<ContactModel> $model The contacts you wish to create.
     * @return List<ContactModel>
     */
    public function createContacts($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/contacts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single contact
     * 
     * @return ContactModel
     */
    public function getContact($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/contacts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single contact
     * 
     * @param ContactModel $model The contact you wish to update.
     * @return ContactModel
     */
    public function updateContact($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/contacts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single contact
     * 
     * @return ErrorResult
     */
    public function deleteContact($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/contacts/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve a list of filings for a specific company, year, and month.
     * 
     * @return WorksheetModel
     */
    public function getFilings($companyId, $year, $month)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a list of filings for a specific company, year, month, and country.
     * 
     * @return WorksheetModel
     */
    public function getFilingsByCountry($companyId, $year, $month, $country)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/{$country}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a list of filings for a specific company, year, country, and region.
     * 
     * @return WorksheetModel
     */
    public function getFilingsByCountryRegion($companyId, $year, $month, $country, $region)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/{$country}/{$region}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a list of filings for a specific company, year, month, country, region, and return name.
     * 
     * @return WorksheetModel
     */
    public function getFilingsByReturnName($companyId, $year, $month, $country, $region, $returnName)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/{$country}/{$region}/{$returnName}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Rebuild a set of worksheets for a specific company based on year, month, country, and region.
     * 
     * @param RebuildWorksheetModel $model The commit request you wish to execute
     * @return WorksheetModel
     */
    public function rebuildFilingsByCountryRegion($companyId, $year, $month, $country, $region, $model)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/{$country}/{$region}/rebuild";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Rebuild a set of worksheets for a specific company based on year, month, and country.
     * 
     * @param RebuildWorksheetModel $model The commit request you wish to execute
     * @return WorksheetModel
     */
    public function rebuildFilingsByCountry($companyId, $year, $month, $country, $model)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/{$country}/rebuild";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Rebuild a set of worksheets for a specific company based on year and month.
     * 
     * @param RebuildWorksheetModel $model The commit request you wish to execute
     * @return WorksheetModel
     */
    public function rebuildFilings($companyId, $year, $month, $model)
    {
        $path = "/api/v2/companies/{$companyId}/filings/{$year}/{$month}/rebuild";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Check managed returns funding configuration for a company
     * 
     * @return List<FundingStatusModel>
     */
    public function listFundingRequestsByCompany($companyId)
    {
        $path = "/api/v2/companies/{$companyId}/funding";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Request managed returns funding setup for a company
     * 
     * @param FundingInitiateModel $model The funding initialization request
     * @return FundingStatusModel
     */
    public function createFundingRequest($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/funding/setup";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve items for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<ItemModel>
     */
    public function listItemsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/items";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new item
     * 
     * @param List<ItemModel> $model The item you wish to create.
     * @return List<ItemModel>
     */
    public function createItems($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/items";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single item
     * 
     * @return ItemModel
     */
    public function getItem($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/items/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single item
     * 
     * @param ItemModel $model The item object you wish to update.
     * @return ItemModel
     */
    public function updateItem($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/items/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single item
     * 
     * @return ErrorResult
     */
    public function deleteItem($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/items/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve locations for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<LocationModel>
     */
    public function listLocationsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/locations";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new location
     * 
     * @param List<LocationModel> $model The location you wish to create.
     * @return List<LocationModel>
     */
    public function createLocations($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/locations";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single location
     * 
     * @return LocationModel
     */
    public function getLocation($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single location
     * 
     * @param LocationModel $model The location you wish to update.
     * @return LocationModel
     */
    public function updateLocation($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single location
     * 
     * @return ErrorResult
     */
    public function deleteLocation($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Point of sale data file generation
     * 
     * @param DateTime? $date The date for which point-of-sale data would be calculated (today by default)
     * @param String $format The format of the file (JSON by default)
     * @param Int32? $partnerId If specified, requests a custom partner-formatted version of the file.
     * @param Boolean? $includeJurisCodes When true, the file will include jurisdiction codes in the result.
     * @return String
     */
    public function buildPointOfSaleDataForLocation($companyId, $id, $date, $format, $partnerId, $includeJurisCodes)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}/pointofsaledata";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['date' => $date, 'format' => $format, 'partnerId' => $partnerId, 'includeJurisCodes' => $includeJurisCodes],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Validate the location against local requirements
     * 
     * @return LocationValidationModel
     */
    public function validateLocation($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}/validate";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve nexus for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<NexusModel>
     */
    public function listNexusByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new nexus
     * 
     * @param List<NexusModel> $model The nexus you wish to create.
     * @return List<NexusModel>
     */
    public function createNexus($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single nexus
     * 
     * @return NexusModel
     */
    public function getNexus($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/nexus/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single nexus
     * 
     * @param NexusModel $model The nexus object you wish to update.
     * @return NexusModel
     */
    public function updateNexus($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/nexus/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single nexus
     * 
     * @return ErrorResult
     */
    public function deleteNexus($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/nexus/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve all settings for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<SettingModel>
     */
    public function listSettingsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/settings";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new setting
     * 
     * @param List<SettingModel> $model The setting you wish to create.
     * @return List<SettingModel>
     */
    public function createSettings($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/settings";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single setting
     * 
     * @return SettingModel
     */
    public function getSetting($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/settings/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single setting
     * 
     * @param SettingModel $model The setting you wish to update.
     * @return SettingModel
     */
    public function updateSetting($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/settings/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single setting
     * 
     * @return ErrorResult
     */
    public function deleteSetting($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/settings/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve tax codes for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<TaxCodeModel>
     */
    public function listTaxCodesByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax code
     * 
     * @param List<TaxCodeModel> $model The tax code you wish to create.
     * @return List<TaxCodeModel>
     */
    public function createTaxCodes($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single tax code
     * 
     * @return TaxCodeModel
     */
    public function getTaxCode($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single tax code
     * 
     * @param TaxCodeModel $model The tax code you wish to update.
     * @return TaxCodeModel
     */
    public function updateTaxCode($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single tax code
     * 
     * @return ErrorResult
     */
    public function deleteTaxCode($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve tax rules for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<TaxRuleModel>
     */
    public function listTaxRules($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax rule
     * 
     * @param List<TaxRuleModel> $model The tax rule you wish to create.
     * @return List<TaxRuleModel>
     */
    public function createTaxRules($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single tax rule
     * 
     * @return TaxRuleModel
     */
    public function getTaxRule($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single tax rule
     * 
     * @param TaxRuleModel $model The tax rule you wish to update.
     * @return TaxRuleModel
     */
    public function updateTaxRule($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single tax rule
     * 
     * @return ErrorResult
     */
    public function deleteTaxRule($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve UPCs for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<UPCModel>
     */
    public function listUPCsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/upcs";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new UPC
     * 
     * @param List<UPCModel> $model The UPC you wish to create.
     * @return List<UPCModel>
     */
    public function createUPCs($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/upcs";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single UPC
     * 
     * @return UPCModel
     */
    public function getUPC($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/upcs/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single UPC
     * 
     * @param UPCModel $model The UPC you wish to update.
     * @return UPCModel
     */
    public function updateUPC($companyId, $id, $model)
    {
        $path = "/api/v2/companies/{$companyId}/upcs/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single UPC
     * 
     * @return ErrorResult
     */
    public function deleteUPC($companyId, $id)
    {
        $path = "/api/v2/companies/{$companyId}/upcs/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve a single company
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @return CompanyModel
     */
    public function getCompany($id, $include)
    {
        $path = "/api/v2/companies/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Update a single company
     * 
     * @param CompanyModel $model The company object you wish to update.
     * @return CompanyModel
     */
    public function updateCompany($id, $model)
    {
        $path = "/api/v2/companies/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Delete a single company
     * 
     * @return ErrorResult
     */
    public function deleteCompanies($id)
    {
        $path = "/api/v2/companies/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Quick setup for a company with a single physical address
     * 
     * @param CompanyInitializationModel $model Information about the company you wish to create.
     * @return CompanyModel
     */
    public function companyInitialize($model)
    {
        $path = "/api/v2/companies/initialize";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all contacts
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<ContactModel>
     */
    public function queryContacts($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/contacts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 countries
     * 
     * @return FetchResult<IsoCountryModel>
     */
    public function listCountries()
    {
        $path = "/api/v2/definitions/countries";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 regions for a country
     * 
     * @return FetchResult<IsoRegionModel>
     */
    public function listRegionsByCountry($country)
    {
        $path = "/api/v2/definitions/countries/{$country}/regions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported entity use codes
     * 
     * @return FetchResult<EntityUseCodeModel>
     */
    public function listEntityUseCodes()
    {
        $path = "/api/v2/definitions/entityusecodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the list of questions that are required for a tax location
     * 
     * @param String $line1 The first line of this location's address.
     * @param String $line2 The second line of this location's address.
     * @param String $line3 The third line of this location's address.
     * @param String $city The city part of this location's address.
     * @param String $region The region, state, or province part of this location's address.
     * @param String $postalCode The postal code of this location's address.
     * @param String $country The country part of this location's address.
     * @param Decimal? $latitude Optionally identify the location via latitude/longitude instead of via address.
     * @param Decimal? $longitude Optionally identify the location via latitude/longitude instead of via address.
     * @return FetchResult<LocationQuestionModel>
     */
    public function listLocationQuestionsByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country, $latitude, $longitude)
    {
        $path = "/api/v2/definitions/locationquestions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country, 'latitude' => $latitude, 'longitude' => $longitude],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for all countries and regions.
     * 
     * @return FetchResult<NexusModel>
     */
    public function definitionsNexusGet()
    {
        $path = "/api/v2/definitions/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for a country.
     * 
     * @return FetchResult<NexusModel>
     */
    public function definitionsNexusByCountryGet($country)
    {
        $path = "/api/v2/definitions/nexus/{$country}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for a country and region.
     * 
     * @return FetchResult<NexusModel>
     */
    public function definitionsNexusByCountryByRegionGet($country, $region)
    {
        $path = "/api/v2/definitions/nexus/{$country}/{$region}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all nexus that apply to a specific address.
     * 
     * @param String $line1 The first address line portion of this address.
     * @param String $line2 The first address line portion of this address.
     * @param String $line3 The first address line portion of this address.
     * @param String $city The city portion of this address.
     * @param String $region The region, state, or province code portion of this address.
     * @param String $postalCode The postal code or zip code portion of this address.
     * @param String $country The two-character ISO-3166 code of the country portion of this address.
     * @return FetchResult<NexusModel>
     */
    public function listNexusByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $path = "/api/v2/definitions/nexus/byaddress";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported extra parameters for creating transactions.
     * 
     * @return FetchResult<ParameterModel>
     */
    public function listParameters()
    {
        $path = "/api/v2/definitions/parameters";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported permissions
     * 
     * @return FetchResult<String>
     */
    public function listPermissions()
    {
        $path = "/api/v2/definitions/permissions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 regions
     * 
     * @return FetchResult<IsoRegionModel>
     */
    public function listRegions()
    {
        $path = "/api/v2/definitions/regions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported permissions
     * 
     * @return FetchResult<SecurityRoleModel>
     */
    public function listSecurityRoles()
    {
        $path = "/api/v2/definitions/securityroles";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported subscription types
     * 
     * @return FetchResult<SubscriptionTypeModel>
     */
    public function listSubscriptionTypes()
    {
        $path = "/api/v2/definitions/subscriptiontypes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported tax authorities.
     * 
     * @return FetchResult<TaxAuthorityModel>
     */
    public function listTaxAuthorities()
    {
        $path = "/api/v2/definitions/taxauthorities";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported forms for each tax authority.
     * 
     * @return FetchResult<TaxAuthorityFormModel>
     */
    public function listTaxAuthorityForms()
    {
        $path = "/api/v2/definitions/taxauthorityforms";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported tax codes.
     * 
     * @return FetchResult<TaxCodeModel>
     */
    public function listTaxCodes()
    {
        $path = "/api/v2/definitions/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported tax code types.
     * 
     * @return TaxCodeTypesModel
     */
    public function listTaxCodeTypes()
    {
        $path = "/api/v2/definitions/taxcodetypes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve status about a funding setup request
     * 
     * @return FundingStatusModel
     */
    public function fundingRequestStatus($requestId)
    {
        $path = "/api/v2/fundingrequests/{$requestId}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Request the javascript for a funding setup widget
     * 
     * @return FundingStatusModel
     */
    public function activateFundingRequest($requestId)
    {
        $path = "/api/v2/fundingrequests/{$requestId}/widget";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all items
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<ItemModel>
     */
    public function queryItems($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/items";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all locations
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<LocationModel>
     */
    public function queryLocations($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/locations";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all nexus
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<NexusModel>
     */
    public function queryNexus($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Change Password
     * 
     * @param PasswordChangeModel $model An object containing your current password and the new password.
     * @return String
     */
    public function changePassword($model)
    {
        $path = "/api/v2/passwords";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Reset a user's password programmatically
     * 
     * @param SetPasswordModel $model The new password for this user
     * @return String
     */
    public function resetPassword($userId, $model)
    {
        $path = "/api/v2/passwords/{$userId}/reset";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Point of sale data file generation
     * 
     * @param PointOfSaleDataRequestModel $model Parameters about the desired file format and report format, specifying which company, locations and TaxCodes to include.
     * @return String
     */
    public function buildPointOfSaleDataFile($model)
    {
        $path = "/api/v2/pointofsaledata/build";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all settings
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<SettingModel>
     */
    public function querySettings($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/settings";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all subscriptions
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<SubscriptionModel>
     */
    public function querySubscriptions($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/subscriptions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all tax codes
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<TaxCodeModel>
     */
    public function queryTaxCodes($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve tax rates for a specified address
     * 
     * @param String $line1 The street address of the location.
     * @param String $line2 The street address of the location.
     * @param String $line3 The street address of the location.
     * @param String $city The city name of the location.
     * @param String $region The state or region of the location
     * @param String $postalCode The postal code of the location.
     * @param String $country The two letter ISO-3166 country code.
     * @return TaxRateModel
     */
    public function taxRatesByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $path = "/api/v2/taxrates/byaddress";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve tax rates for a specified country and postal code
     * 
     * @param String $country The two letter ISO-3166 country code.
     * @param String $postalCode The postal code of the location.
     * @return TaxRateModel
     */
    public function taxRatesByPostalCode($country, $postalCode)
    {
        $path = "/api/v2/taxrates/bypostalcode";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['country' => $country, 'postalCode' => $postalCode],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all tax rules
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<TaxRuleModel>
     */
    public function queryTaxRules($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxrules";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single transaction by ID
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @return TransactionModel
     */
    public function getTransactionById($id, $include)
    {
        $path = "/api/v2/transactions/{$id}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new transaction
     * 
     * @param CreateTransactionModel $model The transaction you wish to create
     * @return TransactionModel
     */
    public function createTransaction($model)
    {
        $path = "/api/v2/transactions/create";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => $model
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all UPCs
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<UPCModel>
     */
    public function queryUPCs($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/upcs";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all users
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult<UserModel>
     */
    public function queryUsers($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/users";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Tests connectivity and version of the service
     * 
     * @return PingResultModel
     */
    public function ping()
    {
        $path = "/api/v2/utilities/ping";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all services to which the current user is subscribed
     * 
     * @return SubscriptionModel
     */
    public function listMySubscriptions()
    {
        $path = "/api/v2/utilities/subscriptions";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Checks if the current user is subscribed to a specific service
     * 
     * @return SubscriptionModel
     */
    public function getMySubscription($serviceTypeId)
    {
        $path = "/api/v2/utilities/subscriptions/{$serviceTypeId}";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Make a single REST call to the AvaTax v2 API server
     *
     * @param string $apiUrl           The relative path of the API on the server
     * @param string $verb             The HTTP verb being used in this request
     * @param string $guzzleParams     The Guzzle parameters for this request, including query string and body parameters
     */
    private function restCall($apiUrl, $verb, $guzzleParams)
    {
        try {
            $request = $this->client->createRequest($verb, $apiUrl, $guzzleParams);
            $response = $this->client->send($request);
            $body = $response->getBody();
            return json_decode($body);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

/*****************************************************************************
 *                              Enumerated constants                         *
 *****************************************************************************/

/**
 * Lists of constants defined for use in AvaTax API calls
 */
class Constants
{

    /**
     * Enumerated values defined for AccountStatusId
     */

    const AccountStatusId_Inactive = "Inactive";
    const AccountStatusId_Active = "Active";
    const AccountStatusId_Test = "Test";
    const AccountStatusId_New = "New";

    /**
     * Enumerated values defined for SecurityRoleId
     */

    const SecurityRoleId_NoAccess = "NoAccess";
    const SecurityRoleId_SiteAdmin = "SiteAdmin";
    const SecurityRoleId_AccountOperator = "AccountOperator";
    const SecurityRoleId_AccountAdmin = "AccountAdmin";
    const SecurityRoleId_AccountUser = "AccountUser";
    const SecurityRoleId_SystemAdmin = "SystemAdmin";
    const SecurityRoleId_Registrar = "Registrar";
    const SecurityRoleId_CSPTester = "CSPTester";
    const SecurityRoleId_CSPAdmin = "CSPAdmin";
    const SecurityRoleId_SystemOperator = "SystemOperator";
    const SecurityRoleId_TechnicalSupportUser = "TechnicalSupportUser";
    const SecurityRoleId_TechnicalSupportAdmin = "TechnicalSupportAdmin";
    const SecurityRoleId_TreasuryUser = "TreasuryUser";
    const SecurityRoleId_TreasuryAdmin = "TreasuryAdmin";
    const SecurityRoleId_ComplianceUser = "ComplianceUser";
    const SecurityRoleId_ComplianceAdmin = "ComplianceAdmin";
    const SecurityRoleId_ProStoresOperator = "ProStoresOperator";
    const SecurityRoleId_CompanyUser = "CompanyUser";
    const SecurityRoleId_CompanyAdmin = "CompanyAdmin";
    const SecurityRoleId_ComplianceTempUser = "ComplianceTempUser";
    const SecurityRoleId_ComplianceRootUser = "ComplianceRootUser";
    const SecurityRoleId_ComplianceOperator = "ComplianceOperator";
    const SecurityRoleId_SSTAdmin = "SSTAdmin";

    /**
     * Enumerated values defined for PasswordStatusId
     */

    const PasswordStatusId_UserCannotChange = "UserCannotChange";
    const PasswordStatusId_UserCanChange = "UserCanChange";
    const PasswordStatusId_UserMustChange = "UserMustChange";

    /**
     * Enumerated values defined for ErrorCodeId
     */

    const ErrorCodeId_ServerConfiguration = "ServerConfiguration";
    const ErrorCodeId_AccountInvalidException = "AccountInvalidException";
    const ErrorCodeId_CompanyInvalidException = "CompanyInvalidException";
    const ErrorCodeId_EntityNotFoundError = "EntityNotFoundError";
    const ErrorCodeId_ValueRequiredError = "ValueRequiredError";
    const ErrorCodeId_RangeError = "RangeError";
    const ErrorCodeId_RangeCompareError = "RangeCompareError";
    const ErrorCodeId_RangeSetError = "RangeSetError";
    const ErrorCodeId_TaxpayerNumberRequired = "TaxpayerNumberRequired";
    const ErrorCodeId_CommonPassword = "CommonPassword";
    const ErrorCodeId_WeakPassword = "WeakPassword";
    const ErrorCodeId_StringLengthError = "StringLengthError";
    const ErrorCodeId_EmailValidationError = "EmailValidationError";
    const ErrorCodeId_EmailMissingError = "EmailMissingError";
    const ErrorCodeId_ParserFieldNameError = "ParserFieldNameError";
    const ErrorCodeId_ParserFieldValueError = "ParserFieldValueError";
    const ErrorCodeId_ParserSyntaxError = "ParserSyntaxError";
    const ErrorCodeId_ParserTooManyParametersError = "ParserTooManyParametersError";
    const ErrorCodeId_ParserUnterminatedValueError = "ParserUnterminatedValueError";
    const ErrorCodeId_DeleteUserSelfError = "DeleteUserSelfError";
    const ErrorCodeId_OldPasswordInvalid = "OldPasswordInvalid";
    const ErrorCodeId_CannotChangePassword = "CannotChangePassword";
    const ErrorCodeId_CannotChangeCompanyCode = "CannotChangeCompanyCode";
    const ErrorCodeId_AuthenticationException = "AuthenticationException";
    const ErrorCodeId_AuthorizationException = "AuthorizationException";
    const ErrorCodeId_ValidationException = "ValidationException";
    const ErrorCodeId_InactiveUserError = "InactiveUserError";
    const ErrorCodeId_AuthenticationIncomplete = "AuthenticationIncomplete";
    const ErrorCodeId_BasicAuthIncorrect = "BasicAuthIncorrect";
    const ErrorCodeId_IdentityServerError = "IdentityServerError";
    const ErrorCodeId_BearerTokenInvalid = "BearerTokenInvalid";
    const ErrorCodeId_ModelRequiredException = "ModelRequiredException";
    const ErrorCodeId_AccountExpiredException = "AccountExpiredException";
    const ErrorCodeId_VisibilityError = "VisibilityError";
    const ErrorCodeId_BearerTokenNotSupported = "BearerTokenNotSupported";
    const ErrorCodeId_InvalidSecurityRole = "InvalidSecurityRole";
    const ErrorCodeId_InvalidRegistrarAction = "InvalidRegistrarAction";
    const ErrorCodeId_RemoteServerError = "RemoteServerError";
    const ErrorCodeId_NoFilterCriteriaException = "NoFilterCriteriaException";
    const ErrorCodeId_OpenClauseException = "OpenClauseException";
    const ErrorCodeId_JsonFormatError = "JsonFormatError";
    const ErrorCodeId_UnhandledException = "UnhandledException";
    const ErrorCodeId_ReportingCompanyMustHaveContactsError = "ReportingCompanyMustHaveContactsError";
    const ErrorCodeId_CompanyProfileNotSet = "CompanyProfileNotSet";
    const ErrorCodeId_ModelStateInvalid = "ModelStateInvalid";
    const ErrorCodeId_DateRangeError = "DateRangeError";
    const ErrorCodeId_InvalidDateRangeError = "InvalidDateRangeError";
    const ErrorCodeId_DeleteInformation = "DeleteInformation";
    const ErrorCodeId_CannotCreateDeletedObjects = "CannotCreateDeletedObjects";
    const ErrorCodeId_CannotModifyDeletedObjects = "CannotModifyDeletedObjects";
    const ErrorCodeId_ReturnNameNotFound = "ReturnNameNotFound";
    const ErrorCodeId_InvalidAddressTypeAndCategory = "InvalidAddressTypeAndCategory";
    const ErrorCodeId_DefaultCompanyLocation = "DefaultCompanyLocation";
    const ErrorCodeId_InvalidCountry = "InvalidCountry";
    const ErrorCodeId_InvalidCountryRegion = "InvalidCountryRegion";
    const ErrorCodeId_BrazilValidationError = "BrazilValidationError";
    const ErrorCodeId_BrazilExemptValidationError = "BrazilExemptValidationError";
    const ErrorCodeId_BrazilPisCofinsError = "BrazilPisCofinsError";
    const ErrorCodeId_JurisdictionNotFoundError = "JurisdictionNotFoundError";
    const ErrorCodeId_MedicalExciseError = "MedicalExciseError";
    const ErrorCodeId_RateDependsTaxabilityError = "RateDependsTaxabilityError";
    const ErrorCodeId_RateDependsEuropeError = "RateDependsEuropeError";
    const ErrorCodeId_RateTypeNotSupported = "RateTypeNotSupported";
    const ErrorCodeId_CannotUpdateNestedObjects = "CannotUpdateNestedObjects";
    const ErrorCodeId_UPCCodeInvalidChars = "UPCCodeInvalidChars";
    const ErrorCodeId_UPCCodeInvalidLength = "UPCCodeInvalidLength";
    const ErrorCodeId_IncorrectPathError = "IncorrectPathError";
    const ErrorCodeId_InvalidJurisdictionType = "InvalidJurisdictionType";
    const ErrorCodeId_MustConfirmResetLicenseKey = "MustConfirmResetLicenseKey";
    const ErrorCodeId_DuplicateCompanyCode = "DuplicateCompanyCode";
    const ErrorCodeId_TINFormatError = "TINFormatError";
    const ErrorCodeId_DuplicateNexusError = "DuplicateNexusError";
    const ErrorCodeId_UnknownNexusError = "UnknownNexusError";
    const ErrorCodeId_ParentNexusNotFound = "ParentNexusNotFound";
    const ErrorCodeId_InvalidTaxCodeType = "InvalidTaxCodeType";
    const ErrorCodeId_CannotActivateCompany = "CannotActivateCompany";
    const ErrorCodeId_DuplicateEntityProperty = "DuplicateEntityProperty";
    const ErrorCodeId_BatchSalesAuditMustBeZippedError = "BatchSalesAuditMustBeZippedError";
    const ErrorCodeId_BatchZipMustContainOneFileError = "BatchZipMustContainOneFileError";
    const ErrorCodeId_BatchInvalidFileTypeError = "BatchInvalidFileTypeError";
    const ErrorCodeId_PointOfSaleFileSize = "PointOfSaleFileSize";
    const ErrorCodeId_PointOfSaleSetup = "PointOfSaleSetup";
    const ErrorCodeId_GetTaxError = "GetTaxError";
    const ErrorCodeId_AddressConflictException = "AddressConflictException";
    const ErrorCodeId_DocumentCodeConflict = "DocumentCodeConflict";
    const ErrorCodeId_MissingAddress = "MissingAddress";
    const ErrorCodeId_InvalidParameter = "InvalidParameter";
    const ErrorCodeId_InvalidParameterValue = "InvalidParameterValue";
    const ErrorCodeId_CompanyCodeConflict = "CompanyCodeConflict";
    const ErrorCodeId_DocumentFetchLimit = "DocumentFetchLimit";
    const ErrorCodeId_AddressIncomplete = "AddressIncomplete";
    const ErrorCodeId_AddressLocationNotFound = "AddressLocationNotFound";
    const ErrorCodeId_MissingLine = "MissingLine";
    const ErrorCodeId_BadDocumentFetch = "BadDocumentFetch";
    const ErrorCodeId_ServerUnreachable = "ServerUnreachable";
    const ErrorCodeId_SubscriptionRequired = "SubscriptionRequired";

    /**
     * Enumerated values defined for ErrorTargetCode
     */

    const ErrorTargetCode_Unknown = "Unknown";
    const ErrorTargetCode_HttpRequest = "HttpRequest";
    const ErrorTargetCode_HttpRequestHeaders = "HttpRequestHeaders";
    const ErrorTargetCode_IncorrectData = "IncorrectData";
    const ErrorTargetCode_AvaTaxApiServer = "AvaTaxApiServer";
    const ErrorTargetCode_AvalaraIdentityServer = "AvalaraIdentityServer";
    const ErrorTargetCode_CustomerAccountSetup = "CustomerAccountSetup";

    /**
     * Enumerated values defined for SeverityLevel
     */

    const SeverityLevel_Success = "Success";
    const SeverityLevel_Warning = "Warning";
    const SeverityLevel_Error = "Error";
    const SeverityLevel_Exception = "Exception";

    /**
     * Enumerated values defined for ResolutionQuality
     */

    const ResolutionQuality_NotCoded = "NotCoded";
    const ResolutionQuality_External = "External";
    const ResolutionQuality_CountryCentroid = "CountryCentroid";
    const ResolutionQuality_RegionCentroid = "RegionCentroid";
    const ResolutionQuality_PartialCentroid = "PartialCentroid";
    const ResolutionQuality_PostalCentroidGood = "PostalCentroidGood";
    const ResolutionQuality_PostalCentroidBetter = "PostalCentroidBetter";
    const ResolutionQuality_PostalCentroidBest = "PostalCentroidBest";
    const ResolutionQuality_Intersection = "Intersection";
    const ResolutionQuality_Interpolated = "Interpolated";
    const ResolutionQuality_Rooftop = "Rooftop";
    const ResolutionQuality_Constant = "Constant";

    /**
     * Enumerated values defined for JurisdictionType
     */

    const JurisdictionType_Country = "Country";
    const JurisdictionType_Composite = "Composite";
    const JurisdictionType_State = "State";
    const JurisdictionType_County = "County";
    const JurisdictionType_City = "City";
    const JurisdictionType_Special = "Special";

    /**
     * Enumerated values defined for BatchType
     */

    const BatchType_AvaCertUpdate = "AvaCertUpdate";
    const BatchType_AvaCertUpdateAll = "AvaCertUpdateAll";
    const BatchType_BatchMaintenance = "BatchMaintenance";
    const BatchType_CompanyLocationImport = "CompanyLocationImport";
    const BatchType_DocumentImport = "DocumentImport";
    const BatchType_ExemptCertImport = "ExemptCertImport";
    const BatchType_ItemImport = "ItemImport";
    const BatchType_SalesAuditExport = "SalesAuditExport";
    const BatchType_SstpTestDeckImport = "SstpTestDeckImport";
    const BatchType_TaxRuleImport = "TaxRuleImport";
    const BatchType_TransactionImport = "TransactionImport";
    const BatchType_UPCBulkImport = "UPCBulkImport";
    const BatchType_UPCValidationImport = "UPCValidationImport";

    /**
     * Enumerated values defined for BatchStatus
     */

    const BatchStatus_Waiting = "Waiting";
    const BatchStatus_SystemErrors = "SystemErrors";
    const BatchStatus_Cancelled = "Cancelled";
    const BatchStatus_Completed = "Completed";
    const BatchStatus_Creating = "Creating";
    const BatchStatus_Deleted = "Deleted";
    const BatchStatus_Errors = "Errors";
    const BatchStatus_Paused = "Paused";
    const BatchStatus_Processing = "Processing";

    /**
     * Enumerated values defined for RoundingLevelId
     */

    const RoundingLevelId_Line = "Line";
    const RoundingLevelId_Document = "Document";

    /**
     * Enumerated values defined for TaxDependencyLevelId
     */

    const TaxDependencyLevelId_Document = "Document";
    const TaxDependencyLevelId_State = "State";
    const TaxDependencyLevelId_TaxRegion = "TaxRegion";
    const TaxDependencyLevelId_Address = "Address";

    /**
     * Enumerated values defined for AddressTypeId
     */

    const AddressTypeId_Location = "Location";
    const AddressTypeId_Salesperson = "Salesperson";

    /**
     * Enumerated values defined for AddressCategoryId
     */

    const AddressCategoryId_Storefront = "Storefront";
    const AddressCategoryId_MainOffice = "MainOffice";
    const AddressCategoryId_Warehouse = "Warehouse";
    const AddressCategoryId_Salesperson = "Salesperson";
    const AddressCategoryId_Other = "Other";

    /**
     * Enumerated values defined for JurisTypeId
     */

    const JurisTypeId_STA = "STA";
    const JurisTypeId_CTY = "CTY";
    const JurisTypeId_CIT = "CIT";
    const JurisTypeId_STJ = "STJ";
    const JurisTypeId_CNT = "CNT";

    /**
     * Enumerated values defined for NexusTypeId
     */

    const NexusTypeId_None = "None";
    const NexusTypeId_SalesOrSellersUseTax = "SalesOrSellersUseTax";
    const NexusTypeId_SalesTax = "SalesTax";
    const NexusTypeId_SSTVolunteer = "SSTVolunteer";
    const NexusTypeId_SSTNonVolunteer = "SSTNonVolunteer";

    /**
     * Enumerated values defined for Sourcing
     */

    const Sourcing_Mixed = "Mixed";
    const Sourcing_Destination = "Destination";
    const Sourcing_Origin = "Origin";

    /**
     * Enumerated values defined for LocalNexusTypeId
     */

    const LocalNexusTypeId_Selected = "Selected";
    const LocalNexusTypeId_StateAdministered = "StateAdministered";
    const LocalNexusTypeId_All = "All";

    /**
     * Enumerated values defined for MatchingTaxType
     */

    const MatchingTaxType_All = "All";
    const MatchingTaxType_BothSalesAndUseTax = "BothSalesAndUseTax";
    const MatchingTaxType_ConsumerUseTax = "ConsumerUseTax";
    const MatchingTaxType_MedicalExcise = "MedicalExcise";
    const MatchingTaxType_Fee = "Fee";
    const MatchingTaxType_VATInputTax = "VATInputTax";
    const MatchingTaxType_VATNonrecoverableInputTax = "VATNonrecoverableInputTax";
    const MatchingTaxType_VATOutputTax = "VATOutputTax";
    const MatchingTaxType_Rental = "Rental";
    const MatchingTaxType_SalesTax = "SalesTax";
    const MatchingTaxType_UseTax = "UseTax";

    /**
     * Enumerated values defined for RateType
     */

    const RateType_ReducedA = "ReducedA";
    const RateType_ReducedB = "ReducedB";
    const RateType_Food = "Food";
    const RateType_General = "General";
    const RateType_IncreasedStandard = "IncreasedStandard";
    const RateType_LinenRental = "LinenRental";
    const RateType_Medical = "Medical";
    const RateType_Parking = "Parking";
    const RateType_SuperReduced = "SuperReduced";
    const RateType_ReducedR = "ReducedR";
    const RateType_Standard = "Standard";
    const RateType_Zero = "Zero";

    /**
     * Enumerated values defined for TaxRuleTypeId
     */

    const TaxRuleTypeId_RateRule = "RateRule";
    const TaxRuleTypeId_RateOverrideRule = "RateOverrideRule";
    const TaxRuleTypeId_BaseRule = "BaseRule";
    const TaxRuleTypeId_ExemptEntityRule = "ExemptEntityRule";
    const TaxRuleTypeId_ProductTaxabilityRule = "ProductTaxabilityRule";
    const TaxRuleTypeId_NexusRule = "NexusRule";

    /**
     * Enumerated values defined for ParameterBagDataType
     */

    const ParameterBagDataType_String = "String";
    const ParameterBagDataType_Boolean = "Boolean";
    const ParameterBagDataType_Numeric = "Numeric";

    /**
     * Enumerated values defined for WorksheetTypeId
     */

    const WorksheetTypeId_Original = "Original";
    const WorksheetTypeId_Amended = "Amended";
    const WorksheetTypeId_Test = "Test";

    /**
     * Enumerated values defined for WorksheetStatusId
     */

    const WorksheetStatusId_PendingApproval = "PendingApproval";
    const WorksheetStatusId_Dirty = "Dirty";
    const WorksheetStatusId_ApprovedToFile = "ApprovedToFile";
    const WorksheetStatusId_PendingFiling = "PendingFiling";
    const WorksheetStatusId_PendingFilingOnBehalf = "PendingFilingOnBehalf";
    const WorksheetStatusId_Filed = "Filed";
    const WorksheetStatusId_FiledOnBehalf = "FiledOnBehalf";
    const WorksheetStatusId_ReturnAccepted = "ReturnAccepted";
    const WorksheetStatusId_ReturnAcceptedOnBehalf = "ReturnAcceptedOnBehalf";
    const WorksheetStatusId_PaymentRemitted = "PaymentRemitted";
    const WorksheetStatusId_Voided = "Voided";
    const WorksheetStatusId_PendingReturn = "PendingReturn";
    const WorksheetStatusId_PendingReturnOnBehalf = "PendingReturnOnBehalf";
    const WorksheetStatusId_DoNotFile = "DoNotFile";
    const WorksheetStatusId_ReturnRejected = "ReturnRejected";
    const WorksheetStatusId_ReturnRejectedOnBehalf = "ReturnRejectedOnBehalf";
    const WorksheetStatusId_ApprovedToFileOnBehalf = "ApprovedToFileOnBehalf";

    /**
     * Enumerated values defined for FilingFrequencyId
     */

    const FilingFrequencyId_Monthly = "Monthly";
    const FilingFrequencyId_Quarterly = "Quarterly";
    const FilingFrequencyId_SemiAnnually = "SemiAnnually";
    const FilingFrequencyId_Annually = "Annually";
    const FilingFrequencyId_Bimonthly = "Bimonthly";
    const FilingFrequencyId_Occasional = "Occasional";
    const FilingFrequencyId_InverseQuarterly = "InverseQuarterly";

    /**
     * Enumerated values defined for FilingTypeId
     */

    const FilingTypeId_PaperReturn = "PaperReturn";
    const FilingTypeId_ElectronicReturn = "ElectronicReturn";
    const FilingTypeId_SER = "SER";
    const FilingTypeId_EFTPaper = "EFTPaper";
    const FilingTypeId_PhonePaper = "PhonePaper";
    const FilingTypeId_SignatureReady = "SignatureReady";
    const FilingTypeId_EfileCheck = "EfileCheck";

    /**
     * Enumerated values defined for PointOfSaleFileType
     */

    const PointOfSaleFileType_Json = "Json";
    const PointOfSaleFileType_Csv = "Csv";
    const PointOfSaleFileType_Xml = "Xml";

    /**
     * Enumerated values defined for DocumentStatus
     */

    const DocumentStatus_Temporary = "Temporary";
    const DocumentStatus_Saved = "Saved";
    const DocumentStatus_Posted = "Posted";
    const DocumentStatus_Committed = "Committed";
    const DocumentStatus_Cancelled = "Cancelled";
    const DocumentStatus_Adjusted = "Adjusted";
    const DocumentStatus_Queued = "Queued";
    const DocumentStatus_PendingApproval = "PendingApproval";
    const DocumentStatus_Any = "Any";

    /**
     * Enumerated values defined for DocumentType
     */

    const DocumentType_SalesOrder = "SalesOrder";
    const DocumentType_SalesInvoice = "SalesInvoice";
    const DocumentType_PurchaseOrder = "PurchaseOrder";
    const DocumentType_PurchaseInvoice = "PurchaseInvoice";
    const DocumentType_ReturnOrder = "ReturnOrder";
    const DocumentType_ReturnInvoice = "ReturnInvoice";
    const DocumentType_InventoryTransferOrder = "InventoryTransferOrder";
    const DocumentType_InventoryTransferInvoice = "InventoryTransferInvoice";
    const DocumentType_ReverseChargeOrder = "ReverseChargeOrder";
    const DocumentType_ReverseChargeInvoice = "ReverseChargeInvoice";
    const DocumentType_Any = "Any";

    /**
     * Enumerated values defined for TaxOverrideTypeId
     */

    const TaxOverrideTypeId_None = "None";
    const TaxOverrideTypeId_TaxAmount = "TaxAmount";
    const TaxOverrideTypeId_Exemption = "Exemption";
    const TaxOverrideTypeId_TaxDate = "TaxDate";
    const TaxOverrideTypeId_AccruedTaxAmount = "AccruedTaxAmount";

    /**
     * Enumerated values defined for AdjustmentReason
     */

    const AdjustmentReason_NotAdjusted = "NotAdjusted";
    const AdjustmentReason_SourcingIssue = "SourcingIssue";
    const AdjustmentReason_ReconciledWithGeneralLedger = "ReconciledWithGeneralLedger";
    const AdjustmentReason_ExemptCertApplied = "ExemptCertApplied";
    const AdjustmentReason_PriceAdjusted = "PriceAdjusted";
    const AdjustmentReason_ProductReturned = "ProductReturned";
    const AdjustmentReason_ProductExchanged = "ProductExchanged";
    const AdjustmentReason_BadDebt = "BadDebt";
    const AdjustmentReason_Other = "Other";
    const AdjustmentReason_Offline = "Offline";

    /**
     * Enumerated values defined for BoundaryLevel
     */

    const BoundaryLevel_Address = "Address";
    const BoundaryLevel_Zip9 = "Zip9";
    const BoundaryLevel_Zip5 = "Zip5";

    /**
     * Enumerated values defined for TaxType
     */

    const TaxType_ConsumerUse = "ConsumerUse";
    const TaxType_Excise = "Excise";
    const TaxType_Fee = "Fee";
    const TaxType_Input = "Input";
    const TaxType_Nonrecoverable = "Nonrecoverable";
    const TaxType_Output = "Output";
    const TaxType_Rental = "Rental";
    const TaxType_Sales = "Sales";
    const TaxType_Use = "Use";

    /**
     * Enumerated values defined for TransactionAddressType
     */


    /**
     * Enumerated values defined for ServiceMode
     */

    const ServiceMode_Automatic = "Automatic";
    const ServiceMode_Local = "Local";
    const ServiceMode_Remote = "Remote";

    /**
     * Enumerated values defined for TaxDebugLevel
     */

    const TaxDebugLevel_Normal = "Normal";
    const TaxDebugLevel_Diagnostic = "Diagnostic";

    /**
     * Enumerated values defined for TaxOverrideType
     */

    const TaxOverrideType_None = "None";
    const TaxOverrideType_TaxAmount = "TaxAmount";
    const TaxOverrideType_Exemption = "Exemption";
    const TaxOverrideType_TaxDate = "TaxDate";
    const TaxOverrideType_AccruedTaxAmount = "AccruedTaxAmount";
    const TaxOverrideType_DeriveTaxable = "DeriveTaxable";

    /**
     * Enumerated values defined for VoidReasonCode
     */

    const VoidReasonCode_Unspecified = "Unspecified";
    const VoidReasonCode_PostFailed = "PostFailed";
    const VoidReasonCode_DocDeleted = "DocDeleted";
    const VoidReasonCode_DocVoided = "DocVoided";
    const VoidReasonCode_AdjustmentCancelled = "AdjustmentCancelled";

    /**
     * Enumerated values defined for CompanyAccessLevel
     */

    const CompanyAccessLevel_None = "None";
    const CompanyAccessLevel_SingleCompany = "SingleCompany";
    const CompanyAccessLevel_SingleAccount = "SingleAccount";
    const CompanyAccessLevel_AllCompanies = "AllCompanies";

    /**
     * Enumerated values defined for AuthenticationTypeId
     */

    const AuthenticationTypeId_None = "None";
    const AuthenticationTypeId_UsernamePassword = "UsernamePassword";
    const AuthenticationTypeId_AccountIdLicenseKey = "AccountIdLicenseKey";
    const AuthenticationTypeId_OpenIdBearerToken = "OpenIdBearerToken";

    /**
     * Enumerated values defined for TransactionAddressType
     */

    const TransactionAddressType_ShipFrom = "ShipFrom";
    const TransactionAddressType_ShipTo = "ShipTo";
    const TransactionAddressType_PointOfOrderAcceptance = "PointOfOrderAcceptance";
    const TransactionAddressType_PointOfOrderOrigin = "PointOfOrderOrigin";
    const TransactionAddressType_SingleLocation = "SingleLocation";
}
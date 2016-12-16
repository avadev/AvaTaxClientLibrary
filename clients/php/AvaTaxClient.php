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
 * @version    2.16.12-30
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */

include_once __DIR__."/vendor/autoload.php";

use GuzzleHttp\Client;

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
            'X-Avalara-Client' => "{$appName}; {$appVersion}; PhpRestClient; 2.16.12-30; {$machineName}"));
            
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
     * Retrieve subscriptions for this account
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[SubscriptionModel]
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
     * Retrieve users for this account
     * 
     * @param String $include A comma separated list of child objects to return underneath the primary object.
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[UserModel]
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
     * @param Dictionary<string, string> $model The user object you wish to update.
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
     * Reset this account's license key
     * 
     * @param Dictionary<string, string> $model A request confirming that you wish to reset the license key of this account.
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
     * @param Dictionary<string, string> $model The address to resolve
     * @return AddressResolutionModel
     */
    public function resolveAddress($model)
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[BatchModel]
     */
    public function queryBatches($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/batches";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
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
     * @return FetchResult[CompanyModel]
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
     * @param Dictionary<string, string> $model Either a single company object or an array of companies to create
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
     * @return FetchResult[TransactionModel]
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
     * @param Dictionary<string, string> $model The adjustment you wish to make
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
     * @param Dictionary<string, string> $model The code change request you wish to execute
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
     * @param Dictionary<string, string> $model The commit request you wish to execute
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
     * @param Dictionary<string, string> $model The settle request containing the actions you wish to execute
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
     * @param Dictionary<string, string> $model The settle request you wish to execute
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
     * @param Dictionary<string, string> $model The void request you wish to execute
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[BatchModel]
     */
    public function listBatchesByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/batches";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new batch
     * 
     * @param Dictionary<string, string> $model The batch you wish to create.
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
     * @param Dictionary<string, string> $model The batch you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[ContactModel]
     */
    public function listContactsByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/contacts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new contact
     * 
     * @param Dictionary<string, string> $model The contacts you wish to create.
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
     * @param Dictionary<string, string> $model The contact you wish to update.
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
     * Retrieve items for this company
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[ItemModel]
     */
    public function listItemsByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/items";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new item
     * 
     * @param Dictionary<string, string> $model The item you wish to create.
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
     * @param Dictionary<string, string> $model The item object you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[LocationModel]
     */
    public function listLocationsByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/locations";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new location
     * 
     * @param Dictionary<string, string> $model The location you wish to create.
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
     * @param Dictionary<string, string> $model The location you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[NexusModel]
     */
    public function listNexusByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new nexus
     * 
     * @param Dictionary<string, string> $model The nexus you wish to create.
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
     * @param Dictionary<string, string> $model The nexus object you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[SettingModel]
     */
    public function listSettingsByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/settings";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new setting
     * 
     * @param Dictionary<string, string> $model The setting you wish to create.
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
     * @param Dictionary<string, string> $model The setting you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[TaxCodeModel]
     */
    public function listTaxCodesByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax code
     * 
     * @param Dictionary<string, string> $model The tax code you wish to create.
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
     * @param Dictionary<string, string> $model The tax code you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[TaxRuleModel]
     */
    public function listTaxRules($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax rule
     * 
     * @param Dictionary<string, string> $model The tax rule you wish to create.
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
     * @param Dictionary<string, string> $model The tax rule you wish to update.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[UPCModel]
     */
    public function listUPCsByCompany($companyId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/upcs";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new UPC
     * 
     * @param Dictionary<string, string> $model The UPC you wish to create.
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
     * @param Dictionary<string, string> $model The UPC you wish to update.
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
     * @param Dictionary<string, string> $model The company object you wish to update.
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
     * @param Dictionary<string, string> $model Information about the company you wish to create.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[ContactModel]
     */
    public function queryContacts($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/contacts";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 countries
     * 
     * @return FetchResult[IsoCountryModel]
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
     * @return FetchResult[IsoRegionModel]
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
     * @return FetchResult[LocationQuestionModel]
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
     * @return FetchResult[NexusModel]
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
     * @return FetchResult[NexusModel]
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
     * @return FetchResult[NexusModel]
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
     * @return FetchResult[NexusModel]
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
     * @return FetchResult[ParameterModel]
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
     * @return FetchResult[String]
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
     * @return FetchResult[IsoRegionModel]
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
     * @return FetchResult[SecurityRoleModel]
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
     * @return FetchResult[SubscriptionTypeModel]
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
     * @return FetchResult[TaxAuthorityModel]
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
     * @return FetchResult[TaxAuthorityFormModel]
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
     * @return FetchResult[TaxCodeModel]
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
     * Retrieve all items
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[ItemModel]
     */
    public function queryItems($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/items";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all locations
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[LocationModel]
     */
    public function queryLocations($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/locations";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all nexus
     * 
     * @param String $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[NexusModel]
     */
    public function queryNexus($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/nexus";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Point of sale data file generation
     * 
     * @param Dictionary<string, string> $model Parameters about the desired file format and report format, specifying which company, locations and TaxCodes to include.
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[SettingModel]
     */
    public function querySettings($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/settings";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
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
     * @return FetchResult[SubscriptionModel]
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[TaxCodeModel]
     */
    public function queryTaxCodes($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxcodes";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[TaxRuleModel]
     */
    public function queryTaxRules($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxrules";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
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
     * @param Dictionary<string, string> $model The transaction you wish to create
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
     * @param Int32? $top If nonzero, return no more than this number of results.
     * @param Int32? $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param String $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult[UPCModel]
     */
    public function queryUPCs($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/upcs";
        $guzzleParams = [
            'auth' => $this->auth,
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
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
     * @return FetchResult[UserModel]
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

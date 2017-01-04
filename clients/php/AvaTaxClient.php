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
     * @param  string          $username   The username for your AvaTax user account
     * @param  string          $password   The password for your AvaTax user account
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
     * @param  int             $accountId      The account ID for your AvaTax account
     * @param  string          $licenseKey     The private license key for your AvaTax account
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
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryAccounts($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve subscriptions for this account
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listSubscriptionsByAccount($accountId, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new subscription
     * 
     * @param SubscriptionModel[] $model The subscription you wish to create.
     * @return SubscriptionModel[]
     */
    public function createSubscriptions($accountId, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/subscriptions";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve users for this account
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listUsersByAccount($accountId, $include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/accounts/{$accountId}/users";
        $guzzleParams = [
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create new users
     * 
     * @param UserModel[] $model The user or array of users you wish to create.
     * @return UserModel[]
     */
    public function createUsers($accountId, $model)
    {
        $path = "/api/v2/accounts/{$accountId}/users";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve a single user
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @return UserModel
     */
    public function getUser($id, $accountId, $include)
    {
        $path = "/api/v2/accounts/{$accountId}/users/{$id}";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single account
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @return AccountModel
     */
    public function getAccount($id, $include)
    {
        $path = "/api/v2/accounts/{$id}";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve geolocation information for a specified address
     * 
     * @param string $line1 Line 1
     * @param string $line2 Line 2
     * @param string $line3 Line 3
     * @param string $city City
     * @param string $region State / Province / Region
     * @param string $postalCode Postal Code / Zip Code
     * @param string $country Two character ISO 3166 Country Code (see /api/v2/definitions/countries for a full list)
     * @param float $latitude Geospatial latitude measurement
     * @param float $longitude Geospatial longitude measurement
     * @return AddressResolutionModel
     */
    public function resolveAddress($line1, $line2, $line3, $city, $region, $postalCode, $country, $latitude, $longitude)
    {
        $path = "/api/v2/addresses/resolve";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all batches
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryBatches($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/batches";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all companies
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryCompanies($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies";
        $guzzleParams = [
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create new companies
     * 
     * @param CompanyModel[] $model Either a single company object or an array of companies to create
     * @return CompanyModel[]
     */
    public function createCompanies($model)
    {
        $path = "/api/v2/companies";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all transactions
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listTransactionsByCompany($companyCode, $include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions";
        $guzzleParams = [
            'query' => ['$include' => $include, '$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single transaction by code
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @return TransactionModel
     */
    public function getTransactionByCode($companyCode, $transactionCode, $include)
    {
        $path = "/api/v2/companies/{$companyCode}/transactions/{$transactionCode}";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all batches for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listBatchesByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/batches";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new batch
     * 
     * @param BatchModel[] $model The batch you wish to create.
     * @return BatchModel[]
     */
    public function createBatches($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/batches";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve contacts for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listContactsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/contacts";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new contact
     * 
     * @param ContactModel[] $model The contacts you wish to create.
     * @return ContactModel[]
     */
    public function createContacts($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/contacts";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Check managed returns funding configuration for a company
     * 
     * @return FundingStatusModel[]
     */
    public function listFundingRequestsByCompany($companyId)
    {
        $path = "/api/v2/companies/{$companyId}/funding";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve items for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listItemsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/items";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new item
     * 
     * @param ItemModel[] $model The item you wish to create.
     * @return ItemModel[]
     */
    public function createItems($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/items";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve locations for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listLocationsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/locations";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new location
     * 
     * @param LocationModel[] $model The location you wish to create.
     * @return LocationModel[]
     */
    public function createLocations($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/locations";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Point of sale data file generation
     * 
     * @param string $date The date for which point-of-sale data would be calculated (today by default)
     * @param string $format The format of the file (JSON by default)
     * @param int $partnerId If specified, requests a custom partner-formatted version of the file.
     * @param boolean $includeJurisCodes When true, the file will include jurisdiction codes in the result.
     * @return string
     */
    public function buildPointOfSaleDataForLocation($companyId, $id, $date, $format, $partnerId, $includeJurisCodes)
    {
        $path = "/api/v2/companies/{$companyId}/locations/{$id}/pointofsaledata";
        $guzzleParams = [
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve nexus for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listNexusByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/nexus";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new nexus
     * 
     * @param NexusModel[] $model The nexus you wish to create.
     * @return NexusModel[]
     */
    public function createNexus($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/nexus";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve all settings for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listSettingsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/settings";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new setting
     * 
     * @param SettingModel[] $model The setting you wish to create.
     * @return SettingModel[]
     */
    public function createSettings($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/settings";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve tax codes for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listTaxCodesByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax code
     * 
     * @param TaxCodeModel[] $model The tax code you wish to create.
     * @return TaxCodeModel[]
     */
    public function createTaxCodes($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxcodes";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve tax rules for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listTaxRules($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new tax rule
     * 
     * @param TaxRuleModel[] $model The tax rule you wish to create.
     * @return TaxRuleModel[]
     */
    public function createTaxRules($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/taxrules";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve UPCs for this company
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function listUPCsByCompany($companyId, $filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/companies/{$companyId}/upcs";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Create a new UPC
     * 
     * @param UPCModel[] $model The UPC you wish to create.
     * @return UPCModel[]
     */
    public function createUPCs($companyId, $model)
    {
        $path = "/api/v2/companies/{$companyId}/upcs";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'DELETE', $guzzleParams);
    }

    /**
     * Retrieve a single company
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @return CompanyModel
     */
    public function getCompany($id, $include)
    {
        $path = "/api/v2/companies/{$id}";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all contacts
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryContacts($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/contacts";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 countries
     * 
     * @return FetchResult
     */
    public function listCountries()
    {
        $path = "/api/v2/definitions/countries";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 regions for a country
     * 
     * @return FetchResult
     */
    public function listRegionsByCountry($country)
    {
        $path = "/api/v2/definitions/countries/{$country}/regions";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported entity use codes
     * 
     * @return FetchResult
     */
    public function listEntityUseCodes()
    {
        $path = "/api/v2/definitions/entityusecodes";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the list of questions that are required for a tax location
     * 
     * @param string $line1 The first line of this location's address.
     * @param string $line2 The second line of this location's address.
     * @param string $line3 The third line of this location's address.
     * @param string $city The city part of this location's address.
     * @param string $region The region, state, or province part of this location's address.
     * @param string $postalCode The postal code of this location's address.
     * @param string $country The country part of this location's address.
     * @param float $latitude Optionally identify the location via latitude/longitude instead of via address.
     * @param float $longitude Optionally identify the location via latitude/longitude instead of via address.
     * @return FetchResult
     */
    public function listLocationQuestionsByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country, $latitude, $longitude)
    {
        $path = "/api/v2/definitions/locationquestions";
        $guzzleParams = [
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country, 'latitude' => $latitude, 'longitude' => $longitude],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for all countries and regions.
     * 
     * @return FetchResult
     */
    public function definitionsNexusGet()
    {
        $path = "/api/v2/definitions/nexus";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for a country.
     * 
     * @return FetchResult
     */
    public function definitionsNexusByCountryGet($country)
    {
        $path = "/api/v2/definitions/nexus/{$country}";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported nexus for a country and region.
     * 
     * @return FetchResult
     */
    public function definitionsNexusByCountryByRegionGet($country, $region)
    {
        $path = "/api/v2/definitions/nexus/{$country}/{$region}";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all nexus that apply to a specific address.
     * 
     * @param string $line1 The first address line portion of this address.
     * @param string $line2 The first address line portion of this address.
     * @param string $line3 The first address line portion of this address.
     * @param string $city The city portion of this address.
     * @param string $region The region, state, or province code portion of this address.
     * @param string $postalCode The postal code or zip code portion of this address.
     * @param string $country The two-character ISO-3166 code of the country portion of this address.
     * @return FetchResult
     */
    public function listNexusByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $path = "/api/v2/definitions/nexus/byaddress";
        $guzzleParams = [
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported extra parameters for creating transactions.
     * 
     * @return FetchResult
     */
    public function listParameters()
    {
        $path = "/api/v2/definitions/parameters";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported permissions
     * 
     * @return FetchResult
     */
    public function listPermissions()
    {
        $path = "/api/v2/definitions/permissions";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * List all ISO 3166 regions
     * 
     * @return FetchResult
     */
    public function listRegions()
    {
        $path = "/api/v2/definitions/regions";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported permissions
     * 
     * @return FetchResult
     */
    public function listSecurityRoles()
    {
        $path = "/api/v2/definitions/securityroles";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported subscription types
     * 
     * @return FetchResult
     */
    public function listSubscriptionTypes()
    {
        $path = "/api/v2/definitions/subscriptiontypes";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported tax authorities.
     * 
     * @return FetchResult
     */
    public function listTaxAuthorities()
    {
        $path = "/api/v2/definitions/taxauthorities";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported forms for each tax authority.
     * 
     * @return FetchResult
     */
    public function listTaxAuthorityForms()
    {
        $path = "/api/v2/definitions/taxauthorityforms";
        $guzzleParams = [
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve the full list of Avalara-supported tax codes.
     * 
     * @return FetchResult
     */
    public function listTaxCodes()
    {
        $path = "/api/v2/definitions/taxcodes";
        $guzzleParams = [
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
            'query' => [],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all items
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryItems($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/items";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all locations
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryLocations($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/locations";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all nexus
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryNexus($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/nexus";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Change Password
     * 
     * @param PasswordChangeModel $model An object containing your current password and the new password.
     * @return string
     */
    public function changePassword($model)
    {
        $path = "/api/v2/passwords";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'PUT', $guzzleParams);
    }

    /**
     * Reset a user's password programmatically
     * 
     * @param SetPasswordModel $model The new password for this user
     * @return string
     */
    public function resetPassword($userId, $model)
    {
        $path = "/api/v2/passwords/{$userId}/reset";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Point of sale data file generation
     * 
     * @param PointOfSaleDataRequestModel $model Parameters about the desired file format and report format, specifying which company, locations and TaxCodes to include.
     * @return string
     */
    public function buildPointOfSaleDataFile($model)
    {
        $path = "/api/v2/pointofsaledata/build";
        $guzzleParams = [
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all settings
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function querySettings($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/settings";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all subscriptions
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function querySubscriptions($filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/subscriptions";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all tax codes
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryTaxCodes($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxcodes";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve tax rates for a specified address
     * 
     * @param string $line1 The street address of the location.
     * @param string $line2 The street address of the location.
     * @param string $line3 The street address of the location.
     * @param string $city The city name of the location.
     * @param string $region The state or region of the location
     * @param string $postalCode The postal code of the location.
     * @param string $country The two letter ISO-3166 country code.
     * @return TaxRateModel
     */
    public function taxRatesByAddress($line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $path = "/api/v2/taxrates/byaddress";
        $guzzleParams = [
            'query' => ['line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'city' => $city, 'region' => $region, 'postalCode' => $postalCode, 'country' => $country],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve tax rates for a specified country and postal code
     * 
     * @param string $country The two letter ISO-3166 country code.
     * @param string $postalCode The postal code of the location.
     * @return TaxRateModel
     */
    public function taxRatesByPostalCode($country, $postalCode)
    {
        $path = "/api/v2/taxrates/bypostalcode";
        $guzzleParams = [
            'query' => ['country' => $country, 'postalCode' => $postalCode],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all tax rules
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryTaxRules($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/taxrules";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve a single transaction by ID
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @return TransactionModel
     */
    public function getTransactionById($id, $include)
    {
        $path = "/api/v2/transactions/{$id}";
        $guzzleParams = [
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
            'query' => [],
            'body' => json_encode($model)
        ];
        return $this->restCall($path, 'POST', $guzzleParams);
    }

    /**
     * Retrieve all UPCs
     * 
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryUPCs($filter, $include, $top, $skip, $orderBy)
    {
        $path = "/api/v2/upcs";
        $guzzleParams = [
            'query' => ['$filter' => $filter, '$include' => $include, '$top' => $top, '$skip' => $skip, '$orderBy' => $orderBy],
            'body' => null
        ];
        return $this->restCall($path, 'GET', $guzzleParams);
    }

    /**
     * Retrieve all users
     * 
     * @param string $include A comma separated list of child objects to return underneath the primary object.
     * @param string $filter A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .
     * @param int $top If nonzero, return no more than this number of results.
     * @param int $skip A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @param string $orderBy A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.
     * @return FetchResult
     */
    public function queryUsers($include, $filter, $top, $skip, $orderBy)
    {
        $path = "/api/v2/users";
        $guzzleParams = [
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
        // Set authentication on the parameters
        if (!isset($guzzleParams['auth'])){
            $guzzleParams['auth'] = $this->auth;
        }
    
        // Contact the server
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

    const ACCOUNTSTATUSID_INACTIVE = "Inactive";
    const ACCOUNTSTATUSID_ACTIVE = "Active";
    const ACCOUNTSTATUSID_TEST = "Test";
    const ACCOUNTSTATUSID_NEW = "New";

    /**
     * Enumerated values defined for SecurityRoleId
     */

    const SECURITYROLEID_NOACCESS = "NoAccess";
    const SECURITYROLEID_SITEADMIN = "SiteAdmin";
    const SECURITYROLEID_ACCOUNTOPERATOR = "AccountOperator";
    const SECURITYROLEID_ACCOUNTADMIN = "AccountAdmin";
    const SECURITYROLEID_ACCOUNTUSER = "AccountUser";
    const SECURITYROLEID_SYSTEMADMIN = "SystemAdmin";
    const SECURITYROLEID_REGISTRAR = "Registrar";
    const SECURITYROLEID_CSPTESTER = "CSPTester";
    const SECURITYROLEID_CSPADMIN = "CSPAdmin";
    const SECURITYROLEID_SYSTEMOPERATOR = "SystemOperator";
    const SECURITYROLEID_TECHNICALSUPPORTUSER = "TechnicalSupportUser";
    const SECURITYROLEID_TECHNICALSUPPORTADMIN = "TechnicalSupportAdmin";
    const SECURITYROLEID_TREASURYUSER = "TreasuryUser";
    const SECURITYROLEID_TREASURYADMIN = "TreasuryAdmin";
    const SECURITYROLEID_COMPLIANCEUSER = "ComplianceUser";
    const SECURITYROLEID_COMPLIANCEADMIN = "ComplianceAdmin";
    const SECURITYROLEID_PROSTORESOPERATOR = "ProStoresOperator";
    const SECURITYROLEID_COMPANYUSER = "CompanyUser";
    const SECURITYROLEID_COMPANYADMIN = "CompanyAdmin";
    const SECURITYROLEID_COMPLIANCETEMPUSER = "ComplianceTempUser";
    const SECURITYROLEID_COMPLIANCEROOTUSER = "ComplianceRootUser";
    const SECURITYROLEID_COMPLIANCEOPERATOR = "ComplianceOperator";
    const SECURITYROLEID_SSTADMIN = "SSTAdmin";

    /**
     * Enumerated values defined for PasswordStatusId
     */

    const PASSWORDSTATUSID_USERCANNOTCHANGE = "UserCannotChange";
    const PASSWORDSTATUSID_USERCANCHANGE = "UserCanChange";
    const PASSWORDSTATUSID_USERMUSTCHANGE = "UserMustChange";

    /**
     * Enumerated values defined for ErrorCodeId
     */

    const ERRORCODEID_SERVERCONFIGURATION = "ServerConfiguration";
    const ERRORCODEID_ACCOUNTINVALIDEXCEPTION = "AccountInvalidException";
    const ERRORCODEID_COMPANYINVALIDEXCEPTION = "CompanyInvalidException";
    const ERRORCODEID_ENTITYNOTFOUNDERROR = "EntityNotFoundError";
    const ERRORCODEID_VALUEREQUIREDERROR = "ValueRequiredError";
    const ERRORCODEID_RANGEERROR = "RangeError";
    const ERRORCODEID_RANGECOMPAREERROR = "RangeCompareError";
    const ERRORCODEID_RANGESETERROR = "RangeSetError";
    const ERRORCODEID_TAXPAYERNUMBERREQUIRED = "TaxpayerNumberRequired";
    const ERRORCODEID_COMMONPASSWORD = "CommonPassword";
    const ERRORCODEID_WEAKPASSWORD = "WeakPassword";
    const ERRORCODEID_STRINGLENGTHERROR = "StringLengthError";
    const ERRORCODEID_EMAILVALIDATIONERROR = "EmailValidationError";
    const ERRORCODEID_EMAILMISSINGERROR = "EmailMissingError";
    const ERRORCODEID_PARSERFIELDNAMEERROR = "ParserFieldNameError";
    const ERRORCODEID_PARSERFIELDVALUEERROR = "ParserFieldValueError";
    const ERRORCODEID_PARSERSYNTAXERROR = "ParserSyntaxError";
    const ERRORCODEID_PARSERTOOMANYPARAMETERSERROR = "ParserTooManyParametersError";
    const ERRORCODEID_PARSERUNTERMINATEDVALUEERROR = "ParserUnterminatedValueError";
    const ERRORCODEID_DELETEUSERSELFERROR = "DeleteUserSelfError";
    const ERRORCODEID_OLDPASSWORDINVALID = "OldPasswordInvalid";
    const ERRORCODEID_CANNOTCHANGEPASSWORD = "CannotChangePassword";
    const ERRORCODEID_CANNOTCHANGECOMPANYCODE = "CannotChangeCompanyCode";
    const ERRORCODEID_AUTHENTICATIONEXCEPTION = "AuthenticationException";
    const ERRORCODEID_AUTHORIZATIONEXCEPTION = "AuthorizationException";
    const ERRORCODEID_VALIDATIONEXCEPTION = "ValidationException";
    const ERRORCODEID_INACTIVEUSERERROR = "InactiveUserError";
    const ERRORCODEID_AUTHENTICATIONINCOMPLETE = "AuthenticationIncomplete";
    const ERRORCODEID_BASICAUTHINCORRECT = "BasicAuthIncorrect";
    const ERRORCODEID_IDENTITYSERVERERROR = "IdentityServerError";
    const ERRORCODEID_BEARERTOKENINVALID = "BearerTokenInvalid";
    const ERRORCODEID_MODELREQUIREDEXCEPTION = "ModelRequiredException";
    const ERRORCODEID_ACCOUNTEXPIREDEXCEPTION = "AccountExpiredException";
    const ERRORCODEID_VISIBILITYERROR = "VisibilityError";
    const ERRORCODEID_BEARERTOKENNOTSUPPORTED = "BearerTokenNotSupported";
    const ERRORCODEID_INVALIDSECURITYROLE = "InvalidSecurityRole";
    const ERRORCODEID_INVALIDREGISTRARACTION = "InvalidRegistrarAction";
    const ERRORCODEID_REMOTESERVERERROR = "RemoteServerError";
    const ERRORCODEID_NOFILTERCRITERIAEXCEPTION = "NoFilterCriteriaException";
    const ERRORCODEID_OPENCLAUSEEXCEPTION = "OpenClauseException";
    const ERRORCODEID_JSONFORMATERROR = "JsonFormatError";
    const ERRORCODEID_UNHANDLEDEXCEPTION = "UnhandledException";
    const ERRORCODEID_REPORTINGCOMPANYMUSTHAVECONTACTSERROR = "ReportingCompanyMustHaveContactsError";
    const ERRORCODEID_COMPANYPROFILENOTSET = "CompanyProfileNotSet";
    const ERRORCODEID_MODELSTATEINVALID = "ModelStateInvalid";
    const ERRORCODEID_DATERANGEERROR = "DateRangeError";
    const ERRORCODEID_INVALIDDATERANGEERROR = "InvalidDateRangeError";
    const ERRORCODEID_DELETEINFORMATION = "DeleteInformation";
    const ERRORCODEID_CANNOTCREATEDELETEDOBJECTS = "CannotCreateDeletedObjects";
    const ERRORCODEID_CANNOTMODIFYDELETEDOBJECTS = "CannotModifyDeletedObjects";
    const ERRORCODEID_RETURNNAMENOTFOUND = "ReturnNameNotFound";
    const ERRORCODEID_INVALIDADDRESSTYPEANDCATEGORY = "InvalidAddressTypeAndCategory";
    const ERRORCODEID_DEFAULTCOMPANYLOCATION = "DefaultCompanyLocation";
    const ERRORCODEID_INVALIDCOUNTRY = "InvalidCountry";
    const ERRORCODEID_INVALIDCOUNTRYREGION = "InvalidCountryRegion";
    const ERRORCODEID_BRAZILVALIDATIONERROR = "BrazilValidationError";
    const ERRORCODEID_BRAZILEXEMPTVALIDATIONERROR = "BrazilExemptValidationError";
    const ERRORCODEID_BRAZILPISCOFINSERROR = "BrazilPisCofinsError";
    const ERRORCODEID_JURISDICTIONNOTFOUNDERROR = "JurisdictionNotFoundError";
    const ERRORCODEID_MEDICALEXCISEERROR = "MedicalExciseError";
    const ERRORCODEID_RATEDEPENDSTAXABILITYERROR = "RateDependsTaxabilityError";
    const ERRORCODEID_RATEDEPENDSEUROPEERROR = "RateDependsEuropeError";
    const ERRORCODEID_RATETYPENOTSUPPORTED = "RateTypeNotSupported";
    const ERRORCODEID_CANNOTUPDATENESTEDOBJECTS = "CannotUpdateNestedObjects";
    const ERRORCODEID_UPCCODEINVALIDCHARS = "UPCCodeInvalidChars";
    const ERRORCODEID_UPCCODEINVALIDLENGTH = "UPCCodeInvalidLength";
    const ERRORCODEID_INCORRECTPATHERROR = "IncorrectPathError";
    const ERRORCODEID_INVALIDJURISDICTIONTYPE = "InvalidJurisdictionType";
    const ERRORCODEID_MUSTCONFIRMRESETLICENSEKEY = "MustConfirmResetLicenseKey";
    const ERRORCODEID_DUPLICATECOMPANYCODE = "DuplicateCompanyCode";
    const ERRORCODEID_TINFORMATERROR = "TINFormatError";
    const ERRORCODEID_DUPLICATENEXUSERROR = "DuplicateNexusError";
    const ERRORCODEID_UNKNOWNNEXUSERROR = "UnknownNexusError";
    const ERRORCODEID_PARENTNEXUSNOTFOUND = "ParentNexusNotFound";
    const ERRORCODEID_INVALIDTAXCODETYPE = "InvalidTaxCodeType";
    const ERRORCODEID_CANNOTACTIVATECOMPANY = "CannotActivateCompany";
    const ERRORCODEID_DUPLICATEENTITYPROPERTY = "DuplicateEntityProperty";
    const ERRORCODEID_BATCHSALESAUDITMUSTBEZIPPEDERROR = "BatchSalesAuditMustBeZippedError";
    const ERRORCODEID_BATCHZIPMUSTCONTAINONEFILEERROR = "BatchZipMustContainOneFileError";
    const ERRORCODEID_BATCHINVALIDFILETYPEERROR = "BatchInvalidFileTypeError";
    const ERRORCODEID_POINTOFSALEFILESIZE = "PointOfSaleFileSize";
    const ERRORCODEID_POINTOFSALESETUP = "PointOfSaleSetup";
    const ERRORCODEID_GETTAXERROR = "GetTaxError";
    const ERRORCODEID_ADDRESSCONFLICTEXCEPTION = "AddressConflictException";
    const ERRORCODEID_DOCUMENTCODECONFLICT = "DocumentCodeConflict";
    const ERRORCODEID_MISSINGADDRESS = "MissingAddress";
    const ERRORCODEID_INVALIDPARAMETER = "InvalidParameter";
    const ERRORCODEID_INVALIDPARAMETERVALUE = "InvalidParameterValue";
    const ERRORCODEID_COMPANYCODECONFLICT = "CompanyCodeConflict";
    const ERRORCODEID_DOCUMENTFETCHLIMIT = "DocumentFetchLimit";
    const ERRORCODEID_ADDRESSINCOMPLETE = "AddressIncomplete";
    const ERRORCODEID_ADDRESSLOCATIONNOTFOUND = "AddressLocationNotFound";
    const ERRORCODEID_MISSINGLINE = "MissingLine";
    const ERRORCODEID_BADDOCUMENTFETCH = "BadDocumentFetch";
    const ERRORCODEID_SERVERUNREACHABLE = "ServerUnreachable";
    const ERRORCODEID_SUBSCRIPTIONREQUIRED = "SubscriptionRequired";

    /**
     * Enumerated values defined for ErrorTargetCode
     */

    const ERRORTARGETCODE_UNKNOWN = "Unknown";
    const ERRORTARGETCODE_HTTPREQUEST = "HttpRequest";
    const ERRORTARGETCODE_HTTPREQUESTHEADERS = "HttpRequestHeaders";
    const ERRORTARGETCODE_INCORRECTDATA = "IncorrectData";
    const ERRORTARGETCODE_AVATAXAPISERVER = "AvaTaxApiServer";
    const ERRORTARGETCODE_AVALARAIDENTITYSERVER = "AvalaraIdentityServer";
    const ERRORTARGETCODE_CUSTOMERACCOUNTSETUP = "CustomerAccountSetup";

    /**
     * Enumerated values defined for SeverityLevel
     */

    const SEVERITYLEVEL_SUCCESS = "Success";
    const SEVERITYLEVEL_WARNING = "Warning";
    const SEVERITYLEVEL_ERROR = "Error";
    const SEVERITYLEVEL_EXCEPTION = "Exception";

    /**
     * Enumerated values defined for ResolutionQuality
     */

    const RESOLUTIONQUALITY_NOTCODED = "NotCoded";
    const RESOLUTIONQUALITY_EXTERNAL = "External";
    const RESOLUTIONQUALITY_COUNTRYCENTROID = "CountryCentroid";
    const RESOLUTIONQUALITY_REGIONCENTROID = "RegionCentroid";
    const RESOLUTIONQUALITY_PARTIALCENTROID = "PartialCentroid";
    const RESOLUTIONQUALITY_POSTALCENTROIDGOOD = "PostalCentroidGood";
    const RESOLUTIONQUALITY_POSTALCENTROIDBETTER = "PostalCentroidBetter";
    const RESOLUTIONQUALITY_POSTALCENTROIDBEST = "PostalCentroidBest";
    const RESOLUTIONQUALITY_INTERSECTION = "Intersection";
    const RESOLUTIONQUALITY_INTERPOLATED = "Interpolated";
    const RESOLUTIONQUALITY_ROOFTOP = "Rooftop";
    const RESOLUTIONQUALITY_CONSTANT = "Constant";

    /**
     * Enumerated values defined for JurisdictionType
     */

    const JURISDICTIONTYPE_COUNTRY = "Country";
    const JURISDICTIONTYPE_COMPOSITE = "Composite";
    const JURISDICTIONTYPE_STATE = "State";
    const JURISDICTIONTYPE_COUNTY = "County";
    const JURISDICTIONTYPE_CITY = "City";
    const JURISDICTIONTYPE_SPECIAL = "Special";

    /**
     * Enumerated values defined for BatchType
     */

    const BATCHTYPE_AVACERTUPDATE = "AvaCertUpdate";
    const BATCHTYPE_AVACERTUPDATEALL = "AvaCertUpdateAll";
    const BATCHTYPE_BATCHMAINTENANCE = "BatchMaintenance";
    const BATCHTYPE_COMPANYLOCATIONIMPORT = "CompanyLocationImport";
    const BATCHTYPE_DOCUMENTIMPORT = "DocumentImport";
    const BATCHTYPE_EXEMPTCERTIMPORT = "ExemptCertImport";
    const BATCHTYPE_ITEMIMPORT = "ItemImport";
    const BATCHTYPE_SALESAUDITEXPORT = "SalesAuditExport";
    const BATCHTYPE_SSTPTESTDECKIMPORT = "SstpTestDeckImport";
    const BATCHTYPE_TAXRULEIMPORT = "TaxRuleImport";
    const BATCHTYPE_TRANSACTIONIMPORT = "TransactionImport";
    const BATCHTYPE_UPCBULKIMPORT = "UPCBulkImport";
    const BATCHTYPE_UPCVALIDATIONIMPORT = "UPCValidationImport";

    /**
     * Enumerated values defined for BatchStatus
     */

    const BATCHSTATUS_WAITING = "Waiting";
    const BATCHSTATUS_SYSTEMERRORS = "SystemErrors";
    const BATCHSTATUS_CANCELLED = "Cancelled";
    const BATCHSTATUS_COMPLETED = "Completed";
    const BATCHSTATUS_CREATING = "Creating";
    const BATCHSTATUS_DELETED = "Deleted";
    const BATCHSTATUS_ERRORS = "Errors";
    const BATCHSTATUS_PAUSED = "Paused";
    const BATCHSTATUS_PROCESSING = "Processing";

    /**
     * Enumerated values defined for RoundingLevelId
     */

    const ROUNDINGLEVELID_LINE = "Line";
    const ROUNDINGLEVELID_DOCUMENT = "Document";

    /**
     * Enumerated values defined for TaxDependencyLevelId
     */

    const TAXDEPENDENCYLEVELID_DOCUMENT = "Document";
    const TAXDEPENDENCYLEVELID_STATE = "State";
    const TAXDEPENDENCYLEVELID_TAXREGION = "TaxRegion";
    const TAXDEPENDENCYLEVELID_ADDRESS = "Address";

    /**
     * Enumerated values defined for AddressTypeId
     */

    const ADDRESSTYPEID_LOCATION = "Location";
    const ADDRESSTYPEID_SALESPERSON = "Salesperson";

    /**
     * Enumerated values defined for AddressCategoryId
     */

    const ADDRESSCATEGORYID_STOREFRONT = "Storefront";
    const ADDRESSCATEGORYID_MAINOFFICE = "MainOffice";
    const ADDRESSCATEGORYID_WAREHOUSE = "Warehouse";
    const ADDRESSCATEGORYID_SALESPERSON = "Salesperson";
    const ADDRESSCATEGORYID_OTHER = "Other";

    /**
     * Enumerated values defined for JurisTypeId
     */

    const JURISTYPEID_STA = "STA";
    const JURISTYPEID_CTY = "CTY";
    const JURISTYPEID_CIT = "CIT";
    const JURISTYPEID_STJ = "STJ";
    const JURISTYPEID_CNT = "CNT";

    /**
     * Enumerated values defined for NexusTypeId
     */

    const NEXUSTYPEID_NONE = "None";
    const NEXUSTYPEID_SALESORSELLERSUSETAX = "SalesOrSellersUseTax";
    const NEXUSTYPEID_SALESTAX = "SalesTax";
    const NEXUSTYPEID_SSTVOLUNTEER = "SSTVolunteer";
    const NEXUSTYPEID_SSTNONVOLUNTEER = "SSTNonVolunteer";

    /**
     * Enumerated values defined for Sourcing
     */

    const SOURCING_MIXED = "Mixed";
    const SOURCING_DESTINATION = "Destination";
    const SOURCING_ORIGIN = "Origin";

    /**
     * Enumerated values defined for LocalNexusTypeId
     */

    const LOCALNEXUSTYPEID_SELECTED = "Selected";
    const LOCALNEXUSTYPEID_STATEADMINISTERED = "StateAdministered";
    const LOCALNEXUSTYPEID_ALL = "All";

    /**
     * Enumerated values defined for MatchingTaxType
     */

    const MATCHINGTAXTYPE_ALL = "All";
    const MATCHINGTAXTYPE_BOTHSALESANDUSETAX = "BothSalesAndUseTax";
    const MATCHINGTAXTYPE_CONSUMERUSETAX = "ConsumerUseTax";
    const MATCHINGTAXTYPE_MEDICALEXCISE = "MedicalExcise";
    const MATCHINGTAXTYPE_FEE = "Fee";
    const MATCHINGTAXTYPE_VATINPUTTAX = "VATInputTax";
    const MATCHINGTAXTYPE_VATNONRECOVERABLEINPUTTAX = "VATNonrecoverableInputTax";
    const MATCHINGTAXTYPE_VATOUTPUTTAX = "VATOutputTax";
    const MATCHINGTAXTYPE_RENTAL = "Rental";
    const MATCHINGTAXTYPE_SALESTAX = "SalesTax";
    const MATCHINGTAXTYPE_USETAX = "UseTax";

    /**
     * Enumerated values defined for RateType
     */

    const RATETYPE_REDUCEDA = "ReducedA";
    const RATETYPE_REDUCEDB = "ReducedB";
    const RATETYPE_FOOD = "Food";
    const RATETYPE_GENERAL = "General";
    const RATETYPE_INCREASEDSTANDARD = "IncreasedStandard";
    const RATETYPE_LINENRENTAL = "LinenRental";
    const RATETYPE_MEDICAL = "Medical";
    const RATETYPE_PARKING = "Parking";
    const RATETYPE_SUPERREDUCED = "SuperReduced";
    const RATETYPE_REDUCEDR = "ReducedR";
    const RATETYPE_STANDARD = "Standard";
    const RATETYPE_ZERO = "Zero";

    /**
     * Enumerated values defined for TaxRuleTypeId
     */

    const TAXRULETYPEID_RATERULE = "RateRule";
    const TAXRULETYPEID_RATEOVERRIDERULE = "RateOverrideRule";
    const TAXRULETYPEID_BASERULE = "BaseRule";
    const TAXRULETYPEID_EXEMPTENTITYRULE = "ExemptEntityRule";
    const TAXRULETYPEID_PRODUCTTAXABILITYRULE = "ProductTaxabilityRule";
    const TAXRULETYPEID_NEXUSRULE = "NexusRule";

    /**
     * Enumerated values defined for ParameterBagDataType
     */

    const PARAMETERBAGDATATYPE_STRING = "String";
    const PARAMETERBAGDATATYPE_BOOLEAN = "Boolean";
    const PARAMETERBAGDATATYPE_NUMERIC = "Numeric";

    /**
     * Enumerated values defined for WorksheetTypeId
     */

    const WORKSHEETTYPEID_ORIGINAL = "Original";
    const WORKSHEETTYPEID_AMENDED = "Amended";
    const WORKSHEETTYPEID_TEST = "Test";

    /**
     * Enumerated values defined for WorksheetStatusId
     */

    const WORKSHEETSTATUSID_PENDINGAPPROVAL = "PendingApproval";
    const WORKSHEETSTATUSID_DIRTY = "Dirty";
    const WORKSHEETSTATUSID_APPROVEDTOFILE = "ApprovedToFile";
    const WORKSHEETSTATUSID_PENDINGFILING = "PendingFiling";
    const WORKSHEETSTATUSID_PENDINGFILINGONBEHALF = "PendingFilingOnBehalf";
    const WORKSHEETSTATUSID_FILED = "Filed";
    const WORKSHEETSTATUSID_FILEDONBEHALF = "FiledOnBehalf";
    const WORKSHEETSTATUSID_RETURNACCEPTED = "ReturnAccepted";
    const WORKSHEETSTATUSID_RETURNACCEPTEDONBEHALF = "ReturnAcceptedOnBehalf";
    const WORKSHEETSTATUSID_PAYMENTREMITTED = "PaymentRemitted";
    const WORKSHEETSTATUSID_VOIDED = "Voided";
    const WORKSHEETSTATUSID_PENDINGRETURN = "PendingReturn";
    const WORKSHEETSTATUSID_PENDINGRETURNONBEHALF = "PendingReturnOnBehalf";
    const WORKSHEETSTATUSID_DONOTFILE = "DoNotFile";
    const WORKSHEETSTATUSID_RETURNREJECTED = "ReturnRejected";
    const WORKSHEETSTATUSID_RETURNREJECTEDONBEHALF = "ReturnRejectedOnBehalf";
    const WORKSHEETSTATUSID_APPROVEDTOFILEONBEHALF = "ApprovedToFileOnBehalf";

    /**
     * Enumerated values defined for FilingFrequencyId
     */

    const FILINGFREQUENCYID_MONTHLY = "Monthly";
    const FILINGFREQUENCYID_QUARTERLY = "Quarterly";
    const FILINGFREQUENCYID_SEMIANNUALLY = "SemiAnnually";
    const FILINGFREQUENCYID_ANNUALLY = "Annually";
    const FILINGFREQUENCYID_BIMONTHLY = "Bimonthly";
    const FILINGFREQUENCYID_OCCASIONAL = "Occasional";
    const FILINGFREQUENCYID_INVERSEQUARTERLY = "InverseQuarterly";

    /**
     * Enumerated values defined for FilingTypeId
     */

    const FILINGTYPEID_PAPERRETURN = "PaperReturn";
    const FILINGTYPEID_ELECTRONICRETURN = "ElectronicReturn";
    const FILINGTYPEID_SER = "SER";
    const FILINGTYPEID_EFTPAPER = "EFTPaper";
    const FILINGTYPEID_PHONEPAPER = "PhonePaper";
    const FILINGTYPEID_SIGNATUREREADY = "SignatureReady";
    const FILINGTYPEID_EFILECHECK = "EfileCheck";

    /**
     * Enumerated values defined for PointOfSaleFileType
     */

    const POINTOFSALEFILETYPE_JSON = "Json";
    const POINTOFSALEFILETYPE_CSV = "Csv";
    const POINTOFSALEFILETYPE_XML = "Xml";

    /**
     * Enumerated values defined for DocumentStatus
     */

    const DOCUMENTSTATUS_TEMPORARY = "Temporary";
    const DOCUMENTSTATUS_SAVED = "Saved";
    const DOCUMENTSTATUS_POSTED = "Posted";
    const DOCUMENTSTATUS_COMMITTED = "Committed";
    const DOCUMENTSTATUS_CANCELLED = "Cancelled";
    const DOCUMENTSTATUS_ADJUSTED = "Adjusted";
    const DOCUMENTSTATUS_QUEUED = "Queued";
    const DOCUMENTSTATUS_PENDINGAPPROVAL = "PendingApproval";
    const DOCUMENTSTATUS_ANY = "Any";

    /**
     * Enumerated values defined for DocumentType
     */

    const DOCUMENTTYPE_SALESORDER = "SalesOrder";
    const DOCUMENTTYPE_SALESINVOICE = "SalesInvoice";
    const DOCUMENTTYPE_PURCHASEORDER = "PurchaseOrder";
    const DOCUMENTTYPE_PURCHASEINVOICE = "PurchaseInvoice";
    const DOCUMENTTYPE_RETURNORDER = "ReturnOrder";
    const DOCUMENTTYPE_RETURNINVOICE = "ReturnInvoice";
    const DOCUMENTTYPE_INVENTORYTRANSFERORDER = "InventoryTransferOrder";
    const DOCUMENTTYPE_INVENTORYTRANSFERINVOICE = "InventoryTransferInvoice";
    const DOCUMENTTYPE_REVERSECHARGEORDER = "ReverseChargeOrder";
    const DOCUMENTTYPE_REVERSECHARGEINVOICE = "ReverseChargeInvoice";
    const DOCUMENTTYPE_ANY = "Any";

    /**
     * Enumerated values defined for TaxOverrideTypeId
     */

    const TAXOVERRIDETYPEID_NONE = "None";
    const TAXOVERRIDETYPEID_TAXAMOUNT = "TaxAmount";
    const TAXOVERRIDETYPEID_EXEMPTION = "Exemption";
    const TAXOVERRIDETYPEID_TAXDATE = "TaxDate";
    const TAXOVERRIDETYPEID_ACCRUEDTAXAMOUNT = "AccruedTaxAmount";

    /**
     * Enumerated values defined for AdjustmentReason
     */

    const ADJUSTMENTREASON_NOTADJUSTED = "NotAdjusted";
    const ADJUSTMENTREASON_SOURCINGISSUE = "SourcingIssue";
    const ADJUSTMENTREASON_RECONCILEDWITHGENERALLEDGER = "ReconciledWithGeneralLedger";
    const ADJUSTMENTREASON_EXEMPTCERTAPPLIED = "ExemptCertApplied";
    const ADJUSTMENTREASON_PRICEADJUSTED = "PriceAdjusted";
    const ADJUSTMENTREASON_PRODUCTRETURNED = "ProductReturned";
    const ADJUSTMENTREASON_PRODUCTEXCHANGED = "ProductExchanged";
    const ADJUSTMENTREASON_BADDEBT = "BadDebt";
    const ADJUSTMENTREASON_OTHER = "Other";
    const ADJUSTMENTREASON_OFFLINE = "Offline";

    /**
     * Enumerated values defined for BoundaryLevel
     */

    const BOUNDARYLEVEL_ADDRESS = "Address";
    const BOUNDARYLEVEL_ZIP9 = "Zip9";
    const BOUNDARYLEVEL_ZIP5 = "Zip5";

    /**
     * Enumerated values defined for TaxType
     */

    const TAXTYPE_CONSUMERUSE = "ConsumerUse";
    const TAXTYPE_EXCISE = "Excise";
    const TAXTYPE_FEE = "Fee";
    const TAXTYPE_INPUT = "Input";
    const TAXTYPE_NONRECOVERABLE = "Nonrecoverable";
    const TAXTYPE_OUTPUT = "Output";
    const TAXTYPE_RENTAL = "Rental";
    const TAXTYPE_SALES = "Sales";
    const TAXTYPE_USE = "Use";

    /**
     * Enumerated values defined for TransactionAddressType
     */


    /**
     * Enumerated values defined for ServiceMode
     */

    const SERVICEMODE_AUTOMATIC = "Automatic";
    const SERVICEMODE_LOCAL = "Local";
    const SERVICEMODE_REMOTE = "Remote";

    /**
     * Enumerated values defined for TaxDebugLevel
     */

    const TAXDEBUGLEVEL_NORMAL = "Normal";
    const TAXDEBUGLEVEL_DIAGNOSTIC = "Diagnostic";

    /**
     * Enumerated values defined for TaxOverrideType
     */

    const TAXOVERRIDETYPE_NONE = "None";
    const TAXOVERRIDETYPE_TAXAMOUNT = "TaxAmount";
    const TAXOVERRIDETYPE_EXEMPTION = "Exemption";
    const TAXOVERRIDETYPE_TAXDATE = "TaxDate";
    const TAXOVERRIDETYPE_ACCRUEDTAXAMOUNT = "AccruedTaxAmount";
    const TAXOVERRIDETYPE_DERIVETAXABLE = "DeriveTaxable";

    /**
     * Enumerated values defined for VoidReasonCode
     */

    const VOIDREASONCODE_UNSPECIFIED = "Unspecified";
    const VOIDREASONCODE_POSTFAILED = "PostFailed";
    const VOIDREASONCODE_DOCDELETED = "DocDeleted";
    const VOIDREASONCODE_DOCVOIDED = "DocVoided";
    const VOIDREASONCODE_ADJUSTMENTCANCELLED = "AdjustmentCancelled";

    /**
     * Enumerated values defined for CompanyAccessLevel
     */

    const COMPANYACCESSLEVEL_NONE = "None";
    const COMPANYACCESSLEVEL_SINGLECOMPANY = "SingleCompany";
    const COMPANYACCESSLEVEL_SINGLEACCOUNT = "SingleAccount";
    const COMPANYACCESSLEVEL_ALLCOMPANIES = "AllCompanies";

    /**
     * Enumerated values defined for AuthenticationTypeId
     */

    const AUTHENTICATIONTYPEID_NONE = "None";
    const AUTHENTICATIONTYPEID_USERNAMEPASSWORD = "UsernamePassword";
    const AUTHENTICATIONTYPEID_ACCOUNTIDLICENSEKEY = "AccountIdLicenseKey";
    const AUTHENTICATIONTYPEID_OPENIDBEARERTOKEN = "OpenIdBearerToken";

    /**
     * Enumerated values defined for TransactionAddressType
     */

    const TRANSACTIONADDRESSTYPE_SHIPFROM = "ShipFrom";
    const TRANSACTIONADDRESSTYPE_SHIPTO = "ShipTo";
    const TRANSACTIONADDRESSTYPE_POINTOFORDERACCEPTANCE = "PointOfOrderAcceptance";
    const TRANSACTIONADDRESSTYPE_POINTOFORDERORIGIN = "PointOfOrderOrigin";
    const TRANSACTIONADDRESSTYPE_SINGLELOCATION = "SingleLocation";
}

/*****************************************************************************
 *                              Transaction Builder                          *
 *****************************************************************************/

/**
 * TransactionBuilder helps you construct a new transaction using a literate interface
 */
class TransactionBuilder
{
    /**
     * The in-progress model
     */
    private $_model;

    /**
     * Keeps track of the line number when adding multiple lines
     */
    private $_line_number;
    
    /**
     * The client that will be used to create the transaction
     */
    private $_client;
        
    /**
     * TransactionBuilder helps you construct a new transaction using a literate interface
     *
     * @param AvaTaxClient  $client        The AvaTaxClient object to use to create this transaction
     * @param string        $companyCode   The code of the company for this transaction
     * @param DocumentType  $type          The type of transaction to create. See Constants.DOCUMENTTYPE_* for allowable values.
     * @param string        $customerCode  The customer code for this transaction
     */
    public function __construct($client, $companyCode, $type, $customerCode)
    {
        $this->_client = $client;
        $this->_line_number = 1;
        $this->_model = [
            'companyCode' => $companyCode,
            'customerCode' => $customerCode,
            'date' => date('Y-m-d H:i:s'),
            'type' => $type,
            'lines' => [],
        ];
    }

    /**
     * Set the commit flag of the transaction.
     *
     * @return
     */
    public function withCommit()
    {
        $this->_model['commit'] = true;
        return $this;
    }

    /**
     * Enable diagnostic information
     *
     * @return  TransactionBuilder
     */
    public function withDiagnostics()
    {
        $this->_model['debugLevel'] = Constants::TAXDEBUGLEVEL_DIAGNOSTIC;
        return $this;
    }

    /**
     * Set a specific discount amount
     *
     * @param   float               $discount
     * @return  TransactionBuilder
     */
    public function withDiscountAmount($discount)
    {
        $this->_model['discount'] = $discount;
        return $this;
    }

    /**
     * Set if discount is applicable for the current line
     *
     * @param   boolean             discounted
     * @return  TransactionBuilder
     */
    public function withItemDiscount($discounted)
    {
        $l = GetMostRecentLine("WithItemDiscount");
        $l['discounted'] = $discounted;
        return $this;
    }

    /**
     * Set a specific transaction code
     *
     * @param   string              code
     * @return  TransactionBuilder
     */
    public function withTransactionCode($code)
    {
        $this->_model['code'] = $code;
        return $this;
    }

    /**
     * Set the document type
     *
     * @param   string              type    See Constants::DOCUMENTTYPE_* for a list of values
     * @return  TransactionBuilder
     */
    public function withType($type)
    {
        $this->_model['type'] = $type;
        return $this;
    }

    /**
     * Add a parameter at the document level
     *
     * @param   string              name
     * @param   string              value
     * @return  TransactionBuilder
     */
    public function withParameter($name, $value)
    {
        if (empty($this->_model['parameters'])) $this->_model['parameters'] = [];
        $this->_model['parameters'][$name] = $value;
        return $this;
    }

    /**
     * Add a parameter to the current line
     *
     * @param   string              name
     * @param   string              value
     * @return  TransactionBuilder
     */
    public function withLineParameter($name, $value)
    {
        $l = GetMostRecentLine("WithLineParameter");
        if (empty($l['parameters'])) $l['parameters'] = [];
        $l[$name] = $value;
        return $this;
    }

    /**
     * Add an address to this transaction
     *
     * @param   string              type          Address Type - see Constants::ADDRESSTYPE_* for acceptable values
     * @param   string              line1         The street address, attention line, or business name of the location.
     * @param   string              line2         The street address, business name, or apartment/unit number of the location.
     * @param   string              line3         The street address or apartment/unit number of the location.
     * @param   string              city          City of the location.
     * @param   string              region        State or Region of the location.
     * @param   string              postalCode    Postal/zip code of the location.
     * @param   string              country       The two-letter country code of the location.
     * @return  TransactionBuilder
     */
    public function withAddress($type, $line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        if (empty($this->_model['addresses'])) $this->_model['addresses'] = [];
        $ai = [
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'city' => $city,
            'region' => $region,
            'postalCode' => $postalCode,
            'country' => $country
        ];
        $this->_model['addresses'][$type] = $ai;
        return $this;
    }

    /**
     * Add a lat/long coordinate to this transaction
     *
     * @param   string              $type       Address Type - see Constants::ADDRESSTYPE_* for acceptable values
     * @param   float               $latitude   The latitude of the geolocation for this transaction
     * @param   float               $longitude  The longitude of the geolocation for this transaction
     * @return  TransactionBuilder
     */
     public function withLatLong($type, $latitude, $longitude)
    {
        $this->_model['addresses'][$type] = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        return $this;
    }

    /**
     * Add an address to this line
     *
     * @param   string              type        Address Type - see Constants::ADDRESSTYPE_* for acceptable values
     * @param   string              line1       The street address, attention line, or business name of the location.
     * @param   string              line2       The street address, business name, or apartment/unit number of the location.
     * @param   string              line3       The street address or apartment/unit number of the location.
     * @param   string              city        City of the location.
     * @param   string              region      State or Region of the location.
     * @param   string              postalCode  Postal/zip code of the location.
     * @param   string              country     The two-letter country code of the location.
     * @return  TransactionBuilder
     */
    public function withLineAddress($type, $line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $line = $this->GetMostRecentLine("WithLineAddress");
        $line['addresses'][$type] = [
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'city' => $city,
            'region' => $region,
            'postalCode' => $postalCode,
            'country' => $country
        ];
        return $this;
    }

    /**
     * Add a document-level Tax Override to the transaction.
     *  - A TaxDate override requires a valid DateTime object to be passed.
     * TODO: Verify Tax Override constraints and add exceptions.
     *
     * @param   string              $type       Type of the Tax Override. See Constants::TAXOVERRIDETYPE_* for a list of allowable values.
     * @param   string              $reason     Reason of the Tax Override.
     * @param   float               $taxAmount  Amount of tax to apply. Required for a TaxAmount Override.
     * @param   date                $taxDate    Date of a Tax Override. Required for a TaxDate Override.
     * @return  TransactionBuilder
     */
    public function withTaxOverride($type, $reason, $taxAmount, $taxDate)
    {
        $this->_model['taxOverride'] = [
            'type' => $type,
            'reason' => $reason,
            'taxAmount' => $taxAmount,
            'taxDate' => $taxDate
        ];

        // Continue building
        return $this;
    }

    /**
     * Add a line-level Tax Override to the current line.
     *  - A TaxDate override requires a valid DateTime object to be passed.
     * TODO: Verify Tax Override constraints and add exceptions.
     *
     * @param   string              $type        Type of the Tax Override. See Constants::TAXOVERRIDETYPE_* for a list of allowable values.
     * @param   string              $reason      Reason of the Tax Override.
     * @param   float               $taxAmount   Amount of tax to apply. Required for a TaxAmount Override.
     * @param   date                $taxDate     Date of a Tax Override. Required for a TaxDate Override.
     * @return  TransactionBuilder
     */
    public function withLineTaxOverride($type, $reason, $taxAmount, $taxDate)
    {
        // Address the DateOverride constraint.
        if (($type == Constants::TAXOVERRIDETYPE_TAXDATE) && (empty($taxDate))) {
            throw new Exception("A valid date is required for a Tax Date Tax Override.");
        }

        $line = $this->GetMostRecentLine("WithLineTaxOverride");
        $line['taxOverride'] = [
            'type' => $type,
            'reason' => $reason,
            'taxAmount' => $taxAmount,
            'taxDate' => $taxDate
        ];

        // Continue building
        return $this;
    }

    /**
     * Add a line to this transaction
     *
     * @param   float               $amount      Value of the item.
     * @param   float               $quantity    Quantity of the item.
     * @param   string              $taxCode     Tax Code of the item. If left blank, the default item (P0000000) is assumed.
     * @return  TransactionBuilder
     */
    public function withLine($amount, $quantity, $taxCode)
    {
        $l = [
            'number' => $this->_line_number,
            'quantity' => $quantity,
            'amount' => $amount,
            'taxCode' => $taxCode
        ];
        array_push($this->_model['lines'], $l);
        $this->_line_number++;

        // Continue building
        return $this;
    }

    /**
     * Add a line to this transaction
     *
     * @param   float               $amount      Value of the line
     * @param   string              $type        Address Type - see Constants::ADDRESSTYPE_* for acceptable values
     * @param   string              $line1       The street address, attention line, or business name of the location.
     * @param   string              $line2       The street address, business name, or apartment/unit number of the location.
     * @param   string              $line3       The street address or apartment/unit number of the location.
     * @param   string              $city        City of the location.
     * @param   string              $region      State or Region of the location.
     * @param   string              $postalCode  Postal/zip code of the location.
     * @param   string              $country     The two-letter country code of the location.
     * @return  TransactionBuilder
     */
    public function withSeparateAddressLine($amount, $type, $line1, $line2, $line3, $city, $region, $postalCode, $country)
    {
        $l = [
            'number' => $this->_line_number,
            'quantity' => 1,
            'amount' => $amount,
            'addresses' => [
                $type => [
                    'line1' => $line1,
                    'line2' => $line2,
                    'line3' => $line3,
                    'city' => $city,
                    'region' => $region,
                    'postalCode' => $postalCode,
                    'country' => $country
                ]
            ]
        ];

        // Put this line in the model
        array_push($this->_model['lines'], $l);
        $this->_line_number++;

        // Continue building
        return $this;
    }

    /**
     * Add a line with an exemption to this transaction
     *
     * @param   float               $amount         The amount of this line item
     * @param   string              $exemptionCode  The exemption code for this line item
     * @return  TransactionBuilder
     */
    public function withExemptLine($amount, $exemptionCode)
    {
        $l = [
            'number' => $this->_line_number,
            'quantity' => 1,
            'amount' => $amount,
            'exemptionCode' => $exemptionCode
        ];
        array_push($this->_model['lines'], $l); 
        $this->_line_number++;

        // Continue building
        return $this;
    }

    /**
     * Checks to see if the current model has a line.
     *
     * @return  TransactionBuilder
     */
    private function getMostRecentLine($memberName)
    {
        $c = count($this->_model['lines']);
        if ($c <= 0) {
            throw new Exception("No lines have been added. The $memberName method applies to the most recent line.  To use this function, first add a line.");
        }

        return $this->_model['lines'][$c-1];
    }

    /**
     * Create this transaction
     *
     * @return  TransactionModel
     */
    public function create()
    {
        return $this->_client->createTransaction($this->_model);
    }

    /**
     * Create a transaction adjustment request that can be used with the AdjustTransaction() API call
     *
     * @return  AdjustTransactionModel
     */
    public function createAdjustmentRequest($desc, $reason)
    {
        return [
            'newTransaction' => $this->_model,
            'adjustmentDescription' => $desc,
            'adjustmentReason' => $reason
        ];
    }
}
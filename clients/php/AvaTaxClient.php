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

require_once __DIR__."/vendor/autoload.php";

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
 *                              Object Models                                *
 *****************************************************************************/


/**
 * An AvaTax account.
 */
class AccountModel
{

    /**
     * @var int The unique ID number assigned to this account.
     */
    public $id;

    /**
     * @var string The name of this account.
     */
    public $name;

    /**
     * @var string The earliest date on which this account may be used.
     */
    public $effectiveDate;

    /**
     * @var string If this account has been closed, this is the last date the account was open.
     */
    public $endDate;

    /**
     * @var string The current status of this account. (See AccountStatusId::* for a list of allowable values)
     */
    public $accountStatusId;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var SubscriptionModel[] Optional: A list of subscriptions granted to this account. To fetch this list, add the query string "?$include=Subscriptions" to your URL.
     */
    public $subscriptions;

    /**
     * @var UserModel[] Optional: A list of all the users belonging to this account. To fetch this list, add the query string "?$include=Users" to your URL.
     */
    public $users;

}

/**
 * Represents a service that this account has subscribed to.
 */
class SubscriptionModel
{

    /**
     * @var int The unique ID number of this subscription.
     */
    public $id;

    /**
     * @var int The unique ID number of the account this subscription belongs to.
     */
    public $accountId;

    /**
     * @var int The unique ID number of the service that the account is subscribed to.
     */
    public $subscriptionTypeId;

    /**
     * @var string A friendly description of the service that the account is subscribed to.
     */
    public $subscriptionDescription;

    /**
     * @var string The date when the subscription began.
     */
    public $effectiveDate;

    /**
     * @var string If the subscription has ended or will end, this date indicates when the subscription ends.
     */
    public $endDate;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * An account user who is permitted to use AvaTax.
 */
class UserModel
{

    /**
     * @var int The unique ID number of this user.
     */
    public $id;

    /**
     * @var int The unique ID number of the account to which this user belongs.
     */
    public $accountId;

    /**
     * @var int If this user is locked to one company (and its children), this is the unique ID number of the company to which this user belongs.
     */
    public $companyId;

    /**
     * @var string The username which is used to log on to the AvaTax website, or to authenticate against API calls.
     */
    public $userName;

    /**
     * @var string The first or given name of the user.
     */
    public $firstName;

    /**
     * @var string The last or family name of the user.
     */
    public $lastName;

    /**
     * @var string The email address to be used to contact this user. If the user has forgotten a password, an email can be sent to this email address with information on how to reset this password.
     */
    public $email;

    /**
     * @var string The postal code in which this user resides.
     */
    public $postalCode;

    /**
     * @var string The security level for this user. (See SecurityRoleId::* for a list of allowable values)
     */
    public $securityRoleId;

    /**
     * @var string The status of the user's password. (See PasswordStatusId::* for a list of allowable values)
     */
    public $passwordStatus;

    /**
     * @var boolean True if this user is currently active.
     */
    public $isActive;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * Helper function for throwing known error response
 */
class ErrorResult
{

    /**
     * @var ErrorInfo Information about the error(s)
     */
    public $error;

}

/**
 * Information about the error that occurred
 */
class ErrorInfo
{

    /**
     * @var string Type of error that occurred (See ErrorCodeId::* for a list of allowable values)
     */
    public $code;

    /**
     * @var string Short one-line message to summaryize what went wrong
     */
    public $message;

    /**
     * @var string What object or service caused the error? (See ErrorTargetCode::* for a list of allowable values)
     */
    public $target;

    /**
     * @var ErrorDetail[] Array of detailed error messages
     */
    public $details;

}

/**
 * Message object
 */
class ErrorDetail
{

    /**
     * @var string Name of the error. (See ErrorCodeId::* for a list of allowable values)
     */
    public $code;

    /**
     * @var int Error message identifier
     */
    public $number;

    /**
     * @var string Concise summary of the message, suitable for display in the caption of an alert box.
     */
    public $message;

    /**
     * @var string A more detailed description of the problem referenced by this error message, suitable for display in the contents area of an alert box.
     */
    public $description;

    /**
     * @var string Indicates the SoapFault code
     */
    public $faultCode;

    /**
     * @var string URL to help for this message
     */
    public $helpLink;

    /**
     * @var string Item the message refers to, if applicable. This is used to indicate a missing or incorrect value.
     */
    public $refersTo;

    /**
     * @var string Severity of the message (See SeverityLevel::* for a list of allowable values)
     */
    public $severity;

}

/**
 * Represents a license key reset request.
 */
class ResetLicenseKeyModel
{

    /**
     * @var int The primary key of the account ID to reset
     */
    public $accountId;

    /**
     * @var boolean Set this value to true to reset the license key for this account.  This license key reset function will only work when called using the credentials of the account administrator of this account.
     */
    public $confirmResetLicenseKey;

}

/**
 * Represents a license key for this account.
 */
class LicenseKeyModel
{

    /**
     * @var int The primary key of the account
     */
    public $accountId;

    /**
     * @var string This is your private license key. You must record this license key for safekeeping.  If you lose this key, you must contact the ResetLicenseKey API in order to request a new one.  Each account can only have one license key at a time.
     */
    public $privateLicenseKey;

    /**
     * @var string If your software allows you to specify the HTTP Authorization header directly, this is the header string you   should use when contacting Avalara to make API calls with this license key.
     */
    public $httpRequestHeader;

}

/**
 * Represents an address to resolve.
 */
class AddressInfo
{

    /**
     * @var string Line1
     */
    public $line1;

    /**
     * @var string Line2
     */
    public $line2;

    /**
     * @var string Line3
     */
    public $line3;

    /**
     * @var string City
     */
    public $city;

    /**
     * @var string State / Province / Region
     */
    public $region;

    /**
     * @var string Two character ISO 3166 Country Code
     */
    public $country;

    /**
     * @var string Postal Code / Zip Code
     */
    public $postalCode;

    /**
     * @var float Geospatial latitude measurement
     */
    public $latitude;

    /**
     * @var float Geospatial longitude measurement
     */
    public $longitude;

}

/**
 * Address Resolution Model
 */
class AddressResolutionModel
{

    /**
     * @var AddressInfo The original address
     */
    public $address;

    /**
     * @var AddressInfo[] The validated address or addresses
     */
    public $validatedAddresses;

    /**
     * @var CoordinateInfo The geospatial coordinates of this address
     */
    public $coordinates;

    /**
     * @var string The resolution quality of the geospatial coordinates (See ResolutionQuality::* for a list of allowable values)
     */
    public $resolutionQuality;

    /**
     * @var TaxAuthorityInfo[] List of informational and warning messages regarding this address
     */
    public $taxAuthorities;

    /**
     * @var AvaTaxMessage[] List of informational and warning messages regarding this address
     */
    public $messages;

}

/**
 * Coordinate Info
 */
class CoordinateInfo
{

    /**
     * @var float Latitude
     */
    public $latitude;

    /**
     * @var float Longitude
     */
    public $longitude;

}

/**
 * Tax Authority Info
 */
class TaxAuthorityInfo
{

    /**
     * @var string Avalara Id
     */
    public $avalaraId;

    /**
     * @var string Jurisdiction Name
     */
    public $jurisdictionName;

    /**
     * @var string Jurisdiction Type (See JurisdictionType::* for a list of allowable values)
     */
    public $jurisdictionType;

    /**
     * @var string Signature Code
     */
    public $signatureCode;

}

/**
 * Informational or warning messages returned by AvaTax with a transaction
 */
class AvaTaxMessage
{

    /**
     * @var string A brief summary of what this message tells us
     */
    public $summary;

    /**
     * @var string Detailed information that explains what the summary provided
     */
    public $details;

    /**
     * @var string Information about what object in your request this message refers to
     */
    public $refersTo;

    /**
     * @var string A category that indicates how severely this message affects the results
     */
    public $severity;

    /**
     * @var string The name of the code or service that generated this message
     */
    public $source;

}

/**
 * Represents a batch of uploaded documents.
 */
class BatchModel
{

    /**
     * @var int The unique ID number of this batch.
     */
    public $id;

    /**
     * @var string The user-friendly readable name for this batch.
     */
    public $name;

    /**
     * @var int The Account ID number of the account that owns this batch.
     */
    public $accountId;

    /**
     * @var int The Company ID number of the company that owns this batch.
     */
    public $companyId;

    /**
     * @var string The type of this batch. (See BatchType::* for a list of allowable values)
     */
    public $type;

    /**
     * @var string This batch's current processing status (See BatchStatus::* for a list of allowable values)
     */
    public $status;

    /**
     * @var string Any optional flags provided for this batch
     */
    public $options;

    /**
     * @var string The agent used to create this batch
     */
    public $batchAgent;

    /**
     * @var string The date/time when this batch started processing
     */
    public $startedDate;

    /**
     * @var int The number of records in this batch; determined by the server
     */
    public $recordCount;

    /**
     * @var int The current record being processed
     */
    public $currentRecord;

    /**
     * @var string The date/time when this batch was completely processed
     */
    public $completedDate;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var BatchFileModel[] The list of files contained in this batch.
     */
    public $files;

}

/**
 * Represents one file in a batch upload.
 */
class BatchFileModel
{

    /**
     * @var int The unique ID number assigned to this batch file.
     */
    public $id;

    /**
     * @var int The unique ID number of the batch that this file belongs to.
     */
    public $batchId;

    /**
     * @var string Logical Name of file (e.g. "Input" or "Error").
     */
    public $name;

    /**
     * @var string Content of the batch file. (This value is encoded as a Base64 string)
     */
    public $content;

    /**
     * @var int Size of content, in bytes.
     */
    public $contentLength;

    /**
     * @var string Content mime type (e.g. text/csv). This is used for HTTP downloading.
     */
    public $contentType;

    /**
     * @var string File extension (e.g. CSV).
     */
    public $fileExtension;

    /**
     * @var int Number of errors that occurred when processing this file.
     */
    public $errorCount;

}

/**
 * A company or business entity.
 */
class CompanyModel
{

    /**
     * @var int The unique ID number of this company.
     */
    public $id;

    /**
     * @var int The unique ID number of the account this company belongs to.
     */
    public $accountId;

    /**
     * @var int If this company is fully owned by another company, this is the unique identity of the parent company.
     */
    public $parentCompanyId;

    /**
     * @var string If this company files Streamlined Sales Tax, this is the PID of this company as defined by the Streamlined Sales Tax governing board.
     */
    public $sstPid;

    /**
     * @var string A unique code that references this company within your account.
     */
    public $companyCode;

    /**
     * @var string The name of this company, as shown to customers.
     */
    public $name;

    /**
     * @var boolean This flag is true if this company is the default company for this account. Only one company may be set as the default.
     */
    public $isDefault;

    /**
     * @var int If set, this is the unique ID number of the default location for this company.
     */
    public $defaultLocationId;

    /**
     * @var boolean This flag indicates whether tax activity can occur for this company. Set this flag to true to permit the company to process transactions.
     */
    public $isActive;

    /**
     * @var string For United States companies, this field contains your Taxpayer Identification Number.   This is a nine digit number that is usually called an EIN for an Employer Identification Number if this company is a corporation,   or SSN for a Social Security Number if this company is a person.  This value is required if you subscribe to Avalara Managed Returns or the SST Certified Service Provider services,   but it is optional if you do not subscribe to either of those services.
     */
    public $taxpayerIdNumber;

    /**
     * @var boolean Set this flag to true to give this company its own unique tax profile.  If this flag is true, this company will have its own Nexus, TaxRule, TaxCode, and Item definitions.  If this flag is false, this company will inherit all profile values from its parent.
     */
    public $hasProfile;

    /**
     * @var boolean Set this flag to true if this company must file its own tax returns.  For users who have Returns enabled, this flag turns on monthly Worksheet generation for the company.
     */
    public $isReportingEntity;

    /**
     * @var string If this company participates in Streamlined Sales Tax, this is the date when the company joined the SST program.
     */
    public $sstEffectiveDate;

    /**
     * @var string The two character ISO-3166 country code of the default country for this company.
     */
    public $defaultCountry;

    /**
     * @var string This is the three character ISO-4217 currency code of the default currency used by this company.
     */
    public $baseCurrencyCode;

    /**
     * @var string Indicates whether this company prefers to round amounts at the document level or line level. (See RoundingLevelId::* for a list of allowable values)
     */
    public $roundingLevelId;

    /**
     * @var boolean Set this value to true to receive warnings in API calls via SOAP.
     */
    public $warningsEnabled;

    /**
     * @var boolean Set this flag to true to indicate that this company is a test company.  If you have Returns enabled, Test companies will not file tax returns and can be used for validation purposes.
     */
    public $isTest;

    /**
     * @var string Used to apply tax detail dependency at a jurisdiction level. (See TaxDependencyLevelId::* for a list of allowable values)
     */
    public $taxDependencyLevelId;

    /**
     * @var boolean Set this value to true to indicate that you are still working to finish configuring this company.  While this value is true, no tax reporting will occur and the company will not be usable for transactions.
     */
    public $inProgress;

    /**
     * @var string Business Identification No
     */
    public $businessIdentificationNo;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var ContactModel[] Optional: A list of contacts defined for this company. To fetch this list, add the query string "?$include=Contacts" to your URL.
     */
    public $contacts;

    /**
     * @var ItemModel[] Optional: A list of items defined for this company. To fetch this list, add the query string "?$include=Items" to your URL.
     */
    public $items;

    /**
     * @var LocationModel[] Optional: A list of locations defined for this company. To fetch this list, add the query string "?$include=Locations" to your URL.
     */
    public $locations;

    /**
     * @var NexusModel[] Optional: A list of nexus defined for this company. To fetch this list, add the query string "?$include=Nexus" to your URL.
     */
    public $nexus;

    /**
     * @var SettingModel[] Optional: A list of settings defined for this company. To fetch this list, add the query string "?$include=Settings" to your URL.
     */
    public $settings;

    /**
     * @var TaxCodeModel[] Optional: A list of tax codes defined for this company. To fetch this list, add the query string "?$include=TaxCodes" to your URL.
     */
    public $taxCodes;

    /**
     * @var TaxRuleModel[] Optional: A list of tax rules defined for this company. To fetch this list, add the query string "?$include=TaxRules" to your URL.
     */
    public $taxRules;

    /**
     * @var UPCModel[] Optional: A list of UPCs defined for this company. To fetch this list, add the query string "?$include=UPCs" to your URL.
     */
    public $upcs;

}

/**
 * A contact person for a company.
 */
class ContactModel
{

    /**
     * @var int The unique ID number of this contact.
     */
    public $id;

    /**
     * @var int The unique ID number of the company to which this contact belongs.
     */
    public $companyId;

    /**
     * @var string A unique code for this contact.
     */
    public $contactCode;

    /**
     * @var string The first or given name of this contact.
     */
    public $firstName;

    /**
     * @var string The middle name of this contact.
     */
    public $middleName;

    /**
     * @var string The last or family name of this contact.
     */
    public $lastName;

    /**
     * @var string Professional title of this contact.
     */
    public $title;

    /**
     * @var string The first line of the postal mailing address of this contact.
     */
    public $line1;

    /**
     * @var string The second line of the postal mailing address of this contact.
     */
    public $line2;

    /**
     * @var string The third line of the postal mailing address of this contact.
     */
    public $line3;

    /**
     * @var string The city of the postal mailing address of this contact.
     */
    public $city;

    /**
     * @var string The state, region, or province of the postal mailing address of this contact.
     */
    public $region;

    /**
     * @var string The postal code or zip code of the postal mailing address of this contact.
     */
    public $postalCode;

    /**
     * @var string The ISO 3166 two-character country code of the postal mailing address of this contact.
     */
    public $country;

    /**
     * @var string The email address of this contact.
     */
    public $email;

    /**
     * @var string The main phone number for this contact.
     */
    public $phone;

    /**
     * @var string The mobile phone number for this contact.
     */
    public $mobile;

    /**
     * @var string The facsimile phone number for this contact.
     */
    public $fax;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * Represents an item in your company's product catalog.
 */
class ItemModel
{

    /**
     * @var int The unique ID number of this item.
     */
    public $id;

    /**
     * @var int The unique ID number of the company that owns this item.
     */
    public $companyId;

    /**
     * @var string A unique code representing this item.
     */
    public $itemCode;

    /**
     * @var int The unique ID number of the tax code that is applied when selling this item.  When creating or updating an item, you can either specify the Tax Code ID number or the Tax Code string; you do not need to specify both values.
     */
    public $taxCodeId;

    /**
     * @var string The unique code string of the Tax Code that is applied when selling this item.  When creating or updating an item, you can either specify the Tax Code ID number or the Tax Code string; you do not need to specify both values.
     */
    public $taxCode;

    /**
     * @var string A friendly description of this item in your product catalog.
     */
    public $description;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * A location where this company does business.
 * Some jurisdictions may require you to list all locations where your company does business.
 */
class LocationModel
{

    /**
     * @var int The unique ID number of this location.
     */
    public $id;

    /**
     * @var int The unique ID number of the company that operates at this location.
     */
    public $companyId;

    /**
     * @var string A code that identifies this location. Must be unique within your company.
     */
    public $locationCode;

    /**
     * @var string A friendly name for this location.
     */
    public $description;

    /**
     * @var string Indicates whether this location is a physical place of business or a temporary salesperson location. (See AddressTypeId::* for a list of allowable values)
     */
    public $addressTypeId;

    /**
     * @var string Indicates the type of place of business represented by this location. (See AddressCategoryId::* for a list of allowable values)
     */
    public $addressCategoryId;

    /**
     * @var string The first line of the physical address of this location.
     */
    public $line1;

    /**
     * @var string The second line of the physical address of this location.
     */
    public $line2;

    /**
     * @var string The third line of the physical address of this location.
     */
    public $line3;

    /**
     * @var string The city of the physical address of this location.
     */
    public $city;

    /**
     * @var string The county name of the physical address of this location. Not required.
     */
    public $county;

    /**
     * @var string The state, region, or province of the physical address of this location.
     */
    public $region;

    /**
     * @var string The postal code or zip code of the physical address of this location.
     */
    public $postalCode;

    /**
     * @var string The two character ISO-3166 country code of the physical address of this location.
     */
    public $country;

    /**
     * @var boolean Set this flag to true to indicate that this is the default location for this company.
     */
    public $isDefault;

    /**
     * @var boolean Set this flag to true to indicate that this location has been registered with a tax authority.
     */
    public $isRegistered;

    /**
     * @var string If this location has a different business name from its legal entity name, specify the "Doing Business As" name for this location.
     */
    public $dbaName;

    /**
     * @var string A friendly name for this location.
     */
    public $outletName;

    /**
     * @var string The date when this location was opened for business, or null if not known.
     */
    public $effectiveDate;

    /**
     * @var string If this place of business has closed, the date when this location closed business.
     */
    public $endDate;

    /**
     * @var string The most recent date when a transaction was processed for this location. Set by AvaTax.
     */
    public $lastTransactionDate;

    /**
     * @var string The date when this location was registered with a tax authority. Not required.
     */
    public $registeredDate;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var LocationSettingModel[] Extra information required by certain jurisdictions for filing.  For a list of settings recognized by Avalara, query the endpoint "/api/v2/definitions/locationquestions".   To determine the list of settings required for this location, query the endpoint "/api/v2/companies/(id)/locations/(id)/validate".
     */
    public $settings;

}

/**
 * Represents a declaration of nexus within a particular taxing jurisdiction.
 */
class NexusModel
{

    /**
     * @var int The unique ID number of this declaration of nexus.
     */
    public $id;

    /**
     * @var int The unique ID number of the company that declared nexus.
     */
    public $companyId;

    /**
     * @var string The two character ISO-3166 country code of the country in which this company declared nexus.
     */
    public $country;

    /**
     * @var string The two or three character ISO region code of the region, state, or province in which this company declared nexus.
     */
    public $region;

    /**
     * @var string The jurisdiction type of the jurisdiction in which this company declared nexus. (See JurisTypeId::* for a list of allowable values)
     */
    public $jurisTypeId;

    /**
     * @var string The code identifying the jurisdiction in which this company declared nexus.
     */
    public $jurisCode;

    /**
     * @var string The common name of the jurisdiction in which this company declared nexus.
     */
    public $jurisName;

    /**
     * @var string The date when this nexus began. If not known, set to null.
     */
    public $effectiveDate;

    /**
     * @var string If this nexus will end or has ended on a specific date, set this to the date when this nexus ends.
     */
    public $endDate;

    /**
     * @var string The short name of the jurisdiction.
     */
    public $shortName;

    /**
     * @var string The signature code of the boundary region as defined by Avalara.
     */
    public $signatureCode;

    /**
     * @var string The state assigned number of this jurisdiction.
     */
    public $stateAssignedNo;

    /**
     * @var string The type of nexus that this company is declaring. (See NexusTypeId::* for a list of allowable values)
     */
    public $nexusTypeId;

    /**
     * @var string Indicates whether this nexus is defined as origin or destination nexus. (See Sourcing::* for a list of allowable values)
     */
    public $sourcing;

    /**
     * @var boolean True if you are also declaring local nexus within this jurisdiction.  Many U.S. states have options for declaring nexus in local jurisdictions as well as within the state.
     */
    public $hasLocalNexus;

    /**
     * @var string If you are declaring local nexus within this jurisdiction, this indicates whether you are declaring only   a specified list of local jurisdictions, all state-administered local jurisdictions, or all local jurisdictions. (See LocalNexusTypeId::* for a list of allowable values)
     */
    public $localNexusTypeId;

    /**
     * @var boolean Set this value to true if your company has a permanent establishment within this jurisdiction.
     */
    public $hasPermanentEstablishment;

    /**
     * @var string Optional - the tax identification number under which you declared nexus.
     */
    public $taxId;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * This object is used to keep track of custom information about a company.
 * A setting can refer to any type of data you need to remember about this company object.
 * When creating this object, you may define your own "set", "name", and "value" parameters.
 * To define your own values, please choose a "set" name that begins with "X-" to indicate an extension.
 */
class SettingModel
{

    /**
     * @var int The unique ID number of this setting.
     */
    public $id;

    /**
     * @var int The unique ID number of the company this setting refers to.
     */
    public $companyId;

    /**
     * @var string A user-defined "set" containing this name-value pair.
     */
    public $set;

    /**
     * @var string A user-defined "name" for this name-value pair.
     */
    public $name;

    /**
     * @var string The value of this name-value pair.
     */
    public $value;

}

/**
 * Represents a tax code that can be applied to items on a transaction.
 * A tax code can have specific rules for specific jurisdictions that change the tax calculation behavior.
 */
class TaxCodeModel
{

    /**
     * @var int The unique ID number of this tax code.
     */
    public $id;

    /**
     * @var int The unique ID number of the company that owns this tax code.
     */
    public $companyId;

    /**
     * @var string A code string that identifies this tax code.
     */
    public $taxCode;

    /**
     * @var string The type of this tax code.
     */
    public $taxCodeTypeId;

    /**
     * @var string A friendly description of this tax code.
     */
    public $description;

    /**
     * @var string If this tax code is a subset of a different tax code, this identifies the parent code.
     */
    public $parentTaxCode;

    /**
     * @var boolean True if this tax code refers to a physical object.
     */
    public $isPhysical;

    /**
     * @var int The Avalara Goods and Service Code represented by this tax code.
     */
    public $goodsServiceCode;

    /**
     * @var string The Avalara Entity Use Code represented by this tax code.
     */
    public $entityUseCode;

    /**
     * @var boolean True if this tax code is active and can be used in transactions.
     */
    public $isActive;

    /**
     * @var boolean True if this tax code has been certified by the Streamlined Sales Tax governing board.  By default, you should leave this value empty.
     */
    public $isSSTCertified;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * Represents a tax rule that changes the behavior of Avalara's tax engine for certain products in certain jurisdictions.
 */
class TaxRuleModel
{

    /**
     * @var int The unique ID number of this tax rule.
     */
    public $id;

    /**
     * @var int The unique ID number of the company that owns this tax rule.
     */
    public $companyId;

    /**
     * @var int The unique ID number of the tax code for this rule.  When creating or updating a tax rule, you may specify either the taxCodeId value or the taxCode value.
     */
    public $taxCodeId;

    /**
     * @var string The code string of the tax code for this rule.  When creating or updating a tax rule, you may specify either the taxCodeId value or the taxCode value.
     */
    public $taxCode;

    /**
     * @var string For U.S. tax rules, this is the state's Federal Information Processing Standard (FIPS) code.
     */
    public $stateFIPS;

    /**
     * @var string The name of the jurisdiction to which this tax rule applies.
     */
    public $jurisName;

    /**
     * @var string The code of the jurisdiction to which this tax rule applies.
     */
    public $jurisCode;

    /**
     * @var string The type of the jurisdiction to which this tax rule applies. (See JurisTypeId::* for a list of allowable values)
     */
    public $jurisTypeId;

    /**
     * @var string The type of customer usage to which this rule applies.
     */
    public $customerUsageType;

    /**
     * @var string Indicates which tax types to which this rule applies. (See MatchingTaxType::* for a list of allowable values)
     */
    public $taxTypeId;

    /**
     * @var string Indicates the rate type to which this rule applies. (See RateType::* for a list of allowable values)
     */
    public $rateTypeId;

    /**
     * @var string This type value determines the behavior of the tax rule.  You can specify that this rule controls the product's taxability or exempt / nontaxable status, the product's rate   (for example, if you have been granted an official ruling for your product's rate that differs from the official rate),   or other types of behavior. (See TaxRuleTypeId::* for a list of allowable values)
     */
    public $taxRuleTypeId;

    /**
     * @var boolean Set this value to true if this tax rule applies in all jurisdictions.
     */
    public $isAllJuris;

    /**
     * @var float The corrected rate for this tax rule.
     */
    public $value;

    /**
     * @var float The maximum cap for the price of this item according to this rule.
     */
    public $cap;

    /**
     * @var float The per-unit threshold that must be met before this rule applies.
     */
    public $threshold;

    /**
     * @var string Custom option flags for this rule.
     */
    public $options;

    /**
     * @var string The first date at which this rule applies. If null, this rule will apply to all dates prior to the end date.
     */
    public $effectiveDate;

    /**
     * @var string The last date for which this rule applies. If null, this rule will apply to all dates after the effective date.
     */
    public $endDate;

    /**
     * @var string A friendly name for this tax rule.
     */
    public $description;

    /**
     * @var string For U.S. tax rules, this is the county's Federal Information Processing Standard (FIPS) code.
     */
    public $countyFIPS;

    /**
     * @var boolean If true, indicates this rule is for Sales Tax Pro.
     */
    public $isSTPro;

    /**
     * @var string The two character ISO 3166 country code for the locations where this rule applies.
     */
    public $country;

    /**
     * @var string The state, region, or province name for the locations where this rule applies.
     */
    public $region;

    /**
     * @var string The sourcing types to which this rule applies. (See Sourcing::* for a list of allowable values)
     */
    public $sourcing;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * One Universal Product Code object as defined for your company.
 */
class UPCModel
{

    /**
     * @var int The unique ID number for this UPC.
     */
    public $id;

    /**
     * @var int The unique ID number of the company to which this UPC belongs.
     */
    public $companyId;

    /**
     * @var string The 12-14 character Universal Product Code, European Article Number, or Global Trade Identification Number.
     */
    public $upc;

    /**
     * @var string Legacy Tax Code applied to any product sold with this UPC.
     */
    public $legacyTaxCode;

    /**
     * @var string Description of the product to which this UPC applies.
     */
    public $description;

    /**
     * @var string If this UPC became effective on a certain date, this contains the first date on which the UPC was effective.
     */
    public $effectiveDate;

    /**
     * @var string If this UPC expired or will expire on a certain date, this contains the last date on which the UPC was effective.
     */
    public $endDate;

    /**
     * @var int A usage identifier for this UPC code.
     */
    public $usage;

    /**
     * @var int A flag indicating whether this UPC code is attached to the AvaTax system or to a company.
     */
    public $isSystem;

    /**
     * @var string The date when this record was created.
     */
    public $createdDate;

    /**
     * @var int The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

/**
 * Represents the answer to one local jurisdiction question for a location.
 */
class LocationSettingModel
{

    /**
     * @var int The unique ID number of the location question answered.
     */
    public $questionId;

    /**
     * @var string The answer the user provided.
     */
    public $value;

}

/**
 * Company Initialization Model
 */
class CompanyInitializationModel
{

    /**
     * @var string Company Name
     */
    public $name;

    /**
     * @var string Company Code - used to distinguish between companies within your accounting system
     */
    public $companyCode;

    /**
     * @var string Vat Registration Id - leave blank if not known.
     */
    public $vatRegistrationId;

    /**
     * @var string United States Taxpayer ID number, usually your Employer Identification Number if you are a business or your   Social Security Number if you are an individual.  This value is required if you subscribe to Avalara Managed Returns or the SST Certified Service Provider services,   but it is optional if you do not subscribe to either of those services.
     */
    public $taxpayerIdNumber;

    /**
     * @var string Address Line1
     */
    public $line1;

    /**
     * @var string Line2
     */
    public $line2;

    /**
     * @var string Line3
     */
    public $line3;

    /**
     * @var string City
     */
    public $city;

    /**
     * @var string Two character ISO 3166 Region code for this company's primary business location.
     */
    public $region;

    /**
     * @var string Postal Code
     */
    public $postalCode;

    /**
     * @var string Two character ISO 3166 Country code for this company's primary business location.
     */
    public $country;

    /**
     * @var string First Name
     */
    public $firstName;

    /**
     * @var string Last Name
     */
    public $lastName;

    /**
     * @var string Title
     */
    public $title;

    /**
     * @var string Email
     */
    public $email;

    /**
     * @var string Phone Number
     */
    public $phoneNumber;

    /**
     * @var string Mobile Number
     */
    public $mobileNumber;

    /**
     * @var string Fax Number
     */
    public $faxNumber;

}

/**
 * Status of an Avalara Managed Returns funding configuration for a company
 */
class FundingStatusModel
{

    /**
     * @var int The unique ID number of this funding request
     */
    public $requestId;

    /**
     * @var int SubledgerProfileID
     */
    public $subledgerProfileID;

    /**
     * @var string CompanyID
     */
    public $companyID;

    /**
     * @var string Domain
     */
    public $domain;

    /**
     * @var string Recipient
     */
    public $recipient;

    /**
     * @var string Sender
     */
    public $sender;

    /**
     * @var string DocumentKey
     */
    public $documentKey;

    /**
     * @var string DocumentType
     */
    public $documentType;

    /**
     * @var string DocumentName
     */
    public $documentName;

    /**
     * @var FundingESignMethodReturn MethodReturn
     */
    public $methodReturn;

    /**
     * @var string Status
     */
    public $status;

    /**
     * @var string ErrorMessage
     */
    public $errorMessage;

    /**
     * @var string LastPolled
     */
    public $lastPolled;

    /**
     * @var string LastSigned
     */
    public $lastSigned;

    /**
     * @var string LastActivated
     */
    public $lastActivated;

    /**
     * @var int TemplateRequestId
     */
    public $templateRequestId;

}

/**
 * Represents the current status of a funding ESign method
 */
class FundingESignMethodReturn
{

    /**
     * @var string Method
     */
    public $method;

    /**
     * @var boolean JavaScriptReady
     */
    public $javaScriptReady;

    /**
     * @var string The actual javascript to use to render this object
     */
    public $javaScript;

}

/**
 * 
 */
class FundingInitiateModel
{

    /**
     * @var boolean Set this value to true to request an email to the recipient
     */
    public $requestEmail;

    /**
     * @var string If you have requested an email for funding setup, this is the recipient who will receive an   email inviting them to setup funding configuration for Avalara Managed Returns. The recipient can  then click on a link in the email and setup funding configuration for this company.
     */
    public $fundingEmailRecipient;

    /**
     * @var boolean Set this value to true to request an HTML-based funding widget that can be embedded within an   existing user interface. A user can then interact with the HTML-based funding widget to set up  funding information for the company.
     */
    public $requestWidget;

}

/**
 * Information about Avalara-defined tax code types.
 * This list is used when creating tax codes and tax rules.
 */
class TaxCodeTypesModel
{

    /**
     * @var object The list of Avalara-defined tax code types.
     */
    public $types;

}

/**
 * Represents a service or a subscription type.
 */
class SubscriptionTypeModel
{

    /**
     * @var int The unique ID number of this subscription type.
     */
    public $id;

    /**
     * @var string The friendly name of the service this subscription type represents.
     */
    public $description;

}

/**
 * Represents a single security role.
 */
class SecurityRoleModel
{

    /**
     * @var int The unique ID number of this security role.
     */
    public $id;

    /**
     * @var string A description of this security role
     */
    public $description;

}

/**
 * Tax Authority Model
 */
class TaxAuthorityModel
{

    /**
     * @var int The unique ID number of this tax authority.
     */
    public $id;

    /**
     * @var string The friendly name of this tax authority.
     */
    public $name;

    /**
     * @var int The type of this tax authority.
     */
    public $taxAuthorityTypeId;

    /**
     * @var int The unique ID number of the jurisdiction for this tax authority.
     */
    public $jurisdictionId;

}

/**
 * Represents a form that can be filed with a tax authority.
 */
class TaxAuthorityFormModel
{

    /**
     * @var int The unique ID number of the tax authority.
     */
    public $taxAuthorityId;

    /**
     * @var string The form name of the form for this tax authority.
     */
    public $formName;

}

/**
 * An extra property that can change the behavior of tax transactions.
 */
class ParameterModel
{

    /**
     * @var int The unique ID number of this property.
     */
    public $id;

    /**
     * @var string The service category of this property. Some properties may require that you subscribe to certain features of avatax before they can be used.
     */
    public $category;

    /**
     * @var string The name of the property. To use this property, add a field on the "properties" object of a /api/v2/companies/(code)/transactions/create call.
     */
    public $name;

    /**
     * @var string The data type of the property. (See ParameterBagDataType::* for a list of allowable values)
     */
    public $dataType;

    /**
     * @var string A full description of this property.
     */
    public $description;

}

/**
 * Information about questions that the local jurisdictions require for each location
 */
class LocationQuestionModel
{

    /**
     * @var int The unique ID number of this location setting type
     */
    public $id;

    /**
     * @var string This is the prompt for this question
     */
    public $question;

    /**
     * @var string If additional information is available about the location setting, this contains descriptive text to help  you identify the correct value to provide in this setting.
     */
    public $description;

    /**
     * @var string If available, this regular expression will verify that the input from the user is in the expected format.
     */
    public $regularExpression;

    /**
     * @var string If available, this is an example value that you can demonstrate to the user to show what is expected.
     */
    public $exampleValue;

    /**
     * @var string Indicates which jurisdiction requires this question
     */
    public $jurisdictionName;

    /**
     * @var string Indicates which type of jurisdiction requires this question (See JurisdictionType::* for a list of allowable values)
     */
    public $jurisdictionType;

    /**
     * @var string Indicates the country that this jurisdiction belongs to
     */
    public $jurisdictionCountry;

    /**
     * @var string Indicates the state, region, or province that this jurisdiction belongs to
     */
    public $jurisdictionRegion;

}

/**
 * Represents an ISO 3166 recognized country
 */
class IsoCountryModel
{

    /**
     * @var string The two character ISO 3166 country code
     */
    public $code;

    /**
     * @var string The full name of this country as it is known in US English
     */
    public $name;

    /**
     * @var boolean True if this country is a member of the European Union
     */
    public $isEuropeanUnion;

}

/**
 * Represents a region, province, or state within a country
 */
class IsoRegionModel
{

    /**
     * @var string The two-character ISO 3166 country code this region belongs to
     */
    public $countryCode;

    /**
     * @var string The three character ISO 3166 region code
     */
    public $code;

    /**
     * @var string The full name, using localized characters, for this region
     */
    public $name;

    /**
     * @var string The word in the local language that classifies what type of a region this represents
     */
    public $classification;

}

/**
 * Represents a code describing the intended use for a product that may affect its taxability
 */
class EntityUseCodeModel
{

    /**
     * @var string The Avalara-recognized entity use code for this definition
     */
    public $code;

    /**
     * @var string The name of this entity use code
     */
    public $name;

    /**
     * @var string Text describing the meaning of this use code
     */
    public $description;

    /**
     * @var string[] A list of countries where this use code is valid
     */
    public $validCountries;

}

/**
 * Represents a listing of all tax calculation data for filings and for accruing to future filings.
 */
class WorksheetModel
{

    /**
     * @var int The unique ID number of this filing.
     */
    public $id;

    /**
     * @var int The unique ID number of the company for this filing.
     */
    public $companyId;

    /**
     * @var int The month of the filing period for this tax filing.   The filing period represents the year and month of the last day of taxes being reported on this filing.   For example, an annual tax filing for Jan-Dec 2015 would have a filing period of Dec 2015.
     */
    public $month;

    /**
     * @var int The year of the filing period for this tax filing.  The filing period represents the year and month of the last day of taxes being reported on this filing.   For example, an annual tax filing for Jan-Dec 2015 would have a filing period of Dec 2015.
     */
    public $year;

    /**
     * @var string Indicates whether this is an original or an amended filing. (See WorksheetTypeId::* for a list of allowable values)
     */
    public $type;

    /**
     * @var string The current status of this tax filing. (See WorksheetStatusId::* for a list of allowable values)
     */
    public $status;

    /**
     * @var WorksheetBucketModel[] A listing of regional tax filings within this time period.
     */
    public $filingRegions;

}

/**
 * Worksheet Bucket
 */
class WorksheetBucketModel
{

    /**
     * @var int TODO
     */
    public $id;

    /**
     * @var string TODO
     */
    public $country;

    /**
     * @var string TODO
     */
    public $region;

    /**
     * @var float TODO
     */
    public $salesAmount;

    /**
     * @var float TODO
     */
    public $taxableAmount;

    /**
     * @var float TODO
     */
    public $taxAmount;

    /**
     * @var float TODO
     */
    public $taxAmountDue;

    /**
     * @var float TODO
     */
    public $nonTaxableAmount;

    /**
     * @var string TODO
     */
    public $approveDate;

    /**
     * @var string TODO
     */
    public $startDate;

    /**
     * @var string TODO
     */
    public $endDate;

    /**
     * @var boolean TODO
     */
    public $hasNexus;

    /**
     * @var string TODO (See WorksheetStatusId::* for a list of allowable values)
     */
    public $worksheetStatusId;

    /**
     * @var WorksheetReturnModel[] TODO
     */
    public $returns;

}

/**
 * TODO
 */
class WorksheetReturnModel
{

    /**
     * @var int TODO
     */
    public $id;

    /**
     * @var string TODO (See WorksheetStatusId::* for a list of allowable values)
     */
    public $status;

    /**
     * @var string TODO (See FilingFrequencyId::* for a list of allowable values)
     */
    public $filingFrequency;

    /**
     * @var string TODO
     */
    public $filedDate;

    /**
     * @var float TODO
     */
    public $salesAmount;

    /**
     * @var string TODO (See FilingTypeId::* for a list of allowable values)
     */
    public $filingType;

    /**
     * @var string TODO
     */
    public $formName;

    /**
     * @var float TODO
     */
    public $remitAmount;

    /**
     * @var string TODO
     */
    public $returnName;

    /**
     * @var string TODO
     */
    public $description;

    /**
     * @var float TODO
     */
    public $taxableAmount;

    /**
     * @var float TODO
     */
    public $taxAmount;

    /**
     * @var float TODO
     */
    public $taxDueAmount;

    /**
     * @var float TODO
     */
    public $nonTaxableAmount;

    /**
     * @var float TODO
     */
    public $nonTaxableDueAmount;

}

/**
 * Commit a worksheet for rebuilding
 */
class RebuildWorksheetModel
{

    /**
     * @var boolean Set this value to true in order to rebuild the worksheets.
     */
    public $commit;

}

/**
 * Tells you whether this location object has been correctly set up to the local jurisdiction's standards
 */
class LocationValidationModel
{

    /**
     * @var boolean True if the location has a value for each jurisdiction-required setting.  The user is required to ensure that the values are correct according to the jurisdiction; this flag  does not indicate whether the taxing jurisdiction has accepted the data you have provided.
     */
    public $settingsValidated;

    /**
     * @var LocationQuestionModel[] A list of settings that must be defined for this location
     */
    public $requiredSettings;

}

/**
 * Password Change Model
 */
class PasswordChangeModel
{

    /**
     * @var string Old Password
     */
    public $oldPassword;

    /**
     * @var string New Password
     */
    public $newPassword;

}

/**
 * Set Password Model
 */
class SetPasswordModel
{

    /**
     * @var string New Password
     */
    public $newPassword;

}

/**
 * Point-of-Sale Data Request Model
 */
class PointOfSaleDataRequestModel
{

    /**
     * @var string A unique code that references a company within your account.
     */
    public $companyCode;

    /**
     * @var string The date associated with the response content. Default is current date. This field can be used to backdate or postdate the response content.
     */
    public $documentDate;

    /**
     * @var string The format of your response. Formats include JSON, CSV, and XML. (See PointOfSaleFileType::* for a list of allowable values)
     */
    public $responseType;

    /**
     * @var string[] A list of tax codes to include in this point-of-sale file. If no tax codes are specified, response will include all distinct tax codes associated with the Items within your company.
     */
    public $taxCodes;

    /**
     * @var string[] A list of location codes to include in this point-of-sale file. If no location codes are specified, response will include all locations within your company.
     */
    public $locationCodes;

    /**
     * @var boolean Set this value to true to include Juris Code in the response.
     */
    public $includeJurisCodes;

    /**
     * @var int A unique code assoicated with the Partner you may be working with. If you are not working with a Partner or your Partner has not provided you an ID, leave null.
     */
    public $partnerId;

}

/**
 * Tax Rate Model
 */
class TaxRateModel
{

    /**
     * @var float Total Rate
     */
    public $totalRate;

    /**
     * @var RateModel[] Rates
     */
    public $rates;

}

/**
 * Rate Model
 */
class RateModel
{

    /**
     * @var float Rate
     */
    public $rate;

    /**
     * @var string Name
     */
    public $name;

    /**
     * @var string Type (See JurisdictionType::* for a list of allowable values)
     */
    public $type;

}

/**
 * A single transaction - for example, a sales invoice or purchase order.
 */
class TransactionModel
{

    /**
     * @var int The unique ID number of this transaction.
     */
    public $id;

    /**
     * @var string A unique customer-provided code identifying this transaction.
     */
    public $code;

    /**
     * @var int The unique ID number of the company that recorded this transaction.
     */
    public $companyId;

    /**
     * @var string The date on which this transaction occurred.
     */
    public $date;

    /**
     * @var string The date that was used when calculating tax for this transaction.  By default, this should be the same as the transaction date; however, when a consumer returns a product purchased in a previous month,  it may be necessary to specify the date of the original transaction in order to correctly return the exact amount of sales tax that was  charged of the consumer on the original date they purchased the product.
     */
    public $taxDate;

    /**
     * @var string The date when payment was made on this transaction. By default, this should be the same as the date of the transaction.
     */
    public $paymentDate;

    /**
     * @var string The status of the transaction. (See DocumentStatus::* for a list of allowable values)
     */
    public $status;

    /**
     * @var string The type of the transaction. For Returns customers, a transaction type of "Invoice" will be reported to the tax authorities.  A sales transaction represents a sale from the company to a customer. A purchase transaction represents a purchase made by the company.  A return transaction represents a customer who decided to request a refund after purchasing a product from the company. An inventory   transfer transaction represents goods that were moved from one location of the company to another location without changing ownership. (See DocumentType::* for a list of allowable values)
     */
    public $type;

    /**
     * @var string If this transaction was created as part of a batch, this code indicates which batch.
     */
    public $batchCode;

    /**
     * @var string The three-character ISO 4217 currency code that was used for payment for this transaction.
     */
    public $currencyCode;

    /**
     * @var string The customer usage type for this transaction. Customer usage types often affect exemption or taxability rules.
     */
    public $customerUsageType;

    /**
     * @var string CustomerVendorCode
     */
    public $customerVendorCode;

    /**
     * @var string If this transaction was exempt, this field will contain the word "Exempt".
     */
    public $exemptNo;

    /**
     * @var boolean If this transaction has been reconciled against the company's ledger, this value is set to true.
     */
    public $reconciled;

    /**
     * @var string If this transaction was made from a specific reporting location, this is the code string of the location.  For customers using Returns, this indicates how tax will be reported according to different locations on the tax forms.
     */
    public $locationCode;

    /**
     * @var string The customer-supplied purchase order number of this transaction.
     */
    public $purchaseOrderNo;

    /**
     * @var string A user-defined reference code for this transaction.
     */
    public $referenceCode;

    /**
     * @var string The salesperson who provided this transaction. Not required.
     */
    public $salespersonCode;

    /**
     * @var string If a tax override was applied to this transaction, indicates what type of tax override was applied. (See TaxOverrideTypeId::* for a list of allowable values)
     */
    public $taxOverrideType;

    /**
     * @var float If a tax override was applied to this transaction, indicates the amount of tax that was requested by the customer.
     */
    public $taxOverrideAmount;

    /**
     * @var string If a tax override was applied to this transaction, indicates the reason for the tax override.
     */
    public $taxOverrideReason;

    /**
     * @var float The total amount of this transaction.
     */
    public $totalAmount;

    /**
     * @var float The amount of this transaction that was exempt.
     */
    public $totalExempt;

    /**
     * @var float The total tax calculated for all lines in this transaction.
     */
    public $totalTax;

    /**
     * @var float The portion of the total amount of this transaction that was taxable.
     */
    public $totalTaxable;

    /**
     * @var float If a tax override was applied to this transaction, indicates the amount of tax Avalara calculated for the transaction.
     */
    public $totalTaxCalculated;

    /**
     * @var string If this transaction was adjusted, indicates the unique ID number of the reason why the transaction was adjusted. (See AdjustmentReason::* for a list of allowable values)
     */
    public $adjustmentReason;

    /**
     * @var string If this transaction was adjusted, indicates a description of the reason why the transaction was adjusted.
     */
    public $adjustmentDescription;

    /**
     * @var boolean If this transaction has been reported to a tax authority, this transaction is considered locked and may not be adjusted after reporting.
     */
    public $locked;

    /**
     * @var string The two-or-three character ISO region code of the region for this transaction.
     */
    public $region;

    /**
     * @var string The two-character ISO 3166 code of the country for this transaction.
     */
    public $country;

    /**
     * @var int If this transaction was adjusted, this indicates the version number of this transaction. Incremented each time the transaction  is adjusted.
     */
    public $version;

    /**
     * @var string The software version used to calculate this transaction.
     */
    public $softwareVersion;

    /**
     * @var int The unique ID number of the origin address for this transaction.
     */
    public $originAddressId;

    /**
     * @var int The unique ID number of the destination address for this transaction.
     */
    public $destinationAddressId;

    /**
     * @var string If this transaction included foreign currency exchange, this is the date as of which the exchange rate was calculated.
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var float If this transaction included foreign currency exchange, this is the exchange rate that was used.
     */
    public $exchangeRate;

    /**
     * @var boolean If true, this seller was considered the importer of record of a product shipped internationally.
     */
    public $isSellerImporterOfRecord;

    /**
     * @var string Description of this transaction.
     */
    public $description;

    /**
     * @var string Email address associated with this transaction.
     */
    public $email;

    /**
     * @var string The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var int The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var TransactionLineModel[] Optional: A list of line items in this transaction. To fetch this list, add the query string "?$include=Lines" or "?$include=Details" to your URL.
     */
    public $lines;

    /**
     * @var TransactionAddressModel[] Optional: A list of line items in this transaction. To fetch this list, add the query string "?$include=Addresses" to your URL.
     */
    public $addresses;

    /**
     * @var TransactionModel[] If this transaction has been adjusted, this list contains all the previous versions of the document.
     */
    public $history;

    /**
     * @var TransactionSummary[] Contains a summary of tax on this transaction.
     */
    public $summary;

    /**
     * @var object Contains a list of extra parameters that were set when the transaction was created.
     */
    public $parameters;

    /**
     * @var AvaTaxMessage[] List of informational and warning messages regarding this API call. These messages are only relevant to the current API call.
     */
    public $messages;

}

/**
 * One line item on this transaction.
 */
class TransactionLineModel
{

    /**
     * @var int The unique ID number of this transaction line item.
     */
    public $id;

    /**
     * @var int The unique ID number of the transaction to which this line item belongs.
     */
    public $transactionId;

    /**
     * @var string The line number or code indicating the line on this invoice or receipt or document.
     */
    public $lineNumber;

    /**
     * @var int The unique ID number of the boundary override applied to this line item.
     */
    public $boundaryOverrideId;

    /**
     * @var string The customer usage type for this line item. Usage type often affects taxability rules.
     */
    public $customerUsageType;

    /**
     * @var string A description of the item or service represented by this line.
     */
    public $description;

    /**
     * @var int The unique ID number of the destination address where this line was delivered or sold.  In the case of a point-of-sale transaction, the destination address and origin address will be the same.  In the case of a shipped transaction, they will be different.
     */
    public $destinationAddressId;

    /**
     * @var int The unique ID number of the origin address where this line was delivered or sold.  In the case of a point-of-sale transaction, the origin address and destination address will be the same.  In the case of a shipped transaction, they will be different.
     */
    public $originAddressId;

    /**
     * @var float The amount of discount that was applied to this line item. This represents the difference between list price and sale price of the item.  In general, a discount represents money that did not change hands; tax is calculated on only the amount of money that changed hands.
     */
    public $discountAmount;

    /**
     * @var int The type of discount, if any, that was applied to this line item.
     */
    public $discountTypeId;

    /**
     * @var float The amount of this line item that was exempt.
     */
    public $exemptAmount;

    /**
     * @var int The unique ID number of the exemption certificate that applied to this line item.
     */
    public $exemptCertId;

    /**
     * @var string If this line item was exempt, this string contains the word 'Exempt'.
     */
    public $exemptNo;

    /**
     * @var boolean True if this item is taxable.
     */
    public $isItemTaxable;

    /**
     * @var boolean True if this item is a Streamlined Sales Tax line item.
     */
    public $isSSTP;

    /**
     * @var string The code string of the item represented by this line item.
     */
    public $itemCode;

    /**
     * @var float The total amount of the transaction, including both taxable and exempt. This is the total price for all items.  To determine the individual item price, divide this by quantity.
     */
    public $lineAmount;

    /**
     * @var float The quantity of products sold on this line item.
     */
    public $quantity;

    /**
     * @var string A user-defined reference identifier for this transaction line item.
     */
    public $ref1;

    /**
     * @var string A user-defined reference identifier for this transaction line item.
     */
    public $ref2;

    /**
     * @var string The date when this transaction should be reported. By default, all transactions are reported on the date when the actual transaction took place.  In some cases, line items may be reported later due to delayed shipments or other business reasons.
     */
    public $reportingDate;

    /**
     * @var string The revenue account number for this line item.
     */
    public $revAccount;

    /**
     * @var string Indicates whether this line item was taxed according to the origin or destination. (See Sourcing::* for a list of allowable values)
     */
    public $sourcing;

    /**
     * @var float The amount of tax generated for this line item.
     */
    public $tax;

    /**
     * @var float The taxable amount of this line item.
     */
    public $taxableAmount;

    /**
     * @var float The tax calculated for this line by Avalara. If the transaction was calculated with a tax override, this amount will be different from the "tax" value.
     */
    public $taxCalculated;

    /**
     * @var string The code string for the tax code that was used to calculate this line item.
     */
    public $taxCode;

    /**
     * @var int The unique ID number for the tax code that was used to calculate this line item.
     */
    public $taxCodeId;

    /**
     * @var string The date that was used for calculating tax amounts for this line item. By default, this date should be the same as the document date.  In some cases, for example when a consumer returns a product purchased previously, line items may be calculated using a tax date in the past  so that the consumer can receive a refund for the correct tax amount that was charged when the item was originally purchased.
     */
    public $taxDate;

    /**
     * @var string The tax engine identifier that was used to calculate this line item.
     */
    public $taxEngine;

    /**
     * @var string If a tax override was specified, this indicates the type of tax override. (See TaxOverrideTypeId::* for a list of allowable values)
     */
    public $taxOverrideType;

    /**
     * @var float If a tax override was specified, this indicates the amount of tax that was requested.
     */
    public $taxOverrideAmount;

    /**
     * @var string If a tax override was specified, represents the reason for the tax override.
     */
    public $taxOverrideReason;

    /**
     * @var boolean True if tax was included in the purchase price of the item.
     */
    public $taxIncluded;

    /**
     * @var TransactionLineDetailModel[] Optional: A list of tax details for this line item. To fetch this list, add the query string "?$include=Details" to your URL.
     */
    public $details;

    /**
     * @var object Contains a list of extra parameters that were set when the transaction was created.
     */
    public $parameters;

}

/**
 * An address used within this transaction.
 */
class TransactionAddressModel
{

    /**
     * @var int The unique ID number of this address.
     */
    public $id;

    /**
     * @var int The unique ID number of the document to which this address belongs.
     */
    public $transactionId;

    /**
     * @var string The boundary level at which this address was validated. (See BoundaryLevel::* for a list of allowable values)
     */
    public $boundaryLevel;

    /**
     * @var string The first line of the address.
     */
    public $line1;

    /**
     * @var string The second line of the address.
     */
    public $line2;

    /**
     * @var string The third line of the address.
     */
    public $line3;

    /**
     * @var string The city for the address.
     */
    public $city;

    /**
     * @var string The region, state, or province for the address.
     */
    public $region;

    /**
     * @var string The postal code or zip code for the address.
     */
    public $postalCode;

    /**
     * @var string The country for the address.
     */
    public $country;

    /**
     * @var int The unique ID number of the tax region for this address.
     */
    public $taxRegionId;

}

/**
 * Summary information about an overall transaction.
 */
class TransactionSummary
{

    /**
     * @var string Two character ISO-3166 country code.
     */
    public $country;

    /**
     * @var string Two or three character ISO region, state or province code, if applicable.
     */
    public $region;

    /**
     * @var string The type of jurisdiction that collects this tax. (See JurisdictionType::* for a list of allowable values)
     */
    public $jurisType;

    /**
     * @var string Jurisdiction Code for the taxing jurisdiction
     */
    public $jurisCode;

    /**
     * @var string The name of the jurisdiction that collects this tax.
     */
    public $jurisName;

    /**
     * @var int The unique ID of the Tax Authority Type that collects this tax.
     */
    public $taxAuthorityType;

    /**
     * @var string The state assigned number of the jurisdiction that collects this tax.
     */
    public $stateAssignedNo;

    /**
     * @var string The tax type of this tax. (See TaxType::* for a list of allowable values)
     */
    public $taxType;

    /**
     * @var string The name of the tax.
     */
    public $taxName;

    /**
     * @var string Group code when special grouping is enabled.
     */
    public $taxGroup;

    /**
     * @var string Indicates the tax rate type. (See RateType::* for a list of allowable values)
     */
    public $rateType;

    /**
     * @var float Tax Base - The adjusted taxable amount.
     */
    public $taxable;

    /**
     * @var float Tax Rate - The rate of taxation, as a fraction of the amount.
     */
    public $rate;

    /**
     * @var float Tax amount - The calculated tax (Base * Rate).
     */
    public $tax;

    /**
     * @var float Tax Calculated by Avalara AvaTax. This may be overriden by a TaxOverride.TaxAmount.
     */
    public $taxCalculated;

    /**
     * @var float The amount of the transaction that was non-taxable.
     */
    public $nonTaxable;

    /**
     * @var float The amount of the transaction that was exempt.
     */
    public $exemption;

}

/**
 * An individual tax detail element. Represents the amount of tax calculated for a particular jurisdiction, for a particular line in an invoice.
 */
class TransactionLineDetailModel
{

    /**
     * @var int The unique ID number of this tax detail.
     */
    public $id;

    /**
     * @var int The unique ID number of the line within this transaction.
     */
    public $transactionLineId;

    /**
     * @var int The unique ID number of this transaction.
     */
    public $transactionId;

    /**
     * @var int The unique ID number of the address used for this tax detail.
     */
    public $addressId;

    /**
     * @var string The two character ISO 3166 country code of the country where this tax detail is assigned.
     */
    public $country;

    /**
     * @var string The two-or-three character ISO region code for the region where this tax detail is assigned.
     */
    public $region;

    /**
     * @var string For U.S. transactions, the Federal Information Processing Standard (FIPS) code for the county where this tax detail is assigned.
     */
    public $countyFIPS;

    /**
     * @var string For U.S. transactions, the Federal Information Processing Standard (FIPS) code for the state where this tax detail is assigned.
     */
    public $stateFIPS;

    /**
     * @var float The amount of this line that was considered exempt in this tax detail.
     */
    public $exemptAmount;

    /**
     * @var int The unique ID number of the exemption reason for this tax detail.
     */
    public $exemptReasonId;

    /**
     * @var boolean True if this detail element represented an in-state transaction.
     */
    public $inState;

    /**
     * @var string The code of the jurisdiction to which this tax detail applies.
     */
    public $jurisCode;

    /**
     * @var string The name of the jurisdiction to which this tax detail applies.
     */
    public $jurisName;

    /**
     * @var int The unique ID number of the jurisdiction to which this tax detail applies.
     */
    public $jurisdictionId;

    /**
     * @var string The Avalara-specified signature code of the jurisdiction to which this tax detail applies.
     */
    public $signatureCode;

    /**
     * @var string The state assigned number of the jurisdiction to which this tax detail applies.
     */
    public $stateAssignedNo;

    /**
     * @var string The type of the jurisdiction to which this tax detail applies. (See JurisTypeId::* for a list of allowable values)
     */
    public $jurisType;

    /**
     * @var float The amount of this line item that was considered nontaxable in this tax detail.
     */
    public $nonTaxableAmount;

    /**
     * @var int The rule according to which portion of this detail was considered nontaxable.
     */
    public $nonTaxableRuleId;

    /**
     * @var string The type of nontaxability that was applied to this tax detail. (See TaxRuleTypeId::* for a list of allowable values)
     */
    public $nonTaxableType;

    /**
     * @var float The rate at which this tax detail was calculated.
     */
    public $rate;

    /**
     * @var int The unique ID number of the rule according to which this tax detail was calculated.
     */
    public $rateRuleId;

    /**
     * @var int The unique ID number of the source of the rate according to which this tax detail was calculated.
     */
    public $rateSourceId;

    /**
     * @var string For Streamlined Sales Tax customers, the SST Electronic Return code under which this tax detail should be applied.
     */
    public $serCode;

    /**
     * @var string Indicates whether this tax detail applies to the origin or destination of the transaction. (See Sourcing::* for a list of allowable values)
     */
    public $sourcing;

    /**
     * @var float The amount of tax for this tax detail.
     */
    public $tax;

    /**
     * @var float The taxable amount of this tax detail.
     */
    public $taxableAmount;

    /**
     * @var string The type of tax that was calculated. Depends on the company's nexus settings as well as the jurisdiction's tax laws. (See TaxType::* for a list of allowable values)
     */
    public $taxType;

    /**
     * @var string The name of the tax against which this tax amount was calculated.
     */
    public $taxName;

    /**
     * @var int The type of the tax authority to which this tax will be remitted.
     */
    public $taxAuthorityTypeId;

    /**
     * @var int The unique ID number of the tax region.
     */
    public $taxRegionId;

    /**
     * @var float The amount of tax that was calculated. This amount may be different if a tax override was used.  If the customer specified a tax override, this calculated tax value represents the amount of tax that would  have been charged if Avalara had calculated the tax for the rule.
     */
    public $taxCalculated;

    /**
     * @var float The amount of tax override that was specified for this tax line.
     */
    public $taxOverride;

    /**
     * @var string The rate type for this tax detail. (See RateType::* for a list of allowable values)
     */
    public $rateType;

    /**
     * @var float Number of units in this line item that were calculated to be taxable according to this rate detail.
     */
    public $taxableUnits;

    /**
     * @var float Number of units in this line item that were calculated to be nontaxable according to this rate detail.
     */
    public $nonTaxableUnits;

    /**
     * @var float Number of units in this line item that were calculated to be exempt according to this rate detail.
     */
    public $exemptUnits;

    /**
     * @var string When calculating units, what basis of measurement did we use for calculating the units?
     */
    public $unitOfBasis;

}

/**
 * A request to adjust tax for a previously existing transaction
 */
class AdjustTransactionModel
{

    /**
     * @var string A reason code indicating why this adjustment was made (See AdjustmentReason::* for a list of allowable values)
     */
    public $adjustmentReason;

    /**
     * @var string If the AdjustmentReason is "Other", specify the reason here
     */
    public $adjustmentDescription;

    /**
     * @var CreateTransactionModel Replace the current transaction with tax data calculated for this new transaction
     */
    public $newTransaction;

}

/**
 * Create a transaction
 */
class CreateTransactionModel
{

    /**
     * @var string Document Type (See DocumentType::* for a list of allowable values)
     */
    public $type;

    /**
     * @var string Transaction Code - the internal reference code used by the client application. This is used for operations such as  Get, Adjust, Settle, and Void. If you leave the transaction code blank, a GUID will be assigned to each transaction.
     */
    public $code;

    /**
     * @var string Company Code - Specify the code of the company creating this transaction here. If you leave this value null,  your account's default company will be used instead.
     */
    public $companyCode;

    /**
     * @var string Transaction Date - The date on the invoice, purchase order, etc.
     */
    public $date;

    /**
     * @var string Salesperson Code - The client application salesperson reference code.
     */
    public $salespersonCode;

    /**
     * @var string Customer Code - The client application customer reference code.
     */
    public $customerCode;

    /**
     * @var string Customer Usage Type - The client application customer or usage type.
     */
    public $customerUsageType;

    /**
     * @var float Discount - The discount amount to apply to the document.
     */
    public $discount;

    /**
     * @var string Purchase Order Number for this document
     */
    public $purchaseOrderNo;

    /**
     * @var string Exemption Number for this document
     */
    public $exemptionNo;

    /**
     * @var object Default addresses for all lines in this document
     */
    public $addresses;

    /**
     * @var LineItemModel[] Document line items list
     */
    public $lines;

    /**
     * @var object Special parameters for this transaction.  To get a full list of available parameters, please use the /api/v2/definitions/parameters endpoint.
     */
    public $parameters;

    /**
     * @var string Reference Code used to reference the original document for a return invoice
     */
    public $referenceCode;

    /**
     * @var string Sets the sale location code (Outlet ID) for reporting this document to the tax authority.
     */
    public $reportingLocationCode;

    /**
     * @var boolean Causes the document to be committed if true.
     */
    public $commit;

    /**
     * @var string BatchCode for batch operations.
     */
    public $batchCode;

    /**
     * @var TaxOverrideModel Specifies a tax override for the entire document
     */
    public $taxOverride;

    /**
     * @var string Indicates the tax effectivity override date for the entire document.
     */
    public $taxDate;

    /**
     * @var string 3 character ISO 4217 currency code.
     */
    public $currencyCode;

    /**
     * @var string Specifies whether the tax calculation is handled Local, Remote, or Automatic (default) (See ServiceMode::* for a list of allowable values)
     */
    public $serviceMode;

    /**
     * @var float Currency exchange rate from this transaction to the company base currency.
     */
    public $exchangeRate;

    /**
     * @var string Effective date of the exchange rate.
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var string Sets the POS Lane Code sent by the User for this document.
     */
    public $posLaneCode;

    /**
     * @var string BusinessIdentificationNo
     */
    public $businessIdentificationNo;

    /**
     * @var boolean Specifies if the Transaction has the seller as IsSellerImporterOfRecord
     */
    public $isSellerImporterOfRecord;

    /**
     * @var string Description
     */
    public $description;

    /**
     * @var string Email
     */
    public $email;

    /**
     * @var string If the user wishes to request additional debug information from this transaction, specify a level higher than 'normal' (See TaxDebugLevel::* for a list of allowable values)
     */
    public $debugLevel;

}

/**
 * Represents an address to resolve.
 */
class AddressLocationInfo
{

    /**
     * @var string If you wish to use the address of an existing location for this company, specify the address here.  Otherwise, leave this value empty.
     */
    public $locationCode;

    /**
     * @var string Line1
     */
    public $line1;

    /**
     * @var string Line2
     */
    public $line2;

    /**
     * @var string Line3
     */
    public $line3;

    /**
     * @var string City
     */
    public $city;

    /**
     * @var string State / Province / Region
     */
    public $region;

    /**
     * @var string Two character ISO 3166 Country Code
     */
    public $country;

    /**
     * @var string Postal Code / Zip Code
     */
    public $postalCode;

    /**
     * @var float Geospatial latitude measurement
     */
    public $latitude;

    /**
     * @var float Geospatial longitude measurement
     */
    public $longitude;

}

/**
 * Represents one line item in a transaction
 */
class LineItemModel
{

    /**
     * @var string Line number within this document
     */
    public $number;

    /**
     * @var float Quantity of items in this line
     */
    public $quantity;

    /**
     * @var float Total amount for this line
     */
    public $amount;

    /**
     * @var object Specify any differences for addresses between this line and the rest of the document
     */
    public $addresses;

    /**
     * @var string Tax Code - System or Custom Tax Code.
     */
    public $taxCode;

    /**
     * @var string Customer Usage Type - The client application customer or usage type.
     */
    public $customerUsageType;

    /**
     * @var string Item Code (SKU)
     */
    public $itemCode;

    /**
     * @var string Exemption number for this line
     */
    public $exemptionCode;

    /**
     * @var boolean True if the document discount should be applied to this line
     */
    public $discounted;

    /**
     * @var boolean Indicates if line has Tax Included; defaults to false
     */
    public $taxIncluded;

    /**
     * @var string Revenue Account
     */
    public $revenueAccount;

    /**
     * @var string Reference 1 - Client specific reference field
     */
    public $ref1;

    /**
     * @var string Reference 2 - Client specific reference field
     */
    public $ref2;

    /**
     * @var string Item description. This is required for SST transactions if an unmapped ItemCode is used.
     */
    public $description;

    /**
     * @var string BusinessIdentificationNo
     */
    public $businessIdentificationNo;

    /**
     * @var TaxOverrideModel Specifies a tax override for this line
     */
    public $taxOverride;

    /**
     * @var object Special parameters that apply to this line within this transaction.  To get a full list of available parameters, please use the /api/v2/definitions/parameters endpoint.
     */
    public $parameters;

}

/**
 * Represents a tax override for a transaction
 */
class TaxOverrideModel
{

    /**
     * @var string Identifies the type of tax override (See TaxOverrideType::* for a list of allowable values)
     */
    public $type;

    /**
     * @var float Indicates a total override of the calculated tax on the document. AvaTax will distribute  the override across all the lines.
     */
    public $taxAmount;

    /**
     * @var string The override tax date to use
     */
    public $taxDate;

    /**
     * @var string This provides the reason for a tax override for audit purposes. It is required for types 2-4.
     */
    public $reason;

}

/**
 * A request to void a previously created transaction
 */
class VoidTransactionModel
{

    /**
     * @var string Please specify the reason for voiding or cancelling this transaction (See VoidReasonCode::* for a list of allowable values)
     */
    public $code;

}

/**
 * Settle this transaction with your ledger by executing one or many actions against that transaction. 
 * You may use this endpoint to verify the transaction, change the transaction's code, and commit the transaction for reporting purposes.
 * This endpoint may be used to execute any or all of these actions at once.
 */
class SettleTransactionModel
{

    /**
     * @var VerifyTransactionModel To use the "Settle" endpoint to verify a transaction, fill out this value.
     */
    public $verify;

    /**
     * @var ChangeTransactionCodeModel To use the "Settle" endpoint to change a transaction's code, fill out this value.
     */
    public $changeCode;

    /**
     * @var CommitTransactionModel To use the "Settle" endpoint to commit a transaction for reporting purposes, fill out this value.  If you use Avalara Returns, committing a transaction will cause that transaction to be filed.
     */
    public $commit;

}

/**
 * Verify this transaction by matching it to values in your accounting system.
 */
class VerifyTransactionModel
{

    /**
     * @var string Transaction Date - The date on the invoice, purchase order, etc.
     */
    public $verifyTransactionDate;

    /**
     * @var float Total Amount - The total amount (not including tax) for the document.
     */
    public $verifyTotalAmount;

    /**
     * @var float Total Tax - The total tax for the document.
     */
    public $verifyTotalTax;

}

/**
 * Settle this transaction with your ledger by verifying its amounts.
 * If the transaction is not yet committed, you may specify the "commit" value to commit it to the ledger and allow it to be reported.
 * You may also optionally change the transaction's code by specifying the "newTransactionCode" value.
 */
class ChangeTransactionCodeModel
{

    /**
     * @var string To change the transaction code for this transaction, specify the new transaction code here.
     */
    public $newCode;

}

/**
 * Commit this transaction as permanent
 */
class CommitTransactionModel
{

    /**
     * @var boolean Set this value to be true to commit this transaction.  Committing a transaction allows it to be reported on a tax return. Uncommitted transactions will not be reported.
     */
    public $commit;

}

/**
 * User Entitlement Model
 */
class UserEntitlementModel
{

    /**
     * @var string[] List of API names and categories that this user is permitted to access
     */
    public $permissions;

    /**
     * @var string What access privileges does the current user have to see companies? (See CompanyAccessLevel::* for a list of allowable values)
     */
    public $accessLevel;

    /**
     * @var int[] The identities of all companies this user is permitted to access
     */
    public $companies;

}

/**
 * Ping Result Model
 */
class PingResultModel
{

    /**
     * @var string Version number
     */
    public $version;

    /**
     * @var boolean Returns true if you provided authentication for this API call; false if you did not.
     */
    public $authenticated;

    /**
     * @var string Returns the type of authentication you provided, if authenticated (See AuthenticationTypeId::* for a list of allowable values)
     */
    public $authenticationType;

    /**
     * @var string The username of the currently authenticated user, if any.
     */
    public $authenticatedUserName;

}


/*****************************************************************************
 *                              Enumerated constants                         *
 *****************************************************************************/


/**
 * Lists of acceptable values for the enumerated data type AccountStatusId
 */
class AccountStatusId
{
    const C_INACTIVE = "Inactive";
    const C_ACTIVE = "Active";
    const C_TEST = "Test";
    const C_NEW = "New";

}


/**
 * Lists of acceptable values for the enumerated data type SecurityRoleId
 */
class SecurityRoleId
{
    const C_NOACCESS = "NoAccess";
    const C_SITEADMIN = "SiteAdmin";
    const C_ACCOUNTOPERATOR = "AccountOperator";
    const C_ACCOUNTADMIN = "AccountAdmin";
    const C_ACCOUNTUSER = "AccountUser";
    const C_SYSTEMADMIN = "SystemAdmin";
    const C_REGISTRAR = "Registrar";
    const C_CSPTESTER = "CSPTester";
    const C_CSPADMIN = "CSPAdmin";
    const C_SYSTEMOPERATOR = "SystemOperator";
    const C_TECHNICALSUPPORTUSER = "TechnicalSupportUser";
    const C_TECHNICALSUPPORTADMIN = "TechnicalSupportAdmin";
    const C_TREASURYUSER = "TreasuryUser";
    const C_TREASURYADMIN = "TreasuryAdmin";
    const C_COMPLIANCEUSER = "ComplianceUser";
    const C_COMPLIANCEADMIN = "ComplianceAdmin";
    const C_PROSTORESOPERATOR = "ProStoresOperator";
    const C_COMPANYUSER = "CompanyUser";
    const C_COMPANYADMIN = "CompanyAdmin";
    const C_COMPLIANCETEMPUSER = "ComplianceTempUser";
    const C_COMPLIANCEROOTUSER = "ComplianceRootUser";
    const C_COMPLIANCEOPERATOR = "ComplianceOperator";
    const C_SSTADMIN = "SSTAdmin";

}


/**
 * Lists of acceptable values for the enumerated data type PasswordStatusId
 */
class PasswordStatusId
{
    const C_USERCANNOTCHANGE = "UserCannotChange";
    const C_USERCANCHANGE = "UserCanChange";
    const C_USERMUSTCHANGE = "UserMustChange";

}


/**
 * Lists of acceptable values for the enumerated data type ErrorCodeId
 */
class ErrorCodeId
{
    const C_SERVERCONFIGURATION = "ServerConfiguration";
    const C_ACCOUNTINVALIDEXCEPTION = "AccountInvalidException";
    const C_COMPANYINVALIDEXCEPTION = "CompanyInvalidException";
    const C_ENTITYNOTFOUNDERROR = "EntityNotFoundError";
    const C_VALUEREQUIREDERROR = "ValueRequiredError";
    const C_RANGEERROR = "RangeError";
    const C_RANGECOMPAREERROR = "RangeCompareError";
    const C_RANGESETERROR = "RangeSetError";
    const C_TAXPAYERNUMBERREQUIRED = "TaxpayerNumberRequired";
    const C_COMMONPASSWORD = "CommonPassword";
    const C_WEAKPASSWORD = "WeakPassword";
    const C_STRINGLENGTHERROR = "StringLengthError";
    const C_EMAILVALIDATIONERROR = "EmailValidationError";
    const C_EMAILMISSINGERROR = "EmailMissingError";
    const C_PARSERFIELDNAMEERROR = "ParserFieldNameError";
    const C_PARSERFIELDVALUEERROR = "ParserFieldValueError";
    const C_PARSERSYNTAXERROR = "ParserSyntaxError";
    const C_PARSERTOOMANYPARAMETERSERROR = "ParserTooManyParametersError";
    const C_PARSERUNTERMINATEDVALUEERROR = "ParserUnterminatedValueError";
    const C_DELETEUSERSELFERROR = "DeleteUserSelfError";
    const C_OLDPASSWORDINVALID = "OldPasswordInvalid";
    const C_CANNOTCHANGEPASSWORD = "CannotChangePassword";
    const C_CANNOTCHANGECOMPANYCODE = "CannotChangeCompanyCode";
    const C_AUTHENTICATIONEXCEPTION = "AuthenticationException";
    const C_AUTHORIZATIONEXCEPTION = "AuthorizationException";
    const C_VALIDATIONEXCEPTION = "ValidationException";
    const C_INACTIVEUSERERROR = "InactiveUserError";
    const C_AUTHENTICATIONINCOMPLETE = "AuthenticationIncomplete";
    const C_BASICAUTHINCORRECT = "BasicAuthIncorrect";
    const C_IDENTITYSERVERERROR = "IdentityServerError";
    const C_BEARERTOKENINVALID = "BearerTokenInvalid";
    const C_MODELREQUIREDEXCEPTION = "ModelRequiredException";
    const C_ACCOUNTEXPIREDEXCEPTION = "AccountExpiredException";
    const C_VISIBILITYERROR = "VisibilityError";
    const C_BEARERTOKENNOTSUPPORTED = "BearerTokenNotSupported";
    const C_INVALIDSECURITYROLE = "InvalidSecurityRole";
    const C_INVALIDREGISTRARACTION = "InvalidRegistrarAction";
    const C_REMOTESERVERERROR = "RemoteServerError";
    const C_NOFILTERCRITERIAEXCEPTION = "NoFilterCriteriaException";
    const C_OPENCLAUSEEXCEPTION = "OpenClauseException";
    const C_JSONFORMATERROR = "JsonFormatError";
    const C_UNHANDLEDEXCEPTION = "UnhandledException";
    const C_REPORTINGCOMPANYMUSTHAVECONTACTSERROR = "ReportingCompanyMustHaveContactsError";
    const C_COMPANYPROFILENOTSET = "CompanyProfileNotSet";
    const C_MODELSTATEINVALID = "ModelStateInvalid";
    const C_DATERANGEERROR = "DateRangeError";
    const C_INVALIDDATERANGEERROR = "InvalidDateRangeError";
    const C_DELETEINFORMATION = "DeleteInformation";
    const C_CANNOTCREATEDELETEDOBJECTS = "CannotCreateDeletedObjects";
    const C_CANNOTMODIFYDELETEDOBJECTS = "CannotModifyDeletedObjects";
    const C_RETURNNAMENOTFOUND = "ReturnNameNotFound";
    const C_INVALIDADDRESSTYPEANDCATEGORY = "InvalidAddressTypeAndCategory";
    const C_DEFAULTCOMPANYLOCATION = "DefaultCompanyLocation";
    const C_INVALIDCOUNTRY = "InvalidCountry";
    const C_INVALIDCOUNTRYREGION = "InvalidCountryRegion";
    const C_BRAZILVALIDATIONERROR = "BrazilValidationError";
    const C_BRAZILEXEMPTVALIDATIONERROR = "BrazilExemptValidationError";
    const C_BRAZILPISCOFINSERROR = "BrazilPisCofinsError";
    const C_JURISDICTIONNOTFOUNDERROR = "JurisdictionNotFoundError";
    const C_MEDICALEXCISEERROR = "MedicalExciseError";
    const C_RATEDEPENDSTAXABILITYERROR = "RateDependsTaxabilityError";
    const C_RATEDEPENDSEUROPEERROR = "RateDependsEuropeError";
    const C_RATETYPENOTSUPPORTED = "RateTypeNotSupported";
    const C_CANNOTUPDATENESTEDOBJECTS = "CannotUpdateNestedObjects";
    const C_UPCCODEINVALIDCHARS = "UPCCodeInvalidChars";
    const C_UPCCODEINVALIDLENGTH = "UPCCodeInvalidLength";
    const C_INCORRECTPATHERROR = "IncorrectPathError";
    const C_INVALIDJURISDICTIONTYPE = "InvalidJurisdictionType";
    const C_MUSTCONFIRMRESETLICENSEKEY = "MustConfirmResetLicenseKey";
    const C_DUPLICATECOMPANYCODE = "DuplicateCompanyCode";
    const C_TINFORMATERROR = "TINFormatError";
    const C_DUPLICATENEXUSERROR = "DuplicateNexusError";
    const C_UNKNOWNNEXUSERROR = "UnknownNexusError";
    const C_PARENTNEXUSNOTFOUND = "ParentNexusNotFound";
    const C_INVALIDTAXCODETYPE = "InvalidTaxCodeType";
    const C_CANNOTACTIVATECOMPANY = "CannotActivateCompany";
    const C_DUPLICATEENTITYPROPERTY = "DuplicateEntityProperty";
    const C_BATCHSALESAUDITMUSTBEZIPPEDERROR = "BatchSalesAuditMustBeZippedError";
    const C_BATCHZIPMUSTCONTAINONEFILEERROR = "BatchZipMustContainOneFileError";
    const C_BATCHINVALIDFILETYPEERROR = "BatchInvalidFileTypeError";
    const C_POINTOFSALEFILESIZE = "PointOfSaleFileSize";
    const C_POINTOFSALESETUP = "PointOfSaleSetup";
    const C_GETTAXERROR = "GetTaxError";
    const C_ADDRESSCONFLICTEXCEPTION = "AddressConflictException";
    const C_DOCUMENTCODECONFLICT = "DocumentCodeConflict";
    const C_MISSINGADDRESS = "MissingAddress";
    const C_INVALIDPARAMETER = "InvalidParameter";
    const C_INVALIDPARAMETERVALUE = "InvalidParameterValue";
    const C_COMPANYCODECONFLICT = "CompanyCodeConflict";
    const C_DOCUMENTFETCHLIMIT = "DocumentFetchLimit";
    const C_ADDRESSINCOMPLETE = "AddressIncomplete";
    const C_ADDRESSLOCATIONNOTFOUND = "AddressLocationNotFound";
    const C_MISSINGLINE = "MissingLine";
    const C_BADDOCUMENTFETCH = "BadDocumentFetch";
    const C_SERVERUNREACHABLE = "ServerUnreachable";
    const C_SUBSCRIPTIONREQUIRED = "SubscriptionRequired";

}


/**
 * Lists of acceptable values for the enumerated data type ErrorTargetCode
 */
class ErrorTargetCode
{
    const C_UNKNOWN = "Unknown";
    const C_HTTPREQUEST = "HttpRequest";
    const C_HTTPREQUESTHEADERS = "HttpRequestHeaders";
    const C_INCORRECTDATA = "IncorrectData";
    const C_AVATAXAPISERVER = "AvaTaxApiServer";
    const C_AVALARAIDENTITYSERVER = "AvalaraIdentityServer";
    const C_CUSTOMERACCOUNTSETUP = "CustomerAccountSetup";

}


/**
 * Lists of acceptable values for the enumerated data type SeverityLevel
 */
class SeverityLevel
{
    const C_SUCCESS = "Success";
    const C_WARNING = "Warning";
    const C_ERROR = "Error";
    const C_EXCEPTION = "Exception";

}


/**
 * Lists of acceptable values for the enumerated data type ResolutionQuality
 */
class ResolutionQuality
{
    const C_NOTCODED = "NotCoded";
    const C_EXTERNAL = "External";
    const C_COUNTRYCENTROID = "CountryCentroid";
    const C_REGIONCENTROID = "RegionCentroid";
    const C_PARTIALCENTROID = "PartialCentroid";
    const C_POSTALCENTROIDGOOD = "PostalCentroidGood";
    const C_POSTALCENTROIDBETTER = "PostalCentroidBetter";
    const C_POSTALCENTROIDBEST = "PostalCentroidBest";
    const C_INTERSECTION = "Intersection";
    const C_INTERPOLATED = "Interpolated";
    const C_ROOFTOP = "Rooftop";
    const C_CONSTANT = "Constant";

}


/**
 * Lists of acceptable values for the enumerated data type JurisdictionType
 */
class JurisdictionType
{
    const C_COUNTRY = "Country";
    const C_COMPOSITE = "Composite";
    const C_STATE = "State";
    const C_COUNTY = "County";
    const C_CITY = "City";
    const C_SPECIAL = "Special";

}


/**
 * Lists of acceptable values for the enumerated data type BatchType
 */
class BatchType
{
    const C_AVACERTUPDATE = "AvaCertUpdate";
    const C_AVACERTUPDATEALL = "AvaCertUpdateAll";
    const C_BATCHMAINTENANCE = "BatchMaintenance";
    const C_COMPANYLOCATIONIMPORT = "CompanyLocationImport";
    const C_DOCUMENTIMPORT = "DocumentImport";
    const C_EXEMPTCERTIMPORT = "ExemptCertImport";
    const C_ITEMIMPORT = "ItemImport";
    const C_SALESAUDITEXPORT = "SalesAuditExport";
    const C_SSTPTESTDECKIMPORT = "SstpTestDeckImport";
    const C_TAXRULEIMPORT = "TaxRuleImport";
    const C_TRANSACTIONIMPORT = "TransactionImport";
    const C_UPCBULKIMPORT = "UPCBulkImport";
    const C_UPCVALIDATIONIMPORT = "UPCValidationImport";

}


/**
 * Lists of acceptable values for the enumerated data type BatchStatus
 */
class BatchStatus
{
    const C_WAITING = "Waiting";
    const C_SYSTEMERRORS = "SystemErrors";
    const C_CANCELLED = "Cancelled";
    const C_COMPLETED = "Completed";
    const C_CREATING = "Creating";
    const C_DELETED = "Deleted";
    const C_ERRORS = "Errors";
    const C_PAUSED = "Paused";
    const C_PROCESSING = "Processing";

}


/**
 * Lists of acceptable values for the enumerated data type RoundingLevelId
 */
class RoundingLevelId
{
    const C_LINE = "Line";
    const C_DOCUMENT = "Document";

}


/**
 * Lists of acceptable values for the enumerated data type TaxDependencyLevelId
 */
class TaxDependencyLevelId
{
    const C_DOCUMENT = "Document";
    const C_STATE = "State";
    const C_TAXREGION = "TaxRegion";
    const C_ADDRESS = "Address";

}


/**
 * Lists of acceptable values for the enumerated data type AddressTypeId
 */
class AddressTypeId
{
    const C_LOCATION = "Location";
    const C_SALESPERSON = "Salesperson";

}


/**
 * Lists of acceptable values for the enumerated data type AddressCategoryId
 */
class AddressCategoryId
{
    const C_STOREFRONT = "Storefront";
    const C_MAINOFFICE = "MainOffice";
    const C_WAREHOUSE = "Warehouse";
    const C_SALESPERSON = "Salesperson";
    const C_OTHER = "Other";

}


/**
 * Lists of acceptable values for the enumerated data type JurisTypeId
 */
class JurisTypeId
{
    const C_STA = "STA";
    const C_CTY = "CTY";
    const C_CIT = "CIT";
    const C_STJ = "STJ";
    const C_CNT = "CNT";

}


/**
 * Lists of acceptable values for the enumerated data type NexusTypeId
 */
class NexusTypeId
{
    const C_NONE = "None";
    const C_SALESORSELLERSUSETAX = "SalesOrSellersUseTax";
    const C_SALESTAX = "SalesTax";
    const C_SSTVOLUNTEER = "SSTVolunteer";
    const C_SSTNONVOLUNTEER = "SSTNonVolunteer";

}


/**
 * Lists of acceptable values for the enumerated data type Sourcing
 */
class Sourcing
{
    const C_MIXED = "Mixed";
    const C_DESTINATION = "Destination";
    const C_ORIGIN = "Origin";

}


/**
 * Lists of acceptable values for the enumerated data type LocalNexusTypeId
 */
class LocalNexusTypeId
{
    const C_SELECTED = "Selected";
    const C_STATEADMINISTERED = "StateAdministered";
    const C_ALL = "All";

}


/**
 * Lists of acceptable values for the enumerated data type MatchingTaxType
 */
class MatchingTaxType
{
    const C_ALL = "All";
    const C_BOTHSALESANDUSETAX = "BothSalesAndUseTax";
    const C_CONSUMERUSETAX = "ConsumerUseTax";
    const C_MEDICALEXCISE = "MedicalExcise";
    const C_FEE = "Fee";
    const C_VATINPUTTAX = "VATInputTax";
    const C_VATNONRECOVERABLEINPUTTAX = "VATNonrecoverableInputTax";
    const C_VATOUTPUTTAX = "VATOutputTax";
    const C_RENTAL = "Rental";
    const C_SALESTAX = "SalesTax";
    const C_USETAX = "UseTax";

}


/**
 * Lists of acceptable values for the enumerated data type RateType
 */
class RateType
{
    const C_REDUCEDA = "ReducedA";
    const C_REDUCEDB = "ReducedB";
    const C_FOOD = "Food";
    const C_GENERAL = "General";
    const C_INCREASEDSTANDARD = "IncreasedStandard";
    const C_LINENRENTAL = "LinenRental";
    const C_MEDICAL = "Medical";
    const C_PARKING = "Parking";
    const C_SUPERREDUCED = "SuperReduced";
    const C_REDUCEDR = "ReducedR";
    const C_STANDARD = "Standard";
    const C_ZERO = "Zero";

}


/**
 * Lists of acceptable values for the enumerated data type TaxRuleTypeId
 */
class TaxRuleTypeId
{
    const C_RATERULE = "RateRule";
    const C_RATEOVERRIDERULE = "RateOverrideRule";
    const C_BASERULE = "BaseRule";
    const C_EXEMPTENTITYRULE = "ExemptEntityRule";
    const C_PRODUCTTAXABILITYRULE = "ProductTaxabilityRule";
    const C_NEXUSRULE = "NexusRule";

}


/**
 * Lists of acceptable values for the enumerated data type ParameterBagDataType
 */
class ParameterBagDataType
{
    const C_STRING = "String";
    const C_BOOLEAN = "Boolean";
    const C_NUMERIC = "Numeric";

}


/**
 * Lists of acceptable values for the enumerated data type WorksheetTypeId
 */
class WorksheetTypeId
{
    const C_ORIGINAL = "Original";
    const C_AMENDED = "Amended";
    const C_TEST = "Test";

}


/**
 * Lists of acceptable values for the enumerated data type WorksheetStatusId
 */
class WorksheetStatusId
{
    const C_PENDINGAPPROVAL = "PendingApproval";
    const C_DIRTY = "Dirty";
    const C_APPROVEDTOFILE = "ApprovedToFile";
    const C_PENDINGFILING = "PendingFiling";
    const C_PENDINGFILINGONBEHALF = "PendingFilingOnBehalf";
    const C_FILED = "Filed";
    const C_FILEDONBEHALF = "FiledOnBehalf";
    const C_RETURNACCEPTED = "ReturnAccepted";
    const C_RETURNACCEPTEDONBEHALF = "ReturnAcceptedOnBehalf";
    const C_PAYMENTREMITTED = "PaymentRemitted";
    const C_VOIDED = "Voided";
    const C_PENDINGRETURN = "PendingReturn";
    const C_PENDINGRETURNONBEHALF = "PendingReturnOnBehalf";
    const C_DONOTFILE = "DoNotFile";
    const C_RETURNREJECTED = "ReturnRejected";
    const C_RETURNREJECTEDONBEHALF = "ReturnRejectedOnBehalf";
    const C_APPROVEDTOFILEONBEHALF = "ApprovedToFileOnBehalf";

}


/**
 * Lists of acceptable values for the enumerated data type FilingFrequencyId
 */
class FilingFrequencyId
{
    const C_MONTHLY = "Monthly";
    const C_QUARTERLY = "Quarterly";
    const C_SEMIANNUALLY = "SemiAnnually";
    const C_ANNUALLY = "Annually";
    const C_BIMONTHLY = "Bimonthly";
    const C_OCCASIONAL = "Occasional";
    const C_INVERSEQUARTERLY = "InverseQuarterly";

}


/**
 * Lists of acceptable values for the enumerated data type FilingTypeId
 */
class FilingTypeId
{
    const C_PAPERRETURN = "PaperReturn";
    const C_ELECTRONICRETURN = "ElectronicReturn";
    const C_SER = "SER";
    const C_EFTPAPER = "EFTPaper";
    const C_PHONEPAPER = "PhonePaper";
    const C_SIGNATUREREADY = "SignatureReady";
    const C_EFILECHECK = "EfileCheck";

}


/**
 * Lists of acceptable values for the enumerated data type PointOfSaleFileType
 */
class PointOfSaleFileType
{
    const C_JSON = "Json";
    const C_CSV = "Csv";
    const C_XML = "Xml";

}


/**
 * Lists of acceptable values for the enumerated data type DocumentStatus
 */
class DocumentStatus
{
    const C_TEMPORARY = "Temporary";
    const C_SAVED = "Saved";
    const C_POSTED = "Posted";
    const C_COMMITTED = "Committed";
    const C_CANCELLED = "Cancelled";
    const C_ADJUSTED = "Adjusted";
    const C_QUEUED = "Queued";
    const C_PENDINGAPPROVAL = "PendingApproval";
    const C_ANY = "Any";

}


/**
 * Lists of acceptable values for the enumerated data type DocumentType
 */
class DocumentType
{
    const C_SALESORDER = "SalesOrder";
    const C_SALESINVOICE = "SalesInvoice";
    const C_PURCHASEORDER = "PurchaseOrder";
    const C_PURCHASEINVOICE = "PurchaseInvoice";
    const C_RETURNORDER = "ReturnOrder";
    const C_RETURNINVOICE = "ReturnInvoice";
    const C_INVENTORYTRANSFERORDER = "InventoryTransferOrder";
    const C_INVENTORYTRANSFERINVOICE = "InventoryTransferInvoice";
    const C_REVERSECHARGEORDER = "ReverseChargeOrder";
    const C_REVERSECHARGEINVOICE = "ReverseChargeInvoice";
    const C_ANY = "Any";

}


/**
 * Lists of acceptable values for the enumerated data type TaxOverrideTypeId
 */
class TaxOverrideTypeId
{
    const C_NONE = "None";
    const C_TAXAMOUNT = "TaxAmount";
    const C_EXEMPTION = "Exemption";
    const C_TAXDATE = "TaxDate";
    const C_ACCRUEDTAXAMOUNT = "AccruedTaxAmount";

}


/**
 * Lists of acceptable values for the enumerated data type AdjustmentReason
 */
class AdjustmentReason
{
    const C_NOTADJUSTED = "NotAdjusted";
    const C_SOURCINGISSUE = "SourcingIssue";
    const C_RECONCILEDWITHGENERALLEDGER = "ReconciledWithGeneralLedger";
    const C_EXEMPTCERTAPPLIED = "ExemptCertApplied";
    const C_PRICEADJUSTED = "PriceAdjusted";
    const C_PRODUCTRETURNED = "ProductReturned";
    const C_PRODUCTEXCHANGED = "ProductExchanged";
    const C_BADDEBT = "BadDebt";
    const C_OTHER = "Other";
    const C_OFFLINE = "Offline";

}


/**
 * Lists of acceptable values for the enumerated data type BoundaryLevel
 */
class BoundaryLevel
{
    const C_ADDRESS = "Address";
    const C_ZIP9 = "Zip9";
    const C_ZIP5 = "Zip5";

}


/**
 * Lists of acceptable values for the enumerated data type TaxType
 */
class TaxType
{
    const C_CONSUMERUSE = "ConsumerUse";
    const C_EXCISE = "Excise";
    const C_FEE = "Fee";
    const C_INPUT = "Input";
    const C_NONRECOVERABLE = "Nonrecoverable";
    const C_OUTPUT = "Output";
    const C_RENTAL = "Rental";
    const C_SALES = "Sales";
    const C_USE = "Use";

}


/**
 * Lists of acceptable values for the enumerated data type TransactionAddressType
 */
class TransactionAddressType
{
    const C_SHIPFROM = "ShipFrom";
    const C_SHIPTO = "ShipTo";
    const C_POINTOFORDERACCEPTANCE = "PointOfOrderAcceptance";
    const C_POINTOFORDERORIGIN = "PointOfOrderOrigin";
    const C_SINGLELOCATION = "SingleLocation";

}


/**
 * Lists of acceptable values for the enumerated data type ServiceMode
 */
class ServiceMode
{
    const C_AUTOMATIC = "Automatic";
    const C_LOCAL = "Local";
    const C_REMOTE = "Remote";

}


/**
 * Lists of acceptable values for the enumerated data type TaxDebugLevel
 */
class TaxDebugLevel
{
    const C_NORMAL = "Normal";
    const C_DIAGNOSTIC = "Diagnostic";

}


/**
 * Lists of acceptable values for the enumerated data type TaxOverrideType
 */
class TaxOverrideType
{
    const C_NONE = "None";
    const C_TAXAMOUNT = "TaxAmount";
    const C_EXEMPTION = "Exemption";
    const C_TAXDATE = "TaxDate";
    const C_ACCRUEDTAXAMOUNT = "AccruedTaxAmount";
    const C_DERIVETAXABLE = "DeriveTaxable";

}


/**
 * Lists of acceptable values for the enumerated data type VoidReasonCode
 */
class VoidReasonCode
{
    const C_UNSPECIFIED = "Unspecified";
    const C_POSTFAILED = "PostFailed";
    const C_DOCDELETED = "DocDeleted";
    const C_DOCVOIDED = "DocVoided";
    const C_ADJUSTMENTCANCELLED = "AdjustmentCancelled";

}


/**
 * Lists of acceptable values for the enumerated data type CompanyAccessLevel
 */
class CompanyAccessLevel
{
    const C_NONE = "None";
    const C_SINGLECOMPANY = "SingleCompany";
    const C_SINGLEACCOUNT = "SingleAccount";
    const C_ALLCOMPANIES = "AllCompanies";

}


/**
 * Lists of acceptable values for the enumerated data type AuthenticationTypeId
 */
class AuthenticationTypeId
{
    const C_NONE = "None";
    const C_USERNAMEPASSWORD = "UsernamePassword";
    const C_ACCOUNTIDLICENSEKEY = "AccountIdLicenseKey";
    const C_OPENIDBEARERTOKEN = "OpenIdBearerToken";

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
     * @param DocumentType  $type          The type of transaction to create. See DocumentType::* for allowable values.
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
     * @param   string              type    See DocumentType::* for a list of values
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
     * @param   string              type          Address Type - see AddressType::* for acceptable values
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
     * @param   string              $type       Address Type - see AddressType::* for acceptable values
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
     * @param   string              type        Address Type - see AddressType::* for acceptable values
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
     * @param   string              $type       Type of the Tax Override. See TaxOverrideType::* for a list of allowable values.
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
     * @param   string              $type        Type of the Tax Override. See TaxOverrideType::* for a list of allowable values.
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
     * @param   string              $type        Address Type - see AddressType::* for acceptable values
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
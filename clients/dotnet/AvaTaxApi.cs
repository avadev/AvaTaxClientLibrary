using System;
using System.Collections.Generic;
#if PORTABLE
using System.Threading.Tasks;
#endif

namespace Avalara.AvaTax.RestClient
{
    public partial class AvaTaxClient
    {
        /// <summary>
        /// Returns the version number of the API used to generate this class
        /// </summary>
        public static string API_VERSION { get { return "2.16.12-30"; } }

#region Methods
        /// <summary>
        /// Retrieve subscriptions for this account
        /// </summary>
        /// <param name="accountId">The ID of the account that owns these subscriptions</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<SubscriptionModel> ListSubscriptionsByAccount(Int32 accountId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<SubscriptionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single subscription
        /// </summary>
        /// <param name="accountId">The ID of the account that owns this subscription</param>
        /// <param name="id">The primary key of this subscription</param>
        public SubscriptionModel GetSubscription(Int32 accountId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions/{id}");
            path.ApplyField("accountId", accountId);
            path.ApplyField("id", id);
            return RestCall<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve users for this account
        /// </summary>
        /// <param name="accountId">The accountID of the user you wish to list.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<UserModel> ListUsersByAccount(Int32 accountId, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public UserModel GetUser(Int32 id, Int32 accountId, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            return RestCall<UserModel>("get", path, null);
        }

        /// <summary>
        /// Update a single user
        /// </summary>
        /// <param name="id">The ID of the user you wish to update.</param>
        /// <param name="accountId">The accountID of the user you wish to update.</param>
        /// <param name="model">The user object you wish to update.</param>
        public UserModel UpdateUser(Int32 id, Int32 accountId, UserModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return RestCall<UserModel>("put", path, model);
        }

        /// <summary>
        /// Retrieve all entitlements for a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        public UserEntitlementModel GetUserEntitlements(Int32 id, Int32 accountId)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}/entitlements");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return RestCall<UserEntitlementModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single account
        /// </summary>
        /// <param name="id">The ID of the account to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public AccountModel GetAccount(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return RestCall<AccountModel>("get", path, null);
        }

        /// <summary>
        /// Reset this account's license key
        /// </summary>
        /// <param name="id">The ID of the account you wish to update.</param>
        /// <param name="model">A request confirming that you wish to reset the license key of this account.</param>
        public LicenseKeyModel AccountResetLicenseKey(Int32 id, ResetLicenseKeyModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}/resetlicensekey");
            path.ApplyField("id", id);
            return RestCall<LicenseKeyModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve geolocation information for a specified address
        /// </summary>
        /// <param name="model">The address to resolve</param>
        public AddressResolutionModel ResolveAddress(AddressInfo model)
        {
            var path = new AvaTaxPath("/api/v2/addresses/resolve");
            return RestCall<AddressResolutionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all batches
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<BatchModel> QueryBatches(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/batches");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all companies
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<CompanyModel> QueryCompanies(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<CompanyModel>>("get", path, null);
        }

        /// <summary>
        /// Create new companies
        /// </summary>
        /// <param name="model">Either a single company object or an array of companies to create</param>
        public List<CompanyModel> CreateCompanies(List<CompanyModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            return RestCall<List<CompanyModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve all transactions
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<TransactionModel> ListTransactionsByCompany(String companyCode, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions");
            path.ApplyField("companyCode", companyCode);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<TransactionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single transaction by code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public TransactionModel GetTransactionByCode(String companyCode, String transactionCode, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("$include", include);
            return RestCall<TransactionModel>("get", path, null);
        }

        /// <summary>
        /// Correct a previously created transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to adjust</param>
        /// <param name="model">The adjustment you wish to make</param>
        public TransactionModel AdjustTransaction(String companyCode, String transactionCode, AdjustTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/adjust");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Change a transaction's code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to change</param>
        /// <param name="model">The code change request you wish to execute</param>
        public TransactionModel ChangeTransactionCode(String companyCode, String transactionCode, ChangeTransactionCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/changecode");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Commit a transaction for reporting
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to commit</param>
        /// <param name="model">The commit request you wish to execute</param>
        public TransactionModel CommitTransaction(String companyCode, String transactionCode, CommitTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/commit");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Perform multiple actions on a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request containing the actions you wish to execute</param>
        public TransactionModel SettleTransaction(String companyCode, String transactionCode, SettleTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/settle");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Verify a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request you wish to execute</param>
        public TransactionModel VerifyTransaction(String companyCode, String transactionCode, VerifyTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/verify");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Void a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to void</param>
        /// <param name="model">The void request you wish to execute</param>
        public TransactionModel VoidTransaction(String companyCode, String transactionCode, VoidTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/void");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all batches for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these batches</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<BatchModel> ListBatchesByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="model">The batch you wish to create.</param>
        public List<BatchModel> CreateBatches(Int32 companyId, List<BatchModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            return RestCall<List<BatchModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch</param>
        /// <param name="id">The primary key of this batch</param>
        public BatchModel GetBatch(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<BatchModel>("get", path, null);
        }

        /// <summary>
        /// Update a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that this batch belongs to.</param>
        /// <param name="id">The ID of the batch you wish to update</param>
        /// <param name="model">The batch you wish to update.</param>
        public BatchModel UpdateBatch(Int32 companyId, Int32 id, BatchModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<BatchModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="id">The ID of the batch you wish to delete.</param>
        public ErrorResult DeleteBatch(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve contacts for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these contacts</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<ContactModel> ListContactsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="model">The contacts you wish to create.</param>
        public List<ContactModel> CreateContacts(Int32 companyId, List<ContactModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            return RestCall<List<ContactModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company for this contact</param>
        /// <param name="id">The primary key of this contact</param>
        public ContactModel GetContact(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ContactModel>("get", path, null);
        }

        /// <summary>
        /// Update a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that this contact belongs to.</param>
        /// <param name="id">The ID of the contact you wish to update</param>
        /// <param name="model">The contact you wish to update.</param>
        public ContactModel UpdateContact(Int32 companyId, Int32 id, ContactModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ContactModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="id">The ID of the contact you wish to delete.</param>
        public ErrorResult DeleteContact(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve items for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that defined these items</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<ItemModel> ListItemsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="model">The item you wish to create.</param>
        public List<ItemModel> CreateItems(Int32 companyId, List<ItemModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            return RestCall<List<ItemModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item object</param>
        /// <param name="id">The primary key of this item</param>
        public ItemModel GetItem(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ItemModel>("get", path, null);
        }

        /// <summary>
        /// Update a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that this item belongs to.</param>
        /// <param name="id">The ID of the item you wish to update</param>
        /// <param name="model">The item object you wish to update.</param>
        public ItemModel UpdateItem(Int32 companyId, Int32 id, ItemModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ItemModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="id">The ID of the item you wish to delete.</param>
        public ErrorResult DeleteItem(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve locations for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these locations</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<LocationModel> ListLocationsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="model">The location you wish to create.</param>
        public List<LocationModel> CreateLocations(Int32 companyId, List<LocationModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            return RestCall<List<LocationModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        public LocationModel GetLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<LocationModel>("get", path, null);
        }

        /// <summary>
        /// Update a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that this location belongs to.</param>
        /// <param name="id">The ID of the location you wish to update</param>
        /// <param name="model">The location you wish to update.</param>
        public LocationModel UpdateLocation(Int32 companyId, Int32 id, LocationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<LocationModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="id">The ID of the location you wish to delete.</param>
        public ErrorResult DeleteLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Point of sale data file generation
        /// </summary>
        /// <param name="companyId">The ID number of the company that owns this location.</param>
        /// <param name="id">The ID number of the location to retrieve point-of-sale data.</param>
        /// <param name="date">The date for which point-of-sale data would be calculated (today by default)</param>
        /// <param name="format">The format of the file (JSON by default)</param>
        /// <param name="partnerId">If specified, requests a custom partner-formatted version of the file.</param>
        /// <param name="includeJurisCodes">When true, the file will include jurisdiction codes in the result.</param>
        public String BuildPointOfSaleDataForLocation(Int32 companyId, Int32 id, DateTime? date, String format, Int32? partnerId, Boolean? includeJurisCodes)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/pointofsaledata");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("date", date);
            path.AddQuery("format", format);
            path.AddQuery("partnerId", partnerId);
            path.AddQuery("includeJurisCodes", includeJurisCodes);
            return RestCallString("get", path, null);
        }

        /// <summary>
        /// Validate the location against local requirements
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        public LocationValidationModel ValidateLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/validate");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<LocationValidationModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve nexus for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these nexus objects</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<NexusModel> ListNexusByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="model">The nexus you wish to create.</param>
        public List<NexusModel> CreateNexus(Int32 companyId, List<NexusModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            return RestCall<List<NexusModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus object</param>
        /// <param name="id">The primary key of this nexus</param>
        public NexusModel GetNexus(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<NexusModel>("get", path, null);
        }

        /// <summary>
        /// Update a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that this nexus belongs to.</param>
        /// <param name="id">The ID of the nexus you wish to update</param>
        /// <param name="model">The nexus object you wish to update.</param>
        public NexusModel UpdateNexus(Int32 companyId, Int32 id, NexusModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<NexusModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="id">The ID of the nexus you wish to delete.</param>
        public ErrorResult DeleteNexus(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve all settings for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these settings</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<SettingModel> ListSettingsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="model">The setting you wish to create.</param>
        public List<SettingModel> CreateSettings(Int32 companyId, List<SettingModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            return RestCall<List<SettingModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting</param>
        /// <param name="id">The primary key of this setting</param>
        public SettingModel GetSetting(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<SettingModel>("get", path, null);
        }

        /// <summary>
        /// Update a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that this setting belongs to.</param>
        /// <param name="id">The ID of the setting you wish to update</param>
        /// <param name="model">The setting you wish to update.</param>
        public SettingModel UpdateSetting(Int32 companyId, Int32 id, SettingModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<SettingModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="id">The ID of the setting you wish to delete.</param>
        public ErrorResult DeleteSetting(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve tax codes for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax codes</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<TaxCodeModel> ListTaxCodesByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="model">The tax code you wish to create.</param>
        public List<TaxCodeModel> CreateTaxCodes(Int32 companyId, List<TaxCodeModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            return RestCall<List<TaxCodeModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code</param>
        /// <param name="id">The primary key of this tax code</param>
        public TaxCodeModel GetTaxCode(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<TaxCodeModel>("get", path, null);
        }

        /// <summary>
        /// Update a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax code belongs to.</param>
        /// <param name="id">The ID of the tax code you wish to update</param>
        /// <param name="model">The tax code you wish to update.</param>
        public TaxCodeModel UpdateTaxCode(Int32 companyId, Int32 id, TaxCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<TaxCodeModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="id">The ID of the tax code you wish to delete.</param>
        public ErrorResult DeleteTaxCode(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve tax rules for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax rules</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<TaxRuleModel> ListTaxRules(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="model">The tax rule you wish to create.</param>
        public List<TaxRuleModel> CreateTaxRules(Int32 companyId, List<TaxRuleModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            return RestCall<List<TaxRuleModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule</param>
        /// <param name="id">The primary key of this tax rule</param>
        public TaxRuleModel GetTaxRule(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<TaxRuleModel>("get", path, null);
        }

        /// <summary>
        /// Update a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax rule belongs to.</param>
        /// <param name="id">The ID of the tax rule you wish to update</param>
        /// <param name="model">The tax rule you wish to update.</param>
        public TaxRuleModel UpdateTaxRule(Int32 companyId, Int32 id, TaxRuleModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<TaxRuleModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="id">The ID of the tax rule you wish to delete.</param>
        public ErrorResult DeleteTaxRule(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve UPCs for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these UPCs</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<UPCModel> ListUPCsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="model">The UPC you wish to create.</param>
        public List<UPCModel> CreateUPCs(Int32 companyId, List<UPCModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            return RestCall<List<UPCModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC</param>
        /// <param name="id">The primary key of this UPC</param>
        public UPCModel GetUPC(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<UPCModel>("get", path, null);
        }

        /// <summary>
        /// Update a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that this UPC belongs to.</param>
        /// <param name="id">The ID of the UPC you wish to update</param>
        /// <param name="model">The UPC you wish to update.</param>
        public UPCModel UpdateUPC(Int32 companyId, Int32 id, UPCModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<UPCModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="id">The ID of the UPC you wish to delete.</param>
        public ErrorResult DeleteUPC(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single company
        /// </summary>
        /// <param name="id">The ID of the company to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public CompanyModel GetCompany(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return RestCall<CompanyModel>("get", path, null);
        }

        /// <summary>
        /// Update a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to update.</param>
        /// <param name="model">The company object you wish to update.</param>
        public CompanyModel UpdateCompany(Int32 id, CompanyModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return RestCall<CompanyModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to delete.</param>
        public ErrorResult DeleteCompanies(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Quick setup for a company with a single physical address
        /// </summary>
        /// <param name="model">Information about the company you wish to create.</param>
        public CompanyModel CompanyInitialize(CompanyInitializationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/initialize");
            return RestCall<CompanyModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all contacts
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<ContactModel> QueryContacts(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/contacts");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 countries
        /// </summary>
        public FetchResult<IsoCountryModel> ListCountries()
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries");
            return RestCall<FetchResult<IsoCountryModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions for a country
        /// </summary>
        /// <param name="country"></param>
        public FetchResult<IsoRegionModel> ListRegionsByCountry(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries/{country}/regions");
            path.ApplyField("country", country);
            return RestCall<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the list of questions that are required for a tax location
        /// </summary>
        /// <param name="line1">The first line of this location's address.</param>
        /// <param name="line2">The second line of this location's address.</param>
        /// <param name="line3">The third line of this location's address.</param>
        /// <param name="city">The city part of this location's address.</param>
        /// <param name="region">The region, state, or province part of this location's address.</param>
        /// <param name="postalCode">The postal code of this location's address.</param>
        /// <param name="country">The country part of this location's address.</param>
        /// <param name="latitude">Optionally identify the location via latitude/longitude instead of via address.</param>
        /// <param name="longitude">Optionally identify the location via latitude/longitude instead of via address.</param>
        public FetchResult<LocationQuestionModel> ListLocationQuestionsByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country, Decimal? latitude, Decimal? longitude)
        {
            var path = new AvaTaxPath("/api/v2/definitions/locationquestions");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            path.AddQuery("latitude", latitude);
            path.AddQuery("longitude", longitude);
            return RestCall<FetchResult<LocationQuestionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for all countries and regions.
        /// </summary>
        public FetchResult<NexusModel> DefinitionsNexusGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus");
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country.
        /// </summary>
        /// <param name="country"></param>
        public FetchResult<NexusModel> DefinitionsNexusByCountryGet(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}");
            path.ApplyField("country", country);
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country and region.
        /// </summary>
        /// <param name="country">The two-character ISO-3166 code for the country.</param>
        /// <param name="region">The two or three character region code for the region.</param>
        public FetchResult<NexusModel> DefinitionsNexusByCountryByRegionGet(String country, String region)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}/{region}");
            path.ApplyField("country", country);
            path.ApplyField("region", region);
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// List all nexus that apply to a specific address.
        /// </summary>
        /// <param name="line1">The first address line portion of this address.</param>
        /// <param name="line2">The first address line portion of this address.</param>
        /// <param name="line3">The first address line portion of this address.</param>
        /// <param name="city">The city portion of this address.</param>
        /// <param name="region">The region, state, or province code portion of this address.</param>
        /// <param name="postalCode">The postal code or zip code portion of this address.</param>
        /// <param name="country">The two-character ISO-3166 code of the country portion of this address.</param>
        public FetchResult<NexusModel> ListNexusByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported extra parameters for creating transactions.
        /// </summary>
        public FetchResult<ParameterModel> ListParameters()
        {
            var path = new AvaTaxPath("/api/v2/definitions/parameters");
            return RestCall<FetchResult<ParameterModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        public FetchResult<String> ListPermissions()
        {
            var path = new AvaTaxPath("/api/v2/definitions/permissions");
            return RestCall<FetchResult<String>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions
        /// </summary>
        public FetchResult<IsoRegionModel> ListRegions()
        {
            var path = new AvaTaxPath("/api/v2/definitions/regions");
            return RestCall<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        public FetchResult<SecurityRoleModel> ListSecurityRoles()
        {
            var path = new AvaTaxPath("/api/v2/definitions/securityroles");
            return RestCall<FetchResult<SecurityRoleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported subscription types
        /// </summary>
        public FetchResult<SubscriptionTypeModel> ListSubscriptionTypes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/subscriptiontypes");
            return RestCall<FetchResult<SubscriptionTypeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        public FetchResult<TaxAuthorityModel> ListTaxAuthorities()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorities");
            return RestCall<FetchResult<TaxAuthorityModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported forms for each tax authority.
        /// </summary>
        public FetchResult<TaxAuthorityFormModel> ListTaxAuthorityForms()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorityforms");
            return RestCall<FetchResult<TaxAuthorityFormModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax codes.
        /// </summary>
        public FetchResult<TaxCodeModel> ListTaxCodes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodes");
            return RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax code types.
        /// </summary>
        public TaxCodeTypesModel ListTaxCodeTypes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodetypes");
            return RestCall<TaxCodeTypesModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all items
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<ItemModel> QueryItems(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/items");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all locations
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<LocationModel> QueryLocations(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/locations");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all nexus
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<NexusModel> QueryNexus(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/nexus");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Point of sale data file generation
        /// </summary>
        /// <param name="model">Parameters about the desired file format and report format, specifying which company, locations and TaxCodes to include.</param>
        public String BuildPointOfSaleDataFile(PointOfSaleDataRequestModel model)
        {
            var path = new AvaTaxPath("/api/v2/pointofsaledata/build");
            return RestCallString("post", path, model);
        }

        /// <summary>
        /// Retrieve all settings
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<SettingModel> QuerySettings(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/settings");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all subscriptions
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<SubscriptionModel> QuerySubscriptions(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/subscriptions");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<SubscriptionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax codes
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<TaxCodeModel> QueryTaxCodes(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxcodes");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rates for a specified address
        /// </summary>
        /// <param name="line1">The street address of the location.</param>
        /// <param name="line2">The street address of the location.</param>
        /// <param name="line3">The street address of the location.</param>
        /// <param name="city">The city name of the location.</param>
        /// <param name="region">The state or region of the location</param>
        /// <param name="postalCode">The postal code of the location.</param>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        public TaxRateModel TaxRatesByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return RestCall<TaxRateModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rates for a specified country and postal code
        /// </summary>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        /// <param name="postalCode">The postal code of the location.</param>
        public TaxRateModel TaxRatesByPostalCode(String country, String postalCode)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/bypostalcode");
            path.AddQuery("country", country);
            path.AddQuery("postalCode", postalCode);
            return RestCall<TaxRateModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax rules
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<TaxRuleModel> QueryTaxRules(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxrules");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single transaction by ID
        /// </summary>
        /// <param name="id">The unique ID number of the transaction to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public TransactionModel GetTransactionById(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/transactions/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return RestCall<TransactionModel>("get", path, null);
        }

        /// <summary>
        /// Create a new transaction
        /// </summary>
        /// <param name="model">The transaction you wish to create</param>
        public TransactionModel CreateTransaction(CreateTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/transactions/create");
            return RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all UPCs
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<UPCModel> QueryUPCs(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/upcs");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all users
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public FetchResult<UserModel> QueryUsers(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/users");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return RestCall<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Tests connectivity and version of the service
        /// </summary>
        public PingResultModel Ping()
        {
            var path = new AvaTaxPath("/api/v2/utilities/ping");
            return RestCall<PingResultModel>("get", path, null);
        }

        /// <summary>
        /// List all services to which the current user is subscribed
        /// </summary>
        public SubscriptionModel ListMySubscriptions()
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions");
            return RestCall<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Checks if the current user is subscribed to a specific service
        /// </summary>
        /// <param name="serviceTypeId">The service to check</param>
        public SubscriptionModel GetMySubscription(String serviceTypeId)
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions/{serviceTypeId}");
            path.ApplyField("serviceTypeId", serviceTypeId);
            return RestCall<SubscriptionModel>("get", path, null);
        }

#endregion

#region Asynchronous
#if PORTABLE
        /// <summary>
        /// Retrieve subscriptions for this account
        /// </summary>
        /// <param name="accountId">The ID of the account that owns these subscriptions</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<SubscriptionModel>> ListSubscriptionsByAccountAsync(Int32 accountId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<SubscriptionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single subscription
        /// </summary>
        /// <param name="accountId">The ID of the account that owns this subscription</param>
        /// <param name="id">The primary key of this subscription</param>
        public async Task<SubscriptionModel> GetSubscriptionAsync(Int32 accountId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions/{id}");
            path.ApplyField("accountId", accountId);
            path.ApplyField("id", id);
            return await RestCallAsync<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve users for this account
        /// </summary>
        /// <param name="accountId">The accountID of the user you wish to list.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<UserModel>> ListUsersByAccountAsync(Int32 accountId, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public async Task<UserModel> GetUserAsync(Int32 id, Int32 accountId, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            return await RestCallAsync<UserModel>("get", path, null);
        }

        /// <summary>
        /// Update a single user
        /// </summary>
        /// <param name="id">The ID of the user you wish to update.</param>
        /// <param name="accountId">The accountID of the user you wish to update.</param>
        /// <param name="model">The user object you wish to update.</param>
        public async Task<UserModel> UpdateUserAsync(Int32 id, Int32 accountId, UserModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return await RestCallAsync<UserModel>("put", path, model);
        }

        /// <summary>
        /// Retrieve all entitlements for a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        public async Task<UserEntitlementModel> GetUserEntitlementsAsync(Int32 id, Int32 accountId)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}/entitlements");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return await RestCallAsync<UserEntitlementModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single account
        /// </summary>
        /// <param name="id">The ID of the account to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public async Task<AccountModel> GetAccountAsync(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCallAsync<AccountModel>("get", path, null);
        }

        /// <summary>
        /// Reset this account's license key
        /// </summary>
        /// <param name="id">The ID of the account you wish to update.</param>
        /// <param name="model">A request confirming that you wish to reset the license key of this account.</param>
        public async Task<LicenseKeyModel> AccountResetLicenseKeyAsync(Int32 id, ResetLicenseKeyModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}/resetlicensekey");
            path.ApplyField("id", id);
            return await RestCallAsync<LicenseKeyModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve geolocation information for a specified address
        /// </summary>
        /// <param name="model">The address to resolve</param>
        public async Task<AddressResolutionModel> ResolveAddressAsync(AddressInfo model)
        {
            var path = new AvaTaxPath("/api/v2/addresses/resolve");
            return await RestCallAsync<AddressResolutionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all batches
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<BatchModel>> QueryBatchesAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/batches");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all companies
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<CompanyModel>> QueryCompaniesAsync(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<CompanyModel>>("get", path, null);
        }

        /// <summary>
        /// Create new companies
        /// </summary>
        /// <param name="model">Either a single company object or an array of companies to create</param>
        public async Task<List<CompanyModel>> CreateCompaniesAsync(List<CompanyModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            return await RestCallAsync<List<CompanyModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve all transactions
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<TransactionModel>> ListTransactionsByCompanyAsync(String companyCode, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions");
            path.ApplyField("companyCode", companyCode);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<TransactionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single transaction by code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public async Task<TransactionModel> GetTransactionByCodeAsync(String companyCode, String transactionCode, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("$include", include);
            return await RestCallAsync<TransactionModel>("get", path, null);
        }

        /// <summary>
        /// Correct a previously created transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to adjust</param>
        /// <param name="model">The adjustment you wish to make</param>
        public async Task<TransactionModel> AdjustTransactionAsync(String companyCode, String transactionCode, AdjustTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/adjust");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Change a transaction's code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to change</param>
        /// <param name="model">The code change request you wish to execute</param>
        public async Task<TransactionModel> ChangeTransactionCodeAsync(String companyCode, String transactionCode, ChangeTransactionCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/changecode");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Commit a transaction for reporting
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to commit</param>
        /// <param name="model">The commit request you wish to execute</param>
        public async Task<TransactionModel> CommitTransactionAsync(String companyCode, String transactionCode, CommitTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/commit");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Perform multiple actions on a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request containing the actions you wish to execute</param>
        public async Task<TransactionModel> SettleTransactionAsync(String companyCode, String transactionCode, SettleTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/settle");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Verify a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request you wish to execute</param>
        public async Task<TransactionModel> VerifyTransactionAsync(String companyCode, String transactionCode, VerifyTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/verify");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Void a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to void</param>
        /// <param name="model">The void request you wish to execute</param>
        public async Task<TransactionModel> VoidTransactionAsync(String companyCode, String transactionCode, VoidTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/void");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all batches for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these batches</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<BatchModel>> ListBatchesByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="model">The batch you wish to create.</param>
        public async Task<List<BatchModel>> CreateBatchesAsync(Int32 companyId, List<BatchModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<BatchModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch</param>
        /// <param name="id">The primary key of this batch</param>
        public async Task<BatchModel> GetBatchAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<BatchModel>("get", path, null);
        }

        /// <summary>
        /// Update a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that this batch belongs to.</param>
        /// <param name="id">The ID of the batch you wish to update</param>
        /// <param name="model">The batch you wish to update.</param>
        public async Task<BatchModel> UpdateBatchAsync(Int32 companyId, Int32 id, BatchModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<BatchModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="id">The ID of the batch you wish to delete.</param>
        public async Task<ErrorResult> DeleteBatchAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve contacts for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these contacts</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<ContactModel>> ListContactsByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="model">The contacts you wish to create.</param>
        public async Task<List<ContactModel>> CreateContactsAsync(Int32 companyId, List<ContactModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<ContactModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company for this contact</param>
        /// <param name="id">The primary key of this contact</param>
        public async Task<ContactModel> GetContactAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ContactModel>("get", path, null);
        }

        /// <summary>
        /// Update a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that this contact belongs to.</param>
        /// <param name="id">The ID of the contact you wish to update</param>
        /// <param name="model">The contact you wish to update.</param>
        public async Task<ContactModel> UpdateContactAsync(Int32 companyId, Int32 id, ContactModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ContactModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="id">The ID of the contact you wish to delete.</param>
        public async Task<ErrorResult> DeleteContactAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve items for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that defined these items</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<ItemModel>> ListItemsByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="model">The item you wish to create.</param>
        public async Task<List<ItemModel>> CreateItemsAsync(Int32 companyId, List<ItemModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<ItemModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item object</param>
        /// <param name="id">The primary key of this item</param>
        public async Task<ItemModel> GetItemAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ItemModel>("get", path, null);
        }

        /// <summary>
        /// Update a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that this item belongs to.</param>
        /// <param name="id">The ID of the item you wish to update</param>
        /// <param name="model">The item object you wish to update.</param>
        public async Task<ItemModel> UpdateItemAsync(Int32 companyId, Int32 id, ItemModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ItemModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="id">The ID of the item you wish to delete.</param>
        public async Task<ErrorResult> DeleteItemAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve locations for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these locations</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<LocationModel>> ListLocationsByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="model">The location you wish to create.</param>
        public async Task<List<LocationModel>> CreateLocationsAsync(Int32 companyId, List<LocationModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<LocationModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        public async Task<LocationModel> GetLocationAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<LocationModel>("get", path, null);
        }

        /// <summary>
        /// Update a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that this location belongs to.</param>
        /// <param name="id">The ID of the location you wish to update</param>
        /// <param name="model">The location you wish to update.</param>
        public async Task<LocationModel> UpdateLocationAsync(Int32 companyId, Int32 id, LocationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<LocationModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="id">The ID of the location you wish to delete.</param>
        public async Task<ErrorResult> DeleteLocationAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Point of sale data file generation
        /// </summary>
        /// <param name="companyId">The ID number of the company that owns this location.</param>
        /// <param name="id">The ID number of the location to retrieve point-of-sale data.</param>
        /// <param name="date">The date for which point-of-sale data would be calculated (today by default)</param>
        /// <param name="format">The format of the file (JSON by default)</param>
        /// <param name="partnerId">If specified, requests a custom partner-formatted version of the file.</param>
        /// <param name="includeJurisCodes">When true, the file will include jurisdiction codes in the result.</param>
        public async Task<String> BuildPointOfSaleDataForLocationAsync(Int32 companyId, Int32 id, DateTime? date, String format, Int32? partnerId, Boolean? includeJurisCodes)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/pointofsaledata");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("date", date);
            path.AddQuery("format", format);
            path.AddQuery("partnerId", partnerId);
            path.AddQuery("includeJurisCodes", includeJurisCodes);
            return await RestCallStringAsync("get", path, null);
        }

        /// <summary>
        /// Validate the location against local requirements
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        public async Task<LocationValidationModel> ValidateLocationAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/validate");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<LocationValidationModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve nexus for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these nexus objects</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<NexusModel>> ListNexusByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="model">The nexus you wish to create.</param>
        public async Task<List<NexusModel>> CreateNexusAsync(Int32 companyId, List<NexusModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<NexusModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus object</param>
        /// <param name="id">The primary key of this nexus</param>
        public async Task<NexusModel> GetNexusAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<NexusModel>("get", path, null);
        }

        /// <summary>
        /// Update a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that this nexus belongs to.</param>
        /// <param name="id">The ID of the nexus you wish to update</param>
        /// <param name="model">The nexus object you wish to update.</param>
        public async Task<NexusModel> UpdateNexusAsync(Int32 companyId, Int32 id, NexusModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<NexusModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="id">The ID of the nexus you wish to delete.</param>
        public async Task<ErrorResult> DeleteNexusAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve all settings for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these settings</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<SettingModel>> ListSettingsByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="model">The setting you wish to create.</param>
        public async Task<List<SettingModel>> CreateSettingsAsync(Int32 companyId, List<SettingModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<SettingModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting</param>
        /// <param name="id">The primary key of this setting</param>
        public async Task<SettingModel> GetSettingAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<SettingModel>("get", path, null);
        }

        /// <summary>
        /// Update a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that this setting belongs to.</param>
        /// <param name="id">The ID of the setting you wish to update</param>
        /// <param name="model">The setting you wish to update.</param>
        public async Task<SettingModel> UpdateSettingAsync(Int32 companyId, Int32 id, SettingModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<SettingModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="id">The ID of the setting you wish to delete.</param>
        public async Task<ErrorResult> DeleteSettingAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve tax codes for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax codes</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<TaxCodeModel>> ListTaxCodesByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="model">The tax code you wish to create.</param>
        public async Task<List<TaxCodeModel>> CreateTaxCodesAsync(Int32 companyId, List<TaxCodeModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<TaxCodeModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code</param>
        /// <param name="id">The primary key of this tax code</param>
        public async Task<TaxCodeModel> GetTaxCodeAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<TaxCodeModel>("get", path, null);
        }

        /// <summary>
        /// Update a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax code belongs to.</param>
        /// <param name="id">The ID of the tax code you wish to update</param>
        /// <param name="model">The tax code you wish to update.</param>
        public async Task<TaxCodeModel> UpdateTaxCodeAsync(Int32 companyId, Int32 id, TaxCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<TaxCodeModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="id">The ID of the tax code you wish to delete.</param>
        public async Task<ErrorResult> DeleteTaxCodeAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve tax rules for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax rules</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<TaxRuleModel>> ListTaxRulesAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="model">The tax rule you wish to create.</param>
        public async Task<List<TaxRuleModel>> CreateTaxRulesAsync(Int32 companyId, List<TaxRuleModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<TaxRuleModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule</param>
        /// <param name="id">The primary key of this tax rule</param>
        public async Task<TaxRuleModel> GetTaxRuleAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<TaxRuleModel>("get", path, null);
        }

        /// <summary>
        /// Update a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax rule belongs to.</param>
        /// <param name="id">The ID of the tax rule you wish to update</param>
        /// <param name="model">The tax rule you wish to update.</param>
        public async Task<TaxRuleModel> UpdateTaxRuleAsync(Int32 companyId, Int32 id, TaxRuleModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<TaxRuleModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="id">The ID of the tax rule you wish to delete.</param>
        public async Task<ErrorResult> DeleteTaxRuleAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve UPCs for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these UPCs</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<UPCModel>> ListUPCsByCompanyAsync(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Create a new UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="model">The UPC you wish to create.</param>
        public async Task<List<UPCModel>> CreateUPCsAsync(Int32 companyId, List<UPCModel> model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            return await RestCallAsync<List<UPCModel>>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC</param>
        /// <param name="id">The primary key of this UPC</param>
        public async Task<UPCModel> GetUPCAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<UPCModel>("get", path, null);
        }

        /// <summary>
        /// Update a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that this UPC belongs to.</param>
        /// <param name="id">The ID of the UPC you wish to update</param>
        /// <param name="model">The UPC you wish to update.</param>
        public async Task<UPCModel> UpdateUPCAsync(Int32 companyId, Int32 id, UPCModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<UPCModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="id">The ID of the UPC you wish to delete.</param>
        public async Task<ErrorResult> DeleteUPCAsync(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single company
        /// </summary>
        /// <param name="id">The ID of the company to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public async Task<CompanyModel> GetCompanyAsync(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCallAsync<CompanyModel>("get", path, null);
        }

        /// <summary>
        /// Update a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to update.</param>
        /// <param name="model">The company object you wish to update.</param>
        public async Task<CompanyModel> UpdateCompanyAsync(Int32 id, CompanyModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return await RestCallAsync<CompanyModel>("put", path, model);
        }

        /// <summary>
        /// Delete a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to delete.</param>
        public async Task<ErrorResult> DeleteCompaniesAsync(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return await RestCallAsync<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Quick setup for a company with a single physical address
        /// </summary>
        /// <param name="model">Information about the company you wish to create.</param>
        public async Task<CompanyModel> CompanyInitializeAsync(CompanyInitializationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/initialize");
            return await RestCallAsync<CompanyModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all contacts
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<ContactModel>> QueryContactsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/contacts");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 countries
        /// </summary>
        public async Task<FetchResult<IsoCountryModel>> ListCountriesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries");
            return await RestCallAsync<FetchResult<IsoCountryModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions for a country
        /// </summary>
        /// <param name="country"></param>
        public async Task<FetchResult<IsoRegionModel>> ListRegionsByCountryAsync(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries/{country}/regions");
            path.ApplyField("country", country);
            return await RestCallAsync<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the list of questions that are required for a tax location
        /// </summary>
        /// <param name="line1">The first line of this location's address.</param>
        /// <param name="line2">The second line of this location's address.</param>
        /// <param name="line3">The third line of this location's address.</param>
        /// <param name="city">The city part of this location's address.</param>
        /// <param name="region">The region, state, or province part of this location's address.</param>
        /// <param name="postalCode">The postal code of this location's address.</param>
        /// <param name="country">The country part of this location's address.</param>
        /// <param name="latitude">Optionally identify the location via latitude/longitude instead of via address.</param>
        /// <param name="longitude">Optionally identify the location via latitude/longitude instead of via address.</param>
        public async Task<FetchResult<LocationQuestionModel>> ListLocationQuestionsByAddressAsync(String line1, String line2, String line3, String city, String region, String postalCode, String country, Decimal? latitude, Decimal? longitude)
        {
            var path = new AvaTaxPath("/api/v2/definitions/locationquestions");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            path.AddQuery("latitude", latitude);
            path.AddQuery("longitude", longitude);
            return await RestCallAsync<FetchResult<LocationQuestionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for all countries and regions.
        /// </summary>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusGetAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus");
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country.
        /// </summary>
        /// <param name="country"></param>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryGetAsync(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}");
            path.ApplyField("country", country);
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country and region.
        /// </summary>
        /// <param name="country">The two-character ISO-3166 code for the country.</param>
        /// <param name="region">The two or three character region code for the region.</param>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryByRegionGetAsync(String country, String region)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}/{region}");
            path.ApplyField("country", country);
            path.ApplyField("region", region);
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// List all nexus that apply to a specific address.
        /// </summary>
        /// <param name="line1">The first address line portion of this address.</param>
        /// <param name="line2">The first address line portion of this address.</param>
        /// <param name="line3">The first address line portion of this address.</param>
        /// <param name="city">The city portion of this address.</param>
        /// <param name="region">The region, state, or province code portion of this address.</param>
        /// <param name="postalCode">The postal code or zip code portion of this address.</param>
        /// <param name="country">The two-character ISO-3166 code of the country portion of this address.</param>
        public async Task<FetchResult<NexusModel>> ListNexusByAddressAsync(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported extra parameters for creating transactions.
        /// </summary>
        public async Task<FetchResult<ParameterModel>> ListParametersAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/parameters");
            return await RestCallAsync<FetchResult<ParameterModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        public async Task<FetchResult<String>> ListPermissionsAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/permissions");
            return await RestCallAsync<FetchResult<String>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions
        /// </summary>
        public async Task<FetchResult<IsoRegionModel>> ListRegionsAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/regions");
            return await RestCallAsync<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        public async Task<FetchResult<SecurityRoleModel>> ListSecurityRolesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/securityroles");
            return await RestCallAsync<FetchResult<SecurityRoleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported subscription types
        /// </summary>
        public async Task<FetchResult<SubscriptionTypeModel>> ListSubscriptionTypesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/subscriptiontypes");
            return await RestCallAsync<FetchResult<SubscriptionTypeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        public async Task<FetchResult<TaxAuthorityModel>> ListTaxAuthoritiesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorities");
            return await RestCallAsync<FetchResult<TaxAuthorityModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported forms for each tax authority.
        /// </summary>
        public async Task<FetchResult<TaxAuthorityFormModel>> ListTaxAuthorityFormsAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorityforms");
            return await RestCallAsync<FetchResult<TaxAuthorityFormModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax codes.
        /// </summary>
        public async Task<FetchResult<TaxCodeModel>> ListTaxCodesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodes");
            return await RestCallAsync<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax code types.
        /// </summary>
        public async Task<TaxCodeTypesModel> ListTaxCodeTypesAsync()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodetypes");
            return await RestCallAsync<TaxCodeTypesModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all items
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<ItemModel>> QueryItemsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/items");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all locations
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<LocationModel>> QueryLocationsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/locations");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all nexus
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<NexusModel>> QueryNexusAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/nexus");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Point of sale data file generation
        /// </summary>
        /// <param name="model">Parameters about the desired file format and report format, specifying which company, locations and TaxCodes to include.</param>
        public async Task<String> BuildPointOfSaleDataFileAsync(PointOfSaleDataRequestModel model)
        {
            var path = new AvaTaxPath("/api/v2/pointofsaledata/build");
            return await RestCallStringAsync("post", path, model);
        }

        /// <summary>
        /// Retrieve all settings
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<SettingModel>> QuerySettingsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/settings");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all subscriptions
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<SubscriptionModel>> QuerySubscriptionsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/subscriptions");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<SubscriptionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax codes
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<TaxCodeModel>> QueryTaxCodesAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxcodes");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rates for a specified address
        /// </summary>
        /// <param name="line1">The street address of the location.</param>
        /// <param name="line2">The street address of the location.</param>
        /// <param name="line3">The street address of the location.</param>
        /// <param name="city">The city name of the location.</param>
        /// <param name="region">The state or region of the location</param>
        /// <param name="postalCode">The postal code of the location.</param>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        public async Task<TaxRateModel> TaxRatesByAddressAsync(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCallAsync<TaxRateModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rates for a specified country and postal code
        /// </summary>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        /// <param name="postalCode">The postal code of the location.</param>
        public async Task<TaxRateModel> TaxRatesByPostalCodeAsync(String country, String postalCode)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/bypostalcode");
            path.AddQuery("country", country);
            path.AddQuery("postalCode", postalCode);
            return await RestCallAsync<TaxRateModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax rules
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<TaxRuleModel>> QueryTaxRulesAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxrules");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single transaction by ID
        /// </summary>
        /// <param name="id">The unique ID number of the transaction to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        public async Task<TransactionModel> GetTransactionByIdAsync(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/transactions/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCallAsync<TransactionModel>("get", path, null);
        }

        /// <summary>
        /// Create a new transaction
        /// </summary>
        /// <param name="model">The transaction you wish to create</param>
        public async Task<TransactionModel> CreateTransactionAsync(CreateTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/transactions/create");
            return await RestCallAsync<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve all UPCs
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<UPCModel>> QueryUPCsAsync(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/upcs");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all users
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        public async Task<FetchResult<UserModel>> QueryUsersAsync(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/users");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCallAsync<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Tests connectivity and version of the service
        /// </summary>
        public async Task<PingResultModel> PingAsync()
        {
            var path = new AvaTaxPath("/api/v2/utilities/ping");
            return await RestCallAsync<PingResultModel>("get", path, null);
        }

        /// <summary>
        /// List all services to which the current user is subscribed
        /// </summary>
        public async Task<SubscriptionModel> ListMySubscriptionsAsync()
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions");
            return await RestCallAsync<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Checks if the current user is subscribed to a specific service
        /// </summary>
        /// <param name="serviceTypeId">The service to check</param>
        public async Task<SubscriptionModel> GetMySubscriptionAsync(String serviceTypeId)
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions/{serviceTypeId}");
            path.ApplyField("serviceTypeId", serviceTypeId);
            return await RestCallAsync<SubscriptionModel>("get", path, null);
        }

#endif
#endregion
    }
}

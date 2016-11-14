using Avalara.AvaTax.RestClient.Model;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace Avalara.AvaTax.RestClient
{
    public partial class AvaTaxClient
    {
        #region Subscriptions

        /// <summary>
        /// Retrieve subscriptions for this account
        /// </summary>
        /// <param name="accountId">The ID of the account that owns these subscriptions</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionModel>> AccountsByAccountIdSubscriptionsGet(Int32 accountId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SubscriptionModel>>("get", path);
        }

        /// <summary>
        /// Retrieve a single subscription
        /// </summary>
        /// <param name="accountId">The ID of the account that owns this subscription</param>
        /// <param name="id">The primary key of this subscription</param>
        /// <returns></returns>
        public async Task<SubscriptionModel> AccountsByAccountIdSubscriptionsByIdGet(Int32 accountId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions/{id}");
            path.ApplyField("accountId", accountId);
            path.ApplyField("id", id);
            return await RestCall<SubscriptionModel>("get", path);
        }
        #endregion

        #region Users

        /// <summary>
        /// Retrieve users for this account
        /// </summary>
        /// <param name="accountId">The accountID of the user you wish to list.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UserModel>> AccountsByAccountIdUsersGet(Int32 accountId, string include, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UserModel>>("get", path);
        }

        /// <summary>
        /// Retrieve a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<UserModel> AccountsByAccountIdUsersByIdGet(Int32 id, Int32 accountId, string include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            return await RestCall<UserModel>("get", path);
        }

        /// <summary>
        /// Update a single user
        /// </summary>
        /// <param name="id">The ID of the user you wish to update.</param>
        /// <param name="accountId">The accountID of the user you wish to update.</param>
        /// <returns></returns>
        public async Task<AccountModel> AccountsByAccountIdUsersByIdPut(Int32 id, Int32 accountId)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return await RestCall<AccountModel>("put", path);
        }

        /// <summary>
        /// Retrieve all entitlements for a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <returns></returns>
        public async Task<UserEntitlementModel> AccountsByAccountIdUsersByIdEntitlementsGet(Int32 id, Int32 accountId)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}/entitlements");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return await RestCall<UserEntitlementModel>("get", path);
        }
        #endregion

        #region Accounts

        /// <summary>
        /// Retrieve a single account
        /// </summary>
        /// <param name="id">The ID of the account to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<AccountModel> AccountsByIdGet(Int32 id, string include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<AccountModel>("get", path);
        }

        /// <summary>
        /// Reset this account's license key
        /// </summary>
        /// <param name="id">The ID of the account you wish to update.</param>
        /// <returns></returns>
        public async Task<LicenseKeyModel> AccountsByIdResetlicensekeyPost(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}/resetlicensekey");
            path.ApplyField("id", id);
            return await RestCall<LicenseKeyModel>("post", path);
        }
        #endregion

        #region Addresses

        /// <summary>
        /// Retrieve geolocation information for a specified address
        /// </summary>
        /// <returns></returns>
        public async Task<AddressResolutionModel> AddressesResolvePost()
        {
            var path = new AvaTaxPath("/api/v2/addresses/resolve");
            return await RestCall<AddressResolutionModel>("post", path);
        }
        #endregion

        #region Batches

        /// <summary>
        /// Retrieve all batches
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<BatchModel>> BatchesGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/batches");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<BatchModel>>("get", path);
        }
        #endregion

        #region Companies

        /// <summary>
        /// Retrieve all companies
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<CompanyModel>> CompaniesGet(string include, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<CompanyModel>>("get", path);
        }

        /// <summary>
        /// Create new companies
        /// </summary>
        /// <returns></returns>
        public async Task<CompanyModel[]> CompaniesPost()
        {
            var path = new AvaTaxPath("/api/v2/companies");
            return await RestCall<CompanyModel[]>("post", path);
        }
        #endregion

        #region Transactions

        /// <summary>
        /// Retrieve all transactions
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TransactionModel>> CompaniesByCompanyCodeTransactionsGet(string companyCode, string include, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions");
            path.ApplyField("companyCode", companyCode);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TransactionModel>>("get", path);
        }

        /// <summary>
        /// Retrieve a single transaction by code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeGet(string companyCode, string transactionCode, string include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("$include", include);
            return await RestCall<TransactionModel>("get", path);
        }

        /// <summary>
        /// Correct a previously created transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to adjust</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeAdjustPost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/adjust");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }

        /// <summary>
        /// Change a transaction's code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to change</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeChangecodePost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/changecode");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }

        /// <summary>
        /// Commit a transaction for reporting
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to commit</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeCommitPost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/commit");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }

        /// <summary>
        /// Perform multiple actions on a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeSettlePost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/settle");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }

        /// <summary>
        /// Verify a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeVerifyPost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/verify");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }

        /// <summary>
        /// Void a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to void</param>
        /// <returns></returns>
        public async Task<TransactionModel> CompaniesByCompanyCodeTransactionsByTransactionCodeVoidPost(string companyCode, string transactionCode)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/void");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            return await RestCall<TransactionModel>("post", path);
        }
        #endregion

        #region Batches

        /// <summary>
        /// Retrieve all batches for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these batches</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<BatchModel>> CompaniesByCompanyIdBatchesGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<BatchModel>>("get", path);
        }

        /// <summary>
        /// Create a new batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <returns></returns>
        public async Task<BatchModel[]> CompaniesByCompanyIdBatchesPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            return await RestCall<BatchModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch</param>
        /// <param name="id">The primary key of this batch</param>
        /// <returns></returns>
        public async Task<BatchModel> CompaniesByCompanyIdBatchesByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<BatchModel>("get", path);
        }

        /// <summary>
        /// Update a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that this batch belongs to.</param>
        /// <param name="id">The ID of the batch you wish to update</param>
        /// <returns></returns>
        public async Task<BatchModel> CompaniesByCompanyIdBatchesByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<BatchModel>("put", path);
        }

        /// <summary>
        /// Delete a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="id">The ID of the batch you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdBatchesByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Contacts

        /// <summary>
        /// Retrieve contacts for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these contacts</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ContactModel>> CompaniesByCompanyIdContactsGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ContactModel>>("get", path);
        }

        /// <summary>
        /// Create a new contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <returns></returns>
        public async Task<ContactModel[]> CompaniesByCompanyIdContactsPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            return await RestCall<ContactModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact</param>
        /// <param name="id">The primary key of this contact</param>
        /// <returns></returns>
        public async Task<ContactModel> CompaniesByCompanyIdContactsByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ContactModel>("get", path);
        }

        /// <summary>
        /// Update a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that this contact belongs to.</param>
        /// <param name="id">The ID of the contact you wish to update</param>
        /// <returns></returns>
        public async Task<ContactModel> CompaniesByCompanyIdContactsByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ContactModel>("put", path);
        }

        /// <summary>
        /// Delete a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="id">The ID of the contact you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdContactsByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Items

        /// <summary>
        /// Retrieve items for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these items</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ItemModel>> CompaniesByCompanyIdItemsGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ItemModel>>("get", path);
        }

        /// <summary>
        /// Create a new item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <returns></returns>
        public async Task<ItemModel[]> CompaniesByCompanyIdItemsPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            return await RestCall<ItemModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item object</param>
        /// <param name="id">The primary key of this item</param>
        /// <returns></returns>
        public async Task<ItemModel> CompaniesByCompanyIdItemsByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ItemModel>("get", path);
        }

        /// <summary>
        /// Update a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that this item belongs to.</param>
        /// <param name="id">The ID of the item you wish to update</param>
        /// <returns></returns>
        public async Task<ItemModel> CompaniesByCompanyIdItemsByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ItemModel>("put", path);
        }

        /// <summary>
        /// Delete a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="id">The ID of the item you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdItemsByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Locations

        /// <summary>
        /// Retrieve locations for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these locations</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<LocationModel>> CompaniesByCompanyIdLocationsGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<LocationModel>>("get", path);
        }

        /// <summary>
        /// Create a new location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <returns></returns>
        public async Task<LocationModel[]> CompaniesByCompanyIdLocationsPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            return await RestCall<LocationModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        /// <returns></returns>
        public async Task<LocationModel> CompaniesByCompanyIdLocationsByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<LocationModel>("get", path);
        }

        /// <summary>
        /// Update a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that this location belongs to.</param>
        /// <param name="id">The ID of the location you wish to update</param>
        /// <returns></returns>
        public async Task<LocationModel> CompaniesByCompanyIdLocationsByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<LocationModel>("put", path);
        }

        /// <summary>
        /// Delete a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="id">The ID of the location you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdLocationsByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }

        /// <summary>
        /// Validate the location against local requirements
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        /// <returns></returns>
        public async Task<LocationValidationModel> CompaniesByCompanyIdLocationsByIdValidateGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/validate");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<LocationValidationModel>("get", path);
        }
        #endregion

        #region Nexus

        /// <summary>
        /// Retrieve nexus for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these nexus objects</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> CompaniesByCompanyIdNexusGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<NexusModel>>("get", path);
        }

        /// <summary>
        /// Create a new nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <returns></returns>
        public async Task<NexusModel[]> CompaniesByCompanyIdNexusPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            return await RestCall<NexusModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus object</param>
        /// <param name="id">The primary key of this nexus</param>
        /// <returns></returns>
        public async Task<NexusModel> CompaniesByCompanyIdNexusByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<NexusModel>("get", path);
        }

        /// <summary>
        /// Update a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that this nexus belongs to.</param>
        /// <param name="id">The ID of the nexus you wish to update</param>
        /// <returns></returns>
        public async Task<NexusModel> CompaniesByCompanyIdNexusByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<NexusModel>("put", path);
        }

        /// <summary>
        /// Delete a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="id">The ID of the nexus you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdNexusByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Settings

        /// <summary>
        /// Retrieve all settings for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these settings</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SettingModel>> CompaniesByCompanyIdSettingsGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SettingModel>>("get", path);
        }

        /// <summary>
        /// Create a new setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <returns></returns>
        public async Task<SettingModel[]> CompaniesByCompanyIdSettingsPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            return await RestCall<SettingModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting</param>
        /// <param name="id">The primary key of this setting</param>
        /// <returns></returns>
        public async Task<SettingModel> CompaniesByCompanyIdSettingsByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<SettingModel>("get", path);
        }

        /// <summary>
        /// Update a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that this setting belongs to.</param>
        /// <param name="id">The ID of the setting you wish to update</param>
        /// <returns></returns>
        public async Task<SettingModel> CompaniesByCompanyIdSettingsByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<SettingModel>("put", path);
        }

        /// <summary>
        /// Delete a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="id">The ID of the setting you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdSettingsByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region TaxCodes

        /// <summary>
        /// Retrieve tax codes for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax codes</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> CompaniesByCompanyIdTaxcodesGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxCodeModel>>("get", path);
        }

        /// <summary>
        /// Create a new tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <returns></returns>
        public async Task<TaxCodeModel[]> CompaniesByCompanyIdTaxcodesPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            return await RestCall<TaxCodeModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code</param>
        /// <param name="id">The primary key of this tax code</param>
        /// <returns></returns>
        public async Task<TaxCodeModel> CompaniesByCompanyIdTaxcodesByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxCodeModel>("get", path);
        }

        /// <summary>
        /// Update a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax code belongs to.</param>
        /// <param name="id">The ID of the tax code you wish to update</param>
        /// <returns></returns>
        public async Task<TaxCodeModel> CompaniesByCompanyIdTaxcodesByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxCodeModel>("put", path);
        }

        /// <summary>
        /// Delete a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="id">The ID of the tax code you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdTaxcodesByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region TaxRules

        /// <summary>
        /// Retrieve tax rules for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax rules</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxRuleModel>> CompaniesByCompanyIdTaxrulesGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxRuleModel>>("get", path);
        }

        /// <summary>
        /// Create a new tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <returns></returns>
        public async Task<TaxRuleModel[]> CompaniesByCompanyIdTaxrulesPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            return await RestCall<TaxRuleModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule</param>
        /// <param name="id">The primary key of this tax rule</param>
        /// <returns></returns>
        public async Task<TaxRuleModel> CompaniesByCompanyIdTaxrulesByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxRuleModel>("get", path);
        }

        /// <summary>
        /// Update a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax rule belongs to.</param>
        /// <param name="id">The ID of the tax rule you wish to update</param>
        /// <returns></returns>
        public async Task<TaxRuleModel> CompaniesByCompanyIdTaxrulesByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxRuleModel>("put", path);
        }

        /// <summary>
        /// Delete a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="id">The ID of the tax rule you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdTaxrulesByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Upcs

        /// <summary>
        /// Retrieve UPCs for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these UPCs</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UPCModel>> CompaniesByCompanyIdUpcsGet(Int32 companyId, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UPCModel>>("get", path);
        }

        /// <summary>
        /// Create a new UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <returns></returns>
        public async Task<UPCModel[]> CompaniesByCompanyIdUpcsPost(Int32 companyId)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            return await RestCall<UPCModel[]>("post", path);
        }

        /// <summary>
        /// Retrieve a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC</param>
        /// <param name="id">The primary key of this UPC</param>
        /// <returns></returns>
        public async Task<UPCModel> CompaniesByCompanyIdUpcsByIdGet(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<UPCModel>("get", path);
        }

        /// <summary>
        /// Update a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that this UPC belongs to.</param>
        /// <param name="id">The ID of the UPC you wish to update</param>
        /// <returns></returns>
        public async Task<UPCModel> CompaniesByCompanyIdUpcsByIdPut(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<UPCModel>("put", path);
        }

        /// <summary>
        /// Delete a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="id">The ID of the UPC you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByCompanyIdUpcsByIdDelete(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }
        #endregion

        #region Companies

        /// <summary>
        /// Retrieve a single company
        /// </summary>
        /// <param name="id">The ID of the company to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<CompanyModel> CompaniesByIdGet(Int32 id, string include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<CompanyModel>("get", path);
        }

        /// <summary>
        /// Update a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to update.</param>
        /// <returns></returns>
        public async Task<CompanyModel> CompaniesByIdPut(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return await RestCall<CompanyModel>("put", path);
        }

        /// <summary>
        /// Delete a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> CompaniesByIdDelete(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path);
        }

        /// <summary>
        /// Quick setup for a company with a single physical address
        /// </summary>
        /// <returns></returns>
        public async Task<CompanyModel> CompaniesInitializePost()
        {
            var path = new AvaTaxPath("/api/v2/companies/initialize");
            return await RestCall<CompanyModel>("post", path);
        }
        #endregion

        #region Contacts

        /// <summary>
        /// Retrieve all contacts
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ContactModel>> ContactsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/contacts");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ContactModel>>("get", path);
        }
        #endregion

        #region Definitions

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
        /// <returns></returns>
        public async Task<FetchResult<LocationQuestionModel>> DefinitionsLocationquestionsGet(string line1, string line2, string line3, string city, string region, string postalCode, string country, Decimal latitude, Decimal longitude)
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
            return await RestCall<FetchResult<LocationQuestionModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for all countries and regions.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus");
            return await RestCall<FetchResult<NexusModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country.
        /// </summary>
        /// <param name="country"></param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryGet(string country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}");
            path.ApplyField("country", country);
            return await RestCall<FetchResult<NexusModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country and region.
        /// </summary>
        /// <param name="country">The two-character ISO-3166 code for the country.</param>
        /// <param name="region">The two or three character region code for the region.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryByRegionGet(string country, string region)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}/{region}");
            path.ApplyField("country", country);
            path.ApplyField("region", region);
            return await RestCall<FetchResult<NexusModel>>("get", path);
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
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByaddressGet(string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCall<FetchResult<NexusModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported extra parameters for creating transactions.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<ParameterModel>> DefinitionsParametersGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/parameters");
            return await RestCall<FetchResult<ParameterModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<String>> DefinitionsPermissionsGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/permissions");
            return await RestCall<FetchResult<String>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<SecurityRoleModel>> DefinitionsSecurityrolesGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/securityroles");
            return await RestCall<FetchResult<SecurityRoleModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported subscription types
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionTypeModel>> DefinitionsSubscriptiontypesGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/subscriptiontypes");
            return await RestCall<FetchResult<SubscriptionTypeModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxAuthorityModel>> DefinitionsTaxauthoritiesGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorities");
            return await RestCall<FetchResult<TaxAuthorityModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxAuthorityFormModel>> DefinitionsTaxauthorityformsGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorityforms");
            return await RestCall<FetchResult<TaxAuthorityFormModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax codes.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> DefinitionsTaxcodesGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodes");
            return await RestCall<FetchResult<TaxCodeModel>>("get", path);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax code types.
        /// </summary>
        /// <returns></returns>
        public async Task<TaxCodeTypesModel> DefinitionsTaxcodetypesGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodetypes");
            return await RestCall<TaxCodeTypesModel>("get", path);
        }
        #endregion

        #region Items

        /// <summary>
        /// Retrieve all items
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ItemModel>> ItemsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/items");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ItemModel>>("get", path);
        }
        #endregion

        #region Locations

        /// <summary>
        /// Retrieve all locations
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<LocationModel>> LocationsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/locations");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<LocationModel>>("get", path);
        }
        #endregion

        #region Nexus

        /// <summary>
        /// Retrieve all nexus
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> NexusGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/nexus");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<NexusModel>>("get", path);
        }
        #endregion

        #region Settings

        /// <summary>
        /// Retrieve all settings
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SettingModel>> SettingsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/settings");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SettingModel>>("get", path);
        }
        #endregion

        #region Subscriptions

        /// <summary>
        /// Retrieve all subscriptions
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionModel>> SubscriptionsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/subscriptions");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SubscriptionModel>>("get", path);
        }
        #endregion

        #region TaxCodes

        /// <summary>
        /// Retrieve all tax codes
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> TaxcodesGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxcodes");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxCodeModel>>("get", path);
        }
        #endregion

        #region TaxRates

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
        /// <returns></returns>
        public async Task<TaxRateModel> TaxratesByaddressGet(string line1, string line2, string line3, string city, string region, string postalCode, string country)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCall<TaxRateModel>("get", path);
        }

        /// <summary>
        /// Retrieve tax rates for a specified country and postal code
        /// </summary>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        /// <param name="postalCode">The postal code of the location.</param>
        /// <returns></returns>
        public async Task<TaxRateModel> TaxratesBypostalcodeGet(string country, string postalCode)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/bypostalcode");
            path.AddQuery("country", country);
            path.AddQuery("postalCode", postalCode);
            return await RestCall<TaxRateModel>("get", path);
        }
        #endregion

        #region TaxRules

        /// <summary>
        /// Retrieve all tax rules
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxRuleModel>> TaxrulesGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxrules");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxRuleModel>>("get", path);
        }
        #endregion

        #region Transactions

        /// <summary>
        /// Retrieve a single transaction by ID
        /// </summary>
        /// <param name="id">The unique ID number of the transaction to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<TransactionModel> TransactionsByIdGet(Int32 id, string include)
        {
            var path = new AvaTaxPath("/api/v2/transactions/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<TransactionModel>("get", path);
        }

        /// <summary>
        /// Create a new transaction
        /// </summary>
        /// <returns></returns>
        public async Task<TransactionModel> TransactionsCreatePost()
        {
            var path = new AvaTaxPath("/api/v2/transactions/create");
            return await RestCall<TransactionModel>("post", path);
        }
        #endregion

        #region Upcs

        /// <summary>
        /// Retrieve all UPCs
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UPCModel>> UpcsGet(string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/upcs");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UPCModel>>("get", path);
        }
        #endregion

        #region Users

        /// <summary>
        /// Retrieve all users
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UserModel>> UsersGet(string include, string filter, Int32 top, Int32 skip, string orderBy)
        {
            var path = new AvaTaxPath("/api/v2/users");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UserModel>>("get", path);
        }
        #endregion

        #region Utilities

        /// <summary>
        /// Tests connectivity and version of the service
        /// </summary>
        /// <returns></returns>
        public async Task<PingResultModel> UtilitiesPingGet()
        {
            var path = new AvaTaxPath("/api/v2/utilities/ping");
            return await RestCall<PingResultModel>("get", path);
        }

        /// <summary>
        /// List all services to which the current user is subscribed
        /// </summary>
        /// <returns></returns>
        public async Task<SubscriptionModel> UtilitiesSubscriptionsGet()
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions");
            return await RestCall<SubscriptionModel>("get", path);
        }

        /// <summary>
        /// Checks if the current user is subscribed to a specific service
        /// </summary>
        /// <param name="serviceTypeId">The service to check</param>
        /// <returns></returns>
        public async Task<SubscriptionModel> UtilitiesSubscriptionsByServiceTypeIdGet(string serviceTypeId)
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions/{serviceTypeId}");
            path.ApplyField("serviceTypeId", serviceTypeId);
            return await RestCall<SubscriptionModel>("get", path);
        }
        #endregion    
    }
}

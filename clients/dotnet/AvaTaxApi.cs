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
        #region Accounts

        /// <summary>
        /// Reset this account's license key
        /// </summary>
        /// <param name="id">The ID of the account you wish to update.</param>
        /// <param name="model">A request confirming that you wish to reset the license key of this account.</param>
        /// <returns></returns>
        public async Task<LicenseKeyModel> AccountResetLicenseKey(Int32 id, ResetLicenseKeyModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}/resetlicensekey");
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<LicenseKeyModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single account
        /// </summary>
        /// <param name="id">The ID of the account to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<AccountModel> GetAccount(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<AccountModel>("get", path, null);
        }
        #endregion

        #region Addresses

        /// <summary>
        /// Retrieve geolocation information for a specified address
        /// </summary>
        /// <param name="model">The address to resolve</param>
        /// <returns></returns>
        public async Task<AddressResolutionModel> ResolveAddress(AddressInfo model)
        {
            var path = new AvaTaxPath("/api/v2/addresses/resolve");
            path.AddQuery("model", model);
            return await RestCall<AddressResolutionModel>("post", path, model);
        }
        #endregion

        #region Batches

        /// <summary>
        /// Create a new batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="model">The batch you wish to create.</param>
        /// <returns></returns>
        public async Task<BatchModel[]> CreateBatches(Int32 companyId, BatchModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<BatchModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch.</param>
        /// <param name="id">The ID of the batch you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteBatch(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this batch</param>
        /// <param name="id">The primary key of this batch</param>
        /// <returns></returns>
        public async Task<BatchModel> GetBatch(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<BatchModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all batches for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these batches</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<BatchModel>> ListBatchesByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all batches
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<BatchModel>> QueryBatches(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/batches");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<BatchModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single batch
        /// </summary>
        /// <param name="companyId">The ID of the company that this batch belongs to.</param>
        /// <param name="id">The ID of the batch you wish to update</param>
        /// <param name="model">The batch you wish to update.</param>
        /// <returns></returns>
        public async Task<BatchModel> UpdateBatch(Int32 companyId, Int32 id, BatchModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/batches/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<BatchModel>("put", path, model);
        }
        #endregion

        #region Companies

        /// <summary>
        /// Quick setup for a company with a single physical address
        /// </summary>
        /// <param name="model">Information about the company you wish to create.</param>
        /// <returns></returns>
        public async Task<CompanyModel> CompanyInitialize(CompanyInitializationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/initialize");
            path.AddQuery("model", model);
            return await RestCall<CompanyModel>("post", path, model);
        }

        /// <summary>
        /// Create new companies
        /// </summary>
        /// <param name="model">Either a single company object or an array of companies to create</param>
        /// <returns></returns>
        public async Task<CompanyModel[]> CreateCompanies(CompanyModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            path.AddQuery("model", model);
            return await RestCall<CompanyModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteCompanies(Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single company
        /// </summary>
        /// <param name="id">The ID of the company to retrieve.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<CompanyModel> GetCompany(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<CompanyModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all companies
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<CompanyModel>> QueryCompanies(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<CompanyModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single company
        /// </summary>
        /// <param name="id">The ID of the company you wish to update.</param>
        /// <param name="model">The company object you wish to update.</param>
        /// <returns></returns>
        public async Task<CompanyModel> UpdateCompany(Int32 id, CompanyModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{id}");
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<CompanyModel>("put", path, model);
        }
        #endregion

        #region Contacts

        /// <summary>
        /// Create a new contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="model">The contacts you wish to create.</param>
        /// <returns></returns>
        public async Task<ContactModel[]> CreateContacts(Int32 companyId, ContactModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<ContactModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact.</param>
        /// <param name="id">The ID of the contact you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteContact(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this contact</param>
        /// <param name="id">The primary key of this contact</param>
        /// <returns></returns>
        public async Task<ContactModel> GetContact(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ContactModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve contacts for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these contacts</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ContactModel>> ListContactsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all contacts
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ContactModel>> QueryContacts(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/contacts");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ContactModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single contact
        /// </summary>
        /// <param name="companyId">The ID of the company that this contact belongs to.</param>
        /// <param name="id">The ID of the contact you wish to update</param>
        /// <param name="model">The contact you wish to update.</param>
        /// <returns></returns>
        public async Task<ContactModel> UpdateContact(Int32 companyId, Int32 id, ContactModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/contacts/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<ContactModel>("put", path, model);
        }
        #endregion

        #region Definitions

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country and region.
        /// </summary>
        /// <param name="country">The two-character ISO-3166 code for the country.</param>
        /// <param name="region">The two or three character region code for the region.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryByRegionGet(String country, String region)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}/{region}");
            path.ApplyField("country", country);
            path.ApplyField("region", region);
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for a country.
        /// </summary>
        /// <param name="country"></param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusByCountryGet(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/{country}");
            path.ApplyField("country", country);
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported nexus for all countries and regions.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> DefinitionsNexusGet()
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus");
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 countries
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<IsoCountryModel>> ListCountries()
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries");
            return await RestCall<FetchResult<IsoCountryModel>>("get", path, null);
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
        /// <returns></returns>
        public async Task<FetchResult<LocationQuestionModel>> ListLocationQuestionsByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country, Decimal? latitude, Decimal? longitude)
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
            return await RestCall<FetchResult<LocationQuestionModel>>("get", path, null);
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
        public async Task<FetchResult<NexusModel>> ListNexusByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/nexus/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported extra parameters for creating transactions.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<ParameterModel>> ListParameters()
        {
            var path = new AvaTaxPath("/api/v2/definitions/parameters");
            return await RestCall<FetchResult<ParameterModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<String>> ListPermissions()
        {
            var path = new AvaTaxPath("/api/v2/definitions/permissions");
            return await RestCall<FetchResult<String>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<IsoRegionModel>> ListRegions()
        {
            var path = new AvaTaxPath("/api/v2/definitions/regions");
            return await RestCall<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// List all ISO 3166 regions for a country
        /// </summary>
        /// <param name="country"></param>
        /// <returns></returns>
        public async Task<FetchResult<IsoRegionModel>> ListRegionsByCountry(String country)
        {
            var path = new AvaTaxPath("/api/v2/definitions/countries/{country}/regions");
            path.ApplyField("country", country);
            return await RestCall<FetchResult<IsoRegionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported permissions
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<SecurityRoleModel>> ListSecurityRoles()
        {
            var path = new AvaTaxPath("/api/v2/definitions/securityroles");
            return await RestCall<FetchResult<SecurityRoleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported subscription types
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionTypeModel>> ListSubscriptionTypes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/subscriptiontypes");
            return await RestCall<FetchResult<SubscriptionTypeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxAuthorityModel>> ListTaxAuthorities()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorities");
            return await RestCall<FetchResult<TaxAuthorityModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax authorities.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxAuthorityFormModel>> ListTaxAuthorityForms()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxauthorityforms");
            return await RestCall<FetchResult<TaxAuthorityFormModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax codes.
        /// </summary>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> ListTaxCodes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodes");
            return await RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve the full list of Avalara-supported tax code types.
        /// </summary>
        /// <returns></returns>
        public async Task<TaxCodeTypesModel> ListTaxCodeTypes()
        {
            var path = new AvaTaxPath("/api/v2/definitions/taxcodetypes");
            return await RestCall<TaxCodeTypesModel>("get", path, null);
        }
        #endregion

        #region Items

        /// <summary>
        /// Create a new item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="model">The item you wish to create.</param>
        /// <returns></returns>
        public async Task<ItemModel[]> CreateItems(Int32 companyId, ItemModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<ItemModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item.</param>
        /// <param name="id">The ID of the item you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteItem(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this item object</param>
        /// <param name="id">The primary key of this item</param>
        /// <returns></returns>
        public async Task<ItemModel> GetItem(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ItemModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve items for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these items</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ItemModel>> ListItemsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all items
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<ItemModel>> QueryItems(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/items");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<ItemModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single item
        /// </summary>
        /// <param name="companyId">The ID of the company that this item belongs to.</param>
        /// <param name="id">The ID of the item you wish to update</param>
        /// <param name="model">The item object you wish to update.</param>
        /// <returns></returns>
        public async Task<ItemModel> UpdateItem(Int32 companyId, Int32 id, ItemModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/items/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<ItemModel>("put", path, model);
        }
        #endregion

        #region Locations

        /// <summary>
        /// Create a new location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="model">The location you wish to create.</param>
        /// <returns></returns>
        public async Task<LocationModel[]> CreateLocations(Int32 companyId, LocationModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<LocationModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location.</param>
        /// <param name="id">The ID of the location you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        /// <returns></returns>
        public async Task<LocationModel> GetLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<LocationModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve locations for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these locations</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<LocationModel>> ListLocationsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all locations
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<LocationModel>> QueryLocations(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/locations");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<LocationModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single location
        /// </summary>
        /// <param name="companyId">The ID of the company that this location belongs to.</param>
        /// <param name="id">The ID of the location you wish to update</param>
        /// <param name="model">The location you wish to update.</param>
        /// <returns></returns>
        public async Task<LocationModel> UpdateLocation(Int32 companyId, Int32 id, LocationModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<LocationModel>("put", path, model);
        }

        /// <summary>
        /// Validate the location against local requirements
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this location</param>
        /// <param name="id">The primary key of this location</param>
        /// <returns></returns>
        public async Task<LocationValidationModel> ValidateLocation(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/locations/{id}/validate");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<LocationValidationModel>("get", path, null);
        }
        #endregion

        #region Nexus

        /// <summary>
        /// Create a new nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="model">The nexus you wish to create.</param>
        /// <returns></returns>
        public async Task<NexusModel[]> CreateNexus(Int32 companyId, NexusModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<NexusModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus.</param>
        /// <param name="id">The ID of the nexus you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteNexus(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this nexus object</param>
        /// <param name="id">The primary key of this nexus</param>
        /// <returns></returns>
        public async Task<NexusModel> GetNexus(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<NexusModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve nexus for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these nexus objects</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> ListNexusByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all nexus
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<NexusModel>> QueryNexus(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/nexus");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<NexusModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single nexus
        /// </summary>
        /// <param name="companyId">The ID of the company that this nexus belongs to.</param>
        /// <param name="id">The ID of the nexus you wish to update</param>
        /// <param name="model">The nexus object you wish to update.</param>
        /// <returns></returns>
        public async Task<NexusModel> UpdateNexus(Int32 companyId, Int32 id, NexusModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/nexus/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<NexusModel>("put", path, model);
        }
        #endregion

        #region Settings

        /// <summary>
        /// Create a new setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="model">The setting you wish to create.</param>
        /// <returns></returns>
        public async Task<SettingModel[]> CreateSettings(Int32 companyId, SettingModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<SettingModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting.</param>
        /// <param name="id">The ID of the setting you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteSetting(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this setting</param>
        /// <param name="id">The primary key of this setting</param>
        /// <returns></returns>
        public async Task<SettingModel> GetSetting(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<SettingModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all settings for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these settings</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SettingModel>> ListSettingsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all settings
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SettingModel>> QuerySettings(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/settings");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SettingModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single setting
        /// </summary>
        /// <param name="companyId">The ID of the company that this setting belongs to.</param>
        /// <param name="id">The ID of the setting you wish to update</param>
        /// <param name="model">The setting you wish to update.</param>
        /// <returns></returns>
        public async Task<SettingModel> UpdateSetting(Int32 companyId, Int32 id, SettingModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/settings/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<SettingModel>("put", path, model);
        }
        #endregion

        #region Subscriptions

        /// <summary>
        /// Retrieve a single subscription
        /// </summary>
        /// <param name="accountId">The ID of the account that owns this subscription</param>
        /// <param name="id">The primary key of this subscription</param>
        /// <returns></returns>
        public async Task<SubscriptionModel> GetSubscription(Int32 accountId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions/{id}");
            path.ApplyField("accountId", accountId);
            path.ApplyField("id", id);
            return await RestCall<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve subscriptions for this account
        /// </summary>
        /// <param name="accountId">The ID of the account that owns these subscriptions</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionModel>> ListSubscriptionsByAccount(Int32 accountId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/subscriptions");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SubscriptionModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all subscriptions
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<SubscriptionModel>> QuerySubscriptions(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/subscriptions");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<SubscriptionModel>>("get", path, null);
        }
        #endregion

        #region TaxCodes

        /// <summary>
        /// Create a new tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="model">The tax code you wish to create.</param>
        /// <returns></returns>
        public async Task<TaxCodeModel[]> CreateTaxCodes(Int32 companyId, TaxCodeModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<TaxCodeModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code.</param>
        /// <param name="id">The ID of the tax code you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteTaxCode(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax code</param>
        /// <param name="id">The primary key of this tax code</param>
        /// <returns></returns>
        public async Task<TaxCodeModel> GetTaxCode(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxCodeModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax codes for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax codes</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> ListTaxCodesByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax codes
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxCodeModel>> QueryTaxCodes(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxcodes");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxCodeModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single tax code
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax code belongs to.</param>
        /// <param name="id">The ID of the tax code you wish to update</param>
        /// <param name="model">The tax code you wish to update.</param>
        /// <returns></returns>
        public async Task<TaxCodeModel> UpdateTaxCode(Int32 companyId, Int32 id, TaxCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxcodes/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<TaxCodeModel>("put", path, model);
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
        public async Task<TaxRateModel> TaxRatesByAddress(String line1, String line2, String line3, String city, String region, String postalCode, String country)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/byaddress");
            path.AddQuery("line1", line1);
            path.AddQuery("line2", line2);
            path.AddQuery("line3", line3);
            path.AddQuery("city", city);
            path.AddQuery("region", region);
            path.AddQuery("postalCode", postalCode);
            path.AddQuery("country", country);
            return await RestCall<TaxRateModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rates for a specified country and postal code
        /// </summary>
        /// <param name="country">The two letter ISO-3166 country code.</param>
        /// <param name="postalCode">The postal code of the location.</param>
        /// <returns></returns>
        public async Task<TaxRateModel> TaxRatesByPostalCode(String country, String postalCode)
        {
            var path = new AvaTaxPath("/api/v2/taxrates/bypostalcode");
            path.AddQuery("country", country);
            path.AddQuery("postalCode", postalCode);
            return await RestCall<TaxRateModel>("get", path, null);
        }
        #endregion

        #region TaxRules

        /// <summary>
        /// Create a new tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="model">The tax rule you wish to create.</param>
        /// <returns></returns>
        public async Task<TaxRuleModel[]> CreateTaxRules(Int32 companyId, TaxRuleModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<TaxRuleModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule.</param>
        /// <param name="id">The ID of the tax rule you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteTaxRule(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this tax rule</param>
        /// <param name="id">The primary key of this tax rule</param>
        /// <returns></returns>
        public async Task<TaxRuleModel> GetTaxRule(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<TaxRuleModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve tax rules for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these tax rules</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxRuleModel>> ListTaxRules(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all tax rules
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<TaxRuleModel>> QueryTaxRules(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/taxrules");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TaxRuleModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single tax rule
        /// </summary>
        /// <param name="companyId">The ID of the company that this tax rule belongs to.</param>
        /// <param name="id">The ID of the tax rule you wish to update</param>
        /// <param name="model">The tax rule you wish to update.</param>
        /// <returns></returns>
        public async Task<TaxRuleModel> UpdateTaxRule(Int32 companyId, Int32 id, TaxRuleModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/taxrules/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<TaxRuleModel>("put", path, model);
        }
        #endregion

        #region Transactions

        /// <summary>
        /// Correct a previously created transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to adjust</param>
        /// <param name="model">The adjustment you wish to make</param>
        /// <returns></returns>
        public async Task<TransactionModel> AdjustTransaction(String companyCode, String transactionCode, AdjustTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/adjust");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Change a transaction's code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to change</param>
        /// <param name="model">The code change request you wish to execute</param>
        /// <returns></returns>
        public async Task<TransactionModel> ChangeTransactionCode(String companyCode, String transactionCode, ChangeTransactionCodeModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/changecode");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Commit a transaction for reporting
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to commit</param>
        /// <param name="model">The commit request you wish to execute</param>
        /// <returns></returns>
        public async Task<TransactionModel> CommitTransaction(String companyCode, String transactionCode, CommitTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/commit");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Create a new transaction
        /// </summary>
        /// <param name="model">The transaction you wish to create</param>
        /// <returns></returns>
        public async Task<TransactionModel> CreateTransaction(CreateTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/transactions/create");
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Retrieve a single transaction by code
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<TransactionModel> GetTransactionByCode(String companyCode, String transactionCode, String include)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("$include", include);
            return await RestCall<TransactionModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve a single transaction by ID
        /// </summary>
        /// <param name="id">The unique ID number of the transaction to retrieve</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<TransactionModel> GetTransactionById(Int32 id, String include)
        {
            var path = new AvaTaxPath("/api/v2/transactions/{id}");
            path.ApplyField("id", id);
            path.AddQuery("$include", include);
            return await RestCall<TransactionModel>("get", path, null);
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
        /// <returns></returns>
        public async Task<FetchResult<TransactionModel>> ListTransactionsByCompany(String companyCode, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions");
            path.ApplyField("companyCode", companyCode);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<TransactionModel>>("get", path, null);
        }

        /// <summary>
        /// Perform multiple actions on a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request containing the actions you wish to execute</param>
        /// <returns></returns>
        public async Task<TransactionModel> SettleTransaction(String companyCode, String transactionCode, SettleTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/settle");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Verify a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to settle</param>
        /// <param name="model">The settle request you wish to execute</param>
        /// <returns></returns>
        public async Task<TransactionModel> VerifyTransaction(String companyCode, String transactionCode, VerifyTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/verify");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }

        /// <summary>
        /// Void a transaction
        /// </summary>
        /// <param name="companyCode">The company code of the company that recorded this transaction</param>
        /// <param name="transactionCode">The transaction code to void</param>
        /// <param name="model">The void request you wish to execute</param>
        /// <returns></returns>
        public async Task<TransactionModel> VoidTransaction(String companyCode, String transactionCode, VoidTransactionModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyCode}/transactions/{transactionCode}/void");
            path.ApplyField("companyCode", companyCode);
            path.ApplyField("transactionCode", transactionCode);
            path.AddQuery("model", model);
            return await RestCall<TransactionModel>("post", path, model);
        }
        #endregion

        #region Upcs

        /// <summary>
        /// Create a new UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="model">The UPC you wish to create.</param>
        /// <returns></returns>
        public async Task<UPCModel[]> CreateUPCs(Int32 companyId, UPCModel[] model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            path.AddQuery("model", model);
            return await RestCall<UPCModel[]>("post", path, model);
        }

        /// <summary>
        /// Delete a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC.</param>
        /// <param name="id">The ID of the UPC you wish to delete.</param>
        /// <returns></returns>
        public async Task<ErrorResult> DeleteUPC(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<ErrorResult>("delete", path, null);
        }

        /// <summary>
        /// Retrieve a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that owns this UPC</param>
        /// <param name="id">The primary key of this UPC</param>
        /// <returns></returns>
        public async Task<UPCModel> GetUPC(Int32 companyId, Int32 id)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            return await RestCall<UPCModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve UPCs for this company
        /// </summary>
        /// <param name="companyId">The ID of the company that owns these UPCs</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UPCModel>> ListUPCsByCompany(Int32 companyId, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs");
            path.ApplyField("companyId", companyId);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all UPCs
        /// </summary>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UPCModel>> QueryUPCs(String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/upcs");
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UPCModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single UPC
        /// </summary>
        /// <param name="companyId">The ID of the company that this UPC belongs to.</param>
        /// <param name="id">The ID of the UPC you wish to update</param>
        /// <param name="model">The UPC you wish to update.</param>
        /// <returns></returns>
        public async Task<UPCModel> UpdateUPC(Int32 companyId, Int32 id, UPCModel model)
        {
            var path = new AvaTaxPath("/api/v2/companies/{companyId}/upcs/{id}");
            path.ApplyField("companyId", companyId);
            path.ApplyField("id", id);
            path.AddQuery("model", model);
            return await RestCall<UPCModel>("put", path, model);
        }
        #endregion

        #region Users

        /// <summary>
        /// Retrieve a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <returns></returns>
        public async Task<UserModel> GetUser(Int32 id, Int32 accountId, String include)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            return await RestCall<UserModel>("get", path, null);
        }

        /// <summary>
        /// Retrieve all entitlements for a single user
        /// </summary>
        /// <param name="id">The ID of the user to retrieve.</param>
        /// <param name="accountId">The accountID of the user you wish to get.</param>
        /// <returns></returns>
        public async Task<UserEntitlementModel> GetUserEntitlements(Int32 id, Int32 accountId)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}/entitlements");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            return await RestCall<UserEntitlementModel>("get", path, null);
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
        /// <returns></returns>
        public async Task<FetchResult<UserModel>> ListUsersByAccount(Int32 accountId, String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users");
            path.ApplyField("accountId", accountId);
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Retrieve all users
        /// </summary>
        /// <param name="include">A comma separated list of child objects to return underneath the primary object.</param>
        /// <param name="filter">A filter statement to identify specific records to retrieve, as defined by https://github.com/Microsoft/api-guidelines/blob/master/Guidelines.md#97-filtering .</param>
        /// <param name="top">If nonzero, return no more than this number of results.</param>
        /// <param name="skip">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <param name="orderBy">A comma separated list of sort statements in the format '(fieldname) [ASC|DESC]', for example 'id ASC'.</param>
        /// <returns></returns>
        public async Task<FetchResult<UserModel>> QueryUsers(String include, String filter, Int32? top, Int32? skip, String orderBy)
        {
            var path = new AvaTaxPath("/api/v2/users");
            path.AddQuery("$include", include);
            path.AddQuery("$filter", filter);
            path.AddQuery("$top", top);
            path.AddQuery("$skip", skip);
            path.AddQuery("$orderBy", orderBy);
            return await RestCall<FetchResult<UserModel>>("get", path, null);
        }

        /// <summary>
        /// Update a single user
        /// </summary>
        /// <param name="id">The ID of the user you wish to update.</param>
        /// <param name="accountId">The accountID of the user you wish to update.</param>
        /// <param name="model">The user object you wish to update.</param>
        /// <returns></returns>
        public async Task<UserModel> UpdateUser(Int32 id, Int32 accountId, UserModel model)
        {
            var path = new AvaTaxPath("/api/v2/accounts/{accountId}/users/{id}");
            path.ApplyField("id", id);
            path.ApplyField("accountId", accountId);
            path.AddQuery("model", model);
            return await RestCall<UserModel>("put", path, model);
        }
        #endregion

        #region Utilities

        /// <summary>
        /// Checks if the current user is subscribed to a specific service
        /// </summary>
        /// <param name="serviceTypeId">The service to check</param>
        /// <returns></returns>
        public async Task<SubscriptionModel> GetMySubscription(String serviceTypeId)
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions/{serviceTypeId}");
            path.ApplyField("serviceTypeId", serviceTypeId);
            return await RestCall<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// List all services to which the current user is subscribed
        /// </summary>
        /// <returns></returns>
        public async Task<SubscriptionModel> ListMySubscriptions()
        {
            var path = new AvaTaxPath("/api/v2/utilities/subscriptions");
            return await RestCall<SubscriptionModel>("get", path, null);
        }

        /// <summary>
        /// Tests connectivity and version of the service
        /// </summary>
        /// <returns></returns>
        public async Task<PingResultModel> Ping()
        {
            var path = new AvaTaxPath("/api/v2/utilities/ping");
            return await RestCall<PingResultModel>("get", path, null);
        }
        #endregion    
    }
}

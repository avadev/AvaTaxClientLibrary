using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using Newtonsoft.Json;

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// The main client class for working with AvaTax
    /// </summary>
    /// <remarks>
    /// This file contains all the basic behavior.  Individual APIs are in the other partial class.
    /// </remarks>
    public partial class AvaTaxClient
    {
        private HttpClient _client;

        #region Constructor
        /// <summary>
        /// Generate a client that connects to one of the standard AvaTax servers
        /// </summary>
        /// <param name="appName"></param>
        /// <param name="appVersion"></param>
        /// <param name="machineName"></param>
        /// <param name="environment"></param>
        public AvaTaxClient(string appName, string appVersion, string machineName, AvaTaxEnvironment environment)
        {
            _client = new HttpClient();
            switch (environment) {
                case AvaTaxEnvironment.Sandbox:
                    _client.BaseAddress = new Uri("https://sandbox-rest.avatax.com");
                    break;
                case AvaTaxEnvironment.Production:
                    _client.BaseAddress = new Uri("https://rest.avatax.com");
                    break;
            }

            // Setup client identifier
            WithClientIdentifier(appName, appVersion, machineName);
        }

        /// <summary>
        /// Generate a client that connects to a custom server
        /// </summary>
        /// <param name="appName"></param>
        /// <param name="appVersion"></param>
        /// <param name="machineName"></param>
        /// <param name="customEnvironment"></param>
        public AvaTaxClient(string appName, string appVersion, string machineName, Uri customEnvironment)
        {
            _client = new HttpClient();
            _client.BaseAddress = customEnvironment;

            // Setup client identifier
            WithClientIdentifier(appName, appVersion, machineName);
        }
        #endregion

        #region Security
        /// <summary>
        /// Sets the default security header string
        /// </summary>
        /// <param name="headerString"></param>
        public AvaTaxClient WithSecurity(string headerString)
        {
            _client.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Basic", headerString);
            return this;
        }

        /// <summary>
        /// Set security using username/password
        /// </summary>
        /// <param name="username"></param>
        /// <param name="password"></param>
        public AvaTaxClient WithSecurity(string username, string password)
        {
            var combined = String.Format("{0}:{1}", username, password);
            var bytes = Encoding.UTF8.GetBytes(combined);
            var base64 = System.Convert.ToBase64String(bytes);
            _client.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Basic", base64);
            return this;
        }

        /// <summary>
        /// Set security using AccountId / License Key
        /// </summary>
        /// <param name="accountId"></param>
        /// <param name="licenseKey"></param>
        public AvaTaxClient WithSecurity(int accountId, string licenseKey)
        {
            var combined = String.Format("{0}:{1}", accountId, licenseKey);
            var bytes = Encoding.UTF8.GetBytes(combined);
            var base64 = System.Convert.ToBase64String(bytes);
            _client.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Basic", base64);
            return this;
        }
        #endregion

        #region Client Identification
        /// <summary>
        /// Configure client identification
        /// </summary>
        /// <param name="appName"></param>
        /// <param name="appVersion"></param>
        /// <param name="machineName"></param>
        /// <returns></returns>
        public AvaTaxClient WithClientIdentifier(string appName, string appVersion, string machineName)
        {
            string clientId = String.Format("{0}; {1}; {2}; {3}; {4}", appName, appVersion, "CSharpRestClient", "2.16.11", machineName);
            if (_client.DefaultRequestHeaders.Any(h => h.Key == "X-Avalara-Client")) {
                _client.DefaultRequestHeaders.Remove("X-Avalara-Client");
            }
            _client.DefaultRequestHeaders.Add("X-Avalara-Client", clientId);
            return this;
        }
        #endregion

        #region Implementation
        /// <summary>
        /// Implementation of asynchronous client APIs
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="verb"></param>
        /// <param name="uri"></param>
        /// <param name="payload"></param>
        /// <returns></returns>
        private async Task<T> RestCallAsync<T>(string verb, AvaTaxPath uri, object payload = null)
        {
            // Make the request
            HttpResponseMessage result = null;
            string json = null;
            if (verb == "get") {
                result = await _client.GetAsync(uri.ToString());
            } else if (verb == "post") {
                json = JsonConvert.SerializeObject(payload);
                result = await _client.PostAsync(uri.ToString(), new StringContent(json, Encoding.UTF8, "application/json"));
            } else if (verb == "put") {
                json = JsonConvert.SerializeObject(payload);
                result = await _client.PutAsync(uri.ToString(), new StringContent(json, Encoding.UTF8, "application/json"));
            } else if (verb == "delete") {
                result = await _client.DeleteAsync(uri.ToString());
            }

            // Read the result
            var s = await result.Content.ReadAsStringAsync();
            if (result.IsSuccessStatusCode) {
                return JsonConvert.DeserializeObject<T>(s);
            } else {
                var err = JsonConvert.DeserializeObject<ErrorResult>(s);
                throw new AvaTaxError(err);
            }
        }

        /// <summary>
        /// Direct implementation of client APIs
        /// </summary>
        /// <typeparam name="T"></typeparam>
        /// <param name="verb"></param>
        /// <param name="uri"></param>
        /// <param name="payload"></param>
        /// <returns></returns>
        private T RestCall<T>(string verb, AvaTaxPath uri, object payload = null)
        {
            return RestCallAsync<T>(verb, uri, payload).Result;
        }
        #endregion
    }
}

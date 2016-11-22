using System;
using System.Collections.Generic;
#if PORTABLE
using System.Linq;
using System.Net.Http;
using System.Threading.Tasks;
#endif
using System.Net;
using System.Text;
using Newtonsoft.Json;
using System.IO;

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
#if PORTABLE
        private HttpClient _client;
#else
        private string _credentials;
        private string _clientHeader;
        private Uri _envUri;
#endif

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
            string envUri = null;
            switch (environment) {
                case AvaTaxEnvironment.Sandbox: envUri = Constants.AVATAX_SANDBOX_URL; break;
                case AvaTaxEnvironment.Production: envUri = Constants.AVATAX_PRODUCTION_URL; break;
                default: throw new Exception("Unrecognized Environment");
            }
            SetupClient(appName, appVersion, machineName, new Uri(envUri));
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
            SetupClient(appName, appVersion, machineName, customEnvironment);
        }

        private void SetupClient(string appName, string appVersion, string machineName, Uri envUri)
        {
#if PORTABLE
            _client = new HttpClient();
            _client.BaseAddress = envUri;
#else
            _envUri = envUri;
#endif

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
#if PORTABLE
            _client.DefaultRequestHeaders.Authorization = new System.Net.Http.Headers.AuthenticationHeaderValue("Basic", headerString);
#else
            _credentials = "Basic " + headerString;
#endif
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
            return WithSecurity(base64);
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
            return WithSecurity(base64);
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
            string clientId = String.Format("{0}; {1}; {2}; {3}; {4}", appName, appVersion, "CSharpRestClient", "2.16.12", machineName);
#if PORTABLE
            if (_client.DefaultRequestHeaders.Any(h => h.Key == "X-Avalara-Client")) {
                _client.DefaultRequestHeaders.Remove("X-Avalara-Client");
            }
            _client.DefaultRequestHeaders.Add("X-Avalara-Client", clientId);
#else
            _clientHeader = clientId;
#endif
            return this;
        }
#endregion

#region Implementation
#if PORTABLE
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
#else
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
            string path = _envUri.ToString() + uri.ToString();

            // Use HttpWebRequest so we can get a decent response
            var wr = (HttpWebRequest)WebRequest.Create(path);
            wr.Timeout = 0;
            wr.Proxy = null;

            // Construct the basic auth, if required
            if (!String.IsNullOrEmpty(_credentials)) {
                wr.Headers[HttpRequestHeader.Authorization] = _credentials;
            }
            if (!String.IsNullOrEmpty(_clientHeader)) {
                wr.Headers[Constants.AVALARA_CLIENT_HEADER] = _clientHeader;
            }

            // Convert the name-value pairs into a byte array
            wr.Method = verb.ToUpper();
            if (payload != null) {
                wr.ContentType = Constants.JSON_MIME_TYPE;
                wr.ServicePoint.Expect100Continue = false;

                // Encode the payload
                var json = JsonConvert.SerializeObject(payload);
                var encoding = new UTF8Encoding();
                byte[] data = encoding.GetBytes(json);
                wr.ContentLength = data.Length;

                // Call the server
                using (var s = wr.GetRequestStream()) {
                    s.Write(data, 0, data.Length);
                    s.Close();
                }
            }

            // Transmit, and get back the response, save it to a temp file
            try {
                using (var response = wr.GetResponse()) {
                    using (var inStream = response.GetResponseStream()) {
                        using (var reader = new StreamReader(inStream)) {
                            var resultString = reader.ReadToEnd();
                            return JsonConvert.DeserializeObject<T>(resultString);
                        }
                    }

                }

            // Catch a web exception
            } catch (WebException webex) {
                HttpWebResponse httpWebResponse = webex.Response as HttpWebResponse;
                if (httpWebResponse != null) {
                    using (Stream stream = httpWebResponse.GetResponseStream()) {
                        using (StreamReader reader = new StreamReader(stream)) {
                            var errString = reader.ReadToEnd();
                            var err = JsonConvert.DeserializeObject<ErrorResult>(errString);
                            throw new AvaTaxError(err);
                        }
                    }
                }

                // If we can't parse it as an AvaTax error, just throw
                throw webex;
            }
        }
#endif
#endregion
    }
}

/*
 * AvaTax Software Development Kit for JavaScript
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Justin Soliz <justin.soliz@@avalara.com>
 * @@author     Ted Spence <ted.spence@@avalara.com>
 * @@copyright  2004-2017 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@version    @SwaggerModel.ApiVersion
 * @@link       https://github.com/avadev/AvaTax-REST-V2-JS-SDK
 */

import fetch from 'isomorphic-fetch';
import { createBasicAuthHeader } from './utils/basic_auth';

export default class AvaTaxClient {
  /**
   * Construct a new AvaTaxClient 
   * 
   * @@constructor
   * @@param string appName      Specify the name of your application here.  Should not contain any semicolons.
   * @@param string appVersion   Specify the version number of your application here.  Should not contain any semicolons.
   * @@param string machineName  Specify the machine name of the machine on which this code is executing here.  Should not contain any semicolons.
   * @@param string environment  Indicates which server to use; acceptable values are "sandbox" or "production", or the full URL of your AvaTax instance.
   */
  constructor({ appName, appVersion, machineName, environment }) {
    this.baseUrl = 'https://rest.avatax.com';
    if (environment == 'sandbox') {
      this.baseUrl = 'https://sandbox-rest.avatax.com';
    } else if (
      environment.substring(0, 8) == 'https://' ||
      environment.substring(0, 7) == 'http://'
    ) {
      this.baseUrl = environment;
    }
    this.clientId =
      appName +
      '; ' +
      appVersion +
      '; JavascriptSdk; 17.6.0-89; ' +
      machineName;
  }

  /**
   * Configure this client to use the specified username/password security settings
   *
   * @@param  string          username        The username for your AvaTax user account
   * @@param  string          password        The password for your AvaTax user account
   * @@param  int             accountId       The account ID of your avatax account
   * @@param  string          licenseKey      The license key of your avatax account
   * @@param  string          bearerToken     The OAuth 2.0 token provided by Avalara Identity
   * @@return AvaTaxClient
   */
  withSecurity({ username, password, accountId, licenseKey, bearerToken }) {
    if (username != null && password != null) {
      this.auth = createBasicAuthHeader(username, password);
    } else if (accountId != null && licenseKey != null) {
      this.auth = createBasicAuthHeader(accountId, licenseKey);
    } else if (bearerToken != null) {
      this.auth = 'Bearer ' + bearerToken;
    }
    return this;
  }

  /**
   * Make a single REST call to the AvaTax v2 API server
   *
   * @@param   string  url        The relative path of the API on the server
   * @@param   string  verb       The HTTP verb being used in this request
   * @@param   string  payload    The request body, if this is being sent to a POST/PUT API call
   */
  restCall({ url, verb, payload }) {
    return fetch(url, {
      method: verb,
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        Authorization: this.auth,
        'X-Avalara-Client': this.clientId
      },
      body: JSON.stringify(payload)
    }).then(res => res.json())
      .then(json => {
        // handle error
        if (json.error) {
          let ex = new Error(json.error.message);
          ex.code = json.error.code;
          ex.target = json.error.target;
          ex.details = json.error.details;
          throw ex;
        } else {
          return json;
        }
      })
  }

  /**
   * Construct a URL with query string parameters
   *
   * @@param   string  url            The root URL of the API being called
   * @@param   string  parameters     A list of name-value pairs in a javascript object to create as query string parameters
   */
  buildUrl({ url, parameters }) {
    var qs = '';
    for (var key in parameters) {
      var value = parameters[key];
      if (value) {
        qs += encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
      }
    }
    if (qs.length > 0) {
      qs = qs.substring(0, qs.length - 1); //chop off last "&"
      url = url + '?' + qs;
    }
    return this.baseUrl + url;
  }

@foreach(var m in SwaggerModel.Methods) {
    var paramlist = new System.Text.StringBuilder();
    var queryparamlist = new System.Text.StringBuilder();
    var paramcomments = new System.Collections.Generic.List<string>();
    string payload = "null";
    foreach (var p in m.Params) {
        if (p.CleanParamName == "X-Avalara-Client") continue;
        paramlist.Append(p.CleanParamName);
        paramlist.Append(", ");
        paramcomments.Add("\r\n     * @param " + JavascriptTypeName(p.TypeName) + " " + p.CleanParamName + " " + PhpTypeComment(SwaggerModel, p));
        
        if (p.ParameterLocation == ParameterLocationType.QueryString) {
            queryparamlist.Append("        " + p.ParamName + ": " + p.CleanParamName + ",\r\n");
        }
        if (p.ParameterLocation == ParameterLocationType.RequestBody) {
            payload = p.CleanParamName;
        }
    }
    if (paramlist.Length > 0) paramlist.Length -= 2;
    if (queryparamlist.Length > 0) queryparamlist.Length -= 3;

<text>
  /**
   * @m.Summary
   *
   * @PhpComment(m.Description, 5)
   *
   * </text>@foreach (var pc in paramcomments) { Write(pc);}<text>
   * @@return @JavascriptTypeName(m.ResponseTypeName)
   */
  @{Write("  " + FirstCharLower(m.Name) + "({ " + paramlist.ToString() + " } = {})");} {
    var path = this.buildUrl({
      url: `@m.URI.Replace("{", "${")`,
@if (queryparamlist.Length > 0) {
    WriteLine("      parameters: {");
    WriteLine(queryparamlist.ToString());
    WriteLine("      }");
} else {
    WriteLine("      parameters: {}");
}
    });
    return this.restCall({ url: path, verb: '@m.HttpVerb.ToLower()', payload: @payload });
  }
</text>}
}

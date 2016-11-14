using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// Build a URL including variables in paths and query strings
    /// </summary>
    /// <remarks>
    /// Since this feature is not consistently available throughout C# / DOTNET versions, this is a cross-version compatibility feature.
    /// </remarks>
    public class AvaTaxPath
    {
        StringBuilder _path = new StringBuilder();
        Dictionary<string, string> _query = new Dictionary<string, string>();

        /// <summary>
        /// Construct a base path
        /// </summary>
        /// <param name="uri"></param>
        public AvaTaxPath(string uri)
        {
            _path.Append(uri);
        }

        /// <summary>
        /// Apply a variable in the path
        /// </summary>
        /// <param name="name"></param>
        /// <param name="value"></param>
        public void ApplyField(string name, object value)
        {
            _path.Replace("{" + name + "}", value.ToString());
        }

        /// <summary>
        /// Apply a variable in the path
        /// </summary>
        /// <param name="name"></param>
        /// <param name="value"></param>
        public void AddQuery(string name, object value)
        {
            if (value != null) {
                _query[name] = value.ToString();
            }
        }

        /// <summary>
        /// Convert this to a string for use in a REST call
        /// </summary>
        /// <returns></returns>
        public override string ToString()
        {
            if (_query.Count > 0) {
                var array = (from kvp in _query select string.Format("{0}={1}", WebUtility.UrlEncode(kvp.Key), WebUtility.UrlEncode(kvp.Value))).ToArray();
                _path.Append("?");
                _path.Append(String.Join("&", array));
            }
            return _path.ToString();
        }
    }
}

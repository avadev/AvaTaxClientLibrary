using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using ClientApiGenerator.Models;

namespace ClientApiGenerator.Filters
{
    public class ApexFilters : Filter
    {
        // Hash set to hold all unique keywords to Apex
        HashSet<String> KeyWordSet = new HashSet<string>();
  
        // List of Apex keywords that has present in either model or enum name
        string[] KeyWordList = { "number", "set", "new", "exception", "currency", "boolean", "string", "blob", "set", "desc", "default", "type", "char", "extends", "virtual", "today", "transaction", "search", "retrieve", "class", "const", "decimal", "global" };

        public SwaggerInfo KeyWordFilter(SwaggerInfo api, string templateType)
        {

            // populate set
            if (KeyWordSet.Count == 0)
            {
                foreach(var w in KeyWordList)
                {
                    KeyWordSet.Add(w);
                }
            }

            // if we are filtering enum names
            if (templateType == "enum")
            {
                foreach (var enumType in api.Enums)
                {
                    foreach (var e in enumType.Items)
                    {
                        // add "Field" to each conflicted enum name to prevent compile error in Apex
                        if (KeyWordSet.Contains(e.Value))
                        {
                            e.Value = e.Value + "Field";
                        }
                    }
                }
            }

            // if we are filtering model property names
            if (templateType == "model")
            {
                foreach(var model in api.Models)
                {
                    foreach(var property in model.Properties)
                    {
                        // two types of property name, both needs to be filtered for key words
                        if (KeyWordSet.Contains(property.CleanParamName))
                        {
                            property._CleanParamName = property.CleanParamName + "Field";
                        } else if (KeyWordSet.Contains(property.StrippedPackageParamName))
                        {
                            property._StrippedPackageParamName = property.StrippedPackageParamName + "Field";
                        }
                    }
                }
                
            }

            // return new api file
            return api;
        }
    }
}

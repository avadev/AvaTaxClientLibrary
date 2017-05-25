using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ClientApiGenerator
{
    public class TypeMap
    {
        /// <summary>
        /// The class name as it exists in CSharp
        /// </summary>
        public string Csharp { get; set; }

        /// <summary>
        /// The class name as it exists in Java
        /// </summary>
        public string Java { get; set; }

        /// <summary>
        /// The documentation name that should appear in comments in PHP - not really a class name
        /// </summary>
        public string PHP { get; set; }

        /// <summary>
        /// The full list of known type maps
        /// </summary>
        public static TypeMap[] ALL_TYPES = new TypeMap[] {
            new TypeMap() { Csharp = "SByte",       Java = "byte",      PHP = "int" },      // signed byte
            new TypeMap() { Csharp = "Byte",        Java = "byte",      PHP = "int" },      // unsigned byte
            new TypeMap() { Csharp = "Int16",       Java = "short",     PHP = "int" },      // signed 16-bit integer
            new TypeMap() { Csharp = "UInt16",      Java = "short",     PHP = "int" },      // unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32",       Java = "int",       PHP = "int" },      // signed 32-bit integer
            new TypeMap() { Csharp = "UInt32",      Java = "int",       PHP = "int" },      // unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64",       Java = "long",      PHP = "int" },      // signed 64-bit integer
            new TypeMap() { Csharp = "UInt64",      Java = "long",      PHP = "int" },      // unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime",    Java = "Instant",   PHP = "string" },   // native representation of date time
            new TypeMap() { Csharp = "Decimal",     Java = "Decimal",   PHP = "float" },    // fixed precision decimal
            new TypeMap() { Csharp = "Single",      Java = "float",     PHP = "float" },    // single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double",      Java = "double",    PHP = "float" },    // double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean",     Java = "boolean",   PHP = "boolean" },  // true/false
            new TypeMap() { Csharp = "SByte?",      Java = "Byte",      PHP = "int" },      // nullable signed byte
            new TypeMap() { Csharp = "Byte?",       Java = "Byte",      PHP = "int" },      // nullable unsigned byte
            new TypeMap() { Csharp = "Int16?",      Java = "Short",     PHP = "int" },      // nullable signed 16-bit integer
            new TypeMap() { Csharp = "UInt16?",     Java = "Short",     PHP = "int" },      // nullable unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32?",      Java = "Integer",   PHP = "int" },      // nullable signed 32-bit integer
            new TypeMap() { Csharp = "UInt32?",     Java = "Integer",   PHP = "int" },      // nullable unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64?",      Java = "Long",      PHP = "int" },      // nullable signed 64-bit integer
            new TypeMap() { Csharp = "UInt64?",     Java = "Long",      PHP = "int" },      // nullable unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime?",   Java = "Instant",   PHP = "string" },   // nullable native representation of date time
            new TypeMap() { Csharp = "Decimal?",    Java = "Decimal",   PHP = "float" },    // nullable fixed precision decimal
            new TypeMap() { Csharp = "Single?",     Java = "Float",     PHP = "float" },    // nullable single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double?",     Java = "Double",    PHP = "float" },    // nullable double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean",     Java = "Boolean",   PHP = "boolean" },  // nullable true/false
            new TypeMap() { Csharp = "Char",        Java = "char",      PHP = "string" },   // single unicode character
            new TypeMap() { Csharp = "String",      Java = "string",    PHP = "string" },   // string of unicode characters
        };
    }

}

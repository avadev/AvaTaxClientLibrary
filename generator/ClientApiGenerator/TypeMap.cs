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
        /// The class name for Ruby
        /// </summary>
        public string Ruby { get; set; }

        /// <summary>
        /// The full list of known type maps
        /// </summary>
        public static TypeMap[] ALL_TYPES = new TypeMap[] {
            new TypeMap() { Csharp = "SByte",       Java = "Byte",      PHP = "int",        Ruby = "Integer" },     // signed byte
            new TypeMap() { Csharp = "Byte",        Java = "Byte",      PHP = "int",        Ruby = "Integer" },     // unsigned byte
            new TypeMap() { Csharp = "Int16",       Java = "Short",     PHP = "int",        Ruby = "Integer" },     // signed 16-bit integer
            new TypeMap() { Csharp = "UInt16",      Java = "Short",     PHP = "int",        Ruby = "Integer" },     // unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32",       Java = "Integer",   PHP = "int",        Ruby = "Integer" },     // signed 32-bit integer
            new TypeMap() { Csharp = "UInt32",      Java = "Integer",   PHP = "int",        Ruby = "Integer" },     // unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64",       Java = "Long",      PHP = "int",        Ruby = "Integer" },     // signed 64-bit integer
            new TypeMap() { Csharp = "UInt64",      Java = "Long",      PHP = "int",        Ruby = "Integer" },     // unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime",    Java = "Date",      PHP = "string",     Ruby = "DateTime" },    // native representation of date time
            new TypeMap() { Csharp = "Decimal",     Java = "BigDecimal",PHP = "float",      Ruby = "BigDecimal" },  // fixed precision decimal
            new TypeMap() { Csharp = "Single",      Java = "Float",     PHP = "float",      Ruby = "Float" },       // single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double",      Java = "Double",    PHP = "float",      Ruby = "Float" },       // double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean",     Java = "Boolean",   PHP = "boolean",    Ruby = "Float" },       // true/false
            new TypeMap() { Csharp = "SByte?",      Java = "Byte",      PHP = "int",        Ruby = "Integer" },     // nullable signed byte
            new TypeMap() { Csharp = "Byte?",       Java = "Byte",      PHP = "int",        Ruby = "Integer" },     // nullable unsigned byte
            new TypeMap() { Csharp = "Int16?",      Java = "Short",     PHP = "int",        Ruby = "Integer" },     // nullable signed 16-bit integer
            new TypeMap() { Csharp = "UInt16?",     Java = "Short",     PHP = "int",        Ruby = "Integer" },     // nullable unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32?",      Java = "Integer",   PHP = "int",        Ruby = "Integer" },     // nullable signed 32-bit integer
            new TypeMap() { Csharp = "UInt32?",     Java = "Integer",   PHP = "int",        Ruby = "Integer" },     // nullable unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64?",      Java = "Long",      PHP = "int",        Ruby = "Integer" },     // nullable signed 64-bit integer
            new TypeMap() { Csharp = "UInt64?",     Java = "Long",      PHP = "int",        Ruby = "Integer" },     // nullable unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime?",   Java = "Date",      PHP = "string",     Ruby = "DateTime" },    // nullable native representation of date time
            new TypeMap() { Csharp = "Decimal?",    Java = "BigDecimal",PHP = "float",      Ruby = "BigDecimal" },  // nullable fixed precision decimal
            new TypeMap() { Csharp = "Single?",     Java = "Float",     PHP = "float",      Ruby = "Float" },       // nullable single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double?",     Java = "Double",    PHP = "float",      Ruby = "Float" },       // nullable double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean?",    Java = "Boolean",   PHP = "boolean",    Ruby = "object" },      // nullable true/false
            new TypeMap() { Csharp = "Char",        Java = "Character", PHP = "string",     Ruby = "String" },      // single unicode character
            new TypeMap() { Csharp = "Char?",       Java = "Character", PHP = "string",     Ruby = "String" },      // single unicode character
            new TypeMap() { Csharp = "String",      Java = "String",    PHP = "string",     Ruby = "String" },      // string of unicode characters
        };
    }

}

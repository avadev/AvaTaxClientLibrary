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
        /// The class name for Python
        /// </summary>
        public string Python { get; set; }

        /// <summary>
        /// The full list of known type maps
        /// </summary>
        public static TypeMap[] ALL_TYPES = new TypeMap[] {
            new TypeMap() { Csharp = "SByte",       Java = "Byte",      PHP = "int",        Ruby = "Integer",       Python="int" },     // signed byte
            new TypeMap() { Csharp = "Byte",        Java = "Byte",      PHP = "int",        Ruby = "Integer",       Python="int" },     // unsigned byte
            new TypeMap() { Csharp = "Int16",       Java = "Short",     PHP = "int",        Ruby = "Integer",       Python="int" },     // signed 16-bit integer
            new TypeMap() { Csharp = "UInt16",      Java = "Short",     PHP = "int",        Ruby = "Integer",       Python="int" },     // unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32",       Java = "Integer",   PHP = "int",        Ruby = "Integer",       Python="int" },     // signed 32-bit integer
            new TypeMap() { Csharp = "UInt32",      Java = "Integer",   PHP = "int",        Ruby = "Integer",       Python="int" },     // unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64",       Java = "Long",      PHP = "int",        Ruby = "Integer",       Python="int" },     // signed 64-bit integer
            new TypeMap() { Csharp = "UInt64",      Java = "Long",      PHP = "int",        Ruby = "Integer",       Python="int" },     // unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime",    Java = "Date",      PHP = "string",     Ruby = "DateTime",      Python="datetime" },    // native representation of date time
            new TypeMap() { Csharp = "Decimal",     Java = "BigDecimal",PHP = "float",      Ruby = "BigDecimal",    Python="decimal" },  // fixed precision decimal
            new TypeMap() { Csharp = "Single",      Java = "Float",     PHP = "float",      Ruby = "Float",         Python="float" },       // single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double",      Java = "Double",    PHP = "float",      Ruby = "Float",         Python="float" },       // double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean",     Java = "Boolean",   PHP = "boolean",    Ruby = "Float",         Python="float" },       // true/false
            new TypeMap() { Csharp = "SByte?",      Java = "Byte",      PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable signed byte
            new TypeMap() { Csharp = "Byte?",       Java = "Byte",      PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable unsigned byte
            new TypeMap() { Csharp = "Int16?",      Java = "Short",     PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable signed 16-bit integer
            new TypeMap() { Csharp = "UInt16?",     Java = "Short",     PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable unsigned 16-bit integer
            new TypeMap() { Csharp = "Int32?",      Java = "Integer",   PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable signed 32-bit integer
            new TypeMap() { Csharp = "UInt32?",     Java = "Integer",   PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable unsigned 32-bit integer
            new TypeMap() { Csharp = "Int64?",      Java = "Long",      PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable signed 64-bit integer
            new TypeMap() { Csharp = "UInt64?",     Java = "Long",      PHP = "int",        Ruby = "Integer",       Python="int" },     // nullable unsigned 64-bit integer
            new TypeMap() { Csharp = "DateTime?",   Java = "Date",      PHP = "string",     Ruby = "DateTime",      Python="datetime" },    // nullable native representation of date time
            new TypeMap() { Csharp = "Decimal?",    Java = "BigDecimal",PHP = "float",      Ruby = "BigDecimal",    Python="decimal" },  // nullable fixed precision decimal
            new TypeMap() { Csharp = "Single?",     Java = "Float",     PHP = "float",      Ruby = "Float",         Python="float" },       // nullable single precision (32-bit) IEEE floating point
            new TypeMap() { Csharp = "Double?",     Java = "Double",    PHP = "float",      Ruby = "Float",         Python="float" },       // nullable double precision (64-bit) IEEE floating point
            new TypeMap() { Csharp = "Boolean?",    Java = "Boolean",   PHP = "boolean",    Ruby = "object",        Python="boolean" },      // nullable true/false
            new TypeMap() { Csharp = "Char",        Java = "Character", PHP = "string",     Ruby = "String",        Python="string" },      // single unicode character
            new TypeMap() { Csharp = "Char?",       Java = "Character", PHP = "string",     Ruby = "String",        Python="string" },      // single unicode character
            new TypeMap() { Csharp = "String",      Java = "String",    PHP = "string",     Ruby = "String",        Python="string" },      // string of unicode characters
        };
    }

}

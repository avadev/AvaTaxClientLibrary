using System;

/*
 * AvaTax API Client Library
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author Ted Spence
 * @@author Zhenya Frolov
 */

namespace Avalara.AvaTax.RestClient
{
    /// <summary>
    /// @CSharpComment(EnumModel.Comment, 4)
    /// </summary>
    public enum @EnumModel.EnumDataType
    {
@foreach(var v in EnumModel.Items) {
WriteLine("        /// <summary>");
WriteLine("        /// " + CSharpComment(v.Comment, 8));
WriteLine("        /// </summary>");
WriteLine("        {0},", v.Value);
WriteLine("");
}
    }
}

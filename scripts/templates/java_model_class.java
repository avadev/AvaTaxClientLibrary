package net.avalara.avatax.rest.client.models;

import net.avalara.avatax.rest.client.enums.*;
import net.avalara.avatax.rest.client.serializer.JsonSerializer;

import java.lang.Override;
import java.math.BigDecimal;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;

/*
 * AvaTax Software Development Kit for Java JRE based environments
 *
 * (c) 2004-2017 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Dustin Welden <dustin.welden@@avalara.com>
 * @@copyright  2004-2017 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@version    @SwaggerModel.ApiVersion
 * @@link       https://github.com/avadev/AvaTax-REST-V2-JRE-SDK
 */

/**
 * @PhpComment(ClassModel.Comment, 0)
 */
public class @ClassModel.SchemaName {

@foreach(var p in ClassModel.Properties) {
<text>
    private @JavaTypeName(p.TypeName) @FirstCharLower(p.CleanParamName);

    /**
     * Getter for @FirstCharLower(p.CleanParamName)
     *
     * @PhpComment(p.Comment, 4)
     */
    public @JavaTypeName(p.TypeName) @Emit("get" + FirstCharUpper(p.CleanParamName) + "() {")
        return @Emit("this." + FirstCharLower(p.CleanParamName) + ";")
    }

    /**
     * Setter for @FirstCharLower(p.CleanParamName)
     *
     * @PhpComment(p.Comment, 4)
     */
    public void @Emit("set" + FirstCharUpper(p.CleanParamName) + "(" + JavaTypeName(p.TypeName) + " value) {")
        @Emit("this." + FirstCharLower(p.CleanParamName) + " = value;")
    }
</text>
}

    /**
     * Returns a JSON string representation of @ClassModel.SchemaName
     */
    @@Override
    public String toString() {
        return JsonSerializer.SerializeObject(this);
    }
}

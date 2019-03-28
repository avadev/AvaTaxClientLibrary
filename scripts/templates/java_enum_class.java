package net.avalara.avatax.rest.client.enums;
import java.util.HashMap;

/*
 * AvaTax Software Development Kit for Java JRE based environments
 *
 * (c) 2004-2018 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @@author     Dustin Welden <dustin.welden@@avalara.com>
 * @@copyright  2004-2018 Avalara, Inc.
 * @@license    https://www.apache.org/licenses/LICENSE-2.0
 * @@link       https://github.com/avadev/AvaTax-REST-V2-JRE-SDK
 */

/**
 * @PhpComment(EnumModel.Summary, 1)
 */
public enum @EnumModel.Name {
@for(int i = 0; i<EnumModel.Values.Count; i++){
var v = EnumModel.Values[i];
WriteLine("    /** ");
WriteLine("     * " + PhpComment(v.Summary, 5));
WriteLine("     */");
WriteLine("    {0}({1}){2}", v.Name, v.Value, ((i < EnumModel.Values.Count - 1) ? "," : ";"));
WriteLine("");
}
    private int value;
	private static HashMap map = new HashMap<>();
	
	private @EnumModel.Name@Emit("(int value)") {
		this.value = value;
	}
	
	static {
		for (@EnumModel.Name enumName : @EnumModel.Name@Emit(".values())") {
			map.put(enumName.value, enumName);
		}
	}
	
	public static @EnumModel.Name @Emit("valueOf(int intValue)") {
		return (@EnumModel.Name) map.get(intValue);
	}
	
	public int getValue() {
		return value;
	}
}

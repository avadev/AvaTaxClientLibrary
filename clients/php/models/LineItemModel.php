<?php 
namespace Avalara;
/*
 * AvaTax API Client Library
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   AvaTax client libraries
 * @package    Avalara.AvaTaxClient
 * @author     Ted Spence <ted.spence@avalara.com>
 * @author     Bob Maidens <bob.maidens@avalara.com>
 * @copyright  2004-2016 Avalara, Inc.
 * @license    https://www.apache.org/licenses/LICENSE-2.0
 * @version    2.16.12-30
 * @link       https://github.com/avadev/AvaTaxClientLibrary
 */


/**
 * Represents one line item in a transaction
 */
final class LineItemModel
{
    /**
     * @var String Line number within this document
     */
    public $number;

    /**
     * @var Decimal Quantity of items in this line
     */
    public $quantity;

    /**
     * @var Decimal Total amount for this line
     */
    public $amount;

    /**
     * @var Dictionary<TransactionAddressType, AddressInfo> Specify any differences for addresses between this line and the rest of the document
     */
    public $addresses;

    /**
     * @var String Tax Code - System or Custom Tax Code.
     */
    public $taxCode;

    /**
     * @var String Customer Usage Type - The client application customer or usage type.
     */
    public $customerUsageType;

    /**
     * @var String Item Code (SKU)
     */
    public $itemCode;

    /**
     * @var String Exemption number for this line
     */
    public $exemptionCode;

    /**
     * @var Boolean? True if the document discount should be applied to this line
     */
    public $discounted;

    /**
     * @var Boolean? Indicates if line has Tax Included; defaults to false
     */
    public $taxIncluded;

    /**
     * @var String Revenue Account
     */
    public $revenueAccount;

    /**
     * @var String Reference 1 - Client specific reference field
     */
    public $ref1;

    /**
     * @var String Reference 2 - Client specific reference field
     */
    public $ref2;

    /**
     * @var String Item description.  This is required for SST transactions if an unmapped ItemCode is used.
     */
    public $description;

    /**
     * @var String BusinessIdentificationNo
     */
    public $businessIdentificationNo;

    /**
     * @var TaxOverrideModel Specifies a tax override for this line
     */
    public $taxOverride;

    /**
     * @var Dictionary<string, string> Special parameters that apply to this line within this transaction.
     *                 To get a full list of available parameters, please use the /api/v2/definitions/parameters endpoint.
     */
    public $parameters;

}

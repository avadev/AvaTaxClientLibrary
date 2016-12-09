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
 * One line item on this transaction.
 */
final class TransactionLineModel
{
    /**
     * @var Int32? The unique ID number of this transaction line item.
     */
    public $id;

    /**
     * @var Int32? The unique ID number of the transaction to which this line item belongs.
     */
    public $transactionId;

    /**
     * @var String The line number or code indicating the line on this invoice or receipt or document.
     */
    public $lineNumber;

    /**
     * @var Int32? The unique ID number of the boundary override applied to this line item.
     */
    public $boundaryOverrideId;

    /**
     * @var String The customer usage type for this line item.  Usage type often affects taxability rules.
     */
    public $customerUsageType;

    /**
     * @var String A description of the item or service represented by this line.
     */
    public $description;

    /**
     * @var Int32? The unique ID number of the destination address where this line was delivered or sold.
     *             In the case of a point-of-sale transaction, the destination address and origin address will be the same.
     *             In the case of a shipped transaction, they will be different.
     */
    public $destinationAddressId;

    /**
     * @var Int32? The unique ID number of the origin address where this line was delivered or sold.
     *             In the case of a point-of-sale transaction, the origin address and destination address will be the same.
     *             In the case of a shipped transaction, they will be different.
     */
    public $originAddressId;

    /**
     * @var Decimal? The amount of discount that was applied to this line item.  This represents the difference between list price and sale price of the item.
     *             In general, a discount represents money that did not change hands; tax is calculated on only the amount of money that changed hands.
     */
    public $discountAmount;

    /**
     * @var Int32? The type of discount, if any, that was applied to this line item.
     */
    public $discountTypeId;

    /**
     * @var Decimal? The amount of this line item that was exempt.
     */
    public $exemptAmount;

    /**
     * @var Int32? The unique ID number of the exemption certificate that applied to this line item.
     */
    public $exemptCertId;

    /**
     * @var String If this line item was exempt, this string contains the word 'Exempt'.
     */
    public $exemptNo;

    /**
     * @var Boolean? True if this item is taxable.
     */
    public $isItemTaxable;

    /**
     * @var Boolean? True if this item is a Streamlined Sales Tax line item.
     */
    public $isSSTP;

    /**
     * @var String The code string of the item represented by this line item.
     */
    public $itemCode;

    /**
     * @var Decimal? The total amount of the transaction, including both taxable and exempt.  This is the total price for all items.
     *             To determine the individual item price, divide this by quantity.
     */
    public $lineAmount;

    /**
     * @var Decimal? The quantity of products sold on this line item.
     */
    public $quantity;

    /**
     * @var String A user-defined reference identifier for this transaction line item.
     */
    public $ref1;

    /**
     * @var String A user-defined reference identifier for this transaction line item.
     */
    public $ref2;

    /**
     * @var DateTime? The date when this transaction should be reported.  By default, all transactions are reported on the date when the actual transaction took place.
     *             In some cases, line items may be reported later due to delayed shipments or other business reasons.
     */
    public $reportingDate;

    /**
     * @var String The revenue account number for this line item.
     */
    public $revAccount;

    /**
     * @var Sourcing? Indicates whether this line item was taxed according to the origin or destination.
     */
    public $sourcing;

    /**
     * @var Decimal? The amount of tax generated for this line item.
     */
    public $tax;

    /**
     * @var Decimal? The taxable amount of this line item.
     */
    public $taxableAmount;

    /**
     * @var Decimal? The tax calculated for this line by Avalara.  If the transaction was calculated with a tax override, this amount will be different from the "tax" value.
     */
    public $taxCalculated;

    /**
     * @var String The code string for the tax code that was used to calculate this line item.
     */
    public $taxCode;

    /**
     * @var Int32? The unique ID number for the tax code that was used to calculate this line item.
     */
    public $taxCodeId;

    /**
     * @var DateTime? The date that was used for calculating tax amounts for this line item.  By default, this date should be the same as the document date.
     *             In some cases, for example when a consumer returns a product purchased previously, line items may be calculated using a tax date in the past
     *             so that the consumer can receive a refund for the correct tax amount that was charged when the item was originally purchased.
     */
    public $taxDate;

    /**
     * @var String The tax engine identifier that was used to calculate this line item.
     */
    public $taxEngine;

    /**
     * @var TaxOverrideTypeId? If a tax override was specified, this indicates the type of tax override.
     */
    public $taxOverrideType;

    /**
     * @var Decimal? If a tax override was specified, this indicates the amount of tax that was requested.
     */
    public $taxOverrideAmount;

    /**
     * @var String If a tax override was specified, represents the reason for the tax override.
     */
    public $taxOverrideReason;

    /**
     * @var Boolean? True if tax was included in the purchase price of the item.
     */
    public $taxIncluded;

    /**
     * @var List<TransactionLineDetailModel> Optional: A list of tax details for this line item.  To fetch this list, add the query string "?$include=Details" to your URL.
     */
    public $details;

    /**
     * @var Dictionary<string, string> Contains a list of extra parameters that were set when the transaction was created.
     */
    public $parameters;

}

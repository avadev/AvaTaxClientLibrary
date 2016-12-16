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
 * A single transaction - for example, a sales invoice or purchase order.
 */
final class TransactionModel
{
    /**
     * @var Int64? The unique ID number of this transaction.
     */
    public $id;

    /**
     * @var String A unique customer-provided code identifying this transaction.
     */
    public $code;

    /**
     * @var Int32? The unique ID number of the company that recorded this transaction.
     */
    public $companyId;

    /**
     * @var DateTime? The date on which this transaction occurred.
     */
    public $date;

    /**
     * @var DateTime? The date that was used when calculating tax for this transaction.
     *             By default, this should be the same as the transaction date; however, when a consumer returns a product purchased in a previous month,
     *             it may be necessary to specify the date of the original transaction in order to correctly return the exact amount of sales tax that was
     *             charged of the consumer on the original date they purchased the product.
     */
    public $taxDate;

    /**
     * @var DateTime? The date when payment was made on this transaction.  By default, this should be the same as the date of the transaction.
     */
    public $paymentDate;

    /**
     * @var DocumentStatus? The status of the transaction.
     */
    public $status;

    /**
     * @var DocumentType? The type of the transaction.  For Returns customers, a transaction type of "Invoice" will be reported to the tax authorities.
     *             A sales transaction represents a sale from the company to a customer.  A purchase transaction represents a purchase made by the company.
     *             A return transaction represents a customer who decided to request a refund after purchasing a product from the company.  An inventory 
     *             transfer transaction represents goods that were moved from one location of the company to another location without changing ownership.
     */
    public $type;

    /**
     * @var String If this transaction was created as part of a batch, this code indicates which batch.
     */
    public $batchCode;

    /**
     * @var String The three-character ISO 4217 currency code that was used for payment for this transaction.
     */
    public $currencyCode;

    /**
     * @var String The customer usage type for this transaction.  Customer usage types often affect exemption or taxability rules.
     */
    public $customerUsageType;

    /**
     * @var String CustomerVendorCode
     */
    public $customerVendorCode;

    /**
     * @var String If this transaction was exempt, this field will contain the word "Exempt".
     */
    public $exemptNo;

    /**
     * @var Boolean? If this transaction has been reconciled against the company's ledger, this value is set to true.
     */
    public $reconciled;

    /**
     * @var String If this transaction was made from a specific reporting location, this is the code string of the location.
     *             For customers using Returns, this indicates how tax will be reported according to different locations on the tax forms.
     */
    public $locationCode;

    /**
     * @var String The customer-supplied purchase order number of this transaction.
     */
    public $purchaseOrderNo;

    /**
     * @var String A user-defined reference code for this transaction.
     */
    public $referenceCode;

    /**
     * @var String The salesperson who provided this transaction.  Not required.
     */
    public $salespersonCode;

    /**
     * @var TaxOverrideTypeId? If a tax override was applied to this transaction, indicates what type of tax override was applied.
     */
    public $taxOverrideType;

    /**
     * @var Decimal? If a tax override was applied to this transaction, indicates the amount of tax that was requested by the customer.
     */
    public $taxOverrideAmount;

    /**
     * @var String If a tax override was applied to this transaction, indicates the reason for the tax override.
     */
    public $taxOverrideReason;

    /**
     * @var Decimal? The total amount of this transaction.
     */
    public $totalAmount;

    /**
     * @var Decimal? The amount of this transaction that was exempt.
     */
    public $totalExempt;

    /**
     * @var Decimal? The total tax calculated for all lines in this transaction.
     */
    public $totalTax;

    /**
     * @var Decimal? The portion of the total amount of this transaction that was taxable.
     */
    public $totalTaxable;

    /**
     * @var Decimal? If a tax override was applied to this transaction, indicates the amount of tax Avalara calculated for the transaction.
     */
    public $totalTaxCalculated;

    /**
     * @var AdjustmentReason? If this transaction was adjusted, indicates the unique ID number of the reason why the transaction was adjusted.
     */
    public $adjustmentReason;

    /**
     * @var String If this transaction was adjusted, indicates a description of the reason why the transaction was adjusted.
     */
    public $adjustmentDescription;

    /**
     * @var Boolean? If this transaction has been reported to a tax authority, this transaction is considered locked and may not be adjusted after reporting.
     */
    public $locked;

    /**
     * @var String The two-or-three character ISO region code of the region for this transaction.
     */
    public $region;

    /**
     * @var String The two-character ISO 3166 code of the country for this transaction.
     */
    public $country;

    /**
     * @var Int32? If this transaction was adjusted, this indicates the version number of this transaction.  Incremented each time the transaction
     *             is adjusted.
     */
    public $version;

    /**
     * @var String The software version used to calculate this transaction.
     */
    public $softwareVersion;

    /**
     * @var Int64? The unique ID number of the origin address for this transaction.
     */
    public $originAddressId;

    /**
     * @var Int64? The unique ID number of the destination address for this transaction.
     */
    public $destinationAddressId;

    /**
     * @var DateTime? If this transaction included foreign currency exchange, this is the date as of which the exchange rate was calculated.
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var Decimal? If this transaction included foreign currency exchange, this is the exchange rate that was used.
     */
    public $exchangeRate;

    /**
     * @var Boolean? If true, this seller was considered the importer of record of a product shipped internationally.
     */
    public $isSellerImporterOfRecord;

    /**
     * @var String Description of this transaction.
     */
    public $description;

    /**
     * @var String Email address associated with this transaction.
     */
    public $email;

    /**
     * @var DateTime? The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var Int32? The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

    /**
     * @var List<TransactionLineModel> Optional: A list of line items in this transaction.  To fetch this list, add the query string "?$include=Lines" or "?$include=Details" to your URL.
     */
    public $lines;

    /**
     * @var List<TransactionAddressModel> Optional: A list of line items in this transaction.  To fetch this list, add the query string "?$include=Addresses" to your URL.
     */
    public $addresses;

    /**
     * @var List<TransactionModel> If this transaction has been adjusted, this list contains all the previous versions of the document.
     */
    public $history;

    /**
     * @var List<TransactionSummary> Contains a summary of tax on this transaction.
     */
    public $summary;

    /**
     * @var Dictionary<string, string> Contains a list of extra parameters that were set when the transaction was created.
     */
    public $parameters;

    /**
     * @var List<AvaTaxMessage> List of informational and warning messages regarding this API call.  These messages are only relevant to the current API call.
     */
    public $messages;

}

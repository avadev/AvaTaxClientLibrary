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
 * Create a transaction
 */
final class CreateTransactionModel
{
    /**
     * @var DocumentType? Document Type
     */
    public $type;

    /**
     * @var String Transaction Code - the internal reference code used by the client application.  This is used for operations such as
     *                 Get, Adjust, Settle, and Void.  If you leave the transaction code blank, a GUID will be assigned to each transaction.
     */
    public $code;

    /**
     * @var String Company Code - If you are posting to /api/v2/transactions/create, you must specify the company code value here.
     *                 If you are posting to /api/v2/companies/(companyCode)/transactions/create, this value must be null.
     */
    public $companyCode;

    /**
     * @var DateTime Transaction Date - The date on the invoice, purchase order, etc.
     */
    public $date;

    /**
     * @var String Salesperson Code - The client application salesperson reference code.
     */
    public $salespersonCode;

    /**
     * @var String Customer Code - The client application customer reference code.
     */
    public $customerCode;

    /**
     * @var String Customer Usage Type - The client application customer or usage type.
     */
    public $customerUsageType;

    /**
     * @var Decimal? Discount - The discount amount to apply to the document.
     */
    public $discount;

    /**
     * @var String Purchase Order Number for this document
     */
    public $purchaseOrderNo;

    /**
     * @var String Exemption Number for this document
     */
    public $exemptionNo;

    /**
     * @var Dictionary<TransactionAddressType, AddressInfo> Default addresses for all lines in this document
     */
    public $addresses;

    /**
     * @var List<LineItemModel> Document line items list
     */
    public $lines;

    /**
     * @var Dictionary<string, string> Special parameters for this transaction.
     *                 To get a full list of available parameters, please use the /api/v2/definitions/parameters endpoint.
     */
    public $parameters;

    /**
     * @var String Reference Code used to reference the original document for a return invoice
     */
    public $referenceCode;

    /**
     * @var String Sets the sale location code (Outlet ID) for reporting this document to the tax authority.
     */
    public $reportingLocationCode;

    /**
     * @var Boolean? Causes the document to be committed if true.
     */
    public $commit;

    /**
     * @var String BatchCode for batch operations.
     */
    public $batchCode;

    /**
     * @var TaxOverrideModel Specifies a tax override for the entire document
     */
    public $taxOverride;

    /**
     * @var DateTime? Indicates the tax effectivity override date for the entire document.
     */
    public $taxDate;

    /**
     * @var String 3 character ISO 4217 currency code.
     */
    public $currencyCode;

    /**
     * @var ServiceMode? Specifies whether the tax calculation is handled Local, Remote, or Automatic (default)
     */
    public $serviceMode;

    /**
     * @var Decimal? Currency exchange rate from this transaction to the company base currency.
     */
    public $exchangeRate;

    /**
     * @var DateTime? Effective date of the exchange rate.
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var String Sets the POS Lane Code sent by the User for this document.
     */
    public $posLaneCode;

    /**
     * @var String BusinessIdentificationNo
     */
    public $businessIdentificationNo;

    /**
     * @var Boolean? Specifies if the Transaction has the seller as IsSellerImporterOfRecord
     */
    public $isSellerImporterOfRecord;

    /**
     * @var String Description
     */
    public $description;

    /**
     * @var String Email
     */
    public $email;

    /**
     * @var TaxDebugLevel? If the user wishes to request additional debug information from this transaction, specify a level higher than 'normal'
     */
    public $debugLevel;

}

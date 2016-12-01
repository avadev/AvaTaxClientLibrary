<?php
/*
 * AvaTax Model
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com
 */
final class TransactionModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var String
     */
    public $code;

    /**
     * @var Int32?
     */
    public $companyId;

    /**
     * @var DateTime?
     */
    public $date;

    /**
     * @var DateTime?
     */
    public $taxDate;

    /**
     * @var DateTime?
     */
    public $paymentDate;

    /**
     * @var DocumentStatus?
     */
    public $status;

    /**
     * @var DocumentType?
     */
    public $type;

    /**
     * @var String
     */
    public $batchCode;

    /**
     * @var String
     */
    public $currencyCode;

    /**
     * @var String
     */
    public $customerUsageType;

    /**
     * @var String
     */
    public $customerVendorCode;

    /**
     * @var String
     */
    public $exemptNo;

    /**
     * @var Boolean?
     */
    public $reconciled;

    /**
     * @var String
     */
    public $locationCode;

    /**
     * @var String
     */
    public $purchaseOrderNo;

    /**
     * @var String
     */
    public $referenceCode;

    /**
     * @var String
     */
    public $salespersonCode;

    /**
     * @var TaxOverrideTypeId?
     */
    public $taxOverrideType;

    /**
     * @var Decimal?
     */
    public $taxOverrideAmount;

    /**
     * @var String
     */
    public $taxOverrideReason;

    /**
     * @var Decimal?
     */
    public $totalAmount;

    /**
     * @var Decimal?
     */
    public $totalExempt;

    /**
     * @var Decimal?
     */
    public $totalTax;

    /**
     * @var Decimal?
     */
    public $totalTaxable;

    /**
     * @var Decimal?
     */
    public $totalTaxCalculated;

    /**
     * @var AdjustmentReason?
     */
    public $adjustmentReason;

    /**
     * @var String
     */
    public $adjustmentDescription;

    /**
     * @var Boolean?
     */
    public $locked;

    /**
     * @var String
     */
    public $region;

    /**
     * @var String
     */
    public $country;

    /**
     * @var Int32?
     */
    public $version;

    /**
     * @var String
     */
    public $softwareVersion;

    /**
     * @var Int32?
     */
    public $originAddressId;

    /**
     * @var Int32?
     */
    public $destinationAddressId;

    /**
     * @var DateTime?
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var Decimal?
     */
    public $exchangeRate;

    /**
     * @var Boolean?
     */
    public $isSellerImporterOfRecord;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $email;

    /**
     * @var DateTime?
     */
    public $modifiedDate;

    /**
     * @var Int32?
     */
    public $modifiedUserId;

    /**
     * @var List<TransactionLineModel>
     */
    public $lines;

    /**
     * @var List<TransactionAddressModel>
     */
    public $addresses;

    /**
     * @var List<TransactionModel>
     */
    public $history;

    /**
     * @var List<TransactionSummary>
     */
    public $summary;

    /**
     * @var Dictionary<string, string>
     */
    public $parameters;

    /**
     * @var List<AvaTaxMessage>
     */
    public $messages;

}
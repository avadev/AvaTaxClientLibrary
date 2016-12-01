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
final class CreateTransactionModel extends AbstractEntity
{
    /**
     * @var DocumentType?
     */
    public $type;

    /**
     * @var String
     */
    public $code;

    /**
     * @var String
     */
    public $companyCode;

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var String
     */
    public $salespersonCode;

    /**
     * @var String
     */
    public $customerCode;

    /**
     * @var String
     */
    public $customerUsageType;

    /**
     * @var Decimal?
     */
    public $discount;

    /**
     * @var String
     */
    public $purchaseOrderNo;

    /**
     * @var String
     */
    public $exemptionNo;

    /**
     * @var Dictionary<TransactionAddressType, AddressInfo>
     */
    public $addresses;

    /**
     * @var List<LineItemModel>
     */
    public $lines;

    /**
     * @var Dictionary<string, string>
     */
    public $parameters;

    /**
     * @var String
     */
    public $referenceCode;

    /**
     * @var String
     */
    public $reportingLocationCode;

    /**
     * @var Boolean?
     */
    public $commit;

    /**
     * @var String
     */
    public $batchCode;

    /**
     * @var TaxOverrideModel
     */
    public $taxOverride;

    /**
     * @var DateTime?
     */
    public $taxDate;

    /**
     * @var String
     */
    public $currencyCode;

    /**
     * @var ServiceMode?
     */
    public $serviceMode;

    /**
     * @var Decimal?
     */
    public $exchangeRate;

    /**
     * @var DateTime?
     */
    public $exchangeRateEffectiveDate;

    /**
     * @var String
     */
    public $posLaneCode;

    /**
     * @var String
     */
    public $businessIdentificationNo;

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
     * @var TaxDebugLevel?
     */
    public $debugLevel;

}
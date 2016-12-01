<?php
/*
 * AvaTax Entity Model Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */
final class TransactionLineModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var Int32?
     */
    public $transactionId;

    /**
     * @var String
     */
    public $lineNumber;

    /**
     * @var Int32?
     */
    public $boundaryOverrideId;

    /**
     * @var String
     */
    public $customerUsageType;

    /**
     * @var String
     */
    public $description;

    /**
     * @var Int32?
     */
    public $destinationAddressId;

    /**
     * @var Int32?
     */
    public $originAddressId;

    /**
     * @var Decimal?
     */
    public $discountAmount;

    /**
     * @var Int32?
     */
    public $discountTypeId;

    /**
     * @var Decimal?
     */
    public $exemptAmount;

    /**
     * @var Int32?
     */
    public $exemptCertId;

    /**
     * @var String
     */
    public $exemptNo;

    /**
     * @var Boolean?
     */
    public $isItemTaxable;

    /**
     * @var Boolean?
     */
    public $isSSTP;

    /**
     * @var String
     */
    public $itemCode;

    /**
     * @var Decimal?
     */
    public $lineAmount;

    /**
     * @var Decimal?
     */
    public $quantity;

    /**
     * @var String
     */
    public $ref1;

    /**
     * @var String
     */
    public $ref2;

    /**
     * @var DateTime?
     */
    public $reportingDate;

    /**
     * @var String
     */
    public $revAccount;

    /**
     * @var Sourcing?
     */
    public $sourcing;

    /**
     * @var Decimal?
     */
    public $tax;

    /**
     * @var Decimal?
     */
    public $taxableAmount;

    /**
     * @var Decimal?
     */
    public $taxCalculated;

    /**
     * @var String
     */
    public $taxCode;

    /**
     * @var Int32?
     */
    public $taxCodeId;

    /**
     * @var DateTime?
     */
    public $taxDate;

    /**
     * @var String
     */
    public $taxEngine;

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
     * @var Boolean?
     */
    public $taxIncluded;

    /**
     * @var List<TransactionLineDetailModel>
     */
    public $details;

    /**
     * @var Dictionary<string, string>
     */
    public $parameters;

}

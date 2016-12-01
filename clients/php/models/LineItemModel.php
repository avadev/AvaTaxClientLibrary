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
final class LineItemModel extends AbstractEntity
{
    /**
     * @var String
     */
    public $number;

    /**
     * @var Decimal
     */
    public $quantity;

    /**
     * @var Decimal
     */
    public $amount;

    /**
     * @var Dictionary<TransactionAddressType, AddressInfo>
     */
    public $addresses;

    /**
     * @var String
     */
    public $taxCode;

    /**
     * @var String
     */
    public $customerUsageType;

    /**
     * @var String
     */
    public $itemCode;

    /**
     * @var String
     */
    public $exemptionCode;

    /**
     * @var Boolean?
     */
    public $discounted;

    /**
     * @var Boolean?
     */
    public $taxIncluded;

    /**
     * @var String
     */
    public $revenueAccount;

    /**
     * @var String
     */
    public $ref1;

    /**
     * @var String
     */
    public $ref2;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $businessIdentificationNo;

    /**
     * @var TaxOverrideModel
     */
    public $taxOverride;

    /**
     * @var Dictionary<string, string>
     */
    public $parameters;

}
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
final class TransactionSummary extends AbstractEntity
{
    /**
     * @var String
     */
    public $country;

    /**
     * @var String
     */
    public $region;

    /**
     * @var JurisdictionType?
     */
    public $jurisType;

    /**
     * @var String
     */
    public $jurisCode;

    /**
     * @var String
     */
    public $jurisName;

    /**
     * @var Int32?
     */
    public $taxAuthorityType;

    /**
     * @var String
     */
    public $stateAssignedNo;

    /**
     * @var TaxType?
     */
    public $taxType;

    /**
     * @var String
     */
    public $taxName;

    /**
     * @var String
     */
    public $taxGroup;

    /**
     * @var RateType?
     */
    public $rateType;

    /**
     * @var Decimal?
     */
    public $taxable;

    /**
     * @var Decimal?
     */
    public $rate;

    /**
     * @var Decimal?
     */
    public $tax;

    /**
     * @var Decimal?
     */
    public $taxCalculated;

    /**
     * @var Decimal?
     */
    public $nonTaxable;

    /**
     * @var Decimal?
     */
    public $exemption;

}
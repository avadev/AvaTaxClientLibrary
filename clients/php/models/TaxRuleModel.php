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
final class TaxRuleModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var Int32
     */
    public $companyId;

    /**
     * @var Int32?
     */
    public $taxCodeId;

    /**
     * @var String
     */
    public $taxCode;

    /**
     * @var String
     */
    public $stateFIPS;

    /**
     * @var String
     */
    public $jurisName;

    /**
     * @var String
     */
    public $jurisCode;

    /**
     * @var JurisTypeId?
     */
    public $jurisTypeId;

    /**
     * @var String
     */
    public $customerUsageType;

    /**
     * @var MatchingTaxType?
     */
    public $taxTypeId;

    /**
     * @var RateType?
     */
    public $rateTypeId;

    /**
     * @var TaxRuleTypeId?
     */
    public $taxRuleTypeId;

    /**
     * @var Boolean?
     */
    public $isAllJuris;

    /**
     * @var Decimal?
     */
    public $value;

    /**
     * @var Decimal?
     */
    public $cap;

    /**
     * @var Decimal?
     */
    public $threshold;

    /**
     * @var String
     */
    public $options;

    /**
     * @var DateTime?
     */
    public $effectiveDate;

    /**
     * @var DateTime?
     */
    public $endDate;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $countyFIPS;

    /**
     * @var Boolean?
     */
    public $isSTPro;

    /**
     * @var String
     */
    public $country;

    /**
     * @var String
     */
    public $region;

    /**
     * @var Sourcing?
     */
    public $sourcing;

    /**
     * @var DateTime?
     */
    public $createdDate;

    /**
     * @var Int32?
     */
    public $createdUserId;

    /**
     * @var DateTime?
     */
    public $modifiedDate;

    /**
     * @var Int32?
     */
    public $modifiedUserId;

}
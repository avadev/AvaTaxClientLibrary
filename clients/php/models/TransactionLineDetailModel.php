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
final class TransactionLineDetailModel extends AbstractEntity
{
    /**
     * @var Int32?
     */
    public $id;

    /**
     * @var Int32?
     */
    public $transactionLineId;

    /**
     * @var Int32?
     */
    public $transactionId;

    /**
     * @var Int32?
     */
    public $addressId;

    /**
     * @var String
     */
    public $country;

    /**
     * @var String
     */
    public $region;

    /**
     * @var String
     */
    public $countyFIPS;

    /**
     * @var String
     */
    public $stateFIPS;

    /**
     * @var Decimal?
     */
    public $exemptAmount;

    /**
     * @var Int32?
     */
    public $exemptReasonId;

    /**
     * @var Boolean?
     */
    public $inState;

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
    public $jurisdictionId;

    /**
     * @var String
     */
    public $signatureCode;

    /**
     * @var String
     */
    public $stateAssignedNo;

    /**
     * @var JurisTypeId?
     */
    public $jurisType;

    /**
     * @var Decimal?
     */
    public $nonTaxableAmount;

    /**
     * @var Int32?
     */
    public $nonTaxableRuleId;

    /**
     * @var TaxRuleTypeId?
     */
    public $nonTaxableType;

    /**
     * @var Decimal?
     */
    public $rate;

    /**
     * @var Int32?
     */
    public $rateRuleId;

    /**
     * @var Int32?
     */
    public $rateSourceId;

    /**
     * @var String
     */
    public $serCode;

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
     * @var TaxType?
     */
    public $taxType;

    /**
     * @var String
     */
    public $taxName;

    /**
     * @var Int32?
     */
    public $taxAuthorityTypeId;

    /**
     * @var Int32?
     */
    public $taxRegionId;

    /**
     * @var Decimal?
     */
    public $taxCalculated;

    /**
     * @var Decimal?
     */
    public $taxOverride;

    /**
     * @var RateType?
     */
    public $rateType;

    /**
     * @var Decimal?
     */
    public $taxableUnits;

    /**
     * @var Decimal?
     */
    public $nonTaxableUnits;

    /**
     * @var Decimal?
     */
    public $exemptUnits;

    /**
     * @var String
     */
    public $unitOfBasis;

}

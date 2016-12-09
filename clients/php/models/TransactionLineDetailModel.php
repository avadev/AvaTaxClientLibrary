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
 * An individual tax detail element.  Represents the amount of tax calculated for a particular jurisdiction, for a particular line in an invoice.
 */
final class TransactionLineDetailModel
{
    /**
     * @var Int32? The unique ID number of this tax detail.
     */
    public $id;

    /**
     * @var Int32? The unique ID number of the line within this transaction.
     */
    public $transactionLineId;

    /**
     * @var Int32? The unique ID number of this transaction.
     */
    public $transactionId;

    /**
     * @var Int32? The unique ID number of the address used for this tax detail.
     */
    public $addressId;

    /**
     * @var String The two character ISO 3166 country code of the country where this tax detail is assigned.
     */
    public $country;

    /**
     * @var String The two-or-three character ISO region code for the region where this tax detail is assigned.
     */
    public $region;

    /**
     * @var String For U.S. transactions, the Federal Information Processing Standard (FIPS) code for the county where this tax detail is assigned.
     */
    public $countyFIPS;

    /**
     * @var String For U.S. transactions, the Federal Information Processing Standard (FIPS) code for the state where this tax detail is assigned.
     */
    public $stateFIPS;

    /**
     * @var Decimal? The amount of this line that was considered exempt in this tax detail.
     */
    public $exemptAmount;

    /**
     * @var Int32? The unique ID number of the exemption reason for this tax detail.
     */
    public $exemptReasonId;

    /**
     * @var Boolean? True if this detail element represented an in-state transaction.
     */
    public $inState;

    /**
     * @var String The code of the jurisdiction to which this tax detail applies.
     */
    public $jurisCode;

    /**
     * @var String The name of the jurisdiction to which this tax detail applies.
     */
    public $jurisName;

    /**
     * @var Int32? The unique ID number of the jurisdiction to which this tax detail applies.
     */
    public $jurisdictionId;

    /**
     * @var String The Avalara-specified signature code of the jurisdiction to which this tax detail applies.
     */
    public $signatureCode;

    /**
     * @var String The state assigned number of the jurisdiction to which this tax detail applies.
     */
    public $stateAssignedNo;

    /**
     * @var JurisTypeId? The type of the jurisdiction to which this tax detail applies.
     */
    public $jurisType;

    /**
     * @var Decimal? The amount of this line item that was considered nontaxable in this tax detail.
     */
    public $nonTaxableAmount;

    /**
     * @var Int32? The rule according to which portion of this detail was considered nontaxable.
     */
    public $nonTaxableRuleId;

    /**
     * @var TaxRuleTypeId? The type of nontaxability that was applied to this tax detail.
     */
    public $nonTaxableType;

    /**
     * @var Decimal? The rate at which this tax detail was calculated.
     */
    public $rate;

    /**
     * @var Int32? The unique ID number of the rule according to which this tax detail was calculated.
     */
    public $rateRuleId;

    /**
     * @var Int32? The unique ID number of the source of the rate according to which this tax detail was calculated.
     */
    public $rateSourceId;

    /**
     * @var String For Streamlined Sales Tax customers, the SST Electronic Return code under which this tax detail should be applied.
     */
    public $serCode;

    /**
     * @var Sourcing? Indicates whether this tax detail applies to the origin or destination of the transaction.
     */
    public $sourcing;

    /**
     * @var Decimal? The amount of tax for this tax detail.
     */
    public $tax;

    /**
     * @var Decimal? The taxable amount of this tax detail.
     */
    public $taxableAmount;

    /**
     * @var TaxType? The type of tax that was calculated.  Depends on the company's nexus settings as well as the jurisdiction's tax laws.
     */
    public $taxType;

    /**
     * @var String The name of the tax against which this tax amount was calculated.
     */
    public $taxName;

    /**
     * @var Int32? The type of the tax authority to which this tax will be remitted.
     */
    public $taxAuthorityTypeId;

    /**
     * @var Int32? The unique ID number of the tax region.
     */
    public $taxRegionId;

    /**
     * @var Decimal? The amount of tax that was calculated.  This amount may be different if a tax override was used.
     *             If the customer specified a tax override, this calculated tax value represents the amount of tax that would
     *             have been charged if Avalara had calculated the tax for the rule.
     */
    public $taxCalculated;

    /**
     * @var Decimal? The amount of tax override that was specified for this tax line.
     */
    public $taxOverride;

    /**
     * @var RateType? The rate type for this tax detail.
     */
    public $rateType;

    /**
     * @var Decimal? Number of units in this line item that were calculated to be taxable according to this rate detail.
     */
    public $taxableUnits;

    /**
     * @var Decimal? Number of units in this line item that were calculated to be nontaxable according to this rate detail.
     */
    public $nonTaxableUnits;

    /**
     * @var Decimal? Number of units in this line item that were calculated to be exempt according to this rate detail.
     */
    public $exemptUnits;

    /**
     * @var String When calculating units, what basis of measurement did we use for calculating the units?
     */
    public $unitOfBasis;

}

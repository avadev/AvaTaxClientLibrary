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
 * Represents a tax rule that changes the behavior of Avalara's tax engine for certain products in certain jurisdictions.
 */
final class TaxRuleModel
{
    /**
     * @var Int32 The unique ID number of this tax rule.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company that owns this tax rule.
     */
    public $companyId;

    /**
     * @var Int32? The unique ID number of the tax code for this rule.
     *             When creating or updating a tax rule, you may specify either the taxCodeId value or the taxCode value.
     */
    public $taxCodeId;

    /**
     * @var String The code string of the tax code for this rule.
     *             When creating or updating a tax rule, you may specify either the taxCodeId value or the taxCode value.
     */
    public $taxCode;

    /**
     * @var String For U.S. tax rules, this is the state's Federal Information Processing Standard (FIPS) code.
     */
    public $stateFIPS;

    /**
     * @var String The name of the jurisdiction to which this tax rule applies.
     */
    public $jurisName;

    /**
     * @var String The code of the jurisdiction to which this tax rule applies.
     */
    public $jurisCode;

    /**
     * @var JurisTypeId? The type of the jurisdiction to which this tax rule applies.
     */
    public $jurisTypeId;

    /**
     * @var String The type of customer usage to which this rule applies.
     */
    public $customerUsageType;

    /**
     * @var MatchingTaxType? Indicates which tax types to which this rule applies.
     */
    public $taxTypeId;

    /**
     * @var RateType? Indicates the rate type to which this rule applies.
     */
    public $rateTypeId;

    /**
     * @var TaxRuleTypeId? This type value determines the behavior of the tax rule.
     *             You can specify that this rule controls the product's taxability or exempt / nontaxable status, the product's rate 
     *             (for example, if you have been granted an official ruling for your product's rate that differs from the official rate), 
     *             or other types of behavior.
     */
    public $taxRuleTypeId;

    /**
     * @var Boolean? Set this value to true if this tax rule applies in all jurisdictions.
     */
    public $isAllJuris;

    /**
     * @var Decimal? The corrected rate for this tax rule.
     */
    public $value;

    /**
     * @var Decimal? The maximum cap for the price of this item according to this rule.
     */
    public $cap;

    /**
     * @var Decimal? The per-unit threshold that must be met before this rule applies.
     */
    public $threshold;

    /**
     * @var String Custom option flags for this rule.
     */
    public $options;

    /**
     * @var DateTime? The first date at which this rule applies.  If null, this rule will apply to all dates prior to the end date.
     */
    public $effectiveDate;

    /**
     * @var DateTime? The last date for which this rule applies.  If null, this rule will apply to all dates after the effective date.
     */
    public $endDate;

    /**
     * @var String A friendly name for this tax rule.
     */
    public $description;

    /**
     * @var String For U.S. tax rules, this is the county's Federal Information Processing Standard (FIPS) code.
     */
    public $countyFIPS;

    /**
     * @var Boolean? If true, indicates this rule is for Sales Tax Pro.
     */
    public $isSTPro;

    /**
     * @var String The two character ISO 3166 country code for the locations where this rule applies.
     */
    public $country;

    /**
     * @var String The state, region, or province name for the locations where this rule applies.
     */
    public $region;

    /**
     * @var Sourcing? The sourcing types to which this rule applies.
     */
    public $sourcing;

    /**
     * @var DateTime? The date when this record was created.
     */
    public $createdDate;

    /**
     * @var Int32? The User ID of the user who created this record.
     */
    public $createdUserId;

    /**
     * @var DateTime? The date/time when this record was last modified.
     */
    public $modifiedDate;

    /**
     * @var Int32? The user ID of the user who last modified this record.
     */
    public $modifiedUserId;

}

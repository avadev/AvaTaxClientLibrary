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
 * A company or business entity.
 */
final class CompanyModel
{
    /**
     * @var Int32 The unique ID number of this company.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the account this company belongs to.
     */
    public $accountId;

    /**
     * @var Int32? If this company is fully owned by another company, this is the unique identity of the parent company.
     */
    public $parentCompanyId;

    /**
     * @var String If this company files Streamlined Sales Tax, this is the PID of this company as defined by the Streamlined Sales Tax governing board.
     */
    public $sstPid;

    /**
     * @var String A unique code that references this company within your account.
     */
    public $companyCode;

    /**
     * @var String The name of this company, as shown to customers.
     */
    public $name;

    /**
     * @var Boolean? This flag is true if this company is the default company for this account.  Only one company may be set as the default.
     */
    public $isDefault;

    /**
     * @var Int32? If set, this is the unique ID number of the default location for this company.
     */
    public $defaultLocationId;

    /**
     * @var Boolean? This flag indicates whether tax activity can occur for this company.  Set this flag to true to permit the company to process transactions.
     */
    public $isActive;

    /**
     * @var String For United States companies, this field contains your Taxpayer Identification Number.  
     *             This is a nine digit number that is usually called an EIN for an Employer Identification Number if this company is a corporation, 
     *             or SSN for a Social Security Number if this company is a person.
     */
    public $taxpayerIdNumber;

    /**
     * @var Boolean? Set this flag to true to give this company its own unique tax profile.
     *             If this flag is true, this company will have its own Nexus, TaxRule, TaxCode, and Item definitions.
     *             If this flag is false, this company will inherit all profile values from its parent.
     */
    public $hasProfile;

    /**
     * @var Boolean? Set this flag to true if this company must file its own tax returns.
     *             For users who have Returns enabled, this flag turns on monthly Worksheet generation for the company.
     */
    public $isReportingEntity;

    /**
     * @var DateTime? If this company participates in Streamlined Sales Tax, this is the date when the company joined the SST program.
     */
    public $sstEffectiveDate;

    /**
     * @var String The two character ISO-3166 country code of the default country for this company.
     */
    public $defaultCountry;

    /**
     * @var String This is the three character ISO-4217 currency code of the default currency used by this company.
     */
    public $baseCurrencyCode;

    /**
     * @var RoundingLevelId? Indicates whether this company prefers to round amounts at the document level or line level.
     */
    public $roundingLevelId;

    /**
     * @var Boolean? Set this value to true to receive warnings in API calls via SOAP.
     */
    public $warningsEnabled;

    /**
     * @var Boolean? Set this flag to true to indicate that this company is a test company.
     *             If you have Returns enabled, Test companies will not file tax returns and can be used for validation purposes.
     */
    public $isTest;

    /**
     * @var TaxDependencyLevelId? Used to apply tax detail dependency at a jurisdiction level.
     */
    public $taxDependencyLevelId;

    /**
     * @var Boolean? Set this value to true to indicate that you are still working to finish configuring this company.
     *             While this value is true, no tax reporting will occur and the company will not be usable for transactions.
     */
    public $inProgress;

    /**
     * @var String Business Identification No
     */
    public $businessIdentificationNo;

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

    /**
     * @var List<ContactModel> Optional: A list of contacts defined for this company.  To fetch this list, add the query string "?$include=Contacts" to your URL.
     */
    public $contacts;

    /**
     * @var List<ItemModel> Optional: A list of items defined for this company.  To fetch this list, add the query string "?$include=Items" to your URL.
     */
    public $items;

    /**
     * @var List<LocationModel> Optional: A list of locations defined for this company.  To fetch this list, add the query string "?$include=Locations" to your URL.
     */
    public $locations;

    /**
     * @var List<NexusModel> Optional: A list of nexus defined for this company.  To fetch this list, add the query string "?$include=Nexus" to your URL.
     */
    public $nexus;

    /**
     * @var List<SettingModel> Optional: A list of settings defined for this company.  To fetch this list, add the query string "?$include=Settings" to your URL.
     */
    public $settings;

    /**
     * @var List<TaxCodeModel> Optional: A list of tax codes defined for this company.  To fetch this list, add the query string "?$include=TaxCodes" to your URL.
     */
    public $taxCodes;

    /**
     * @var List<TaxRuleModel> Optional: A list of tax rules defined for this company.  To fetch this list, add the query string "?$include=TaxRules" to your URL.
     */
    public $taxRules;

    /**
     * @var List<UPCModel> Optional: A list of UPCs defined for this company.  To fetch this list, add the query string "?$include=UPCs" to your URL.
     */
    public $upcs;

}

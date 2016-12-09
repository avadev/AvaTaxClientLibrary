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
 * A location where this company does business.
            Some jurisdictions may require you to list all locations where your company does business.
 */
final class LocationModel
{
    /**
     * @var Int32 The unique ID number of this location.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company that operates at this location.
     */
    public $companyId;

    /**
     * @var String A code that identifies this location.  Must be unique within your company.
     */
    public $locationCode;

    /**
     * @var String A friendly name for this location.
     */
    public $description;

    /**
     * @var AddressTypeId Indicates whether this location is a physical place of business or a temporary salesperson location.
     */
    public $addressTypeId;

    /**
     * @var AddressCategoryId Indicates the type of place of business represented by this location.
     */
    public $addressCategoryId;

    /**
     * @var String The first line of the physical address of this location.
     */
    public $line1;

    /**
     * @var String The second line of the physical address of this location.
     */
    public $line2;

    /**
     * @var String The third line of the physical address of this location.
     */
    public $line3;

    /**
     * @var String The city of the physical address of this location.
     */
    public $city;

    /**
     * @var String The county name of the physical address of this location.  Not required.
     */
    public $county;

    /**
     * @var String The state, region, or province of the physical address of this location.
     */
    public $region;

    /**
     * @var String The postal code or zip code of the physical address of this location.
     */
    public $postalCode;

    /**
     * @var String The two character ISO-3166 country code of the physical address of this location.
     */
    public $country;

    /**
     * @var Boolean? Set this flag to true to indicate that this is the default location for this company.
     */
    public $isDefault;

    /**
     * @var Boolean? Set this flag to true to indicate that this location has been registered with a tax authority.
     */
    public $isRegistered;

    /**
     * @var String If this location has a different business name from its legal entity name, specify the "Doing Business As" name for this location.
     */
    public $dbaName;

    /**
     * @var String A friendly name for this location.
     */
    public $outletName;

    /**
     * @var DateTime? The date when this location was opened for business, or null if not known.
     */
    public $effectiveDate;

    /**
     * @var DateTime? If this place of business has closed, the date when this location closed business.
     */
    public $endDate;

    /**
     * @var DateTime? The most recent date when a transaction was processed for this location.  Set by AvaTax.
     */
    public $lastTransactionDate;

    /**
     * @var DateTime? The date when this location was registered with a tax authority.  Not required.
     */
    public $registeredDate;

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
     * @var List<LocationSettingModel> Extra information required by certain jurisdictions for filing.
     *             For a list of settings recognized by Avalara, query the endpoint "/api/v2/definitions/locationquestions". 
     *             To determine the list of settings required for this location, query the endpoint "/api/v2/companies/(id)/locations/(id)/validate".
     */
    public $settings;

}

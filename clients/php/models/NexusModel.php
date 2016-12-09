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
 * Represents a declaration of nexus within a particular taxing jurisdiction.
 */
final class NexusModel
{
    /**
     * @var Int32 The unique ID number of this declaration of nexus.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company that declared nexus.
     */
    public $companyId;

    /**
     * @var String The two character ISO-3166 country code of the country in which this company declared nexus.
     */
    public $country;

    /**
     * @var String The two or three character ISO region code of the region, state, or province in which this company declared nexus.
     */
    public $region;

    /**
     * @var JurisTypeId? The jurisdiction type of the jurisdiction in which this company declared nexus.
     */
    public $jurisTypeId;

    /**
     * @var String The code identifying the jurisdiction in which this company declared nexus.
     */
    public $jurisCode;

    /**
     * @var String The common name of the jurisdiction in which this company declared nexus.
     */
    public $jurisName;

    /**
     * @var DateTime? The date when this nexus began.  If not known, set to null.
     */
    public $effectiveDate;

    /**
     * @var DateTime? If this nexus will end or has ended on a specific date, set this to the date when this nexus ends.
     */
    public $endDate;

    /**
     * @var String The short name of the jurisdiction.
     */
    public $shortName;

    /**
     * @var String The signature code of the boundary region as defined by Avalara.
     */
    public $signatureCode;

    /**
     * @var String The state assigned number of this jurisdiction.
     */
    public $stateAssignedNo;

    /**
     * @var NexusTypeId? The type of nexus that this company is declaring.
     */
    public $nexusTypeId;

    /**
     * @var Sourcing? Indicates whether this nexus is defined as origin or destination nexus.
     */
    public $sourcing;

    /**
     * @var Boolean? True if you are also declaring local nexus within this jurisdiction.
     *             Many U.S. states have options for declaring nexus in local jurisdictions as well as within the state.
     */
    public $hasLocalNexus;

    /**
     * @var LocalNexusTypeId? If you are declaring local nexus within this jurisdiction, this indicates whether you are declaring only 
     *             a specified list of local jurisdictions, all state-administered local jurisdictions, or all local jurisdictions.
     */
    public $localNexusTypeId;

    /**
     * @var Boolean? Set this value to true if your company has a permanent establishment within this jurisdiction.
     */
    public $hasPermanentEstablishment;

    /**
     * @var String Optional - the tax identification number under which you declared nexus.
     */
    public $taxId;

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

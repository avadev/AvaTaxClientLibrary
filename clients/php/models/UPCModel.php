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
 * One Universal Product Code object as defined for your company.
 */
final class UPCModel
{
    /**
     * @var Int32 The unique ID number for this UPC.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company to which this UPC belongs.
     */
    public $companyId;

    /**
     * @var String The 12-14 character Universal Product Code, European Article Number, or Global Trade Identification Number.
     */
    public $upc;

    /**
     * @var String Legacy Tax Code applied to any product sold with this UPC.
     */
    public $legacyTaxCode;

    /**
     * @var String Description of the product to which this UPC applies.
     */
    public $description;

    /**
     * @var DateTime? If this UPC became effective on a certain date, this contains the first date on which the UPC was effective.
     */
    public $effectiveDate;

    /**
     * @var DateTime? If this UPC expired or will expire on a certain date, this contains the last date on which the UPC was effective.
     */
    public $endDate;

    /**
     * @var Int32? A usage identifier for this UPC code.
     */
    public $usage;

    /**
     * @var Int32? A flag indicating whether this UPC code is attached to the AvaTax system or to a company.
     */
    public $isSystem;

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

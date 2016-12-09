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
 * A contact person for a company.
 */
final class ContactModel
{
    /**
     * @var Int32 The unique ID number of this contact.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company to which this contact belongs.
     */
    public $companyId;

    /**
     * @var String A unique code for this contact.
     */
    public $contactCode;

    /**
     * @var String The first or given name of this contact.
     */
    public $firstName;

    /**
     * @var String The middle name of this contact.
     */
    public $middleName;

    /**
     * @var String The last or family name of this contact.
     */
    public $lastName;

    /**
     * @var String Professional title of this contact.
     */
    public $title;

    /**
     * @var String The first line of the postal mailing address of this contact.
     */
    public $line1;

    /**
     * @var String The second line of the postal mailing address of this contact.
     */
    public $line2;

    /**
     * @var String The third line of the postal mailing address of this contact.
     */
    public $line3;

    /**
     * @var String The city of the postal mailing address of this contact.
     */
    public $city;

    /**
     * @var String The state, region, or province of the postal mailing address of this contact.
     */
    public $region;

    /**
     * @var String The postal code or zip code of the postal mailing address of this contact.
     */
    public $postalCode;

    /**
     * @var String The ISO 3166 two-character country code of the postal mailing address of this contact.
     */
    public $country;

    /**
     * @var String The email address of this contact.
     */
    public $email;

    /**
     * @var String The main phone number for this contact.
     */
    public $phone;

    /**
     * @var String The mobile phone number for this contact.
     */
    public $mobile;

    /**
     * @var String The facsimile phone number for this contact.
     */
    public $fax;

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

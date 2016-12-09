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
 * An account user who is permitted to use AvaTax.
 */
final class UserModel
{
    /**
     * @var Int32 The unique ID number of this user.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the account to which this user belongs.
     */
    public $accountId;

    /**
     * @var Int32? If this user is locked to one company (and its children), this is the unique ID number of the company to which this user belongs.
     */
    public $companyId;

    /**
     * @var String The username which is used to log on to the AvaTax website, or to authenticate against API calls.
     */
    public $userName;

    /**
     * @var String The first or given name of the user.
     */
    public $firstName;

    /**
     * @var String The last or family name of the user.
     */
    public $lastName;

    /**
     * @var String The email address to be used to contact this user.  If the user has forgotten a password, an email can be sent to this email address with information on how to reset this password.
     */
    public $email;

    /**
     * @var String The postal code in which this user resides.
     */
    public $postalCode;

    /**
     * @var SecurityRoleId The security level for this user.
     */
    public $securityRoleId;

    /**
     * @var PasswordStatusId? The status of the user's password.
     */
    public $passwordStatus;

    /**
     * @var Boolean? True if this user is currently active.
     */
    public $isActive;

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

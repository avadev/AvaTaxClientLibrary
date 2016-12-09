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
 * An AvaTax account.
 */
final class AccountModel
{
    /**
     * @var Int32 The unique ID number assigned to this account.
     */
    public $id;

    /**
     * @var String The name of this account.
     */
    public $name;

    /**
     * @var DateTime? The earliest date on which this account may be used.
     */
    public $effectiveDate;

    /**
     * @var DateTime? If this account has been closed, this is the last date the account was open.
     */
    public $endDate;

    /**
     * @var AccountStatusId? The current status of this account.
     */
    public $accountStatusId;

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
     * @var List<SubscriptionModel> Optional: A list of subscriptions granted to this account.  To fetch this list, add the query string "?$include=Subscriptions" to your URL.
     */
    public $subscriptions;

    /**
     * @var List<UserModel> Optional: A list of all the users belonging to this account.  To fetch this list, add the query string "?$include=Users" to your URL.
     */
    public $users;

}

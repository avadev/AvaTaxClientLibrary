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
 * Represents a service that this account has subscribed to.
 */
final class SubscriptionModel
{
    /**
     * @var Int32 The unique ID number of this subscription.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the account this subscription belongs to.
     */
    public $accountId;

    /**
     * @var Int32? The unique ID number of the service that the account is subscribed to.
     */
    public $subscriptionTypeId;

    /**
     * @var String A friendly description of the service that the account is subscribed to.
     */
    public $subscriptionDescription;

    /**
     * @var DateTime? The date when the subscription began.
     */
    public $effectiveDate;

    /**
     * @var DateTime? If the subscription has ended or will end, this date indicates when the subscription ends.
     */
    public $endDate;

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

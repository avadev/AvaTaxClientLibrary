<?php
/*
 * AvaTax Model
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com
 */
final class AccountModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var String
     */
    public $name;

    /**
     * @var DateTime?
     */
    public $effectiveDate;

    /**
     * @var DateTime?
     */
    public $endDate;

    /**
     * @var AccountStatusId?
     */
    public $accountStatusId;

    /**
     * @var DateTime?
     */
    public $createdDate;

    /**
     * @var Int32?
     */
    public $createdUserId;

    /**
     * @var DateTime?
     */
    public $modifiedDate;

    /**
     * @var Int32?
     */
    public $modifiedUserId;

    /**
     * @var List<SubscriptionModel>
     */
    public $subscriptions;

    /**
     * @var List<UserModel>
     */
    public $users;

}
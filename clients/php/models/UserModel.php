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
final class UserModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var Int32
     */
    public $accountId;

    /**
     * @var Int32?
     */
    public $companyId;

    /**
     * @var String
     */
    public $userName;

    /**
     * @var String
     */
    public $firstName;

    /**
     * @var String
     */
    public $lastName;

    /**
     * @var String
     */
    public $email;

    /**
     * @var String
     */
    public $postalCode;

    /**
     * @var SecurityRoleId
     */
    public $securityRoleId;

    /**
     * @var PasswordStatusId?
     */
    public $passwordStatus;

    /**
     * @var Boolean?
     */
    public $isActive;

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

}
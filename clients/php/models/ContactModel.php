<?php
/*
 * AvaTax Entity Model Class
 *
 * (c) 2004-2016 Avalara, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Avalara.AvaTax;

/**
 * @author Ted Spence <ted.spence@avalara.com>
 * @author Bob Maidens <bob.maidens@avalara.com>
 */
final class ContactModel extends AbstractEntity
{
    /**
     * @var Int32
     */
    public $id;

    /**
     * @var Int32
     */
    public $companyId;

    /**
     * @var String
     */
    public $contactCode;

    /**
     * @var String
     */
    public $firstName;

    /**
     * @var String
     */
    public $middleName;

    /**
     * @var String
     */
    public $lastName;

    /**
     * @var String
     */
    public $title;

    /**
     * @var String
     */
    public $line1;

    /**
     * @var String
     */
    public $line2;

    /**
     * @var String
     */
    public $line3;

    /**
     * @var String
     */
    public $city;

    /**
     * @var String
     */
    public $region;

    /**
     * @var String
     */
    public $postalCode;

    /**
     * @var String
     */
    public $country;

    /**
     * @var String
     */
    public $email;

    /**
     * @var String
     */
    public $phone;

    /**
     * @var String
     */
    public $mobile;

    /**
     * @var String
     */
    public $fax;

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

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
final class CompanyInitializationModel extends AbstractEntity
{
    /**
     * @var String
     */
    public $name;

    /**
     * @var String
     */
    public $companyCode;

    /**
     * @var String
     */
    public $vatRegistrationId;

    /**
     * @var String
     */
    public $taxpayerIdNumber;

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
    public $firstName;

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
    public $email;

    /**
     * @var String
     */
    public $phoneNumber;

    /**
     * @var String
     */
    public $mobileNumber;

    /**
     * @var String
     */
    public $faxNumber;

}
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
final class LocationModel extends AbstractEntity
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
    public $locationCode;

    /**
     * @var String
     */
    public $description;

    /**
     * @var AddressTypeId
     */
    public $addressTypeId;

    /**
     * @var AddressCategoryId
     */
    public $addressCategoryId;

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
    public $county;

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
     * @var Boolean?
     */
    public $isDefault;

    /**
     * @var Boolean?
     */
    public $isRegistered;

    /**
     * @var String
     */
    public $dbaName;

    /**
     * @var String
     */
    public $outletName;

    /**
     * @var DateTime?
     */
    public $effectiveDate;

    /**
     * @var DateTime?
     */
    public $endDate;

    /**
     * @var DateTime?
     */
    public $lastTransactionDate;

    /**
     * @var DateTime?
     */
    public $registeredDate;

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
     * @var List<LocationSettingModel>
     */
    public $settings;

}

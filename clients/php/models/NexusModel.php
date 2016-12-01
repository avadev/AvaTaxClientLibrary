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
final class NexusModel extends AbstractEntity
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
    public $country;

    /**
     * @var String
     */
    public $region;

    /**
     * @var JurisTypeId?
     */
    public $jurisTypeId;

    /**
     * @var String
     */
    public $jurisCode;

    /**
     * @var String
     */
    public $jurisName;

    /**
     * @var DateTime?
     */
    public $effectiveDate;

    /**
     * @var DateTime?
     */
    public $endDate;

    /**
     * @var String
     */
    public $shortName;

    /**
     * @var String
     */
    public $signatureCode;

    /**
     * @var String
     */
    public $stateAssignedNo;

    /**
     * @var NexusTypeId?
     */
    public $nexusTypeId;

    /**
     * @var Sourcing?
     */
    public $sourcing;

    /**
     * @var Boolean?
     */
    public $hasLocalNexus;

    /**
     * @var LocalNexusTypeId?
     */
    public $localNexusTypeId;

    /**
     * @var Boolean?
     */
    public $hasPermanentEstablishment;

    /**
     * @var String
     */
    public $taxId;

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

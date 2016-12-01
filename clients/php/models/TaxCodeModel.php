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
final class TaxCodeModel extends AbstractEntity
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
    public $taxCode;

    /**
     * @var String
     */
    public $taxCodeTypeId;

    /**
     * @var String
     */
    public $description;

    /**
     * @var String
     */
    public $parentTaxCode;

    /**
     * @var Boolean?
     */
    public $isPhysical;

    /**
     * @var Int32?
     */
    public $goodsServiceCode;

    /**
     * @var String
     */
    public $entityUseCode;

    /**
     * @var Boolean?
     */
    public $isActive;

    /**
     * @var Boolean?
     */
    public $isSSTCertified;

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

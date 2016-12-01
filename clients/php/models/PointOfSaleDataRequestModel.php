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
final class PointOfSaleDataRequestModel extends AbstractEntity
{
    /**
     * @var String
     */
    public $companyCode;

    /**
     * @var DateTime?
     */
    public $documentDate;

    /**
     * @var PointOfSaleFileType?
     */
    public $responseType;

    /**
     * @var String
     */
    public $taxCodes;

    /**
     * @var String
     */
    public $locationCodes;

    /**
     * @var Boolean?
     */
    public $includeJurisCodes;

    /**
     * @var Int32?
     */
    public $partnerId;

}
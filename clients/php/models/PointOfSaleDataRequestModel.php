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
 * Point-of-Sale Data Request Model
 */
final class PointOfSaleDataRequestModel
{
    /**
     * @var String A unique code that references a company within your account.
     */
    public $companyCode;

    /**
     * @var DateTime? The date associated with the response content. Default is current date. This field can be used to backdate or postdate the response content.
     */
    public $documentDate;

    /**
     * @var PointOfSaleFileType? The format of your response. Formats include JSON, CSV, and XML.
     */
    public $responseType;

    /**
     * @var List<String> A list of tax codes to include in this point-of-sale file. If no tax codes are specified, response will include all distinct tax codes associated with the Items within your company.
     */
    public $taxCodes;

    /**
     * @var List<String> A list of location codes to include in this point-of-sale file. If no location codes are specified, response will include all locations within your company.
     */
    public $locationCodes;

    /**
     * @var Boolean? Set this value to true to include Juris Code in the response.
     */
    public $includeJurisCodes;

    /**
     * @var Int32? A unique code assoicated with the Partner you may be working with. If you are not working with a Partner or your Partner has not provided you an ID, leave null.
     */
    public $partnerId;

}

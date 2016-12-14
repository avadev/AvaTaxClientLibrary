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
 * Represents an item in your company's product catalog.
 */
final class ItemModel
{
    /**
     * @var Int64 The unique ID number of this item.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company that owns this item.
     */
    public $companyId;

    /**
     * @var String A unique code representing this item.
     */
    public $itemCode;

    /**
     * @var Int32? The unique ID number of the tax code that is applied when selling this item.
     *             When creating or updating an item, you can either specify the Tax Code ID number or the Tax Code string; you do not need to specify both values.
     */
    public $taxCodeId;

    /**
     * @var String The unique code string of the Tax Code that is applied when selling this item.
     *             When creating or updating an item, you can either specify the Tax Code ID number or the Tax Code string; you do not need to specify both values.
     */
    public $taxCode;

    /**
     * @var String A friendly description of this item in your product catalog.
     */
    public $description;

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

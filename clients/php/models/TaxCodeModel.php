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
 * Represents a tax code that can be applied to items on a transaction.
            A tax code can have specific rules for specific jurisdictions that change the tax calculation behavior.
 */
final class TaxCodeModel
{
    /**
     * @var Int32 The unique ID number of this tax code.
     */
    public $id;

    /**
     * @var Int32 The unique ID number of the company that owns this tax code.
     */
    public $companyId;

    /**
     * @var String A code string that identifies this tax code.
     */
    public $taxCode;

    /**
     * @var String The type of this tax code.
     */
    public $taxCodeTypeId;

    /**
     * @var String A friendly description of this tax code.
     */
    public $description;

    /**
     * @var String If this tax code is a subset of a different tax code, this identifies the parent code.
     */
    public $parentTaxCode;

    /**
     * @var Boolean? True if this tax code refers to a physical object.
     */
    public $isPhysical;

    /**
     * @var Int64? The Avalara Goods and Service Code represented by this tax code.
     */
    public $goodsServiceCode;

    /**
     * @var String The Avalara Entity Use Code represented by this tax code.
     */
    public $entityUseCode;

    /**
     * @var Boolean? True if this tax code is active and can be used in transactions.
     */
    public $isActive;

    /**
     * @var Boolean? True if this tax code has been certified by the Streamlined Sales Tax governing board.
     *             By default, you should leave this value empty.
     */
    public $isSSTCertified;

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

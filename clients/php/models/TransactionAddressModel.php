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
 * An address used within this transaction.
 */
final class TransactionAddressModel
{
    /**
     * @var Int64? The unique ID number of this address.
     */
    public $id;

    /**
     * @var Int64? The unique ID number of the document to which this address belongs.
     */
    public $transactionId;

    /**
     * @var BoundaryLevel? The boundary level at which this address was validated.
     */
    public $boundaryLevel;

    /**
     * @var String The first line of the address.
     */
    public $line1;

    /**
     * @var String The second line of the address.
     */
    public $line2;

    /**
     * @var String The third line of the address.
     */
    public $line3;

    /**
     * @var String The city for the address.
     */
    public $city;

    /**
     * @var String The region, state, or province for the address.
     */
    public $region;

    /**
     * @var String The postal code or zip code for the address.
     */
    public $postalCode;

    /**
     * @var String The country for the address.
     */
    public $country;

    /**
     * @var Int32? The unique ID number of the tax region for this address.
     */
    public $taxRegionId;

}

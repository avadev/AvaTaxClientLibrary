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
 * Represents an address to resolve.  
            Note that there are no data validations on this model since malformed addresses will get "best-guess" resolution.
 */
final class AddressInfo
{
    /**
     * @var String Line1
     */
    public $line1;

    /**
     * @var String Line2
     */
    public $line2;

    /**
     * @var String Line3
     */
    public $line3;

    /**
     * @var String City
     */
    public $city;

    /**
     * @var String State / Province / Region
     */
    public $region;

    /**
     * @var String Two character ISO 3166 Country Code
     */
    public $country;

    /**
     * @var String Postal Code / Zip Code
     */
    public $postalCode;

    /**
     * @var Decimal? Geospatial latitude measurement
     */
    public $latitude;

    /**
     * @var Decimal? Geospatial longitude measurement
     */
    public $longitude;

}

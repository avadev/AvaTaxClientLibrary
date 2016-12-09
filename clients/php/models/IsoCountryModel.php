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
 * Represents an ISO 3166 recognized country
 */
final class IsoCountryModel
{
    /**
     * @var String The two character ISO 3166 country code
     */
    public $code;

    /**
     * @var String The full name of this country as it is known in US English
     */
    public $name;

    /**
     * @var Boolean? True if this country is a member of the European Union
     */
    public $isEuropeanUnion;

}

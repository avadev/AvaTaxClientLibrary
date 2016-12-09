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
 * Represents a region, province, or state within a country
 */
final class IsoRegionModel
{
    /**
     * @var String The two-character ISO 3166 country code this region belongs to
     */
    public $countryCode;

    /**
     * @var String The three character ISO 3166 region code
     */
    public $code;

    /**
     * @var String The full name, using localized characters, for this region
     */
    public $name;

    /**
     * @var String The word in the local language that classifies what type of a region this represents
     */
    public $classification;

}

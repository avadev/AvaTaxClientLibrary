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
 * Represents a license key for this account.
 */
final class LicenseKeyModel
{
    /**
     * @var Int32? The primary key of the account
     */
    public $accountId;

    /**
     * @var String This is your private license key.  You must record this license key for safekeeping.
     *             If you lose this key, you must contact the ResetLicenseKey API in order to request a new one.
     *             Each account can only have one license key at a time.
     */
    public $privateLicenseKey;

    /**
     * @var String If your software allows you to specify the HTTP Authorization header directly, this is the header string you 
     *             should use when contacting Avalara to make API calls with this license key.
     */
    public $httpRequestHeader;

}

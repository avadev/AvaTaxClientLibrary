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
 * Represents a license key reset request.
 */
final class ResetLicenseKeyModel
{
    /**
     * @var Int32 The primary key of the account ID to reset
     */
    public $accountId;

    /**
     * @var Boolean Set this value to true to reset the license key for this account.
     *             This license key reset function will only work when called using the credentials of the account administrator of this account.
     */
    public $confirmResetLicenseKey;

}

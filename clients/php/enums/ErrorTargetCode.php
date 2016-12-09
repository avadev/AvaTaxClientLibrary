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
 * 
 */
class ErrorTargetCode
{

    const Unknown = "Unknown";
    const HttpRequest = "HttpRequest";
    const HttpRequestHeaders = "HttpRequestHeaders";
    const IncorrectData = "IncorrectData";
    const AvaTaxApiServer = "AvaTaxApiServer";
    const AvalaraIdentityServer = "AvalaraIdentityServer";
    const CustomerAccountSetup = "CustomerAccountSetup";
}

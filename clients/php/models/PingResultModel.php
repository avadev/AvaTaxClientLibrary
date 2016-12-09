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
 * Ping Result Model
 */
final class PingResultModel
{
    /**
     * @var String Version number
     */
    public $version;

    /**
     * @var Boolean? Returns true if you provided authentication for this API call; false if you did not.
     */
    public $authenticated;

    /**
     * @var AuthenticationTypeId? Returns the type of authentication you provided, if authenticated
     */
    public $authenticationType;

    /**
     * @var String The username of the currently authenticated user, if any.
     */
    public $authenticatedUserName;

}

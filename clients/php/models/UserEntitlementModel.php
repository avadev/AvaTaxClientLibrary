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
 * User Entitlement Model
 */
final class UserEntitlementModel
{
    /**
     * @var List<String> List of API names and categories that this user is permitted to access
     */
    public $permissions;

    /**
     * @var CompanyAccessLevel? What access privileges does the current user have to see companies?
     */
    public $accessLevel;

    /**
     * @var List<Int32> The identities of all companies this user is permitted to access
     */
    public $companies;

}
